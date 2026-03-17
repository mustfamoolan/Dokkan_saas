<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Dokkan') }} - المندوب</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Minimal Styling using Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Tajawal', 'sans-serif'],
                    },
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#10b981',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="antialiased text-gray-800">

    <div class="min-h-screen flex flex-col">
        @auth('representative')
        <!-- Navbar -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-xl font-bold text-primary">دكان المناديب</span>
                        
                        <div class="hidden sm:mr-6 sm:flex sm:space-x-8 sm:space-x-reverse">
                            <a href="{{ route('rep.dashboard') }}" class="{{ request()->routeIs('rep.dashboard') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">لوحة القيادة</a>
                            <a href="{{ route('rep.orders.index') }}" class="{{ request()->routeIs('rep.orders.*') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">طلباتي</a>
                            <a href="{{ route('rep.customers.index') }}" class="{{ request()->routeIs('rep.customers.*') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">عملائي</a>
                            <a href="{{ route('rep.financials.index') }}" class="{{ request()->routeIs('rep.financials.*') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">المالية</a>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="ml-3 relative flex items-center space-x-4 space-x-reverse">
                            <a href="{{ route('rep.profile.index') }}" class="text-sm font-medium text-gray-700 hover:text-primary">
                                {{ auth('representative')->user()->name }}
                            </a>
                            <form method="POST" action="{{ route('rep.logout') }}">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">
                                    تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu (Simple Placeholder) -->
            <div class="sm:hidden flex justify-around border-t py-2 bg-gray-50">
                <a href="{{ route('rep.dashboard') }}" class="text-sm {{ request()->routeIs('rep.dashboard') ? 'text-primary font-bold' : 'text-gray-600' }}">الرئيسية</a>
                <a href="{{ route('rep.orders.index') }}" class="text-sm {{ request()->routeIs('rep.orders.*') ? 'text-primary font-bold' : 'text-gray-600' }}">الطلبات</a>
                <a href="{{ route('rep.customers.index') }}" class="text-sm {{ request()->routeIs('rep.customers.*') ? 'text-primary font-bold' : 'text-gray-600' }}">العملاء</a>
                <a href="{{ route('rep.financials.index') }}" class="text-sm {{ request()->routeIs('rep.financials.*') ? 'text-primary font-bold' : 'text-gray-600' }}">المالية</a>
            </div>
        </nav>
        @endauth

        <!-- Main Content -->
        <main class="flex-1 max-w-7xl w-full mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="bg-white border-t py-4 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} دكان - بوابة المندوب
        </footer>
    </div>
</body>
</html>
