@php
    use App\Models\Ticket;
    use Illuminate\Support\Facades\Auth;

    $isAdmin = Auth::guard('web')->check();
    $isCustomer = Auth::guard('customer')->check();
    $activeStatuses = ['New', 'In Progress', 'Technician En Route', 'In Repair', 'Awaiting Customer'];

    if ($isAdmin) {
        $ticketCount = Ticket::whereIn('ticket_status', $activeStatuses)->count();
    } elseif ($isCustomer) {
        $ticketCount = Ticket::where('customer_id', Auth::guard('customer')->id())
            ->whereIn('ticket_status', $activeStatuses)
            ->count();
    } else {
        $ticketCount = 0;
    }

    if ($isAdmin) {
        $menus = [
            [
                'title' => 'Dashboard',
                'url' => route('dashboard'),
                'active' => request()->routeIs('dashboard'),
                'icon' => 'dashboard',
            ],
            [
                'title' => 'All Tickets',
                'url' => route('tickets.index'),
                'active' => request()->routeIs('tickets.*'),
                'icon' => 'tickets',
                'count' => $ticketCount,
            ],
            [
                'title' => 'Customer',
                'url' => route('customers.index'),
                'active' => request()->routeIs('customers.*'),
                'icon' => 'customer',
            ],
            [
                'title' => 'Category',
                'url' => route('categories.index'),
                'active' => request()->routeIs('categories.*'),
                'icon' => 'category',
            ],
            [
                'title' => 'Technician',
                'url' => route('technicians.index'),
                'active' => request()->routeIs('technicians.*'),
                'icon' => 'technician',
            ],
        ];
    } else {
        $menus = [
            [
                'title' => 'Dashboard',
                'url' => route('customer.dashboard'),
                'active' => request()->routeIs('customer.dashboard'),
                'icon' => 'dashboard',
            ],
            [
                'title' => 'All Tickets',
                'url' => route('customer.tickets.index'),
                'active' => request()->routeIs('customer.tickets.*'),
                'icon' => 'tickets',
                'count' => $ticketCount,
            ],
            [
                'title' => 'My Profile',
                'url' => route('customer.profile'),
                'active' => request()->routeIs('customer.profile'),
                'icon' => 'customer',
            ],
        ];
    }
@endphp

<aside id="sidebar"
    class="fixed left-0 top-0 z-[999] flex h-screen w-[260px] -translate-x-full flex-col border-r border-gray-200 bg-white transition-transform duration-300 dark:border-gray-800 dark:bg-gray-950 lg:translate-x-0">
    <div class="flex items-center gap-3 px-6 py-6">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-500 text-white">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 13v-2a9 9 0 0118 0v2" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M3 13h3v6H5a2 2 0 01-2-2v-4zM21 13h-3v6h1a2 2 0 002-2v-4z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 19a6 6 0 01-6 3" />
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                AsnetDesk
            </h1>
            <p class="text-xs text-gray-400 dark:text-gray-500">
                Support Management System
            </p>
        </div>
    </div>
    <div class="mx-6 border-t border-gray-100 dark:border-gray-800"></div>
    <div class="flex-1 overflow-y-auto px-4 py-6">
        <p class="mb-4 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
            {{ $isAdmin ? 'Administration' : 'Customer Portal' }}
        </p>
        <nav class="space-y-1">

            @foreach ($menus as $menu)
                <a href="{{ $menu['url'] }}" @if ($menu['active']) aria-current="page" @endif
                    class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium transition
                    {{ $menu['active']
                        ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400'
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">

                    @if ($menu['icon'] === 'dashboard')
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M3 3h8v8H3zM13 3h8v5h-8zM13 10h8v11h-8zM3 13h8v8H3z" />
                        </svg>
                    @elseif ($menu['icon'] === 'tickets')
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                        </svg>
                    @elseif ($menu['icon'] === 'customer')
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                        </svg>
                    @elseif ($menu['icon'] === 'category')
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z" />
                        </svg>
                    @elseif ($menu['icon'] === 'technician')
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M14.7 6.3a4 4 0 00-5 5l-5.4 5.4a2.1 2.1 0 003 3l5.4-5.4a4 4 0 005-5l-2.5 2.5-3-3 2.5-2.5z" />
                        </svg>
                    @endif

                    <span>{{ $menu['title'] }}</span>

                    @if (isset($menu['count']))
                        <span
                            class="ml-auto rounded-full bg-brand-50 px-2 py-0.5 text-xs font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                            {{ $menu['count'] }}
                        </span>
                    @endif

                </a>
            @endforeach

        </nav>
    </div>
    <div class="border-t border-gray-100 p-5 dark:border-gray-800">
        <p class="text-xs text-gray-400 dark:text-gray-500">
            ASNET Support System
        </p>
    </div>
</aside>
<div id="sidebarOverlay" onclick="closeSidebar()"
    class="fixed inset-0 z-[998] hidden bg-gray-900/50 backdrop-blur-sm lg:hidden">
</div>
