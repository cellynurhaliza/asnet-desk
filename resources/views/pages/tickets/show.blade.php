@extends('layouts.app')

@section('title', 'Ticket Details')
@section('page-title', 'Ticket Details')

@section('content')
    @php
        $statusStyles = [
            'New' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
            'In Progress' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
            'Technician En Route' => 'bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400',
            'In Repair' => 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',
            'Awaiting Customer' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-400',
            'Completed' => 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400',
            'Cancelled' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
        ];

        $statusClass =
            $statusStyles[$ticket->ticket_status] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300';
        $statusLabel = $ticket->ticket_status;

        $priorityStyles = [
            'Urgent' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
            'High' => 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',
            'Medium' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
            'Low' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300',
        ];

        $priorityClass = $priorityStyles[$ticket->priority ?? 'Medium'] ?? $priorityStyles['Medium'];
    @endphp

    <div class="grid grid-cols-1 items-start gap-5 xl:grid-cols-12">

        <div class="space-y-5 xl:col-span-8">

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">

                <div
                    class="flex flex-col gap-3 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-base font-semibold text-gray-800 dark:text-white">
                            #TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                        </h2>

                        <span class="inline-flex rounded-lg px-3 py-1.5 text-xs font-medium {{ $statusClass }}">
                            <span class="mr-2">●</span>
                            {{ $statusLabel }}
                        </span>

                        <span class="inline-flex rounded-lg px-3 py-1.5 text-xs font-semibold {{ $priorityClass }}">
                            {{ $ticket->priority ?? 'Medium' }} Priority
                        </span>
                    </div>

                    <a href="{{ route('tickets.index') }}"
                        class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        Back
                    </a>
                </div>

                <div class="space-y-4 p-5">

                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-blue-50 text-base font-semibold text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">

                            @if ($ticket->customer?->photo)
                                <img src="{{ asset('storage/' . $ticket->customer->photo) }}"
                                    alt="{{ $ticket->customer->username }}" class="h-full w-full object-cover"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                <span class="hidden h-full w-full items-center justify-center">
                                    {{ strtoupper(substr($ticket->customer?->username ?? 'C', 0, 1)) }}
                                </span>
                            @else
                                {{ strtoupper(substr($ticket->customer?->username ?? 'C', 0, 1)) }}
                            @endif

                        </div>

                        <div class="min-w-0">
                            <h3 class="font-semibold text-gray-800 dark:text-white">
                                {{ $ticket->customer?->username ?? '-' }}
                            </h3>

                            <p class="mt-1 text-sm text-gray-400">
                                PIC: {{ $ticket->customer?->pic ?? '-' }}
                                ·
                                {{ $ticket->customer?->phone_number ?? 'Without a phone number' }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                        <p class="mb-3 text-xs font-medium text-gray-400">Complaint</p>
                        <p class="whitespace-pre-line text-sm leading-6 text-gray-800 dark:text-gray-200">
                            {{ $ticket->complaint ?: '-' }}
                        </p>
                    </div>

                    <div
                        class="rounded-xl border border-blue-100 bg-blue-50/50 p-4 dark:border-blue-500/20 dark:bg-blue-500/5">
                        <p class="mb-3 text-xs font-medium text-gray-400">Response / Action</p>
                        <p class="whitespace-pre-line text-sm leading-6 text-gray-800 dark:text-gray-200">
                            {{ $ticket->action ?: 'No action has been taken yet.' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                        <div class="mb-3 flex items-center justify-between">
                            <p class="text-xs font-medium text-gray-400">
                                Assigned Technicians
                            </p>

                            <span
                                class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                                {{ $ticket->technicians->count() }} Assigned
                            </span>
                        </div>

                        @forelse ($ticket->technicians as $technician)
                            <div class="flex items-center gap-3 py-2">
                                <div
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                                    {{ strtoupper(substr($technician->technician_name, 0, 1)) }}
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">
                                        {{ $technician->technician_name }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">
                                No technician assigned yet.
                            </p>
                        @endforelse
                    </div>

                    <details class="group border-t border-gray-200 pt-4 dark:border-gray-800">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between text-sm font-semibold text-gray-600 dark:text-gray-300">
                            <span class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 110-18 9 9 0 010 18z" />
                                </svg>
                                Customer Information
                            </span>

                            <svg class="h-4 w-4 transition group-open:rotate-180" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>

                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                            <div>
                                <p class="text-xs text-gray-400">Customer Name</p>
                                <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ $ticket->customer?->username ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-400">PIC</p>
                                <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ $ticket->customer?->pic ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-400">Customer ID (CID)</p>
                                <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ $ticket->customer?->cid ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-400">Email Address</p>
                                <p class="mt-1 break-all text-sm text-gray-700 dark:text-gray-200">
                                    {{ $ticket->customer?->email ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-400">Phone Number</p>
                                <p class="mt-1 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $ticket->customer?->phone_number ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-400">Service Type</p>
                                <p class="mt-1 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $ticket->customer?->service_type ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-400">Account Type</p>
                                <p class="mt-1 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $ticket->customer?->account_type ?? '-' }}
                                </p>
                            </div>

                        </div>
                    </details>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">

                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-800">
                    <h3 class="font-semibold text-gray-800 dark:text-white">
                        Messages & Notes
                    </h3>

                    <span
                        class="flex h-7 min-w-7 items-center justify-center rounded-full bg-blue-50 px-2 text-xs font-semibold text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                        {{ $ticket->activities->count() }}
                    </span>
                </div>

                <div class="space-y-5 p-5">

                    @php
                        $notes = $ticket->activities->where('activity_type', 'Note Added');
                    @endphp

                    @forelse ($notes as $note)
                        <div class="rounded-xl border-l-4 border-green-500 bg-gray-50 p-4 dark:bg-gray-900">
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                    {{ $note->performed_by ?? 'Administrator' }}
                                </p>

                                <p class="text-xs text-gray-400">
                                    {{ $note->created_at?->format('Y-m-d H:i:s') }}
                                </p>
                            </div>

                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-600 dark:text-gray-300">
                                {{ $note->description }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">There are no additional notes.</p>
                    @endforelse

                    <details class="group border-b border-gray-200 pb-4 dark:border-gray-800">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between text-sm font-medium text-gray-600 dark:text-gray-300">
                            <span class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h8m-8 4h5m-8 5l-2 2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5z" />
                                </svg>
                                Add a Note
                            </span>

                            <svg class="h-4 w-4 transition group-open:rotate-180" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>

                        <form action="{{ route('tickets.addNote', $ticket->id) }}" method="POST" class="mt-4 space-y-3">
                            @csrf

                            <textarea name="note" rows="3" required maxlength="2000" placeholder="Write a message or note..."
                                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"></textarea>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                                    Add Note
                                </button>
                            </div>
                        </form>
                    </details>

                    <details class="group" open>
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between text-sm font-semibold text-gray-600 dark:text-gray-300">
                            <span class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Change History
                            </span>

                            <svg class="h-4 w-4 transition group-open:rotate-180" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>

                        <div class="mt-5 space-y-0">
                            @forelse ($ticket->activities->where('activity_type', '!=', 'Note Added')->sortBy('id') as $activity)
                                <div class="relative flex gap-4 pb-6 last:pb-0">
                                    <div class="relative flex w-7 shrink-0 justify-center">
                                        <span
                                            class="absolute top-1 h-3 w-3 rounded-full bg-blue-400 ring-4 ring-blue-50 dark:ring-blue-500/10"></span>

                                        @unless ($loop->last)
                                            <span class="absolute top-4 h-full w-px bg-gray-200 dark:bg-gray-700"></span>
                                        @endunless
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            <span class="font-semibold text-gray-800 dark:text-white">
                                                {{ $activity->performed_by ?? 'Administrator' }}
                                            </span>
                                            ·
                                        <p class="whitespace-pre-line text-sm text-gray-600 dark:text-gray-300">
                                            {{ $activity->description }}
                                        </p>
                                        </p>

                                        <p class="mt-2 text-xs text-gray-400">
                                            {{ $activity->created_at?->format('Y-m-d H:i:s') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400">No change history available.</p>
                            @endforelse
                        </div>
                    </details>
                </div>
            </div>
        </div>

        <div class="space-y-4 xl:col-span-4">

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">

                <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
                    <h3 class="font-semibold text-gray-800 dark:text-white">Handling</h3>
                </div>

                <form action="{{ route('tickets.updateHandling', $ticket->id) }}" method="POST" class="space-y-4 p-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Ticket Status
                        </label>

                        <select name="ticket_status" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-3 text-sm text-gray-700 outline-none focus:border-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">

                            @php
                                $statuses = [
                                    'New',
                                    'In Progress',
                                    'Technician En Route',
                                    'In Repair',
                                    'Awaiting Customer',
                                    'Completed',
                                    'Cancelled',
                                ];
                            @endphp

                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected($ticket->ticket_status === $status)>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Response / Action
                            <span class="text-xs font-normal text-gray-400">Optional</span>
                        </label>

                        <textarea name="action" rows="4" placeholder="Enter a response or action..."
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-3 text-sm text-gray-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">{{ old('action', $ticket->action) }}</textarea>

                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <button type="button" data-action="OK"
                                class="quick-action rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-blue-600 hover:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                OK
                            </button>

                            <button type="button" data-action="Back to normal"
                                class="quick-action rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-blue-600 hover:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                Back to Normal
                            </button>
                        </div>
                    </div>

                    <details class="group border-y border-gray-200 py-4 dark:border-gray-800">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between text-sm font-medium text-gray-600 dark:text-gray-300">
                            <span class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M7 12h10m-7 6h4" />
                                </svg>
                                Additional Options
                            </span>

                            <svg class="h-4 w-4 transition group-open:rotate-180" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>

                        <div>
                            <label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Assigned Technicians
                            </label>

                            <div class="space-y-2">
                                @forelse ($technicians as $technician)
                                    <label
                                        class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-3 transition hover:border-blue-400 hover:bg-blue-50/50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800 dark:has-[:checked]:border-blue-500 dark:has-[:checked]:bg-blue-500/10">

                                        <input type="checkbox" name="technician_ids[]" value="{{ $technician->id }}"
                                            @checked(in_array($technician->id, old('technician_ids', $ticket->technicians->pluck('id')->toArray())))
                                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                                            {{ strtoupper(substr($technician->technician_name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                {{ $technician->technician_name }}
                                            </p>
                                        </div>

                                        <span class="text-xs text-gray-400">
                                            Select
                                        </span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-400">
                                        No technicians available.
                                    </p>
                                @endforelse
                            </div>

                            @error('technician_ids')
                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror

                            @error('technician_ids.*')
                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror

                            <p class="mt-2 text-xs text-gray-400">
                                You can select multiple technicians.
                            </p>
                        </div>
                    </details>

                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Changes
                    </button>
                </form>

                <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-800">
                    <details class="group">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between text-sm font-medium text-red-600">
                            <span class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4h6v3m-9 0h12" />
                                </svg>
                                Delete Ticket
                            </span>

                            <svg class="h-4 w-4 transition group-open:rotate-180" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>

                        <div class="mt-3 rounded-lg bg-red-50 p-3 dark:bg-red-500/10">
                            <p class="mb-3 text-xs leading-5 text-red-600 dark:text-red-400">
                                Are you sure you want to delete this ticket? Its activity history will also be deleted.
                            </p>

                            <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="w-full rounded-lg border border-red-200 bg-white px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 dark:border-red-500/30 dark:bg-transparent">
                                    Yes, Delete Ticket
                                </button>
                            </form>
                        </div>
                    </details>
                </div>
            </div>

            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-950">
                <div class="grid grid-cols-2 gap-5">

                    <div>
                        <p class="text-xs text-gray-400">Reported At (WIB)</p>
                        <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $ticket->reporting_time?->format('Y-m-d H:i:s') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Completed At (WIB)</p>
                        <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $ticket->ticket_status === 'Completed' ? $ticket->updated_at?->format('Y-m-d H:i:s') ?? '-' : 'Not completed' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Ticket Age</p>
                        <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $ticket->created_at?->diffForHumans() ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Created At (WIB)</p>
                        <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $ticket->created_at?->format('Y-m-d H:i:s') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Ticket Priority</p>

                        <p class="mt-1">
                            <span class="inline-flex rounded-lg px-3 py-1.5 text-xs font-semibold {{ $priorityClass }}">
                                {{ $ticket->priority ?? 'Medium' }}
                            </span>
                        </p>
                    </div>

                </div>

                <div class="mt-5 border-t border-gray-200 pt-4 dark:border-gray-800">
                    <details class="group">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between text-sm font-semibold text-gray-600 dark:text-gray-300">
                            <span class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 12l16-9-7 18-2-7-7-2z" />
                                </svg>
                                Telegram Activity
                            </span>

                            <svg class="h-4 w-4 transition group-open:rotate-180" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>

                        <p class="mt-3 text-xs leading-5 text-gray-400">
                            Telegram activity integration has not been configured.
                        </p>
                    </details>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.quick-action').forEach(button => {
            button.addEventListener('click', function() {
                const textarea = document.querySelector('textarea[name="action"]');

                if (textarea) {
                    textarea.value = this.dataset.action;
                    textarea.focus();
                }
            });
        });
    </script>
@endsection
