@extends('layouts.app')

@section('title', 'Create Ticket')
@section('page-title', 'Create Ticket')

@section('content')
    <div class="mx-auto max-w-5xl space-y-5">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Create New Ticket
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Submit a support request to our technical team.
                </p>
            </div>

            <a href="{{ route('customer.tickets.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
                Back to Tickets
            </a>
        </div>

        <form action="{{ route('customer.tickets.store') }}" method="POST">
            @csrf

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

                <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">
                        Ticket Information
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Please provide the details of your issue.
                    </p>
                </div>

                <div class="space-y-6 p-6">

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Customer
                        </label>

                        <input type="text"
                            value="{{ auth('customer')->user()->username }}"
                            readonly
                            class="h-11 w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-50 px-4 text-sm text-gray-500 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-400">

                        <p class="mt-1 text-xs text-gray-400">
                            This ticket will automatically be registered under your account.
                        </p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Ticket Name <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter ticket name"
                            required
                            maxlength="255"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Category <span class="text-red-500">*</span>
                        </label>

                        <select name="category_id"
                            required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

                            <option value="">Select Category</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected(old('category_id') == $category->id)>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('category_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror

                        @if ($categories->isEmpty())
                            <p class="mt-1 text-xs text-red-500">
                                No active categories are currently available. Please contact the administrator.
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Ticket Priority <span class="text-red-500">*</span>
                        </label>

                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">

                            @php
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
                            @endphp

                            @foreach ($priorities as $priority => $style)
                                <label class="cursor-pointer">
                                    <input type="radio"
                                        name="priority"
                                        value="{{ $priority }}"
                                        class="peer sr-only"
                                        @checked(old('priority', 'Medium') === $priority)
                                        required>

                                    <div class="rounded-xl border border-gray-200 bg-white p-4 transition
                                        hover:border-gray-300
                                        peer-checked:border-brand-500 peer-checked:bg-brand-50
                                        dark:border-gray-800 dark:bg-gray-950 dark:peer-checked:border-brand-500 dark:peer-checked:bg-brand-500/10">

                                        <div class="flex items-center gap-2">
                                            <span class="h-2.5 w-2.5 rounded-full {{ $style['dot'] }}"></span>

                                            <span class="text-sm font-semibold text-gray-800 dark:text-white">
                                                {{ $priority }}
                                            </span>
                                        </div>

                                        <p class="mt-2 text-xs text-gray-400">
                                            {{ $style['description'] }}
                                        </p>

                                        <div class="mt-3 hidden items-center gap-1 text-xs font-medium text-brand-600 peer-checked:flex dark:text-brand-400">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Selected
                                        </div>
                                    </div>
                                </label>
                            @endforeach

                        </div>

                        @error('priority')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Complaint Details <span class="text-red-500">*</span>
                        </label>

                        <textarea name="complaint"
                            rows="6"
                            required
                            placeholder="Describe your issue in detail..."
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('complaint') }}</textarea>

                        @error('complaint')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Reporting Time
                        </label>

                        <input type="text"
                            value="{{ now()->format('d M Y, H:i') }}"
                            readonly
                            class="h-11 w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-50 px-4 text-sm text-gray-500 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-400">

                        <p class="mt-1 text-xs text-gray-400">
                            Automatically recorded when you submit the ticket.
                        </p>
                    </div>

                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-800 sm:flex-row sm:justify-end">

                    <a href="{{ route('customer.tickets.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        Cancel
                    </a>

                    <button type="submit"
                        @disabled($categories->isEmpty())
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600 disabled:cursor-not-allowed disabled:opacity-50">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>

                        Submit Ticket
                    </button>

                </div>
            </div>
        </form>

    </div>
@endsection
