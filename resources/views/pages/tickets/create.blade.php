@extends('layouts.app')

@section('title', 'Create Ticket')
@section('page-title', 'Create Ticket')

@section('content')

    <form action="{{ route('tickets.store') }}" method="POST" id="ticketForm">
        @csrf

        <div class="mx-auto max-w-6xl">

            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-950">

                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5 dark:border-gray-800">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                            Create New Ticket
                        </h3>
                        <p class="mt-1 text-sm text-gray-400">
                            Enter customer information and ticket details.
                        </p>
                    </div>

                    <a href="{{ route('dashboard') }}"
                        class="text-sm text-gray-400 transition hover:text-gray-700 dark:hover:text-white">
                        ✕
                    </a>
                </div>

                <div class="space-y-6 p-6">

                    <div>
                        <div class="mb-3 flex items-center justify-between">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                Customer <span class="text-error-500">*</span>
                            </label>

                            <span class="text-xs text-gray-400">
                                {{ $customers->count() }} customers
                            </span>
                        </div>

                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <circle cx="11" cy="11" r="7" stroke-width="2" />
                                <path stroke-linecap="round" stroke-width="2" d="m16 16 4 4" />
                            </svg>

                            <input type="text" id="customerSearch"
                                placeholder="Search customer name, username, or phone number..." autocomplete="off"
                                class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>

                        <input type="hidden" name="new_customer_name" id="newCustomerName">

                        <div id="customerList"
                            class="mt-3 grid max-h-48 grid-cols-1 gap-2 overflow-y-auto sm:grid-cols-2 lg:grid-cols-3">

                            @forelse ($customers as $customer)
                                <label
                                    data-search="{{ strtolower(($customer->username ?? '') . ' ' . ($customer->pic ?? '') . ' ' . ($customer->phone_number ?? '')) }}"
                                    class="customer-item flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 px-3 py-3 transition hover:border-brand-400 hover:bg-gray-50 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/50 dark:border-gray-700 dark:hover:bg-gray-800 dark:has-[:checked]:border-brand-500 dark:has-[:checked]:bg-brand-500/10">

                                    <input type="radio" name="customer_id" value="{{ $customer->id }}"
                                        @checked(old('customer_id') == $customer->id) class="h-4 w-4 text-brand-500 focus:ring-brand-500">

                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-gray-800 dark:text-white">
                                            {{ $customer->pic }}
                                        </p>

                                        <p class="mt-1 truncate text-xs text-gray-400">
                                            {{ $customer->username ?? '-' }}
                                            ·
                                            {{ $customer->phone_number ?? '-' }}
                                        </p>
                                    </div>
                                </label>
                            @empty
                                <div class="col-span-full py-4 text-center text-sm text-gray-400">
                                    No customers available.
                                </div>
                            @endforelse
                        </div>

                        <div id="customerNoResult"
                            class="mt-3 hidden rounded-lg border border-dashed border-gray-300 p-4 text-center dark:border-gray-700">

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Customer not found.
                            </p>

                            <button type="button" id="addNewCustomerButton"
                                class="mt-3 inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-brand-600">

                                <span>+</span>
                                Add New Customer
                            </button>
                        </div>

                        <div id="newCustomerPreview"
                            class="mt-3 hidden rounded-lg border border-brand-500 bg-brand-50 p-3 dark:bg-brand-500/10">

                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        New customer selected
                                    </p>

                                    <p id="newCustomerPreviewName"
                                        class="mt-1 text-sm font-semibold text-gray-800 dark:text-white">
                                    </p>
                                </div>

                                <button type="button" id="cancelNewCustomer"
                                    class="text-xs font-medium text-red-500 hover:text-red-600">
                                    Cancel
                                </button>
                            </div>
                        </div>

                        @error('customer_id')
                            <p class="mt-2 text-xs text-error-500">{{ $message }}</p>
                        @enderror

                        @error('new_customer_name')
                            <p class="mt-2 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-800"></div>

                    <div>
                        <h4 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white">
                            Ticket Information
                        </h4>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Category <span class="text-error-500">*</span>
                                </label>

                                <select name="category_id" required
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="">Select Category</option>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('category_id')
                                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Ticket Status
                                </label>

                                <div
                                    class="flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 dark:border-gray-700 dark:bg-gray-900">
                                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                    <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                                        New
                                    </span>
                                    <span class="ml-auto text-xs text-gray-400">
                                        Default Status
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 border-t border-gray-100 pt-5 md:grid-cols-2 dark:border-gray-800">

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Customer Complaint
                            </label>

                            <textarea id="complaintDetails" name="complaint_details" rows="3" placeholder="Enter customer complaint..."
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-700 dark:bg-gray-800 dark:text-white"></textarea>

                            <div id="complaintChips" class="mb-3 flex flex-wrap gap-2">
                                <button type="button" data-value="LOS"
                                    class="choice-chip rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-600 transition hover:border-brand-400 dark:border-gray-700 dark:text-gray-300 dark:peer-checked:bg-brand-500/10 dark:peer-checked:text-brand-400">LOS</button>
                                <button type="button" data-value="Disconnected"
                                    class="choice-chip rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-600 transition hover:border-brand-400 dark:border-gray-700 dark:text-gray-300">Disconnected</button>
                                <button type="button" data-value="Slow Connection"
                                    class="choice-chip rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-600 transition hover:border-brand-400 dark:border-gray-700 dark:text-gray-300">Slow
                                    Connection</button>
                            </div>

                            <div id="complaintInputs"></div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Response / Action
                            </label>

                            <textarea id="actionDetails" name="action_details" rows="3" placeholder="Enter response or action taken..."
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-700 dark:bg-gray-800 dark:text-white"></textarea>

                            <div id="actionChips">
                                <button type="button" data-value="OK"
                                    class="choice-chip rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-600 transition hover:border-brand-400 dark:border-gray-700 dark:text-gray-300">OK</button>
                                <button type="button" data-value="Connection Restored"
                                    class="choice-chip rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-600 transition hover:border-brand-400 dark:border-gray-700 dark:text-gray-300">Connection
                                    Restored</button>
                            </div>

                            <div id="actionInputs"></div>
                        </div>

                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-800"></div>

                    <div>
                        <h4 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white">
                            Additional Settings
                        </h4>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                            <div>
                                <label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Ticket Priority <span class="text-error-500">*</span>
                                </label>

                                @php
                                    $priorities = [
                                        'Urgent' => [
                                            'style' =>
                                                'border-red-200 text-red-600 hover:bg-red-50 dark:border-red-500/20 dark:text-red-400 dark:hover:border-red-500/40 dark:hover:bg-red-500/5',
                                            'selected' =>
                                                'border-red-500 bg-red-50 text-red-700 ring-1 ring-red-500 dark:border-red-400 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-400/50',
                                        ],
                                        'High' => [
                                            'style' =>
                                                'border-orange-200 text-orange-600 hover:bg-orange-50 dark:border-orange-500/20 dark:text-orange-400 dark:hover:border-orange-500/40 dark:hover:bg-orange-500/5',
                                            'selected' =>
                                                'border-orange-500 bg-orange-50 text-orange-700 ring-1 ring-orange-500 dark:border-orange-400 dark:bg-orange-500/10 dark:text-orange-300 dark:ring-orange-400/50',
                                        ],
                                        'Medium' => [
                                            'style' =>
                                                'border-yellow-200 text-yellow-600 hover:bg-yellow-50 dark:border-yellow-500/20 dark:text-yellow-400 dark:hover:border-yellow-500/40 dark:hover:bg-yellow-500/5',
                                            'selected' =>
                                                'border-yellow-500 bg-yellow-50 text-yellow-700 ring-1 ring-yellow-500 dark:border-yellow-400 dark:bg-yellow-500/10 dark:text-yellow-300 dark:ring-yellow-400/50',
                                        ],
                                        'Low' => [
                                            'style' =>
                                                'border-green-200 text-green-600 hover:bg-green-50 dark:border-green-500/20 dark:text-green-400 dark:hover:border-green-500/40 dark:hover:bg-green-500/5',
                                            'selected' =>
                                                'border-green-500 bg-green-50 text-green-700 ring-1 ring-green-500 dark:border-green-400 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-400/50',
                                        ],
                                    ];
                                @endphp

                                <div class="grid grid-cols-2 gap-3">
                                    @foreach ($priorities as $priority => $styles)
                                        <label
                                            class="priority-option flex cursor-pointer items-center justify-center rounded-lg border bg-white px-4 py-3 text-sm font-semibold transition-all duration-200
                dark:bg-gray-800/50
                {{ old('priority') === $priority ? $styles['selected'] : $styles['style'] }}">

                                            <input type="radio" name="priority" value="{{ $priority }}"
                                                class="sr-only" @checked(old('priority') === $priority)>

                                            {{ $priority }}
                                        </label>
                                    @endforeach
                                </div>

                                @error('priority')
                                    <p class="mt-2 text-xs text-error-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="mb-3 flex items-center justify-between">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Assigned Technicians
                                    </label>

                                    <span class="text-xs text-gray-400">
                                        {{ $technicians->count() }} available
                                    </span>
                                </div>

                                <div
                                    class="max-h-64 space-y-2 overflow-y-auto rounded-lg border border-gray-200 p-3 dark:border-gray-700">

                                    @forelse ($technicians as $technician)
                                        <label
                                            class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-100 px-3 py-3 transition hover:border-brand-400 hover:bg-gray-50 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/50 dark:border-gray-700 dark:hover:bg-gray-800 dark:has-[:checked]:border-brand-500 dark:has-[:checked]:bg-brand-500/10">

                                            <input type="checkbox" name="technician_ids[]" value="{{ $technician->id }}"
                                                @checked(in_array($technician->id, old('technician_ids', [])))
                                                class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">

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
                                        <p class="py-5 text-center text-sm text-gray-400">
                                            No technicians available.
                                        </p>
                                    @endforelse

                                </div>

                                <p class="mt-2 text-xs text-gray-400">
                                    You can select multiple technicians.
                                </p>

                                @error('technician_ids')
                                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                                @enderror

                                @error('technician_ids.*')
                                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="flex cursor-pointer items-center gap-2">
                                    <input type="checkbox" id="manualTime" name="manual_time" value="1"
                                        @checked(old('manual_time'))
                                        class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">

                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Set Report Time Manually
                                    </span>
                                </label>

                                <div id="reportTimeWrapper"
                                    class="{{ old('manual_time') ? '' : 'hidden' }} mt-3 max-w-md">

                                    <label class="mb-2 block text-sm text-gray-500 dark:text-gray-400">
                                        Report Date & Time (WIB)
                                    </label>

                                    <input type="datetime-local" name="reported_at" value="{{ old('reported_at') }}"
                                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div
                    class="flex flex-col-reverse gap-2 border-t border-gray-200 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-end dark:border-gray-800 dark:bg-gray-900/50">

                    <a href="{{ route('tickets.index') }}"
                        class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-center text-sm font-medium text-gray-600 transition hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        Cancel
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                        ✓ Save Ticket
                    </button>
                </div>

            </div>
        </div>
    </form>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const search = document.getElementById('customerSearch');
            const customers = document.querySelectorAll('.customer-item');
            const noResult = document.getElementById('customerNoResult');

            search?.addEventListener('input', function() {
                let count = 0;
                const keyword = this.value.toLowerCase().trim();

                customers.forEach(customer => {
                    const match = customer.dataset.search.includes(keyword);
                    customer.classList.toggle('hidden', !match);
                    if (match) count++;
                });

                noResult?.classList.toggle('hidden', count > 0);
            });

            const selected = {
                complaints: [],
                actions: []
            };

            const containers = {
                complaints: document.getElementById('complaintInputs'),
                actions: document.getElementById('actionInputs')
            };

            document.querySelectorAll('.choice-chip').forEach(button => {
                button.addEventListener('click', function() {
                    const group = this.dataset.group;
                    const value = this.dataset.value;
                    const values = selected[group];
                    const index = values.indexOf(value);

                    if (index === -1) {
                        values.push(value);
                        this.classList.add('border-brand-500', 'bg-brand-50', 'text-brand-600',
                            'dark:bg-brand-500/10');
                    } else {
                        values.splice(index, 1);
                        this.classList.remove('border-brand-500', 'bg-brand-50', 'text-brand-600',
                            'dark:bg-brand-500/10');
                    }

                    containers[group].innerHTML = '';

                    values.forEach(item => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = group + '[]';
                        input.value = item;
                        containers[group].appendChild(input);
                    });
                });
            });

            const manualTime = document.getElementById('manualTime');
            const reportTime = document.getElementById('reportTimeWrapper');

            manualTime?.addEventListener('change', function() {
                reportTime.classList.toggle('hidden', !this.checked);
            });

        });

        function setupChipInput(containerId, inputId, fieldName) {
            const container = document.getElementById(containerId);
            const input = document.getElementById(inputId);

            if (!container || !input) return;

            container.querySelectorAll('[data-value]').forEach(button => {
                button.addEventListener('click', function() {
                    const value = this.dataset.value;
                    const values = input.value
                        .split(',')
                        .map(item => item.trim())
                        .filter(Boolean);

                    const index = values.indexOf(value);

                    if (index !== -1) {
                        values.splice(index, 1);
                        this.classList.remove('bg-brand-500', 'text-white');
                    } else {
                        values.push(value);
                        this.classList.add('bg-brand-500', 'text-white');
                    }

                    input.value = values.join(', ');
                });
            });
        }

        setupChipInput(
            'complaintChips',
            'complaintDetails',
            'complaints'
        );

        setupChipInput(
            'actionChips',
            'actionDetails',
            'actions'
        );


        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('customerSearch');
            const customerItems = document.querySelectorAll('.customer-item');
            const noResult = document.getElementById('customerNoResult');

            const addButton = document.getElementById('addNewCustomerButton');
            const newCustomerInput = document.getElementById('newCustomerName');
            const preview = document.getElementById('newCustomerPreview');
            const previewName = document.getElementById('newCustomerPreviewName');
            const cancelButton = document.getElementById('cancelNewCustomer');

            function filterCustomers() {
                const keyword = searchInput.value.trim().toLowerCase();
                let found = 0;

                customerItems.forEach(item => {
                    const searchText = item.dataset.search || '';
                    const match = searchText.includes(keyword);

                    item.classList.toggle('hidden', !match);

                    if (match) found++;
                });

                noResult.classList.toggle(
                    'hidden',
                    keyword === '' || found > 0
                );
            }

            searchInput.addEventListener('input', function() {
                newCustomerInput.value = '';
                preview.classList.add('hidden');

                filterCustomers();
            });

            addButton.addEventListener('click', function() {
                const customerName = searchInput.value.trim();

                if (!customerName) return;

                document.querySelectorAll(
                    'input[name="customer_id"]'
                ).forEach(radio => {
                    radio.checked = false;
                });

                newCustomerInput.value = customerName;

                previewName.textContent = customerName;
                preview.classList.remove('hidden');
                noResult.classList.add('hidden');
            });

            cancelButton.addEventListener('click', function() {
                newCustomerInput.value = '';
                preview.classList.add('hidden');
                searchInput.focus();
                filterCustomers();
            });

            filterCustomers();
        });

        document.querySelectorAll('.priority-option').forEach(option => {
            option.querySelector('input').addEventListener('change', function() {
                document.querySelectorAll('.priority-option').forEach(item => {
                    item.classList.remove(
                        'border-red-500', 'bg-red-50', 'text-red-600', 'ring-1', 'ring-red-500',
                        'border-orange-500', 'bg-orange-50', 'text-orange-600',
                        'ring-orange-500',
                        'border-yellow-500', 'bg-yellow-50', 'text-yellow-600',
                        'ring-yellow-500',
                        'border-green-500', 'bg-green-50', 'text-green-600', 'ring-green-500'
                    );
                });

                const selectedStyles = {
                    Urgent: ['border-red-500', 'bg-red-50', 'text-red-600', 'ring-1', 'ring-red-500'],
                    High: ['border-orange-500', 'bg-orange-50', 'text-orange-600', 'ring-1',
                        'ring-orange-500'
                    ],
                    Medium: ['border-yellow-500', 'bg-yellow-50', 'text-yellow-600', 'ring-1',
                        'ring-yellow-500'
                    ],
                    Low: ['border-green-500', 'bg-green-50', 'text-green-600', 'ring-1',
                        'ring-green-500'
                    ]
                };

                option.classList.add(...selectedStyles[this.value]);
            });
        });
    </script>

@endsection
