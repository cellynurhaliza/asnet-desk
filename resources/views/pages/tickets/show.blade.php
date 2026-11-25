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
                            #{{ $ticket->ticket_code }}
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

            @if ($ticket->customer_confirmation_status === 'Not Resolved' && $ticket->customer_resolution_note)
                <div class="rounded-xl border border-red-200 bg-red-50 p-5 dark:border-red-500/30 dark:bg-red-500/10">

                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v4m0 4h.01M10.29 3.86l-7.4 12.82A2 2 0 004.62 19h14.76a2 2 0 001.73-2.32l-7.4-12.82a2 2 0 00-3.46 0Z" />
                        </svg>

                        <h3 class="font-semibold text-red-700 dark:text-red-400">
                            Customer Reported Unresolved Issue
                        </h3>
                    </div>

                    <p class="mt-3 text-sm text-gray-700 dark:text-gray-300">
                        Customer:
                        <strong>{{ $ticket->customer->username ?? '-' }}</strong>
                    </p>

                    <div class="mt-3 rounded-lg border border-red-200 bg-white p-4 dark:border-red-500/20 dark:bg-gray-900">
                        <p class="mb-2 text-xs font-semibold uppercase text-gray-500">
                            Customer Feedback
                        </p>

                        <p class="whitespace-pre-line text-sm text-gray-700 dark:text-gray-300">
                            {{ $ticket->customer_resolution_note }}</p>
                    </div>

                    <p class="mt-3 text-xs text-red-600 dark:text-red-400">
                        Ticket status remains Awaiting Customer.
                        Please review the issue and continue handling the ticket.
                    </p>
                </div>
            @endif


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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>

                        <form action="{{ route('tickets.addNote', $ticket->id) }}" method="POST"
                            class="mt-4 space-y-3">
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
                            class="flex cursor-pointer list-none items-center justify-between text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <span class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Change History
                                <span
                                    class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                    {{ $ticket->activities->where('activity_type', '!=', 'Note Added')->count() }}
                                </span>
                            </span>

                            <svg class="h-4 w-4 text-gray-400 transition-transform duration-200 group-open:rotate-180"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>

                        <div class="mt-5">
                            @forelse ($ticket->activities->where('activity_type', '!=', 'Note Added')->sortBy('id') as $activity)
                                <div class="relative flex gap-4 pb-6">
                                    <div class="relative flex w-7 shrink-0 justify-center">
                                        <span
                                            class="relative z-10 mt-1 h-3 w-3 rounded-full ring-4
                        {{ $activity->activity_type === 'Photo Uploaded'
                            ? 'bg-green-500 ring-green-50 dark:ring-green-500/10'
                            : 'bg-blue-500 ring-blue-50 dark:ring-blue-500/10' }}">
                                        </span>

                                        @unless ($loop->last)
                                            <span class="absolute top-4 h-full w-px bg-gray-200 dark:bg-gray-700"></span>
                                        @endunless
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                            <span class="text-sm font-semibold text-gray-800 dark:text-white">
                                                {{ $activity->performed_by ?? 'Administrator' }}
                                            </span>

                                            <span class="text-xs text-gray-400">·</span>

                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                                {{ $activity->activity_type }}
                                            </span>
                                        </div>

                                        <p
                                            class="mt-1 whitespace-pre-line text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                                            {{ $activity->description }}
                                        </p>
                                        

                                        @if ($activity->activity_type === 'Photos Uploaded' && $activity->photos->isNotEmpty())
                                            <div class="mt-3 flex flex-wrap gap-3">
                                                @foreach ($activity->photos as $photo)
                                                    <button type="button"
                                                        onclick="openTicketPhoto(@js(asset('storage/' . $photo->photo_path)))"
                                                        class="group overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                                                        <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                                            alt="Ticket Photo"
                                                            class="h-24 w-24 object-cover transition duration-200 group-hover:scale-105">
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif

                                        <p class="mt-2 text-xs text-gray-400">
                                            {{ $activity->created_at?->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="rounded-xl border border-dashed border-gray-200 px-4 py-8 text-center dark:border-gray-700">
                                    <svg class="mx-auto h-8 w-8 text-gray-300 dark:text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-400">No change history available.</p>
                                </div>
                            @endforelse
                        </div>
                    </details>

                    <div id="ticketPhotoModal"
                        class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/80 p-4"
                        onclick="closeTicketPhoto()">

                        <div class="relative max-h-[90vh] max-w-5xl" onclick="event.stopPropagation()">

                            <button type="button" onclick="closeTicketPhoto()"
                                class="absolute -right-3 -top-3 z-10 flex h-9 w-9 items-center justify-center rounded-full bg-white text-gray-700 shadow-lg transition hover:bg-gray-200">

                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <img id="ticketPhotoPreview" src="" alt="Ticket Photo Preview"
                                class="max-h-[85vh] max-w-full rounded-xl object-contain shadow-2xl">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4 xl:col-span-4">

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">

                <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
                    <h3 class="font-semibold text-gray-800 dark:text-white">Handling</h3>
                </div>

                <form action="{{ route('tickets.updateHandling', $ticket->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-4 p-5">
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

                    <div class="border-y border-gray-200 py-4 dark:border-gray-800">
                        <div>
                            <label for="coordinates"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Coordinates
                            </label>

                            <input type="text" id="coordinates" name="coordinates"
                                value="{{ old(
                                    'coordinates',
                                    $ticket->latitude && $ticket->longitude ? $ticket->latitude . ', ' . $ticket->longitude : '',
                                ) }}"
                                placeholder="Example: 6.483694°S, 106.472572°E"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">

                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                                Enter latitude and longitude separated by a comma.
                            </p>

                            @error('coordinates')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        @if ($ticket->latitude && $ticket->longitude)
                            <div class="mt-4">
                                <p class="text-xs text-gray-400">Location Point</p>

                                <p class="mt-1 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $ticket->latitude }}, {{ $ticket->longitude }}
                                </p>

                                <a href="https://www.google.com/maps?q={{ $ticket->latitude }},{{ $ticket->longitude }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="mt-2 inline-flex text-sm font-medium text-blue-600 hover:underline">
                                    Open in Google Maps
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="border-gray-200 py-5 dark:border-gray-800">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Ticket Photos
                            </label>

                            <span class="text-xs text-gray-400">Optional</span>
                        </div>

                        <label for="ticketPhotos"
                            class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-7 text-center transition hover:border-blue-400 hover:bg-blue-50/50 dark:border-gray-700 dark:bg-gray-900/50 dark:hover:border-blue-500 dark:hover:bg-blue-500/5">

                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-gray-500 shadow-sm dark:bg-gray-800 dark:text-gray-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 16V4m0 0L8 8m4-4l4 4M4 16v3a1 1 0 001 1h14a1 1 0 001-1v-3" />
                                </svg>
                            </div>

                            <p class="mt-3 text-sm font-medium text-gray-700 dark:text-gray-200">
                                Click to upload photos
                            </p>
                            <p class="mt-1 text-xs text-gray-400">
                                JPG, JPEG, PNG or WEBP
                            </p>
                            <p class="mt-1 text-xs text-gray-400">
                                Maximum 10 photos, 5 MB each
                            </p>

                            <input type="file" id="ticketPhotos" name="photos[]"
                                accept="image/jpeg,image/png,image/webp" multiple class="hidden">
                        </label>

                        <div id="ticketPhotoPreviewList" class="mt-4 hidden">
                            <div class="mb-3 flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Selected Photos
                                </p>

                                <button type="button" id="clearTicketPhotos"
                                    class="text-xs font-medium text-red-600 hover:text-red-700">
                                    Clear All
                                </button>
                            </div>

                            <div id="ticketPhotoPreviewItems"
                                class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4"></div>
                        </div>

                        <p id="ticketPhotoError" class="mt-2 hidden text-sm text-red-600"></p>

                        <p id="ticketPhotoError" class="mt-2 hidden text-xs text-red-500"></p>

                        @error('photos')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror

                        @error('photos.*')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
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
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div>
                        <p class="text-xs text-gray-400">Reported At (WIB)</p>
                        <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $ticket->reporting_time?->format('Y-m-d H:i:s') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Issue Started At (WIB)</p>
                        <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $ticket->incident_started_at?->format('Y-m-d H:i:s') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Completed At (WIB)</p>
                        <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $ticket->resolved_at?->format('Y-m-d H:i:s') ?? 'Not completed' }}
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

        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('ticketPhotos');
            const previewList = document.getElementById('ticketPhotoPreviewList');
            const previewItems = document.getElementById('ticketPhotoPreviewItems');
            const errorMessage = document.getElementById('ticketPhotoError');

            if (!input || !previewList || !previewItems) return;

            let selectedFiles = new DataTransfer();

            function showError(message = '') {
                if (errorMessage) {
                    errorMessage.textContent = message;
                    errorMessage.classList.toggle('hidden', !message);
                }
            }

            function renderPreview() {
                previewItems.innerHTML = '';

                const files = Array.from(selectedFiles.files);

                previewList.classList.toggle('hidden', files.length === 0);

                files.forEach((file, index) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative group';

                    const image = document.createElement('img');
                    image.src = URL.createObjectURL(file);
                    image.alt = file.name;
                    image.className =
                        'h-32 w-full rounded-xl border border-gray-200 object-cover dark:border-gray-700';

                    image.onload = () => URL.revokeObjectURL(image.src);

                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.innerHTML = '&times;';
                    removeButton.className =
                        'absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-red-600 text-lg text-white shadow hover:bg-red-700';

                    removeButton.addEventListener('click', function() {
                        const updatedFiles = new DataTransfer();

                        files.forEach((item, itemIndex) => {
                            if (itemIndex !== index) {
                                updatedFiles.items.add(item);
                            }
                        });

                        selectedFiles = updatedFiles;
                        input.files = selectedFiles.files;

                        renderPreview();
                        showError();
                    });

                    const fileName = document.createElement('p');
                    fileName.textContent = file.name;
                    fileName.className =
                        'mt-2 truncate text-xs text-gray-600 dark:text-gray-400';

                    wrapper.appendChild(image);
                    wrapper.appendChild(removeButton);
                    wrapper.appendChild(fileName);

                    previewItems.appendChild(wrapper);
                });
            }

            input.addEventListener('change', function() {
                const newFiles = Array.from(this.files);
                const currentFiles = Array.from(selectedFiles.files);

                const combinedFiles = [...currentFiles];

                newFiles.forEach(file => {
                    const alreadyExists = combinedFiles.some(existing =>
                        existing.name === file.name &&
                        existing.size === file.size &&
                        existing.lastModified === file.lastModified
                    );

                    if (!alreadyExists) {
                        combinedFiles.push(file);
                    }
                });

                if (combinedFiles.length > 10) {
                    showError('Maksimal 10 foto yang dapat dipilih.');
                    input.files = selectedFiles.files;
                    return;
                }

                const invalidFile = combinedFiles.find(file =>
                    !['image/jpeg', 'image/png', 'image/webp'].includes(file.type)
                );

                if (invalidFile) {
                    showError('Format foto harus JPG, JPEG, PNG, atau WEBP.');
                    input.files = selectedFiles.files;
                    return;
                }

                const oversizedFile = combinedFiles.find(file => file.size > 5 * 1024 * 1024);

                if (oversizedFile) {
                    showError('Ukuran setiap foto maksimal 5 MB.');
                    input.files = selectedFiles.files;
                    return;
                }

                selectedFiles = new DataTransfer();

                combinedFiles.forEach(file => {
                    selectedFiles.items.add(file);
                });

                input.files = selectedFiles.files;

                showError();
                renderPreview();
            });

            const clearButton = document.getElementById('clearTicketPhotos');

            if (clearButton) {
                clearButton.addEventListener('click', function() {
                    selectedFiles = new DataTransfer();
                    input.files = selectedFiles.files;

                    showError();
                    renderPreview();
                });
            }
        });

        function openTicketPhoto(imageUrl) {
            const modal = document.getElementById('ticketPhotoModal');
            const image = document.getElementById('ticketPhotoPreview');

            image.src = imageUrl;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeTicketPhoto() {
            const modal = document.getElementById('ticketPhotoModal');
            const image = document.getElementById('ticketPhotoPreview');

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            image.src = '';
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeTicketPhoto();
            }
        });
    </script>

    <div id="ticketPhotoModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/80 p-4"
        onclick="closeTicketPhoto()">

        <button type="button" onclick="closeTicketPhoto()"
            class="absolute right-5 top-5 rounded-full bg-white/10 p-2 text-white hover:bg-white/20">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <img id="ticketPhotoPreview" src="" alt="Ticket Photo Preview" onclick="event.stopPropagation()"
            class="max-h-[85vh] max-w-full rounded-lg object-contain shadow-2xl">
    </div>

@endsection
