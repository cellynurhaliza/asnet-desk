@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
    @php
        $displayName = $customer->pic ?: $customer->username;

        $initials = collect(explode(' ', trim($displayName ?? '')))
            ->filter()
            ->take(2)
            ->map(fn($part) => strtoupper(substr($part, 0, 1)))
            ->implode('');

        $statusColors = [
            'Active' => 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400',
            'Banned' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
            'Disabled' => 'bg-gray-100 text-gray-600 dark:bg-gray-500/10 dark:text-gray-400',
            'Inactive' => 'bg-gray-100 text-gray-600 dark:bg-gray-500/10 dark:text-gray-400',
            'Limited' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-400',
            'Suspended' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
        ];
    @endphp

    <div class="mx-auto max-w-6xl space-y-5">

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">

                <div
                    class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-full bg-brand-100 text-2xl font-semibold text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                    @if ($customer->photo)
                        <img src="{{ asset('storage/' . $customer->photo) }}" alt="{{ $displayName }}"
                            class="h-full w-full object-cover">
                    @else
                        {{ $initials ?: 'C' }}
                    @endif
                </div>

                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                        {{ $displayName }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $customer->email }}
                    </p>

                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <span
                            class="rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                            Customer
                        </span>

                        <span
                            class="rounded-full px-3 py-1 text-xs font-medium {{ $statusColors[$customer->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $customer->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('customer.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

                <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-800">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                        Personal Information
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Update your personal contact information.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Customer Name
                        </label>

                        <input type="text" value="{{ $customer->username }}" readonly
                            class="h-11 w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-50 px-4 text-sm text-gray-500 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-400">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Person in Charge (PIC)
                        </label>

                        <input type="text" name="pic" value="{{ old('pic', $customer->pic) }}"
                            placeholder="Enter person in charge name"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

                        @error('pic')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email Address <span class="text-red-500">*</span>
                        </label>

                        <input type="email" name="email" value="{{ old('email', $customer->email) }}" required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Phone Number
                        </label>

                        <input type="text" name="phone_number"
                            value="{{ old('phone_number', $customer->phone_number) }}" placeholder="Enter phone number"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

                        @error('phone_number')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Address
                        </label>

                        <textarea name="address" rows="3" placeholder="Enter your address"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('address', $customer->address) }}</textarea>

                        @error('address')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="flex justify-end border-t border-gray-200 px-6 py-4 dark:border-gray-800">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7" />
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Change Password
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Set a new password for your account.
                </p>
            </div>

            <form action="{{ route('customer.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-5 p-6">

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            New Password <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            <input type="password" id="new_password" name="password" required minlength="6"
                                autocomplete="new-password" placeholder="Enter new password"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 pr-12 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

                            <button type="button" onclick="togglePassword('new_password', 'new_password_icon')"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-brand-500">

                                <svg id="new_password_icon" class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                                </svg>
                            </button>
                        </div>

                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Confirm New Password <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            <input type="password" id="confirm_password" name="password_confirmation" required
                                minlength="6" autocomplete="new-password" placeholder="Confirm new password"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 pr-12 text-sm text-gray-800 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

                            <button type="button" onclick="togglePassword('confirm_password', 'confirm_password_icon')"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-brand-500">

                                <svg id="confirm_password_icon" class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>

                <div class="flex justify-end border-t border-gray-200 px-6 py-4 dark:border-gray-800">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        <script>
            function togglePassword(inputId, iconId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3l18 18M10.584 10.587a2 2 0 0 0 2.829 2.829" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.88 5.09A10.94 10.94 0 0 1 12 5c4.478 0 8.268 2.943 9.542 7a10.96 10.96 0 0 1-4.132 5.411M6.61 6.61A10.97 10.97 0 0 0 2.458 12c1.274 4.057 5.064 7 9.542 7 1.17 0 2.29-.204 3.33-.579" />
            `;
                } else {
                    input.type = 'password';
                    icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
            `;
                }
            }
        </script>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Account Information
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Your account information managed by the administrator.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                        Service Type
                    </label>

                    <input type="text" value="{{ $customer->service_type ?: '-' }}" readonly
                        class="h-11 w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-50 px-4 text-sm text-gray-500 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-400">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                        Account Type
                    </label>

                    <input type="text" value="{{ $customer->account_type ?: '-' }}" readonly
                        class="h-11 w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-50 px-4 text-sm text-gray-500 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-400">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                        Account Status
                    </label>

                    <div class="flex h-11 items-center">
                        <span
                            class="rounded-full px-3 py-1 text-xs font-medium {{ $statusColors[$customer->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $customer->status ?: '-' }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                        Member Since
                    </label>

                    <input type="text" value="{{ $customer->created_at?->format('d M Y') ?? '-' }}" readonly
                        class="h-11 w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-50 px-4 text-sm text-gray-500 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-400">
                </div>

            </div>
        </div>

    </div>
@endsection
