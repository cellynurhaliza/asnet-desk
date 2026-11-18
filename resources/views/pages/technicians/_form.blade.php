@csrf

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Technician Name <span class="text-error-500">*</span>
        </label>

        <input type="text" name="technician_name"
            value="{{ old('technician_name', $technician->technician_name ?? '') }}" placeholder="Enter technician name"
            required
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

        @error('technician_name')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Phone Number
        </label>

        <input type="text" name="phone_number" value="{{ old('phone_number', $technician->phone_number ?? '') }}"
            placeholder="Enter phone number"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

        @error('phone_number')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Telegram Username
        </label>

        <div class="flex items-center">
            <span
                class="rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800">
                @
            </span>

            <input type="text" name="telegram_username"
                value="{{ old('telegram_username', $technician->telegram_username ?? '') }}" placeholder="username"
                class="w-full rounded-r-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
        </div>

        @error('telegram_username')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Telegram Role
        </label>

        <select name="role_telegram"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

            <option value="">-- Select Telegram Role --</option>

            <option value="Technician" @selected(old('role_telegram', $technician->role_telegram ?? '') == 'Technician')>
                Technician
            </option>

            <option value="Administrator" @selected(old('role_telegram', $technician->role_telegram ?? '') == 'Administrator')>
                Administrator
            </option>
        </select>

        @error('role_telegram')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">

            <label for="active_technician" class="flex cursor-pointer items-center gap-3">

                <input type="hidden" name="active_technician" value="0">

                <input type="checkbox" id="active_technician" name="active_technician" value="1"
                    class="h-5 w-5 cursor-pointer rounded border-gray-300 text-brand-500 focus:ring-brand-500"
                    @checked((string) old('active_technician', isset($technician) ? (int) $technician->active_technician : 1) === '1')>

                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-white">
                        Active Technician
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Enable this option if the technician is currently active.
                    </p>
                </div>

            </label>

        </div>

        @error('active_technician')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

</div>

<div class="mt-6 flex justify-end gap-2 border-t border-gray-100 pt-5 dark:border-gray-800">

    <a href="{{ route('technicians.index') }}"
        class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
        Cancel
    </a>

    <button type="submit" onclick="return validateBeforeConfirm(event)"
        class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
        {{ isset($technician) ? 'Update Technician' : 'Save Technician' }}
    </button>

</div>
