@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;

    $isAdmin = Auth::guard('web')->check();
    $isCustomer = Auth::guard('customer')->check();

    $account = $isAdmin ? Auth::guard('web')->user() : Auth::guard('customer')->user();

    $displayName = $isAdmin ? $account->name ?? 'Administrator' : ($account->pic ?: $account->username ?? 'Customer');

    $displayEmail = $account->email ?? '';
    $displayRole = $isAdmin ? 'Administrator' : 'Customer';

    $avatar = collect(explode(' ', trim($displayName)))
        ->filter()
        ->take(2)
        ->map(fn($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
@endphp

<header
    class="sticky top-0 z-[50] flex h-[80px] items-center justify-between border-b border-gray-200 bg-white px-4 md:px-6 dark:border-gray-800 dark:bg-gray-950">
    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()"
            class="rounded-lg border border-gray-200 p-2 text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-800 lg:hidden">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                @yield('page-title', 'Dashboard')
            </h2>
            <p class="text-xs text-gray-400">
                Welcome to AsnetDesk
            </p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <button id="themeToggle" onclick="toggleTheme()"
            class="rounded-full p-2 text-gray-500 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
            title="Toggle Dark Mode">
            <svg id="sunIcon" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="4" stroke-width="2" />
                <path stroke-linecap="round" stroke-width="2"
                    d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42" />
            </svg>
            <svg id="moonIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.5 14.5A8.5 8.5 0 019.5 3.5a8.5 8.5 0 1011 11z" />
            </svg>
        </button>
        {{-- <button
            class="relative rounded-full p-2 text-gray-500 transition hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
            title="Notifications">

            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" />
            </svg>

            <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-error-500"></span>
        </button> --}}
        <div class="relative" id="userDropdown">
            <button type="button" onclick="toggleUserDropdown()"
                class="flex items-center gap-2 rounded-xl p-1.5 transition hover:bg-gray-100 dark:hover:bg-gray-800">
                <div class="h-9 w-9 shrink-0 overflow-hidden rounded-full bg-brand-100 dark:bg-brand-500/15">

                    @if (!empty($account->photo))
                        <img src="{{ asset('storage/' . $account->photo) }}" alt="{{ $displayName }}"
                            class="h-full w-full object-cover"
                            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">

                        <div
                            class="hidden h-full w-full items-center justify-center text-sm font-semibold text-brand-600 dark:text-brand-400">
                            {{ $avatar ?: 'U' }}
                        </div>
                    @else
                        <div
                            class="flex h-full w-full items-center justify-center text-sm font-semibold text-brand-600 dark:text-brand-400">
                            {{ $avatar ?: 'U' }}
                        </div>
                    @endif

                </div>
                <div class="hidden text-left sm:block">
                    <p class="text-sm font-semibold text-gray-800 dark:text-white">
                        {{ $displayName }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ $displayRole }}
                    </p>
                </div>
                <svg id="dropdownChevron"
                    class="hidden h-4 w-4 text-gray-400 transition-transform duration-200 sm:block" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="userDropdownMenu"
                class="absolute right-0 top-full z-[60] mt-3 hidden w-56 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-gray-900">
                <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                    <p class="text-sm font-semibold text-gray-800 dark:text-white">
                        {{ $displayName }}
                    </p>
                    <p class="mt-1 text-xs text-gray-400">
                        {{ $displayEmail }}
                    </p>
                    <span
                        class="mt-2 inline-flex rounded-full bg-brand-50 px-2 py-1 text-xs font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                        {{ $displayRole }}
                    </span>
                </div>

                @if ($isCustomer)
                    <div class="border-b border-gray-100 p-2 dark:border-gray-800">
                        <a href="{{ route('customer.profile') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8z" />
                            </svg>
                            My Profile
                        </a>
                    </div>
                @endif

                <div class="p-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-500 transition hover:bg-red-50 dark:hover:bg-red-500/10">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M15 17l5-5-5-5M20 12H9m4 5v1a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h5a2 2 0 012 2v1" />
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<script>
    function toggleUserDropdown() {
        const menu = document.getElementById('userDropdownMenu');
        const chevron = document.getElementById('dropdownChevron');

        if (!menu || !chevron) return;

        menu.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdown');
        const menu = document.getElementById('userDropdownMenu');
        const chevron = document.getElementById('dropdownChevron');

        if (dropdown && !dropdown.contains(event.target)) {
            menu?.classList.add('hidden');
            chevron?.classList.remove('rotate-180');
        }
    });
</script>
