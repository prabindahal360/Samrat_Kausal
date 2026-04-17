<aside class="w-64 bg-gray-900 text-white flex flex-col min-h-screen">
    <div class="p-6 border-b border-gray-800">
        <h1 class="text-xl font-bold">Bulk Email System</h1>
    </div>

    <nav class="flex-1 p-4 space-y-2">

        @auth

            {{-- ================= ADMIN MENU ================= --}}
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('dashboard') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('dashboard') ? 'bg-gray-800' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('customers.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('customers.*') ? 'bg-gray-800' : '' }}">
                    Customers
                </a>

                <a href="{{ route('customers.import.form') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('customers.import.form') ? 'bg-gray-800' : '' }}">
                    Import Customers
                </a>

                <a href="{{ route('campaigns.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('campaigns.*') ? 'bg-gray-800' : '' }}">
                    Campaigns
                </a>

                <div class="pt-4 mt-4 border-t border-gray-700">
                    <p class="px-4 text-xs uppercase text-gray-400 mb-2">Admin</p>

                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800">
                        Manage Users
                    </a>
                </div>

                {{-- ================= USER MENU ================= --}}
            @else
                <a href="{{ route('user.dashboard') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('user.dashboard') ? 'bg-gray-800' : '' }}">
                    My Dashboard
                </a>

                <a href="{{ route('user.products') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('user.products') ? 'bg-gray-800' : '' }}">
                    Products
                </a>

                <a href="{{ route('user.profile') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('user.profile') ? 'bg-gray-800' : '' }}">
                    My Profile
                </a>
            @endif

        @endauth

    </nav>
</aside>
