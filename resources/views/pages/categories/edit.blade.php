@extends('layouts.app')

@section('title', 'Edit Category')
@section('page-title', 'Categories')

@section('content')

    <div class="mx-auto max-w-4xl">

        <div
            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-950">

            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5 dark:border-gray-800">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                        Edit Category
                    </h3>

                    <p class="mt-1 text-sm text-gray-400">
                        Update category information.
                    </p>
                </div>

                <a href="{{ route('categories.index') }}"
                    class="text-sm text-gray-400 transition hover:text-gray-700 dark:hover:text-white">
                    ✕
                </a>
            </div>

            <form id="categoryForm" action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-5 p-6">

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Category Name
                        </label>

                        <input type="text" name="category_name"
                            value="{{ old('category_name', $category->category_name) }}" placeholder="Enter category name"
                            required
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

                        @error('category_name')
                            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Performance Order
                        </label>

                        <input type="number" name="performance_order" min="0"
                            value="{{ old('performance_order', $category->performance_order) }}" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

                        @error('performance_order')
                            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status
                        </label>

                        <select name="active_category" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

                            <option value="1" @selected(old('active_category', $category->active_category) == 1)>
                                Active
                            </option>

                            <option value="0" @selected(old('active_category', $category->active_category) == 0)>
                                Inactive
                            </option>
                        </select>

                        @error('active_category')
                            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div
                    class="flex justify-end gap-2 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-800 dark:bg-gray-900/50">

                    <a href="{{ route('categories.index') }}"
                        class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        Cancel
                    </a>

                    <button type="submit" onclick="return validateBeforeConfirm(event)"
                        class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                        Update Category
                    </button>

                </div>
            </form>

        </div>

    </div>
    <div id="confirmModal"
        class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">

        <div id="confirmModalContent"
            class="w-full max-w-md scale-95 rounded-2xl bg-white p-6 opacity-0 shadow-xl transition-all duration-200 dark:bg-gray-900">

            <div
                class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 dark:bg-brand-500/10">
                <svg class="h-7 w-7 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ isset($category) ? 'Update Category?' : 'Save Category?' }}
                </h3>

                <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">
                    {{ isset($category)
                        ? 'Are you sure you want to update this category information?'
                        : 'Are you sure you want to save this category information?' }}
                </p>
            </div>

            <div class="mt-6 flex justify-center gap-3">
                <button type="button" onclick="closeConfirmModal()"
                    class="rounded-lg border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Cancel
                </button>

                <button type="button" onclick="confirmSubmit()"
                    class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                    Yes, {{ isset($category) ? 'Update' : 'Save' }}
                </button>
            </div>
        </div>
    </div>
    <script>
        function validateBeforeConfirm(event) {
            const form = event.target.closest('form');

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
            const form = document.getElementById('categoryForm');

            if (form) {
                form.requestSubmit();
            }
        }
    </script>
@endsection
