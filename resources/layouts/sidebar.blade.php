<aside class="w-64 bg-gray-900 text-white flex flex-col min-h-screen">
    <div class="p-6 border-b border-gray-800">
        <h1 class="text-xl font-bold">Bulk Email System</h1>
    </div>

    <nav class="flex-1 p-4 space-y-2">
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

        @auth
            @if(auth()->user()->role === 'admin')
                <div class="pt-4 mt-4 border-t border-gray-700">
                    <p class="px-4 text-xs uppercase text-gray-400 mb-2">Admin</p>

                    <a href="#"
                       class="block px-4 py-2 rounded hover:bg-gray-800">
                        Manage Users
                    </a>
                </div>
            @endif
        @endauth
    </nav>
</aside>