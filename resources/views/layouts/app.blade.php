<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | AsnetDesk</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (
            localStorage.getItem('asnet-theme') === 'dark'
        ) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>

<body class="bg-gray-50 font-outfit text-gray-800 dark:bg-gray-900 dark:text-gray-100">

    @if (session('success') || session('error'))
        @php
            $isSuccess = session()->has('success');
            $toastId = $isSuccess ? 'successToast' : 'errorToast';
            $toastMessage = $isSuccess ? session('success') : session('error');
        @endphp

        <div id="{{ $toastId }}"
            class="fixed right-5 top-5 z-[99999] flex w-full max-w-sm items-center gap-3 rounded-xl border bg-white p-4 shadow-lg transition-all duration-300
        {{ $isSuccess ? 'border-green-200 dark:border-green-900' : 'border-red-200 dark:border-red-900' }}
        dark:bg-gray-900">

            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full
            {{ $isSuccess ? 'bg-green-100 dark:bg-green-500/10' : 'bg-red-100 dark:bg-red-500/10' }}">

                @if ($isSuccess)
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                @else
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v4m0 4h.01M10.3 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.7 3.86a2 2 0 00-3.4 0z" />
                    </svg>
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <h4 class="text-sm font-semibold text-gray-800 dark:text-white">
                    {{ $isSuccess ? 'Success!' : 'Something went wrong!' }}
                </h4>
                <p class="mt-1 text-xs leading-5 text-gray-500 dark:text-gray-400">
                    {{ $toastMessage }}
                </p>
            </div>
            <button type="button" onclick="closeToast('{{ $toastId }}')"
                class="text-gray-400 transition hover:text-gray-700 dark:hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <script>
            function closeToast(id) {
                const toast = document.getElementById(id);

                if (!toast) return;

                toast.classList.add('translate-x-5', 'opacity-0');

                setTimeout(() => {
                    toast.remove();
                }, 300);
            }

            setTimeout(() => {
                closeToast('{{ $toastId }}');
            }, 3000);
        </script>
    @endif

    <div class="min-h-screen">
        @include('components.sidebar')
        <div id="mainContent" class="lg:ml-[260px]">
            @include('components.header')
            <main class="p-4 md:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        function toggleTheme() {
            const html = document.documentElement;

            html.classList.toggle('dark');

            const isDark = html.classList.contains('dark');

            localStorage.setItem(
                'asnet-theme',
                isDark ? 'dark' : 'light'
            );

            updateThemeIcon();
        }

        function updateThemeIcon() {
            const isDark = document.documentElement.classList.contains('dark');

            document.getElementById('sunIcon')?.classList.toggle('hidden', !isDark);
            document.getElementById('moonIcon')?.classList.toggle('hidden', isDark);
        }

        updateThemeIcon();
    </script>
</body>

</html>
