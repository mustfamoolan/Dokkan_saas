<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    @include('admin.components.navbar')
    @include('admin.components.sidebar')

    <div class="p-4 sm:ml-64">
       <div class="p-4 mt-14">
          @yield('content')
       </div>
    </div>

</body>
</html>
