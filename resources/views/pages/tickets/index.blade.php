@extends('layouts.app')

@section('title', 'All Tickets')
@section('page-title', 'All Tickets')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    All Tickets
                </h3>
                <p class="mt-1 text-sm text-gray-400">
                    Manage and monitor all customer support tickets.
                </p>
            </div>
            <a href="{{ route('tickets.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                <span class="text-lg">+</span>
                Create Ticket
            </a>
        </div>
        <div
            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-950">

            <div class="border-b border-gray-200 p-5 dark:border-gray-800 space-y-6">

                <div>

                    <form action="{{ route('tickets.export') }}" method="GET"
                        class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4 xl:items-end">

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Customer
                            </label>

                            <select name="customer_id" id="exportCustomer"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">

                                <option value="">-- Select Customer --</option>

                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected(request('customer_id') == $customer->id)>
                                        {{ $customer->username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Export Month
                            </label>

                            <select name="month"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <option value="all">Full Year</option>
                                @foreach ([
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ] as $number => $month)
                                    <option value="{{ $number }}" @selected($number == now()->month)>
                                        {{ $month }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Year
                            </label>

                            <select name="year"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                @foreach (range(2023, now()->year + 5) as $year)
                                    <option value="{{ $year }}" @selected($year == now()->year)>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <button type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0 3-3m-3 3-3-3m8 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z" />
                                </svg>
                                Export Excel
                            </button>
                        </div>
                    </form>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-800"></div>

                <div>
                    <form action="{{ route('tickets.index') }}" method="GET"
                        class="flex flex-col gap-3 sm:flex-row sm:items-end">

                        <div class="w-full sm:max-w-xs">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Search Tickets
                            </label>

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search ticket..."
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        </div>

                        <div class="w-full sm:w-56">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status
                            </label>

                            <select name="status"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <option value="">-- Select Status --</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                                Search
                            </button>

                            @if (request()->filled('search') || request()->filled('status'))
                                <a href="{{ route('tickets.index') }}"
                                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                Ticket
                            </th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                Customer
                            </th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                Complaints & Actions
                            </th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-gray-500">
                                Status
                            </th>
                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Priority
                            </th>
                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                        @forelse ($tickets as $ticket)
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                        {{ $ticket->name }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">
                                        #{{ $ticket->ticket_code }}
                                    </p>
                                    <p class="mt-2 text-xs text-gray-400">
                                        {{ $ticket->reporting_time?->format('d M Y, H:i') ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($ticket->customer?->photo)
                                            <img src="{{ asset('storage/' . $ticket->customer->photo) }}"
                                                alt="{{ $ticket->customer->username }}"
                                                class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <div
                                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-50 text-sm font-semibold text-brand-600 dark:bg-brand-500/10">
                                                {{ strtoupper(substr($ticket->customer?->username ?? 'C', 0, 1)) }}
                                            </div>
                                        @endif

                                        <div>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                {{ $ticket->customer?->username ?? 'Unknown Customer' }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                PIC : {{ $ticket->customer?->pic ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="max-w-[320px] space-y-3">
                                        <div>
                                            <p class="mb-1 text-xs font-semibold uppercase text-gray-400">
                                                Complaint
                                            </p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ $ticket->complaint ?: '-' }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="mb-1 text-xs font-semibold uppercase text-gray-400">
                                                Action Taken
                                            </p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ $ticket->action ?: '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $statusClass = match (strtolower($ticket->ticket_status ?? '')) {
                                            'new' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',

                                            'in progress'
                                                => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',

                                            'technician en route'
                                                => 'bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400',

                                            'in repair'
                                                => 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',

                                            'awaiting customer'
                                                => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-400',

                                            'completed'
                                                => 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400',

                                            'cancelled'
                                                => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',

                                            default => 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-medium {{ $statusClass }}">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                        {{ $ticket->ticket_status ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $priorityStyles = [
                                            'Urgent' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
                                            'High' =>
                                                'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',
                                            'Medium' =>
                                                'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                                            'Low' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                        ];

                                        $priority = $ticket->priority ?? 'Medium';
                                        $priorityClass = $priorityStyles[$priority] ?? $priorityStyles['Medium'];
                                    @endphp

                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $priorityClass }}">
                                        {{ $priority }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="flex max-w-[180px] flex-wrap justify-center gap-1.5">
                                            @forelse ($ticket->technicians as $technician)
                                                <span
                                                    class="rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                                                    {{ $technician->technician_name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400">
                                                    Unassigned
                                                </span>
                                            @endforelse
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('tickets.show', $ticket->id) }}" title="View Ticket"
                                                class="group flex h-9 w-9 items-center justify-center rounded-lg border border-purple-100 bg-purple-50 text-purple-600 transition hover:border-purple-200 hover:bg-purple-600 hover:text-white dark:border-purple-500/20 dark:bg-purple-500/10 dark:text-purple-400 dark:hover:bg-purple-500 dark:hover:text-white">
                                                <svg class="h-4 w-4 transition-transform group-hover:scale-110"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" />
                                                    <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST"
                                                data-name="#{{ $ticket->ticket_code }} - {{ $ticket->name }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="openDeleteModal(this)"
                                                    title="Delete Ticket"
                                                    class="group flex h-9 w-9 items-center justify-center rounded-lg border border-red-100 bg-red-50 text-red-600 transition hover:border-red-200 hover:bg-red-600 hover:text-white dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500 dark:hover:text-white">
                                                    <svg class="h-4 w-4 transition-transform group-hover:scale-110"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.8"
                                                            d="M4 7h16M10 11v6m4-6v6M5 7l1 13h12l1-13M9 7V4h6v3" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-sm font-medium text-gray-500">
                                        No tickets found.
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">
                                        Create a new ticket to get started.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($tickets->hasPages())
                <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
                    {{ $tickets->links() }}
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
                    Delete Ticket?
                </h3>
                <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">
                    Are you sure you want to delete
                    <span id="deleteTicketName" class="font-semibold text-gray-800 dark:text-white"></span>?
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

            const ticketName = deleteForm.dataset.name;

            document.getElementById('deleteTicketName').textContent = ticketName;

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

        const exportCustomer = document.getElementById('exportCustomer');

        if (exportCustomer) {
            exportCustomer.addEventListener('change', function() {
                const params = new URLSearchParams(window.location.search);

                if (this.value) {
                    params.set('customer_id', this.value);
                } else {
                    params.delete('customer_id');
                }

                window.location.href = `{{ route('tickets.index') }}?${params.toString()}`;
            });
        }
    </script>
@endsection
