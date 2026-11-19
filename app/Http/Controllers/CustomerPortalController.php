<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\TicketActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\User;
use App\Notifications\CustomerReportedUnresolved;
use Illuminate\Support\Facades\Notification;

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

                'customer_confirmation_required' => true,
                'customer_confirmation_status' => null,
                'customer_resolution_note' => null,
                'customer_confirmed_at' => null,
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

        try {
            $customerPassword = Crypt::decryptString($customer->password);
        } catch (DecryptException $e) {
            $customerPassword = null;
        }

        return view('pages.customers.profile', compact(
            'customer',
            'customerPassword'
        ));
    }
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
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

        DB::table('customer')
            ->where('id', $customer->id)
            ->update([
                'pic' => $validated['pic'] ?? null,
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'updated_at' => now(),
            ]);

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

        $encryptedPassword = Crypt::encryptString(
            $validated['password']
        );

        DB::table('customer')
            ->where('id', $customer->id)
            ->update([
                'password' => $encryptedPassword,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('customer.profile')
            ->with('success', 'Password updated successfully.');
    }


    public function submitResolutionFeedback(Request $request, $ticketId)
    {
        $validated = $request->validate([
            'answer' => ['required', 'in:yes,no'],
            'customer_resolution_note' => [
                'required_if:answer,no',
                'nullable',
                'string',
                'max:5000',
            ],
        ], [
            'answer.required' => 'Please select an answer.',
            'customer_resolution_note.required_if' =>
            'Please explain what issue remains unresolved.',
        ]);

        $customer = Auth::guard('customer')->user();

        DB::transaction(function () use (
            $ticketId,
            $customer,
            $validated
        ) {
            $ticket = Ticket::where('customer_id', $customer->id)
                ->where('customer_confirmation_required', true)
                ->where('ticket_status', 'Awaiting Customer')
                ->where('customer_confirmation_status', 'Pending')
                ->lockForUpdate()
                ->firstOrFail();

            if ($validated['answer'] === 'yes') {
                $ticket->update([
                    'ticket_status' => 'Completed',
                    'customer_confirmation_status' => 'Confirmed',
                    'customer_resolution_note' => null,
                    'customer_confirmed_at' => now(),
                    'customer_confirmation_due_at' => null,
                ]);

                TicketActivity::create([
                    'ticket_id' => $ticket->id,
                    'activity_type' => 'Customer Confirmed Resolution',
                    'description' => 'Customer confirmed that the issue has been resolved.',
                    'performed_by' => $customer->username,
                ]);
            } else {
                $note = $validated['customer_resolution_note'];

                $ticket->update([
                    'ticket_status' => 'Awaiting Customer',
                    'customer_confirmation_status' => 'Not Resolved',
                    'customer_resolution_note' => $note,
                    'customer_confirmed_at' => now(),
                    'customer_confirmation_due_at' => null,
                ]);

                TicketActivity::create([
                    'ticket_id' => $ticket->id,
                    'activity_type' => 'Customer Reported Unresolved',
                    'description' => "Customer reported that the issue is not resolved.\n\nCustomer Note:\n{$note}",
                    'performed_by' => $customer->username,
                ]);

                $admins = User::where('role', 'admin')->get();

                Notification::send(
                    $admins,
                    new CustomerReportedUnresolved(
                        $ticket->id,
                        $ticket->name,
                        $customer->username,
                        $note
                    )
                );
            }
        });

        return redirect()
            ->route('customer.tickets.show', $ticketId)
            ->with(
                'success',
                $validated['answer'] === 'yes'
                    ? 'Thank you! Your ticket has been confirmed as completed.'
                    : 'Your feedback has been submitted. The administrator will review your issue.'
            );
    }
}
