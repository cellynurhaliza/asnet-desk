<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sign In | Asnet Desk</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <script>
        if (localStorage.getItem('asnet-theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 font-outfit dark:bg-gray-950">
    <div class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-500 text-white shadow-lg shadow-brand-500/20">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M3 13v-2a9 9 0 0118 0v2" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M3 13h3v6H5a2 2 0 01-2-2v-4zM21 13h-3v6h1a2 2 0 002-2v-4z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M18 19a6 6 0 01-6 3" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Asnet Desk
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    IT Service Management System
                </p>
            </div>
            <div
                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-sm sm:p-8 dark:border-gray-800 dark:bg-gray-900">
                <div class="mb-7">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                        Welcome Back!
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Please sign in to your account to continue.
                    </p>
                </div>

                @if (session('success'))
                    <div
                        class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900 dark:bg-green-500/10 dark:text-green-400">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="login" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Name / Username
                        </label>
                        <input type="text" id="login" name="login" value="{{ old('login') }}"
                            placeholder="Enter your name or username" autocomplete="username" autofocus required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:placeholder:text-gray-500">

                        @error('login')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror

                    </div>
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Password
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Enter your password"
                                autocomplete="current-password" required
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 pr-12 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:placeholder:text-gray-500">
                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-gray-400 transition hover:text-gray-700 dark:hover:text-gray-200"
                                aria-label="Toggle password visibility">
                                <svg id="eyeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                        d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z" />
                                </svg>
                            </button>
                        </div>

                        @error('password')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror

                    </div>
                    <button type="submit"
                        class="flex h-11 w-full items-center justify-center rounded-lg bg-brand-500 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-600 focus:outline-none focus:ring-3 focus:ring-brand-500/20">
                        Sign In
                    </button>
                </form>
            </div>
            <p class="mt-7 text-center text-xs text-gray-400 dark:text-gray-500">
                &copy; {{ date('Y') }} Asnet Desk.
            </p>
        </div>
    </div>
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                        d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                        d="M9.9 5.2A10.5 10.5 0 0112 5c6 0 9.5 7 9.5 7s-1.2 2.4-3.1 3.9M6.2 6.2C3.8 8 2.5 12 2.5 12s3.5 7 9.5 7c1.1 0 2.1-.2 3-.6" />
                `;
            } else {
                password.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                        d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z" />
                `;
            }
        }
    </script>
</body>

</html>
