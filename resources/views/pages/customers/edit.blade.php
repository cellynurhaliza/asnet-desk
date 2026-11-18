@extends('layouts.app')

@section('title', 'Edit Customer')
@section('page-title', 'Customers')

@section('content')
<div class="mx-auto max-w-5xl">

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-950">

        <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                Edit Customer
            </h3>
            <p class="mt-1 text-sm text-gray-400">
                Update customer information.
            </p>
        </div>

        <form id="customerForm" action="{{ route('customers.update', $customer->id) }}"
            method="POST" enctype="multipart/form-data">

            @method('PUT')

            <div class="p-6">
                @include('pages.customers._form')
            </div>

        </form>

    </div>

</div>
    <div id="confirmModal"
        class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">

        <div id="confirmModalContent"
            class="w-full max-w-md scale-95 rounded-2xl bg-white p-6 opacity-0 shadow-xl transition-all duration-200 dark:bg-gray-900">

            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 dark:bg-brand-500/10">
                <svg class="h-7 w-7 text-brand-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ isset($customer) ? 'Update Customer?' : 'Save Customer?' }}
                </h3>

                <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">
                    {{ isset($customer)
                        ? 'Are you sure you want to update this customer information?'
                        : 'Are you sure you want to save this customer information?' }}
                </p>
            </div>

            <div class="mt-6 flex justify-center gap-3">
                <button type="button"
                    onclick="closeConfirmModal()"
                    class="rounded-lg border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Cancel
                </button>

                <button type="button"
                    onclick="confirmSubmit()"
                    class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                    Yes, {{ isset($customer) ? 'Update' : 'Save' }}
                </button>
            </div>
        </div>
    </div>

    <script>
        function validateBeforeConfirm(event) {
            const form = document.getElementById('customerForm');

            if (!form) return false;

            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
                return false;
            }

            event.preventDefault();
            openConfirmModal();

            return false;
        }

        function openConfirmModal() {
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('confirmModalContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('confirmModalContent');

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 200);
        }

        function confirmSubmit() {
            const form = document.getElementById('customerForm');

            if (form) {
                form.requestSubmit();
            }
        }
    </script>
@endsection
