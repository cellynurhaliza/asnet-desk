 @extends('layouts.app')

@section('title', 'Technician Details')
@section('page-title', 'Technician Details')

@section('content')
    <div class="space-y-5">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Technician Details
                </h3>
                <p class="mt-1 text-sm text-gray-400">
                    View technician information and account details.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('technicians.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 12H5m7 7-7-7 7-7" />
                    </svg>
                    Back
                </a>

                <a href="{{ route('technicians.edit', $technician->id) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16.862 4.487l2.651 2.651M8 16l3.5-.5L19.5 7.5a2.121 2.121 0 00-3-3L6.5 14.5 6 18l2-.5z" />
                    </svg>
                    Edit Technician
                </a>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-950">

            <div class="border-b border-gray-200 bg-gray-50 px-6 py-6 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">

                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-50 text-2xl font-bold text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                        {{ strtoupper(substr($technician->technician_name, 0, 1)) }}
                    </div>

                    <div class="flex-1">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                            {{ $technician->technician_name }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Technician ID: #{{ $technician->id }}
                        </p>
                    </div>

                    <div>
                        @if ($technician->active_technician)
                            <span class="inline-flex items-center gap-2 rounded-full bg-success-50 px-3 py-1.5 text-xs font-medium text-success-600 dark:bg-success-500/10 dark:text-success-400">
                                <span class="h-2 w-2 rounded-full bg-success-500"></span>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                                Inactive
                            </span>
                        @endif
                    </div>

                </div>
            </div>

            <div class="p-6">

                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">
                        Personal Information
                    </h3>
                    <p class="mt-1 text-sm text-gray-400">
                        Basic information about this technician.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Technician Name
                        </p>
                        <p class="mt-2 text-sm font-medium text-gray-800 dark:text-white">
                            {{ $technician->technician_name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Phone Number
                        </p>
                        <p class="mt-2 text-sm font-medium text-gray-800 dark:text-white">
                            {{ $technician->phone_number ?: '-' }}
                        </p>
                    </div>

                </div>

                <div class="my-7 border-t border-gray-100 dark:border-gray-800"></div>

                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">
                        Telegram Information
                    </h3>
                    <p class="mt-1 text-sm text-gray-400">
                        Telegram account and assigned role.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Telegram Username
                        </p>
                        <p class="mt-2 text-sm font-medium text-gray-800 dark:text-white">
                            {{ $technician->telegram_username
                                ? '@' . ltrim($technician->telegram_username, '@')
                                : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Telegram Role
                        </p>
                        <p class="mt-2 text-sm font-medium text-gray-800 dark:text-white">
                            {{ $technician->role_telegram ?: '-' }}
                        </p>
                    </div>

                </div>

                <div class="my-7 border-t border-gray-100 dark:border-gray-800"></div>

                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">
                        System Information
                    </h3>
                    <p class="mt-1 text-sm text-gray-400">
                        Record creation and update information.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Created At
                        </p>
                        <p class="mt-2 text-sm font-medium text-gray-800 dark:text-white">
                            {{ $technician->created_at
                                ? $technician->created_at->format('d M Y, H:i')
                                : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Last Updated
                        </p>
                        <p class="mt-2 text-sm font-medium text-gray-800 dark:text-white">
                            {{ $technician->updated_at
                                ? $technician->updated_at->format('d M Y, H:i')
                                : '-' }}
                        </p>
                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection