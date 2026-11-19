<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Technician;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\TicketActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TicketController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;

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
            'status' => ['nullable', 'in:' . implode(',', $statuses)],
            'priority' => ['nullable', 'in:Urgent,High,Medium,Low'],
        ]);

        $tickets = Ticket::with([
            'customer',
            'category',
            'technician',
            'technicians',
        ])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%")
                        ->orWhere('complaint', 'ilike', "%{$search}%")
                        ->orWhere('action', 'ilike', "%{$search}%")
                        ->orWhere('ticket_status', 'ilike', "%{$search}%")
                        ->orWhere('priority', 'ilike', "%{$search}%")
                        ->orWhereHas('customer', function ($customer) use ($search) {
                            $customer->where('username', 'ilike', "%{$search}%")
                                ->orWhere('pic', 'ilike', "%{$search}%");
                        })
                        ->orWhereHas('technicians', function ($technician) use ($search) {
                            $technician->where('technician_name', 'ilike', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('ticket_status', $request->status);
            })
            ->when($request->filled('priority'), function ($query) use ($request) {
                $query->where('priority', $request->priority);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.tickets.index', compact('tickets', 'statuses'));
    }


    public function create()
    {
        $customers = Customer::orderBy('username', 'asc')->get();

        $categories = Category::where('active_category', true)
            ->orderBy('performance_order', 'asc')
            ->get();

        $technicians = Technician::where('active_technician', true)
            ->orderBy('technician_name')
            ->get();

        return view('pages.tickets.create', compact(
            'categories',
            'technicians',
            'customers'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => [
                'nullable',
                'required_without:new_customer_name',
                'exists:customer,id',
            ],

            'new_customer_name' => [
                'nullable',
                'required_without:customer_id',
                'string',
                'max:150',
            ],

            'category_id' => ['required', 'exists:category,id'],

            'complaint_details' => ['nullable', 'string'],
            'action_details' => ['nullable', 'string'],

            'manual_time' => ['nullable', 'boolean'],
            'reported_at' => [
                'nullable',
                'required_if:manual_time,1',
                'date',
            ],

            'priority' => [
                'required',
                'in:Urgent,High,Medium,Low',
            ],

            'technician_ids' => ['nullable', 'array'],
            'technician_ids.*' => [
                'integer',
                'distinct',
                'exists:technician,id',
            ],
        ]);

        $ticket = DB::transaction(function () use ($validated) {

            if (!empty($validated['new_customer_name'])) {
                $customerName = trim($validated['new_customer_name']);

                $baseUsername = strtolower(
                    preg_replace('/[^a-zA-Z0-9]+/', '', $customerName)
                );

                $baseUsername = $baseUsername ?: 'customer';

                $username = $baseUsername;
                $counter = 1;

                while (Customer::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }

                $email = $username . '@gmail.com';
                $emailCounter = 1;

                while (Customer::where('email', $email)->exists()) {
                    $email = $username . $emailCounter . '@gmail.com';
                    $emailCounter++;
                }

                $customer = Customer::create([
                    'username' => $username,
                    'pic' => $username,
                    'email' => $email,
                    'password' => Hash::make(Str::random(32)),
                ]);

                $customerId = $customer->id;
            } else {
                $customer = Customer::findOrFail(
                    $validated['customer_id']
                );

                $customerId = $customer->id;
            }

            $category = Category::findOrFail(
                $validated['category_id']
            );

            $reportingTime = !empty($validated['manual_time'])
                ? Carbon::parse($validated['reported_at'], 'Asia/Jakarta')
                : now();

            $technicianIds = $validated['technician_ids'] ?? [];

            $ticket = Ticket::create([
                'customer_id' => $customerId,
                'kategori_id' => $category->id,
                'technician_id' => $technicianIds[0] ?? null,

                'name' => $category->category_name . ' - ' . $customer->username,

                'complaint' => trim($validated['complaint_details'] ?? '') ?: '-',
                'action' => trim($validated['action_details'] ?? '') ?: null,

                'reporting_time' => $reportingTime,
                'ticket_status' => 'New',
                'priority' => $validated['priority'],
            ]);

            $ticket->technicians()->sync($technicianIds);

            $actor = Auth::user()?->name ?? 'Administrator';

            TicketActivity::create([
                'ticket_id' => $ticket->id,
                'activity_type' => 'Created',
                'description' => 'Ticket was created.',
                'performed_by' => $actor,
            ]);

            if (!empty($technicianIds)) {
                $technicianNames = Technician::whereIn('id', $technicianIds)
                    ->orderBy('technician_name')
                    ->pluck('technician_name')
                    ->toArray();

                TicketActivity::create([
                    'ticket_id' => $ticket->id,
                    'activity_type' => 'Assignment',
                    'description' => 'Ticket assigned to: ' .
                        implode(', ', $technicianNames) . '.',
                    'performed_by' => $actor,
                ]);
            }

            return $ticket;
        });

        if ($request->input('save_and_new')) {
            return redirect()
                ->route('tickets.create')
                ->with('success', 'Ticket created successfully.');
        }

        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load([
            'customer',
            'category',
            'technician',
            'technicians',
            'activities',
        ]);

        $technicians = Technician::where('active_technician', true)
            ->orderBy('technician_name')
            ->get();

        return view('pages.tickets.show', compact(
            'ticket',
            'technicians'
        ));
    }

    public function updateHandling(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'ticket_status' => [
                'required',
                'in:New,In Progress,Technician En Route,In Repair,Awaiting Customer,Completed,Cancelled',
            ],

            'action' => ['nullable', 'string'],

            'priority' => [
                'sometimes',
                'required',
                'in:Urgent,High,Medium,Low',
            ],

            'technician_ids' => ['nullable', 'array'],
            'technician_ids.*' => [
                'integer',
                'distinct',
                'exists:technician,id',
            ],
        ]);

        $oldStatus = $ticket->ticket_status;
        $oldAction = $ticket->action;
        $oldPriority = $ticket->priority ?? 'Medium';

        $oldTechnicianIds = $ticket->technicians()
            ->pluck('technician.id')
            ->map(fn($id) => (int) $id)
            ->sort()
            ->values()
            ->toArray();

        $newTechnicianIds = collect(
            $validated['technician_ids'] ?? []
        )
            ->map(fn($id) => (int) $id)
            ->sort()
            ->values()
            ->toArray();

        $performedBy = Auth::user()?->name ?? 'Administrator';

        DB::transaction(function () use (
            $ticket,
            $validated,
            $oldStatus,
            $oldAction,
            $oldPriority,
            $oldTechnicianIds,
            $newTechnicianIds,
            $performedBy
        ) {
            $updateData = [
                'ticket_status' => $validated['ticket_status'],
                'action' => $validated['action'] ?? null,

                'technician_id' => $newTechnicianIds[0] ?? null,
            ];

            if (array_key_exists('priority', $validated)) {
                $updateData['priority'] = $validated['priority'];
            }

            if (
                $oldStatus !== 'Awaiting Customer' &&
                $validated['ticket_status'] === 'Awaiting Customer' &&
                $ticket->customer_confirmation_required
            ) {
                $updateData['customer_confirmation_status'] = 'Pending';
                $updateData['customer_resolution_note'] = null;
                $updateData['customer_confirmed_at'] = null;
                $updateData['customer_confirmation_due_at'] = now()->addHours(24);
            }

            if (
                $oldStatus === 'Awaiting Customer' &&
                $validated['ticket_status'] !== 'Awaiting Customer'
            ) {
                $updateData['customer_confirmation_due_at'] = null;
            }

            $ticket->update($updateData);

            $ticket->technicians()->sync($newTechnicianIds);

            if ($oldStatus !== $validated['ticket_status']) {
                TicketActivity::create([
                    'ticket_id' => $ticket->id,
                    'activity_type' => 'Status Updated',
                    'description' => "Status changed from {$oldStatus} to {$validated['ticket_status']}.",
                    'performed_by' => $performedBy,
                ]);
            }

            $newAction = $validated['action'] ?? null;

            if ($oldAction !== $newAction) {
                TicketActivity::create([
                    'ticket_id' => $ticket->id,
                    'activity_type' => 'Action Updated',
                    'description' => "Response / Action updated:\n\n"
                        . "Previous: " . ($oldAction ?: '-') . "\n\n"
                        . "Updated: " . ($newAction ?: '-'),
                    'performed_by' => $performedBy,
                ]);
            }

            $newPriority = $validated['priority'] ?? $oldPriority;

            if ($oldPriority !== $newPriority) {
                TicketActivity::create([
                    'ticket_id' => $ticket->id,
                    'activity_type' => 'Priority Updated',
                    'description' => "Priority changed from {$oldPriority} to {$newPriority}.",
                    'performed_by' => $performedBy,
                ]);
            }

            if ($oldTechnicianIds !== $newTechnicianIds) {
                $technicianNames = Technician::whereIn('id', $newTechnicianIds)
                    ->orderBy('technician_name')
                    ->pluck('technician_name')
                    ->toArray();

                $description = empty($technicianNames)
                    ? 'All technicians have been unassigned.'
                    : 'Technician assignment updated: ' .
                    implode(', ', $technicianNames) . '.';

                TicketActivity::create([
                    'ticket_id' => $ticket->id,
                    'activity_type' => 'Technician Updated',
                    'description' => $description,
                    'performed_by' => $performedBy,
                ]);
            }
        });

        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket handling updated successfully.');
    }

    public function addNote(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'note' => 'required|string|max:2000',
        ]);

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'activity_type' => 'Note Added',
            'description' => $validated['note'],
            'performed_by' => Auth::user()?->name ?? 'Administrator',
        ]);

        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', 'Note added successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        DB::transaction(function () use ($ticket) {
            $ticket->activities()->delete();
            $ticket->delete();
        });

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    private function canChangeStatus(string $current, string $next): bool
    {
        $workflow = [
            'New' => ['In Progress', 'Cancelled'],
            'In Progress' => ['Technician En Route', 'Cancelled'],
            'Technician En Route' => ['In Repair', 'Cancelled'],
            'In Repair' => ['Awaiting Customer', 'Completed', 'Cancelled'],
            'Awaiting Customer' => ['In Repair', 'Completed', 'Cancelled'],
            'Completed' => [],
            'Cancelled' => [],
        ];

        if ($current === $next) {
            return true;
        }

        return in_array($next, $workflow[$current] ?? [], true);
    }
}
