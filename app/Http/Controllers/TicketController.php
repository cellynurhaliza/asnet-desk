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
use Illuminate\Support\Carbon;
use App\Exports\TicketsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Models\TicketPhoto;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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

        $customers = Customer::orderBy('username')->get();

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
            ->when(request('customer_id'), function ($query) {
                $query->where('customer_id', request('customer_id'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.tickets.index', compact('tickets', 'statuses', 'customers'));
    }


    public function create()
    {
        $customers = Customer::where('status', 'Active')
            ->orderBy('pic')
            ->get();

        $categories = Category::where('active_category', true)
            ->orderBy('performance_order', 'asc')
            ->get();

        $technicians = Technician::where('active_technician', true)
            ->orderBy('technician_name')
            ->get();

        $priorities = [
            'Urgent' => [
                'description' => 'Critical issue',
                'color' => 'border-red-300 bg-red-50 text-red-600',
                'dark' => 'dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-400',
                'dot' => 'bg-red-500',
            ],
            'High' => [
                'description' => 'High impact',
                'color' => 'border-orange-300 bg-orange-50 text-orange-600',
                'dark' => 'dark:border-orange-500/30 dark:bg-orange-500/10 dark:text-orange-400',
                'dot' => 'bg-orange-500',
            ],
            'Medium' => [
                'description' => 'Normal issue',
                'color' => 'border-blue-300 bg-blue-50 text-blue-600',
                'dark' => 'dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-400',
                'dot' => 'bg-blue-500',
            ],
            'Low' => [
                'description' => 'Minor issue',
                'color' => 'border-gray-300 bg-gray-50 text-gray-600',
                'dark' => 'dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300',
                'dot' => 'bg-gray-400',
            ],
        ];

        return view('pages.tickets.create', compact(
            'categories',
            'technicians',
            'customers',
            'priorities'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => [
                'nullable',
                'required_without:new_customer_name',
                Rule::exists('customer', 'id')->where('status', 'Active'),
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

            'is_issue_start_now' => ['required', 'boolean'],
            'incident_started_at' => [
                'required_if:is_issue_start_now,0',
                'nullable',
                'date',
                'before_or_equal:now',
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

                $customer = Customer::create([
                    'username' => $username,
                    'pic' => $username,
                    'email' => null,
                    'password' => Crypt::encryptString($username),
                    'status' => 'Active',
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

            $incidentStartedAt = $validated['is_issue_start_now']
                ? $reportingTime
                : Carbon::parse(
                    $validated['incident_started_at'],
                    'Asia/Jakarta'
                );

            $technicianIds = $validated['technician_ids'] ?? [];

            $priority = $validated['is_issue_start_now']
                ? $validated['priority']
                : 'High';

            $ticket = Ticket::create([
                'customer_id' => $customerId,
                'kategori_id' => $category->id,
                'technician_id' => $technicianIds[0] ?? null,

                'name' => $category->category_name . ' - ' . $customer->username,

                'complaint' => trim($validated['complaint_details'] ?? '') ?: '-',
                'action' => trim($validated['action_details'] ?? '') ?: null,

                'reporting_time' => $reportingTime,
                'incident_started_at' => $incidentStartedAt,

                'ticket_status' => 'New',
                'priority' => $priority,
            ]);

            $ticket->ticket_code = 'TKT-' .
                $reportingTime->format('dmy') . '-' .
                str_pad($ticket->id, 5, '0', STR_PAD_LEFT);

            $ticket->save();

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
            'activities.photos',
            'photos',
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

            'photos' => ['nullable', 'array', 'max:10'],
            'photos.*' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'latitude' => ['nullable', 'string', 'max:20'],
            'longitude' => ['nullable', 'string', 'max:20'],

            'coordinates' => ['nullable', 'string', 'max:45'],
        ]);

        $performedBy = Auth::user()?->name ?? 'Administrator';

        $uploadedPaths = [];

        try {
            DB::transaction(function () use (
                $request,
                $ticket,
                $validated,
                $performedBy,
                &$uploadedPaths
            ) {
                $ticket = Ticket::whereKey($ticket->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $oldStatus = $ticket->ticket_status;
                $oldAction = $ticket->action;
                $oldPriority = $ticket->priority ?? 'Medium';

                $oldTechnicianIds = $ticket->technicians()
                    ->pluck('technician.id')
                    ->map(fn($id) => (int) $id)
                    ->sort()
                    ->values()
                    ->toArray();

                $photos = $request->file('photos', []) ?? [];

                if (count($photos) > 0) {
                    $activity = TicketActivity::create([
                        'ticket_id' => $ticket->id,
                        'activity_type' => 'Photos Uploaded',
                        'description' => count($photos) . ' photos uploaded',
                        'performed_by' => $performedBy,
                    ]);

                    foreach ($photos as $photo) {
                        $path = $photo->store('ticket-photos', 'public');

                        $uploadedPaths[] = $path;

                        TicketPhoto::create([
                            'ticket_id' => $ticket->id,
                            'activity_id' => $activity->id,
                            'photo_path' => $path,
                        ]);
                    }
                }

                $newTechnicianIds = collect(
                    $validated['technician_ids'] ?? []
                )
                    ->map(fn($id) => (int) $id)
                    ->sort()
                    ->values()
                    ->toArray();

                $newStatus = $validated['ticket_status'];

                $latitude = null;
                $longitude = null;

                if (!empty($validated['coordinates'])) {
                    $coordinates = explode(',', $validated['coordinates'], 2);

                    if (count($coordinates) !== 2) {
                        throw ValidationException::withMessages([
                            'coordinates' => 'Please enter latitude and longitude separated by a comma.',
                        ]);
                    }

                    $latitude = trim($coordinates[0]);
                    $longitude = trim($coordinates[1]);
                }

                $updateData = [
                    'ticket_status' => $newStatus,
                    'action' => $validated['action'] ?? null,
                    'technician_id' => $newTechnicianIds[0] ?? null,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ];

                if (array_key_exists('priority', $validated)) {
                    $updateData['priority'] = $validated['priority'];
                }

                if ($newStatus === 'Completed' && $oldStatus !== 'Completed') {
                    $updateData['resolved_at'] = now();
                } elseif ($newStatus !== 'Completed') {
                    $updateData['resolved_at'] = null;
                }

                if (
                    $oldStatus !== 'Awaiting Customer' &&
                    $newStatus === 'Awaiting Customer' &&
                    $ticket->customer_confirmation_required
                ) {
                    $updateData['customer_confirmation_status'] = 'Pending';
                    $updateData['customer_resolution_note'] = null;
                    $updateData['customer_confirmed_at'] = null;
                    $updateData['customer_confirmation_due_at'] = now()->addHours(24);
                }

                if (
                    $oldStatus === 'Awaiting Customer' &&
                    $newStatus !== 'Awaiting Customer'
                ) {
                    $updateData['customer_confirmation_due_at'] = null;
                }

                $ticket->update($updateData);

                $ticket->technicians()->sync($newTechnicianIds);

                if ($oldStatus !== $newStatus) {
                    TicketActivity::create([
                        'ticket_id' => $ticket->id,
                        'activity_type' => 'Status Updated',
                        'description' => "Status changed from {$oldStatus} to {$newStatus}.",
                        'performed_by' => $performedBy,
                    ]);
                }

                $newAction = $validated['action'] ?? null;

                if ($oldAction !== $newAction) {
                    TicketActivity::create([
                        'ticket_id' => $ticket->id,
                        'activity_type' => 'Action Updated',
                        'description' => "Response / Action updated:\n"
                            . "Previous: " . ($oldAction ?: '-') . "\n"
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
                        : 'Technician assignment updated: '
                        . implode(', ', $technicianNames) . '.';

                    TicketActivity::create([
                        'ticket_id' => $ticket->id,
                        'activity_type' => 'Technician Updated',
                        'description' => $description,
                        'performed_by' => $performedBy,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            foreach ($uploadedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $e;
        }

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
    public function export(Request $request)
    {
        $validated = $request->validate([
            'month' => ['required', 'regex:/^(all|[1-9]|1[0-2])$/'],
            'year' => ['required', 'integer', 'between:2023,2100'],
            'customer_id' => ['nullable', 'integer', 'exists:customer,id'],
        ]);

        $month = $validated['month'] === 'all'
            ? null
            : (int) $validated['month'];

        $year = (int) $validated['year'];

        $customerId = !empty($validated['customer_id'])
            ? (int) $validated['customer_id']
            : null;

        $period = $month
            ? \Carbon\Carbon::create()->month($month)->translatedFormat('F')
            : 'Full_Year';

        $filename = "Log_Operasional_{$period}_{$year}.xlsx";

        return Excel::download(
            new TicketsExport($month, $year, $customerId),
            $filename
        );
    }
}
