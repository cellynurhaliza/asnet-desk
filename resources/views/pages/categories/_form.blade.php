@csrf

<div class="space-y-5">

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Category Name <span class="text-error-500">*</span>
        </label>

        <input type="text" name="category_name" value="{{ old('category_name', $category->category_name ?? '') }}"
            placeholder="Enter category name" required
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

        @error('category_name')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Performance Order <span class="text-error-500">*</span>
        </label>

        <input type="number" name="performance_order" min="0"
            value="{{ old('performance_order', $category->performance_order ?? 0) }}" placeholder="Enter display order"
            required
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

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
            <option value="1" @selected(old('active_category', $category->active_category ?? 1) == 1)>
                Active
            </option>
            <option value="0" @selected(old('active_category', $category->active_category ?? 1) == 0)>
                Inactive
            </option>
        </select>

        @error('active_category')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

</div>

<div class="mt-6 flex justify-end gap-2 border-t border-gray-100 pt-5 dark:border-gray-800">

    <a href="{{ route('categories.index') }}"
        class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
        Cancel
    </a>

    <button type="submit" onclick="return validateBeforeConfirm(event)"
        class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
        {{ isset($category) ? 'Update Category' : 'Save Category' }}
    </button>

</div>
