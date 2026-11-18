@extends('layouts.app')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
    <div class="space-y-5">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Category Management
                </h3>
                <p class="mt-1 text-sm text-gray-400">
                    Manage ticket categories.
                </p>
            </div>

            <a href="{{ route('categories.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                <span class="text-lg">+</span>
                Add Category
            </a>
        </div>

        <div
            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-950">

            <div class="border-b border-gray-200 p-5 dark:border-gray-800">
                <form action="{{ route('categories.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search category..."
                        class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-brand-500 sm:max-w-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">

                    <button type="submit"
                        class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        Search
                    </button>

                    @if (request('search'))
                        <a href="{{ route('categories.index') }}"
                            class="rounded-lg border border-gray-200 px-5 py-2.5 text-center text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">No.</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">Category Name</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">Performance Order</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">Status</th>
                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">

                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $categories->firstItem() + $loop->index }}
                                </td>

                                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white">
                                    {{ $category->category_name }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $category->performance_order }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($category->active_category)
                                        <span
                                            class="rounded-full bg-success-50 px-3 py-1 text-xs font-medium text-success-600 dark:bg-success-500/10 dark:text-success-400">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                            Inactive
                                        </span>
                                    @endif
                                </td>


                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-3">

                                        <a href="{{ route('categories.show', $category->id) }}" title="View Category"
                                            class="group flex h-9 w-9 items-center justify-center rounded-lg border border-purple-100 bg-purple-50 text-purple-600 transition duration-200 hover:border-purple-200 hover:bg-purple-600 hover:text-white dark:border-purple-500/20 dark:bg-purple-500/10 dark:text-purple-400 dark:hover:border-purple-500 dark:hover:bg-purple-500 dark:hover:text-white">

                                            <svg class="h-4 w-4 transition-transform duration-200 group-hover:scale-110"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                    d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" />
                                                <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('categories.edit', $category->id) }}"
                                            class="group flex h-9 w-9 items-center justify-center rounded-lg border border-blue-100 bg-blue-50 text-blue-600 transition duration-200 hover:border-blue-200 hover:bg-blue-600 hover:text-white dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:border-blue-500 dark:hover:bg-blue-500 dark:hover:text-white">

                                            <svg class="h-4 w-4 transition-transform duration-200 group-hover:scale-110"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                    d="M16.862 4.487l2.651 2.651M8 16l3.5-.5L19.5 7.5a2.121 2.121 0 00-3-3L6.5 14.5 6 18l2-.5z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                            class="delete-technician-form"
                                            onsubmit="return confirm('Are you sure you want to delete this category?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="button" onclick="openDeleteModal(this)" title="Delete Technician"
                                                class="group flex h-9 w-9 items-center justify-center rounded-lg border border-red-100 bg-red-50 text-red-500 transition duration-200 hover:border-red-200 hover:bg-red-500 hover:text-white dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400 dark:hover:border-red-500 dark:hover:bg-red-500 dark:hover:text-white">

                                                <svg class="h-4 w-4 transition-transform duration-200 group-hover:scale-110"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400">
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
                    {{ $categories->links() }}
                </div>
            @endif

        </div>
    </div>
    <div id="deleteModal"
        class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">

        <div id="deleteModalContent"
            class="w-full max-w-md scale-95 rounded-2xl bg-white p-6 opacity-0 shadow-xl transition-all duration-200 dark:bg-gray-900">

            <div
                class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-error-50 dark:bg-error-500/10">
                <svg class="h-7 w-7 text-error-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
            </div>

            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Delete Technician?
                </h3>

                <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">
                    Are you sure you want to delete
                    <span id="deleteTechnicianName" class="font-semibold text-gray-800 dark:text-white"></span>?
                    This action cannot be undone.
                </p>
            </div>

            <div class="mt-6 flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="rounded-lg border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Cancel
                </button>

                <button type="button" onclick="confirmDelete()"
                    class="rounded-lg bg-error-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-error-600">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>
    <script>
        let deleteForm = null;

        function openDeleteModal(button) {
            deleteForm = button.closest('form');

            const technicianName = deleteForm.dataset.name;

            document.getElementById('deleteTechnicianName').textContent = technicianName;

            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
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
