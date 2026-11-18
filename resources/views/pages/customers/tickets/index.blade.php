@extends('layouts.app')

@section('title', 'All Tickets')
@section('page-title', 'All Tickets')

@section('content')
    @php
        $statusColors = [
            'New' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
            'In Progress' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-400',
            'Technician En Route' => 'bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400',
            'In Repair' => 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',
            'Awaiting Customer' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
            'Completed' => 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400',
            'Cancelled' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
        ];

        $priorityColors = [
            'Urgent' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
            'High' => 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',
            'Medium' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
            'Low' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300',
        ];

        $priorities = ['Urgent', 'High', 'Medium', 'Low'];
    @endphp

    <div class="space-y-5">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    My Tickets
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    View and track all your support requests.
                </p>
            </div>

            <a href="{{ route('customer.tickets.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                Create New Ticket
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

            <form method="GET" action="{{ route('customer.tickets.index') }}"
                class="flex flex-col gap-3 border-b border-gray-200 p-4 dark:border-gray-800 sm:flex-row sm:items-center">

                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m21 21-4.35-4.35M19 11a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                    </svg>

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search ticket name or complaint..."
                        class="h-11 w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>

                <select name="status"
                    class="h-11 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">-- Select Status --</option>

                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>

                <select name="priority"
                    class="h-11 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">-- Select Priority --</option>

                    @foreach ($priorities as $priority)
                        <option value="{{ $priority }}" @selected(request('priority') === $priority)>
                            {{ $priority }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                    class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                    Search
                </button>

                @if (request()->filled('search') || request()->filled('status') || request()->filled('priority'))
                    <a href="{{ route('customer.tickets.index') }}"
                        class="rounded-lg border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        Reset
                    </a>
                @endif
            </form>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left">

                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                Ticket
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                Category
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                Reporting Time
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                Assigned Technicians
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                Priority
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                Status
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                        @forelse ($tickets as $ticket)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">

                                <td class="px-5 py-4">
                                    <div class="font-medium text-gray-800 dark:text-white">
                                        {{ $ticket->name }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">
                                        #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $ticket->category?->category_name ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $ticket->reporting_time?->format('d M Y, H:i') ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    @forelse ($ticket->technicians as $technician)
                                        <span class="mb-1 inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                            {{ $technician->technician_name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-400">
                                            Not Assigned
                                        </span>
                                    @endforelse
                                </td>

                                <td class="px-5 py-4">
                                    @php
                                        $priority = $ticket->priority ?? 'Medium';
                                    @endphp

                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $priorityColors[$priority] ?? $priorityColors['Medium'] }}">
                                        {{ $priority }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $statusColors[$ticket->ticket_status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $ticket->ticket_status }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('customer.tickets.show', $ticket->id) }}"
                                        class="inline-flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                        View Detail
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                        <svg class="h-7 w-7 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z" />
                                        </svg>
                                    </div>

                                    <h3 class="mt-4 font-semibold text-gray-800 dark:text-white">
                                        No Tickets Found
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500">
                                        You haven't created any tickets yet, or no results match your search.
                                    </p>

                                    <a href="{{ route('customer.tickets.create') }}"
                                        class="mt-4 inline-flex rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                                        Create Your First Ticket
                                    </a>

                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            @if ($tickets->hasPages() || $tickets->total() > 0)
                <div class="flex flex-col gap-3 border-t border-gray-200 px-5 py-4 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }}
                        of {{ $tickets->total() }} tickets
                    </p>

                    <div>
                        {{ $tickets->links() }}
                    </div>

                </div>
            @endif

        </div>
    </div>
@endsection
