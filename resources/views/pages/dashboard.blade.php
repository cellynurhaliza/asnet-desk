@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
    <div class="mb-8 flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
        <div>
            @php
                $hour = now()->hour;
                $greeting = match (true) {
                    $hour >= 5 && $hour < 11 => 'Good Morning',
                    $hour >= 11 && $hour < 15 => 'Good Afternoon',
                    $hour >= 15 && $hour < 18 => 'Good Evening',
                    default => 'Good Evening',
                };

                $userName = auth()->user()->name ?? 'Administrator';
            @endphp

            <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                {{ $greeting }}, {{ $userName }}!
            </h2>

            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Here's what's happening with your support tickets today.
            </p>
        </div>

        <a href="{{ route('tickets.create') }}"
            class="group inline-flex items-center justify-center gap-2 rounded-xl bg-brand-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-500/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-600 hover:shadow-brand-500/30 focus:outline-none focus:ring-4 focus:ring-brand-500/20">
            <svg class="h-5 w-5 transition-transform duration-200 group-hover:rotate-90" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create New Ticket
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($statistics as $item)
            <div
                class="rounded-xl border border-gray-200 bg-white p-5 transition hover:border-gray-300 dark:border-gray-800 dark:bg-gray-950 dark:hover:border-gray-700">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ $item['title'] }}
                    </p>

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg {{ $item['color'] }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="{{ $item['icon'] }}" />
                        </svg>
                    </div>
                </div>

                <div class="mt-4">
                    <h3 class="text-3xl font-semibold tracking-tight text-gray-800 dark:text-white">
                        {{ number_format($item['value']) }}
                    </h3>

                    <p class="mt-1.5 text-xs text-gray-400 dark:text-gray-500">
                        {{ $item['description'] }}
                    </p>
                </div>

                <div class="mt-4 h-1 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                    <div class="h-full w-1/3 rounded-full {{ $item['color'] }}"></div>
                </div>
            </div>
        @endforeach
    </div>

    @php
        $priorityColors = [
            'Urgent' => 'bg-red-500',
            'High' => 'bg-orange-500',
            'Medium' => 'bg-blue-500',
            'Low' => 'bg-gray-400',
        ];

        $priorityTextColors = [
            'Urgent' => 'text-red-600 dark:text-red-400',
            'High' => 'text-orange-600 dark:text-orange-400',
            'Medium' => 'text-blue-600 dark:text-blue-400',
            'Low' => 'text-gray-600 dark:text-gray-400',
        ];

        $selectedPriority = request('priority');
    @endphp

    <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-sm dark:border-gray-800 dark:bg-gray-950">
        <div class="mb-6 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                    Priority Distribution
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Overview of ticket priorities across unfinished tickets.
                </p>
            </div>

            <a href="{{ url()->current() }}" class="text-sm font-medium text-brand-500 transition hover:text-brand-600">
                Clear Filter
            </a>
        </div>

        <div class="flex h-5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
            @foreach ($priorityStats as $item)
                @if ($item['percentage'] > 0)
                    <a href="{{ request()->fullUrlWithQuery(['priority' => $item['priority']]) }}"
                        title="{{ $item['priority'] }}: {{ $item['percentage'] }}%"
                        style="width: {{ $item['percentage'] }}%"
                        class="{{ $priorityColors[$item['priority']] }} transition-opacity hover:opacity-75
                    {{ $selectedPriority === $item['priority'] ? 'ring-2 ring-inset ring-gray-900 dark:ring-white' : '' }}">
                    </a>
                @endif
            @endforeach
        </div>

        <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ($priorityStats as $item)
                <a href="{{ request()->fullUrlWithQuery(['priority' => $item['priority']]) }}"
                    class="rounded-xl border p-4 transition hover:shadow-sm
                {{ $selectedPriority === $item['priority']
                    ? 'border-brand-500 bg-brand-50 dark:border-brand-500 dark:bg-brand-500/10'
                    : 'border-gray-200 bg-white hover:border-gray-300 dark:border-gray-800 dark:bg-gray-950 dark:hover:border-gray-700' }}">

                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full {{ $priorityColors[$item['priority']] }}"></span>

                        <span class="text-sm font-medium {{ $priorityTextColors[$item['priority']] }}">
                            {{ $item['priority'] }}
                        </span>
                    </div>

                    <div class="mt-3 flex items-end justify-between gap-2">
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">
                            {{ number_format($item['count']) }}
                        </p>

                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">
                            {{ $item['percentage'] }}%
                        </p>
                    </div>

                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                        Click to filter tickets
                    </p>
                </a>
            @endforeach
        </div>
    </div>
    <div
        class="mt-8 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-950">
        <div
            class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                        Recent Tickets
                    </h3>

                    <span
                        class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        {{ $recentTickets->total() }} Tickets
                    </span>
                </div>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Overview of your latest support requests.
                </p>
            </div>

            <a href="{{ route('tickets.index', request()->filled('priority') ? ['priority' => request('priority')] : []) }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-brand-500 transition hover:text-brand-600">
                View All Tickets

                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="space-y-4">
            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-950">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px] text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                    Ticket
                                </th>

                                <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                    Customer
                                </th>

                                <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                    Complaints & Actions
                                </th>

                                <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                    Status
                                </th>

                                <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                    Priority
                                </th>

                                <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                    Assigned Technicians
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($recentTickets as $ticket)
                                <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                            {{ $ticket->name }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-400">
                                            #TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                                        </p>

                                        <p class="mt-2 text-xs text-gray-400">
                                            {{ $ticket->reporting_time?->format('d M Y, H:i') ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($ticket->customer?->photo)
                                                <img src="{{ asset('storage/' . $ticket->customer->photo) }}"
                                                    alt="{{ $ticket->customer->username }}"
                                                    class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div
                                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-50 text-sm font-semibold text-brand-600 dark:bg-brand-500/10">
                                                    {{ strtoupper(substr($ticket->customer?->username ?? 'C', 0, 1)) }}
                                                </div>
                                            @endif

                                            <div>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                    {{ $ticket->customer?->username ?? 'Unknown Customer' }}
                                                </p>

                                                <p class="mt-1 text-xs text-gray-400">
                                                    {{ $ticket->customer ? 'PIC : ' . $ticket->customer->pic : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="max-w-[320px] space-y-3">
                                            <div>
                                                <p class="mb-1 text-xs font-semibold uppercase text-gray-400">
                                                    Complaint
                                                </p>

                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $ticket->complaint ?: '-' }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="mb-1 text-xs font-semibold uppercase text-gray-400">
                                                    Action Taken
                                                </p>

                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $ticket->action ?: '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        @php
                                            $statusClass = match (strtolower($ticket->ticket_status ?? '')) {
                                                'new'
                                                    => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                                                'in progress'
                                                    => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                                'technician en route'
                                                    => 'bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400',
                                                'in repair'
                                                    => 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',
                                                'awaiting customer'
                                                    => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-400',
                                                'completed'
                                                    => 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400',
                                                'cancelled'
                                                    => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
                                                default
                                                    => 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400',
                                            };
                                        @endphp

                                        <span
                                            class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-medium {{ $statusClass }}">
                                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                            {{ $ticket->ticket_status ?? 'Unknown' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-center">
                                        @php
                                            $priorityBadge = match ($ticket->priority) {
                                                'Urgent'
                                                    => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
                                                'High'
                                                    => 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',
                                                'Medium'
                                                    => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                                                'Low'
                                                    => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                                default
                                                    => 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400',
                                            };
                                        @endphp

                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $priorityBadge }}">
                                            {{ $ticket->priority ?? 'Medium' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            @forelse ($ticket->technicians as $technician)
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    {{ $technician->technician_name }}
                                                </span>
                                            @empty
                                                <span class="text-sm text-gray-400">
                                                    Unassigned
                                                </span>
                                            @endforelse

                                            <a href="{{ route('tickets.show', $ticket->id) }}" title="View Ticket"
                                                class="group flex h-9 w-9 items-center justify-center rounded-lg border border-purple-100 bg-purple-50 text-purple-600 transition hover:border-purple-200 hover:bg-purple-600 hover:text-white dark:border-purple-500/20 dark:bg-purple-500/10 dark:text-purple-400 dark:hover:bg-purple-500 dark:hover:text-white">
                                                <svg class="h-4 w-4 transition-transform group-hover:scale-110"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" />
                                                    <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <p class="text-sm font-medium text-gray-500">
                                            No tickets found.
                                        </p>

                                        <p class="mt-1 text-xs text-gray-400">
                                            Create a new ticket to get started.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div
            class="flex flex-col gap-4 border-t border-gray-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Showing
                <span class="font-medium text-gray-800 dark:text-white">
                    {{ $recentTickets->firstItem() ?? 0 }}
                </span>
                to
                <span class="font-medium text-gray-800 dark:text-white">
                    {{ $recentTickets->lastItem() ?? 0 }}
                </span>
                of
                <span class="font-medium text-gray-800 dark:text-white">
                    {{ $recentTickets->total() }}
                </span>
                active tickets
            </p>

            @if ($recentTickets->hasPages())
                <div>
                    {{ $recentTickets->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
