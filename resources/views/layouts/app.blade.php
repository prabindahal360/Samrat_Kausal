<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex flex-col">
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

                            <a href="{{ route('admin.users.index') }}"
                               class="block px-4 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800' : '' }}">
                                Manage Users
                            </a>
                        </div>
                    @endif
                @endauth
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">

            <!-- Topbar -->
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    @yield('page_title', 'Dashboard')
                </h2>

                <div class="flex items-center gap-4">
                    <div class="text-sm text-gray-600">
                        {{ auth()->user()->name ?? 'User' }}
                        @auth
                            <span class="ml-2 px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">
                                {{ auth()->user()->role }}
                            </span>
                        @endauth
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @if(session('success'))
                    <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-3">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-3">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>