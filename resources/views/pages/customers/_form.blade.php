@csrf

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Customer Name <span class="text-error-500">*</span>
        </label>

        <input type="text" name="username" value="{{ old('username', $customer->username ?? '') }}" required
            placeholder="Enter customer name"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

        @error('username')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            PIC <span class="text-error-500">*</span>
        </label>

        <input type="text" name="pic" value="{{ old('pic', $customer->pic ?? '') }}" required
            placeholder="Enter PIC name"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

        @error('pic')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Email <span class="text-error-500">*</span>
        </label>
        <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}" required
            placeholder="example@email.com"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

        @error('email')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror

    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Phone Number
        </label>
        <input type="text" name="phone_number" value="{{ old('phone_number', $customer->phone_number ?? '') }}"
            placeholder="Enter phone number"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

        @error('phone_number')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror

    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Password
            @if (!isset($customer))
                <span class="text-red-500">*</span>
            @endif
        </label>

        <div class="relative">
            <input type="password" name="password" id="customerPassword" {{ isset($customer) ? '' : 'required' }}
                placeholder="{{ isset($customer) ? 'Leave blank to keep current password' : 'Minimum 6 characters' }}"
                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 pr-12 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

            <button type="button" id="togglePasswordButton" onclick="toggleCustomerPassword()"
                class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-gray-400 transition hover:text-brand-500"
                aria-label="Show password" aria-pressed="false">

                <svg id="customerPasswordIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">

                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z" />

                    <circle cx="12" cy="12" r="3" />
                </svg>
            </button>
        </div>

        @error('password')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
        <script>
            function toggleCustomerPassword() {
                const input = document.getElementById('customerPassword');
                const icon = document.getElementById('customerPasswordIcon');
                const button = document.getElementById('togglePasswordButton');

                if (!input || !icon || !button) return;

                const isPassword = input.type === 'password';

                input.type = isPassword ? 'text' : 'password';

                if (isPassword) {
                    icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 3l18 18" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M10.6 10.6a2 2 0 002.8 2.8" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.9 5.2A10.5 10.5 0 0112 5c6 0 9.5 7 9.5 7s-1.2 2.4-3.1 3.9M6.2 6.2C3.8 8 2.5 12 2.5 12s3.5 7 9.5 7c1.1 0 2.1-.2 3-.6" />
            `;

                    button.setAttribute('aria-label', 'Hide password');
                    button.setAttribute('aria-pressed', 'true');
                } else {
                    icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z" />
                <circle cx="12" cy="12" r="3" />
            `;

                    button.setAttribute('aria-label', 'Show password');
                    button.setAttribute('aria-pressed', 'false');
                }
            }
        </script>
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Service Type <span class="text-error-500">*</span>
        </label>
        <select name="service_type" required
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

            <option value="">-- Select service type --</option>

            @foreach ($serviceTypes as $type)
                <option value="{{ $type }}" @selected(old('service_type', $customer->service_type ?? '') === $type)>
                    {{ $type }}
                </option>
            @endforeach

        </select>

        @error('service_type')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror

    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Status <span class="text-error-500">*</span>
        </label>
        <select name="status" required
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $customer->status ?? 'Active') === $status)>
                    {{ $status }}
                </option>
            @endforeach

        </select>

        @error('status')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror

    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            IP Address
        </label>
        <input type="text" name="ip_address" value="{{ old('ip_address', $customer->ip_address ?? '') }}"
            placeholder="Example: 192.168.1.1"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

        @error('ip_address')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            VLAN
        </label>
        <input type="text" name="vlan" value="{{ old('vlan', $customer->vlan ?? '') }}" placeholder="Enter VLAN"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

        @error('vlan')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            POP Location
        </label>
        <input type="text" name="pop_location" value="{{ old('pop_location', $customer->pop_location ?? '') }}"
            placeholder="Enter POP location"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

        @error('pop_location')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Port PON
        </label>
        <input type="text" name="port_pon" value="{{ old('port_pon', $customer->port_pon ?? '') }}"
            placeholder="Enter Port PON"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

        @error('port_pon')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            User Location
        </label>
        <input type="text" name="user_location"
            value="{{ old('user_location', $customer->user_location ?? '') }}" placeholder="Enter user location"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

        @error('user_location')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Bandwidth
        </label>

        <div class="flex gap-2">
            <input type="number" name="bandwidth_value"
                value="{{ old('bandwidth_value', isset($customer->bandwidth) ? preg_replace('/\s*(Kbps|Mbps|Gbps)$/i', '', $customer->bandwidth) : '') }}"
                min="0" step="any" placeholder="Enter bandwidth"
                class="min-w-0 flex-1 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

            <select name="bandwidth_unit"
                class="w-28 rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

                @foreach (['Kbps', 'Mbps', 'Gbps'] as $unit)
                    <option value="{{ $unit }}" @selected(old('bandwidth_unit', isset($customer->bandwidth) ? (preg_match('/(Kbps|Mbps|Gbps)$/i', $customer->bandwidth, $matches) ? $matches[1] : 'Mbps') : 'Mbps') === $unit)>
                        {{ $unit }}
                    </option>
                @endforeach
            </select>
        </div>

        @error('bandwidth_value')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror

        @error('bandwidth_unit')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Access Method
        </label>
        <select name="access_method"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

            <option value="">-- Select access method --</option>

            @foreach ($AccessMethods as $AccessMethod)
                <option value="{{ $AccessMethod }}" @selected(old('access_method', $customer->access_method ?? '') === $AccessMethod)>
                    {{ $AccessMethod }}
                </option>
            @endforeach
        </select>

        @error('access_method')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>


    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Media
        </label>

        <select name="media"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

            <option value="">-- Select media --</option>

            @foreach (['Modem', 'Core to Core'] as $media)
                <option value="{{ $media }}" @selected(old('media', $customer->media ?? '') === $media)>
                    {{ $media }}
                </option>
            @endforeach

        </select>

        @error('media')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Connection Type
        </label>
        <select name="connection_type"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

            <option value="">-- Select connection type --</option>

            @foreach ($ConnectionTypes as $ConnectionType)
                <option value="{{ $ConnectionType }}" @selected(old('connection_type', $customer->connection_type ?? '') === $ConnectionType)>
                    {{ $ConnectionType }}
                </option>
            @endforeach
        </select>

        @error('connection_type')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Coordinates
        </label>
        <input type="text" name="coordinates" value="{{ old('coordinates', $customer->coordinates ?? '') }}"
            placeholder="Example: -6.5971,106.8060"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">

        @error('coordinates')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror

    </div>
    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Address
        </label>
        <textarea name="address" rows="3" placeholder="Enter customer address"
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('address', $customer->address ?? '') }}</textarea>

        @error('address')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror

    </div>
    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Customer Photo
        </label>

        @if (!empty($customer?->photo))
            <img src="{{ asset('storage/' . $customer->photo) }}" class="mb-3 h-20 w-20 rounded-lg object-cover"
                alt="Customer Photo">
        @endif

        <input type="file" name="photo" accept="image/*"
            class="block w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-600 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:text-sm file:font-medium dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:file:bg-gray-800">
        <p class="mt-1 text-xs text-gray-400">
            JPG, JPEG, PNG or WEBP. Maximum 2 MB.
        </p>

        @error('photo')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror

    </div>
</div>
<div class="mt-6 flex justify-end gap-2 border-t border-gray-100 pt-5 dark:border-gray-800">
    <a href="{{ route('customers.index') }}"
        class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
        Cancel
    </a>
    <button type="submit" onclick="return validateBeforeConfirm(event)"
        class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
        {{ isset($customer) ? 'Update Customer' : 'Save Customer' }}
    </button>
</div>
