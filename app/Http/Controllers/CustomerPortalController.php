<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\TicketActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerPortalController extends Controller
{
    public function dashboard()
    {
        $customerId = Auth::guard('customer')->id();

        $activeStatuses = [
            'New',
            'In Progress',
            'Technician En Route',
            'In Repair',
            'Awaiting Customer',
        ];

        $statistics = [
            [
                'title' => 'Total Tickets',
                'value' => Ticket::where('customer_id', $customerId)->count(),
            ],
            [
                'title' => 'New Tickets',
                'value' => Ticket::where('customer_id', $customerId)
                    ->where('ticket_status', 'New')
                    ->count(),
            ],
            [
                'title' => 'Active Tickets',
                'value' => Ticket::where('customer_id', $customerId)
                    ->whereIn('ticket_status', $activeStatuses)
                    ->count(),
            ],
            [
                'title' => 'Completed Tickets',
                'value' => Ticket::where('customer_id', $customerId)
                    ->where('ticket_status', 'Completed')
                    ->count(),
            ],
        ];

        $recentTickets = Ticket::with([
            'category',
            'technicians',
        ])
            ->where('customer_id', $customerId)
            ->latest()
            ->paginate(5);

        return view('pages.customers.dashboard', compact(
            'statistics',
            'recentTickets'
        ));
    }

    public function tickets(Request $request)
    {
        $customerId = Auth::guard('customer')->id();

        $statuses = [
            'New',
            'In Progress',
            'Technician En Route',
            'In Repair',
            'Awaiting Customer',
            'Completed',
            'Cancelled',
        ];

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in($statuses)],
            'priority' => ['nullable', Rule::in(['Urgent', 'High', 'Medium', 'Low'])],
        ]);

        $query = Ticket::with([
            'category',
            'technicians',
        ])->where('customer_id', $customerId);

        if (!empty($validated['search'])) {
            $search = $validated['search'];

            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('complaint', 'ilike', "%{$search}%");
            });
        }

        if (!empty($validated['status'])) {
            $query->where('ticket_status', $validated['status']);
        }

        if (!empty($validated['priority'])) {
            $query->where('priority', $validated['priority']);
        }

        $tickets = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.customers.tickets.index', compact(
            'tickets',
            'statuses'
        ));
    }

    public function createTicket()
    {
        $categories = Category::where('active_category', true)
            ->orderBy('performance_order')
            ->get();

        return view('pages.customers.tickets.create', compact('categories'));
    }

    public function storeTicket(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => [
                'required',
                'exists:category,id',
            ],
            'priority' => [
                'required',
                Rule::in(['Urgent', 'High', 'Medium', 'Low']),
            ],
            'complaint' => ['required', 'string'],
        ], [
            'name.required' => 'Ticket name is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category is invalid.',
            'priority.required' => 'Please select ticket priority.',
            'priority.in' => 'Selected ticket priority is invalid.',
            'complaint.required' => 'Complaint details are required.',
        ]);

        $customer = Auth::guard('customer')->user();

        DB::transaction(function () use ($validated, $customer) {
            $ticket = Ticket::create([
                'customer_id' => $customer->id,
                'kategori_id' => $validated['category_id'],
                'technician_id' => null,
                'name' => $validated['name'],
                'complaint' => $validated['complaint'],
                'action' => null,
                'reporting_time' => now(),
                'ticket_status' => 'New',
                'priority' => $validated['priority'],
            ]);

            TicketActivity::create([
                'ticket_id' => $ticket->id,
                'activity_type' => 'Created',
                'description' => 'Ticket created by customer.',
                'performed_by' => null,
            ]);
        });

        return redirect()
            ->route('customer.tickets.index')
            ->with('success', 'Your ticket has been created successfully.');
    }

    public function showTicket($ticket)
    {
        $ticket = Ticket::with([
            'category',
            'technicians',
            'activities',
        ])
            ->where('customer_id', Auth::guard('customer')->id())
            ->findOrFail($ticket);

        return view('pages.customers.tickets.show', compact('ticket'));
    }

    public function profile()
    {
        $customer = Auth::guard('customer')->user();

        return view('pages.customers.profile', compact('customer'));
    }
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:150'],
            'pic' => ['nullable', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('customer', 'email')->ignore($customer->id),
            ],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
        ]);

        $customer->update($validated);

        return redirect()
            ->route('customer.profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $customer->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
