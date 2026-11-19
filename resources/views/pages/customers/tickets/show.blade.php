@extends('layouts.app')

@section('title', 'Ticket Detail')
@section('page-title', 'Ticket Detail')

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

        $status = $ticket->ticket_status;
        $priority = $ticket->priority ?? 'Medium';

        $statusClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-600';
        $priorityClass = $priorityColors[$priority] ?? $priorityColors['Medium'];
    @endphp

    <div class="space-y-5">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                        {{ $ticket->name }}
                    </h3>

                    <span class="rounded-full px-3 py-1 text-xs font-medium {{ $statusClass }}">
                        {{ $status }}
                    </span>
                </div>

                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Ticket #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                </p>
            </div>

            <a href="{{ route('customer.tickets.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">

                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>

                Back to My Tickets
            </a>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white">
                        Ticket Overview
                    </h4>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Current information about your support request.
                    </p>
                </div>

                <div
                    class="hidden h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-500 dark:bg-brand-500/10 sm:flex">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Ticket ID
                    </p>
                    <p class="mt-2 text-sm font-semibold text-gray-800 dark:text-white">
                        #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Category
                    </p>
                    <p class="mt-2 text-sm font-semibold text-gray-800 dark:text-white">
                        {{ $ticket->category?->category_name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Reporting Time
                    </p>
                    <p class="mt-2 text-sm font-semibold text-gray-800 dark:text-white">
                        {{ $ticket->reporting_time?->format('d M Y, H:i') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Ticket Priority
                    </p>
                    <div class="mt-2">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $priorityClass }}">
                            {{ $priority }}
                        </span>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Ticket Status
                    </p>
                    <div class="mt-2">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $statusClass }}">
                            {{ $status }}
                        </span>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Assigned Technicians
                    </p>

                    <div class="mt-2 flex flex-wrap gap-2">
                        @forelse ($ticket->technicians as $technician)
                            <span
                                class="inline-flex items-center gap-1.5 rounded-lg bg-gray-100 px-2.5 py-1.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m12-13a4 4 0 1 1-8 0 4 4 0 0 1 8 0Zm2 5h4m-2-2v4" />
                                </svg>

                                {{ $technician->technician_name }}
                            </span>
                        @empty
                            <span class="text-sm text-gray-400">
                                Not Assigned Yet
                            </span>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="mb-4 flex items-center gap-3">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h8M8 14h5m-9 7h16a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z" />
                    </svg>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white">
                        Complaint Details
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Description of the reported issue.
                    </p>
                </div>
            </div>

            <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800/50">
                <p class="whitespace-pre-line text-sm leading-7 text-gray-600 dark:text-gray-300">
                    {{ $ticket->complaint ?: '-' }}</p>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="mb-4 flex items-center gap-3">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6" />
                    </svg>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white">
                        Response / Action
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Updates and resolution from the support team.
                    </p>
                </div>
            </div>


            @if (
                $ticket->customer_confirmation_required &&
                    $ticket->ticket_status === 'Awaiting Customer' &&
                    $ticket->customer_confirmation_status === 'Pending')
                <div
                    class="overflow-hidden rounded-xl border border-amber-200 bg-white dark:border-amber-500/30 dark:bg-white/[0.03]">

                    <div
                        class="border-b border-amber-200 bg-amber-50 px-6 py-5 dark:border-amber-500/30 dark:bg-amber-500/10">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                            Ticket Resolution Confirmation
                        </h3>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            Our team has completed the handling process.
                            Please confirm whether your issue has been resolved.
                        </p>
                    </div>

                    <div class="space-y-4 p-6">

                        <p class="font-medium text-gray-800 dark:text-white">
                            Has your issue been resolved?
                        </p>

                        <form action="{{ route('customer.tickets.resolution', $ticket->id) }}" method="POST"
                            id="resolutionForm">
                            @csrf

                            <div class="space-y-3">

                                <label
                                    class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-4 hover:border-green-500 dark:border-gray-700">
                                    <input type="radio" name="answer" value="yes" required
                                        onchange="toggleResolutionNote()" class="h-4 w-4 text-green-600">

                                    <div>
                                        <p class="font-medium text-green-600">
                                            Yes, My Issue Is Resolved
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            The issue has been completely resolved.
                                        </p>
                                    </div>
                                </label>

                                <label
                                    class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-4 hover:border-red-500 dark:border-gray-700">
                                    <input type="radio" name="answer" value="no" required
                                        onchange="toggleResolutionNote()" class="h-4 w-4 text-red-600">

                                    <div>
                                        <p class="font-medium text-red-600">
                                            No, The Issue Still Persists
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            The issue has not been completely resolved.
                                        </p>
                                    </div>
                                </label>

                            </div>

                            <div id="resolutionNoteContainer" class="mt-5 hidden">

                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Please Describe the Remaining Issue
                                    <span class="text-red-500">*</span>
                                </label>

                                <textarea name="customer_resolution_note" id="customer_resolution_note" rows="4" maxlength="5000"
                                    placeholder="Explain which part of the issue has not been resolved..."
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('customer_resolution_note') }}</textarea>

                                @error('customer_resolution_note')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror

                            </div>

                            @error('answer')
                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror

                            <div class="mt-5 flex justify-end">
                                <button type="submit"
                                    class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                                    Submit Confirmation
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            @endif

            @if ($ticket->customer_confirmation_status === 'Not Resolved' && $ticket->customer_resolution_note)
                <div class="rounded-xl border border-red-200 bg-red-50 p-5 dark:border-red-500/30 dark:bg-red-500/10">
                    <h3 class="font-semibold text-red-700 dark:text-red-400">
                        Your Issue Is Still Unresolved
                    </h3>

                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ $ticket->customer_resolution_note }}
                    </p>

                    <p class="mt-3 text-xs text-gray-500">
                        Your feedback has been submitted to the administrator.
                    </p>
                </div>
            @endif

            <script>
                function toggleResolutionNote() {
                    const selected = document.querySelector(
                        'input[name="answer"]:checked'
                    );

                    const container = document.getElementById(
                        'resolutionNoteContainer'
                    );

                    const textarea = document.getElementById(
                        'customer_resolution_note'
                    );

                    const isNo = selected && selected.value === 'no';

                    container.classList.toggle('hidden', !isNo);
                    textarea.required = !!isNo;

                    if (!isNo) {
                        textarea.value = '';
                    }
                }

                document.addEventListener('DOMContentLoaded', function() {
                    toggleResolutionNote();
                });
            </script>


            <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800/50">
                @if ($ticket->action)
                    <p class="whitespace-pre-line text-sm leading-7 text-gray-600 dark:text-gray-300">
                        {{ $ticket->action }}</p>
                @else
                    <p class="text-sm italic text-gray-400">
                        No response or action has been recorded yet.
                    </p>
                @endif
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="mb-5">
                <h4 class="font-semibold text-gray-800 dark:text-white">
                    Activity History
                </h4>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Track the progress and updates of your ticket.
                </p>
            </div>

            @if ($ticket->activities->isNotEmpty())
                <div class="space-y-5">
                    @foreach ($ticket->activities->sortByDesc('created_at') as $activity)
                        <div class="relative flex gap-4">

                            <div class="flex flex-col items-center">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-500 dark:bg-brand-500/10">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                            </div>

                            <div class="min-w-0 flex-1 pb-4">
                                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                    <h5 class="text-sm font-semibold text-gray-800 dark:text-white">
                                        {{ $activity->activity_type }}
                                    </h5>

                                    <span class="text-xs text-gray-400">
                                        {{ $activity->created_at?->format('d M Y, H:i') ?? '-' }}
                                    </span>
                                </div>

                                <p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-600 dark:text-gray-300">
                                    {{ $activity->description }}
                                </p>

                                @if ($activity->performed_by)
                                    <p class="mt-2 text-xs text-gray-400">
                                        By {{ $activity->performed_by }}
                                    </p>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center">
                    <p class="text-sm text-gray-400">
                        No activity history available.
                    </p>
                </div>
            @endif

        </div>

    </div>
@endsection
