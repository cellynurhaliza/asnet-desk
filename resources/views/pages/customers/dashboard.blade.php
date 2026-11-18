@extends('layouts.app')

@section('title', 'Customer Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    @php
        $customer = auth('customer')->user();

        $priorityColors = [
            'Urgent' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
            'High' => 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',
            'Medium' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
            'Low' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
        ];

        $statusColors = [
            'New' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
            'In Progress' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-400',
            'Technician En Route' => 'bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400',
            'In Repair' => 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',
            'Awaiting Customer' => 'bg-pink-50 text-pink-600 dark:bg-pink-500/10 dark:text-pink-400',
            'Completed' => 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400',
            'Cancelled' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
        ];
    @endphp

    <div class="space-y-6">

        <div class="rounded-2xl bg-brand-500 p-6 text-white shadow-theme-sm">
            <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
                <div>
                    <p class="text-sm text-white/80">Customer Portal</p>

                    <h3 class="mt-2 text-2xl font-semibold">
                        Welcome, {{ $customer->username }}!
                    </h3>

                    <p class="mt-2 text-sm text-white/80">
                        Manage your support requests and track your ticket progress here.
                    </p>
                </div>

                <a href="{{ route('customer.tickets.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-semibold text-brand-600 transition hover:bg-gray-100">
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
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-gray-950">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ $stat['title'] }}
                    </p>

                    <h3 class="mt-3 text-3xl font-semibold text-gray-800 dark:text-white">
                        {{ number_format($stat['value']) }}
                    </h3>
                </div>
            @endforeach
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-950">

            <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">
                        Recent Tickets
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Your latest support requests
                    </p>
                </div>

                <a href="{{ route('customer.tickets.index') }}"
                    class="text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400">
                    View All Tickets →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left">

                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">Ticket</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">Category</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">Technicians</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">Date</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">Priority</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">Status</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                        @forelse ($recentTickets as $ticket)
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-900">

                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                        {{ $ticket->name }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        #TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                                    </p>

                                    <p class="mt-1 max-w-[200px] truncate text-xs text-gray-400">
                                        {{ $ticket->complaint }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $ticket->category?->category_name ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex flex-col gap-1.5">
                                        @forelse ($ticket->technicians as $technician)
                                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                                {{ $technician->technician_name }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-gray-400">
                                                Not Assigned
                                            </span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $ticket->reporting_time?->format('d M Y') ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    @php
                                        $priority = $ticket->priority ?? 'Medium';
                                        $priorityClass = $priorityColors[$priority] ?? $priorityColors['Medium'];
                                    @endphp

                                    <span class="inline-flex whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                                        {{ $priority }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    @php
                                        $status = $ticket->ticket_status ?? 'Unknown';
                                        $statusClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400';
                                    @endphp

                                    <span class="inline-flex whitespace-nowrap rounded-full px-3 py-1 text-xs font-medium {{ $statusClass }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <a href="{{ route('customer.tickets.show', $ticket->id) }}"
                                        class="inline-flex whitespace-nowrap text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400">
                                        View Details
                                    </a>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                        <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                        class="mt-4 inline-flex rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                                        Create Your First Ticket
                                    </a>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            @if ($recentTickets->hasPages())
                <div class="flex flex-col gap-3 border-t border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">
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
                        tickets
                    </p>

                    {{ $recentTickets->links() }}
                </div>
            @endif

        </div>

    </div>

@endsection
