@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Customer Management
                </h3>
                <p class="mt-1 text-sm text-gray-400">
                    Manage all registered customers.
                </p>
            </div>
            <a href="{{ route('customers.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                <span class="text-lg">+</span>
                Add Customer
            </a>
        </div>
        <div
            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-950">
            <div class="border-b border-gray-200 p-5 dark:border-gray-800">
                <form action="{{ route('customers.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search customer name, PIC, CID, email or phone..."
                        class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 sm:max-w-md dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <button type="submit"
                        class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        Search
                    </button>

                    @if (request('search'))
                        <a href="{{ route('customers.index') }}"
                            class="rounded-lg border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                            Reset
                        </a>
                    @endif

                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left text-sm">
                    <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                        <tr>
                            <th class="whitespace-nowrap px-3 py-4 font-medium text-gray-500">No.</th>
                            <th class="whitespace-nowrap px-3 py-4 font-medium text-gray-500">CID</th>
                            <th class="whitespace-nowrap px-3 py-4 font-medium text-gray-500">Customer Name</th>
                            <th class="whitespace-nowrap px-3 py-4 font-medium text-gray-500">PIC</th>
                            <th class="px-3 py-4 font-medium text-gray-500">Contact</th>
                            <th class="px-3 py-4 font-medium text-gray-500">
                                Service Type
                            </th>
                            <th class="px-3 py-4 font-medium text-gray-500">
                                POP Location / User Location
                            </th>
                            <th class="px-3 py-4 font-medium text-gray-500">
                                Access Method / Connection Type
                            </th>
                            <th class="px-3 py-4 font-medium text-gray-500">Status</th>
                            <th class="px-3 py-4 text-center font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse ($customers as $index => $customer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">

                                <td class="whitespace-nowrap px-3 py-4 text-gray-500">
                                    {{ $customers->firstItem() + $index }}
                                </td>

                                <td class="whitespace-nowrap px-3 py-4">
                                    <span class="font-medium text-brand-600 dark:text-brand-400">
                                        {{ $customer->cid }}
                                    </span>
                                </td>

                                <td class="px-3 py-4">
                                    <span class="font-medium text-gray-800 dark:text-white">
                                        {{ $customer->username }}
                                    </span>
                                </td>

                                <td class="px-3 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $customer->pic }}
                                </td>

                                <td class="px-3 py-4">
                                    <div class="space-y-1">
                                        <p class="text-gray-800 dark:text-white">
                                            {{ $customer->email }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $customer->phone_number ?: '-' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-3 py-4">
                                    <div class="space-y-1">
                                        <p class="text-gray-800 dark:text-white">
                                            {{ $customer->service_type ?: '-' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-3 py-4">
                                    <div class="space-y-1">
                                        <p class="text-gray-800 dark:text-white">
                                            {{ $customer->pop_location ?: '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $customer->user_location ?: '-' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-3 py-4">
                                    <div class="space-y-1">
                                        <p class="text-gray-800 dark:text-white">
                                            {{ $customer->access_method ?: '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $customer->connection_type ?: '-' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-3 py-4">
                                    @php
                                        $statusClass = match ($customer->status) {
                                            'Active'
                                                => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                                            'Banned',
                                            'Disabled',
                                            'Suspended'
                                                => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                                            'Inactive'
                                                => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                            'Limited'
                                                => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
                                            default => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                        };
                                    @endphp

                                    <span
                                        class="inline-flex whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                                        {{ $customer->status }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">

                                        <a href="{{ route('customers.show', $customer->id) }}" title="View Customer"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-purple-100 bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white dark:border-purple-500/20 dark:bg-purple-500/10 dark:text-purple-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                    d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" />
                                                <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('customers.edit', $customer->id) }}" title="Edit Customer"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-blue-100 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                    d="M16.862 4.487l2.651 2.651M8 16l3.5-.5L19.5 7.5a2.121 2.121 0 00-3-3L6.5 14.5 6 18l2-.5z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                            class="delete-customer-form" data-name="{{ $customer->username }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button" onclick="openDeleteModal(this)" title="Delete Customer"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-red-100 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                        d="M4 7h16M10 11v6m4-6v6M5 7l1 13h12l1-13M9 7V4h6v3" />
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-3 py-10 text-center text-gray-500">
                                    No customers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($customers->hasPages())
                <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
                    {{ $customers->links() }}
                </div>
            @endif

        </div>
    </div>
    <div id="deleteModal"
        class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
        <div id="deleteModalContent"
            class="w-full max-w-md scale-95 rounded-2xl bg-white p-6 opacity-0 shadow-xl transition-all duration-200 dark:bg-gray-900">
            <div class="flex flex-col items-center text-center">
                <div
                    class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M6 7h12M9 7V4h6v3m-8 0 1 13h8l1-13M10 11v5m4-5v5" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Delete Customer?
                </h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Are you sure you want to delete
                    <span id="deleteCustomerName" class="font-semibold text-gray-800 dark:text-white"></span>?
                    This action cannot be undone.
                </p>
            </div>
            <div class="mt-7 flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="rounded-lg border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button type="button" onclick="confirmDelete()"
                    class="rounded-lg bg-red-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-red-600">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>
    <script>
        let deleteForm = null;

        function openDeleteModal(button) {
            deleteForm = button.closest('form');

            const customerName = deleteForm.dataset.name;

            document.getElementById('deleteCustomerName').textContent = customerName;

            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            requestAnimationFrame(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                deleteForm = null;
            }, 200);
        }

        function confirmDelete() {
            if (deleteForm) {
                deleteForm.submit();
            }
        }
    </script>
@endsection
