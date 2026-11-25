@extends('layouts.app')

@section('title', 'Customer Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    @php
        $customer = auth('customer')->user();

        $priorityColors = [
            'Urgent' => 'bg-rose-50 text-rose-700 ring-rose-600/10 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-500/20',
            'High' => 'bg-orange-50 text-orange-700 ring-orange-600/10 dark:bg-orange-500/10 dark:text-orange-400 dark:ring-orange-500/20',
            'Medium' => 'bg-amber-50 text-amber-700 ring-amber-600/10 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-500/20',
            'Low' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20',
        ];

        $statusColors = [
            'New' => 'bg-blue-50 text-blue-700 ring-blue-600/10 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/20',
            'In Progress' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/10 dark:bg-yellow-500/10 dark:text-yellow-400 dark:ring-yellow-500/20',
            'Technician En Route' => 'bg-purple-50 text-purple-700 ring-purple-600/10 dark:bg-purple-500/10 dark:text-purple-400 dark:ring-purple-500/20',
            'In Repair' => 'bg-orange-50 text-orange-700 ring-orange-600/10 dark:bg-orange-500/10 dark:text-orange-400 dark:ring-orange-500/20',
            'Awaiting Customer' => 'bg-amber-50 text-amber-700 ring-amber-600/10 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-500/20',
            'Completed' => 'bg-green-50 text-green-700 ring-green-600/10 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20',
            'Cancelled' => 'bg-red-50 text-red-700 ring-red-600/10 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20',
        ];
    @endphp

    <div class="space-y-6">

        <div class="relative overflow-hidden rounded-2xl bg-brand-500 p-6 shadow-sm sm:p-8">
            <div class="absolute -right-12 -top-12 h-48 w-48 rounded-full bg-white/10"></div>
            <div class="absolute -right-5 bottom-0 h-32 w-32 rounded-full bg-white/10"></div>

            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="max-w-xl">
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-medium text-white">
                        <span class="h-2 w-2 rounded-full bg-green-300"></span>
                        Customer Portal
                    </div>

                    <h3 class="mt-4 text-2xl font-bold text-white sm:text-3xl">
                        Welcome, {{ $customer->username }}!
                    </h3>

                    <p class="mt-2 max-w-lg text-sm leading-relaxed text-white/80">
                        Manage your support requests, monitor ticket progress and stay updated with your service issues.
                    </p>
                </div>

                <a href="{{ route('customer.tickets.create') }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-brand-600 shadow-sm transition hover:bg-gray-100">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 5v14m-7-7h14" />
                    </svg>
                    Create New Ticket
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($statistics as $stat)
                <div class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $stat['title'] }}
                        </p>

                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M8 7h8M8 11h8m-8 4h5M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" />
                            </svg>
                        </div>
                    </div>

                    <h3 class="mt-5 text-3xl font-bold tracking-tight text-gray-800 dark:text-white">
                        {{ number_format($stat['value']) }}
                    </h3>

                    <div class="mt-3 h-1 w-10 rounded-full bg-brand-500/70 transition-all group-hover:w-16"></div>
                </div>
            @endforeach
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="flex flex-col gap-3 border-b border-gray-200 px-5 py-5 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M8 7h8M8 11h8m-8 4h5M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" />
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">
                                Recent Tickets
                            </h3>
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                Your latest support requests
                            </p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('customer.tickets.index') }}"
                    class="inline-flex items-center gap-1 text-sm font-semibold text-brand-600 transition hover:text-brand-700 dark:text-brand-400">
                    View All Tickets
                    <svg class="h-4 w-4 transition-transform hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M5 12h14m-6-6 6 6-6 6" />
                    </svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] text-left">

                    <thead class="bg-gray-50/80 dark:bg-gray-800/40">
                        <tr>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Ticket
                            </th>

                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Category
                            </th>

                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Technicians
                            </th>

                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Date
                            </th>

                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Priority
                            </th>

                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Status
                            </th>

                            <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                        @forelse ($recentTickets as $ticket)
                            <tr class="transition-colors hover:bg-gray-50/80 dark:hover:bg-gray-800/30">

                                <td class="px-5 py-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                    d="M8 7h8M8 11h8m-8 4h5M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" />
                                            </svg>
                                        </div>

                                        <div class="min-w-0">
                                            <p class="max-w-xs truncate text-sm font-semibold text-gray-800 dark:text-white">
                                                {{ $ticket->name }}
                                            </p>

                                            <p class="mt-1 text-xs font-medium text-gray-400">
                                                #{{ $ticket->ticket_code }}
                                            </p>

                                            <p class="mt-1 max-w-[200px] truncate text-xs text-gray-500 dark:text-gray-400">
                                                {{ $ticket->complaint }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $ticket->category?->category_name ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex max-w-[190px] flex-wrap gap-1.5">
                                        @forelse ($ticket->technicians as $technician)
                                            <span class="inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                                {{ $technician->technician_name }}
                                            </span>
                                        @empty
                                            <span class="text-xs italic text-gray-400">
                                                Not Assigned
                                            </span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                        <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M8 2v4m8-4v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2Z" />
                                        </svg>
                                        {{ $ticket->reporting_time?->format('d M Y') ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    @php
                                        $priority = $ticket->priority ?? 'Medium';
                                    @endphp

                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $priorityColors[$priority] ?? $priorityColors['Medium'] }}">
                                        <span class="h-1.5 w-1.5 rounded-full
                                            {{ $priority === 'Urgent' ? 'bg-rose-500' : '' }}
                                            {{ $priority === 'High' ? 'bg-orange-500' : '' }}
                                            {{ $priority === 'Medium' ? 'bg-amber-500' : '' }}
                                            {{ $priority === 'Low' ? 'bg-emerald-500' : '' }}">
                                        </span>
                                        {{ $priority }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    @php
                                        $status = $ticket->ticket_status ?? 'Unknown';
                                        $statusClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-600 ring-gray-500/10 dark:bg-gray-800 dark:text-gray-400';
                                    @endphp

                                    <span class="inline-flex whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('customer.tickets.show', $ticket->id) }}"
                                        title="View Ticket"
                                        class="group mx-auto flex h-9 w-9 items-center justify-center rounded-lg border border-purple-100 bg-purple-50 text-purple-600 transition hover:border-purple-200 hover:bg-purple-600 hover:text-white dark:border-purple-500/20 dark:bg-purple-500/10 dark:text-purple-400 dark:hover:bg-purple-500 dark:hover:text-white">
                                        <svg class="h-4 w-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" />
                                            <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                                        </svg>
                                    </a>
                                </td>
                                
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-14 text-center">
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800">
                                        <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                        </svg>
                                    </div>

                                    <h4 class="mt-4 text-sm font-semibold text-gray-800 dark:text-white">
                                        No Tickets Yet
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        You haven't submitted any support tickets.
                                    </p>

                                    <a href="{{ route('customer.tickets.create') }}"
                                        class="mt-5 inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 5v14m-7-7h14" />
                                        </svg>
                                        Create Your First Ticket
                                    </a>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            @if ($recentTickets->hasPages())
                <div class="flex flex-col gap-3 border-t border-gray-200 px-5 py-4 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Showing
                        <span class="font-semibold text-gray-700 dark:text-gray-200">
                            {{ $recentTickets->firstItem() ?? 0 }}
                        </span>
                        to
                        <span class="font-semibold text-gray-700 dark:text-gray-200">
                            {{ $recentTickets->lastItem() ?? 0 }}
                        </span>
                        of
                        <span class="font-semibold text-gray-700 dark:text-gray-200">
                            {{ $recentTickets->total() }}
                        </span>
                        tickets
                    </p>

                    <div>
                        {{ $recentTickets->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
