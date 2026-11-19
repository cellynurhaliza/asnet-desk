@extends('layouts.app')

@section('title', 'Customer Details')
@section('page-title', 'Customer Details')

@section('content')
    <div class="space-y-5">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Customer Details
                </h3>
                <p class="mt-1 text-sm text-gray-400">
                    View customer information and network account details.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('customers.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7-7-7 7-7" />
                    </svg>

                    Back
                </a>

                <a href="{{ route('customers.edit', $customer->id) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M16.862 4.487l2.651 2.651M8 16l3.5-.5L19.5 7.5a2.121 2.121 0 00-3-3L6.5 14.5 6 18l2-.5z" />
                    </svg>

                    Edit Customer
                </a>
            </div>
        </div>

        <div
            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-950">

            <div class="border-b border-gray-200 p-6 dark:border-gray-800">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center">

                    @if ($customer->photo)
                        <img src="{{ asset('storage/' . $customer->photo) }}" alt="{{ $customer->pic }}"
                            class="h-20 w-20 rounded-full border-4 border-gray-100 object-cover dark:border-gray-800">
                    @else
                        <div
                            class="flex h-20 w-20 items-center justify-center rounded-full bg-brand-50 text-2xl font-semibold text-brand-600 dark:bg-brand-500/10">
                            {{ strtoupper(substr($customer->pic ?? 'C', 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-1">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                            {{ $customer->username }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-400">
                            PIC: {{ $customer->pic ?: '-' }}
                        </p>

                        <div class="mt-3">
                            @php
                                $statusClass = match ($customer->status) {
                                    'Active'
                                        => 'bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400',
                                    'Banned',
                                    'Disabled',
                                    'Suspended'
                                        => 'bg-error-50 text-error-600 dark:bg-error-500/10 dark:text-error-400',
                                    'Limited'
                                        => 'bg-warning-50 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400',
                                    default => 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400',
                                };
                            @endphp

                            <span class="rounded-full px-3 py-1 text-xs font-medium {{ $statusClass }}">
                                {{ $customer->status ?? 'Unknown' }}
                            </span>
                        </div>
                    </div>

                    <div class="sm:text-right">
                        <p class="text-xs text-gray-400">Customer ID (CID)</p>
                        <p class="mt-1 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            {{ $customer->cid ?: '-' }}
                        </p>
                    </div>

                </div>
            </div>

            <div class="border-b border-gray-200 p-6 dark:border-gray-800">

                <h3 class="mb-5 text-sm font-semibold text-gray-800 dark:text-white">
                    Customer Information
                </h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                    <div>
                        <p class="text-xs text-gray-400">Customer Name</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->username ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">PIC</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->pic ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Email Address</p>
                        <p class="mt-1 break-all text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->email ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Phone Number</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->phone_number ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Service Type</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->service_type ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Account Type</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->account_type ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Account Status</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->status ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-500">
                            Password
                        </label>

                        <div class="flex items-center gap-2">
                            <input type="password" id="customerPassword" value="{{ $customerPassword ?? '' }}" readonly
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800">

                            <button type="button"
                                onclick="
        const input = document.getElementById('customerPassword');
        const icon = document.getElementById('passwordEyeIcon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = `
                <path d='M3 3l18 18'></path>
                <path d='M10.6 10.6a2 2 0 002.8 2.8'></path>
                <path d='M9.9 5.2A10.8 10.8 0 0112 5c5 0 9 5 10 7a13.5 13.5 0 01-3 3.8'></path>
                <path d='M6.2 6.2A13.5 13.5 0 002 12c1 2 5 7 10 7a10.8 10.8 0 004.1-.8'></path>
            `;
        } else {
            input.type = 'password';
            icon.innerHTML = `
                <path d='M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z'></path>
                <circle cx='12' cy='12' r='3'></circle>
            `;
        }
    "
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-700">
                                <svg id="passwordEyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="border-b border-gray-200 p-6 dark:border-gray-800">

                <h3 class="mb-5 text-sm font-semibold text-gray-800 dark:text-white">
                    Network Information
                </h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                    <div>
                        <p class="text-xs text-gray-400">IP Address</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->ip_address ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">VLAN</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->vlan ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Port PON</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->port_pon ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Bandwidth</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->bandwidth ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Access Method</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->access_method ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Connection Type</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->connection_type ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">POP Location</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->pop_location ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">User Location</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->user_location ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Media</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->media ?: '-' }}
                        </p>
                    </div>

                </div>
            </div>

            <div class="border-b border-gray-200 p-6 dark:border-gray-800">

                <h3 class="mb-5 text-sm font-semibold text-gray-800 dark:text-white">
                    Location Information
                </h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                    <div>
                        <p class="text-xs text-gray-400">Coordinates</p>
                        <p class="mt-1 break-all text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->coordinates ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Address</p>
                        <p class="mt-1 whitespace-pre-line text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $customer->address ?: '-' }}
                        </p>
                    </div>

                </div>
            </div>

            <div class="bg-gray-50/70 p-6 dark:bg-gray-900/40">

                <h3 class="mb-5 text-sm font-semibold text-gray-800 dark:text-white">
                    System Information
                </h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                    <div>
                        <p class="text-xs text-gray-400">Created At</p>
                        <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $customer->created_at?->format('d M Y, H:i') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Last Updated</p>
                        <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $customer->updated_at?->format('d M Y, H:i') ?? '-' }}
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
