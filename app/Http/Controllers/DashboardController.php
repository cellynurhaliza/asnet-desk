<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $activeStatuses = [
            'New',
            'In Progress',
            'Technician En Route',
            'In Repair',
            'Awaiting Customer',
        ];

        $validated = $request->validate([
            'priority' => ['nullable', 'in:Urgent,High,Medium,Low'],
        ]);

        $selectedPriority = $validated['priority'] ?? null;

        $statistics = [
            [
                'title' => 'Total Tickets',
                'value' => Ticket::count(),
                'description' => 'All registered tickets',
                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2',
                'color' => 'bg-brand-50 text-brand-500 dark:bg-brand-500/10 dark:text-brand-400',
            ],
            [
                'title' => 'New Tickets',
                'value' => Ticket::where('ticket_status', 'New')->count(),
                'description' => 'Waiting to be handled',
                'icon' => 'M12 4v16m8-8H4',
                'color' => 'bg-blue-50 text-blue-500 dark:bg-blue-500/10 dark:text-blue-400',
            ],
            [
                'title' => 'Active Tickets',
                'value' => Ticket::whereIn('ticket_status', $activeStatuses)->count(),
                'description' => 'Including new and ongoing tickets',
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'color' => 'bg-warning-50 text-warning-500 dark:bg-warning-500/10 dark:text-warning-400',
            ],
            [
                'title' => 'Tickets Completed',
                'value' => Ticket::where('ticket_status', 'Completed')->count(),
                'description' => 'Successfully completed tickets',
                'icon' => 'M5 13l4 4L19 7',
                'color' => 'bg-success-50 text-success-500 dark:bg-success-500/10 dark:text-success-400',
            ],
        ];

        $priorities = ['Urgent', 'High', 'Medium', 'Low'];

        $priorityCounts = Ticket::query()
            ->where('ticket_status', '!=', 'Completed')
            ->selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority');

        $totalPriorityTickets = $priorityCounts->sum();

        $priorityStats = collect($priorities)->map(function ($priority) use (
            $priorityCounts,
            $totalPriorityTickets
        ) {
            $count = (int) ($priorityCounts[$priority] ?? 0);

            return [
                'priority' => $priority,
                'count' => $count,
                'percentage' => $totalPriorityTickets > 0
                    ? round(($count / $totalPriorityTickets) * 100, 1)
                    : 0,
            ];
        });

        $recentTickets = Ticket::with([
            'customer',
            'category',
            'technicians',
        ])
            ->whereIn('ticket_status', $activeStatuses)
            ->when($selectedPriority, function ($query) use ($selectedPriority) {
                $query->where('priority', $selectedPriority);
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('pages.dashboard', compact(
            'statistics',
            'priorityStats',
            'recentTickets'
        ));
    }
}
