<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-slate-900 text-white flex flex-col">
            <div class="p-4 text-xl font-bold border-b border-slate-800">Dokkan Admin</div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="#" class="block p-2 hover:bg-slate-800 rounded">Dashboard</a>
            </nav>
            <div class="p-4 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left p-2 hover:bg-slate-800 rounded text-red-400">Logout</button>
                </form>
            </div>
        </div>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow p-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold">@yield('title', 'Dashboard')</h1>
                <div>{{ auth()->user()->name }}</div>
            </header>
            <main class="p-6 flex-1 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
