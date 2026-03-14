<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokkan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col items-center justify-center h-screen">
    <h1 class="text-6xl font-black text-slate-900 mb-4">Dokkan</h1>
    <p class="text-xl text-gray-500 mb-8">A clean, professional Laravel baseline.</p>
    <div class="space-x-4">
        @auth
            <a href="/admin/dashboard" class="bg-slate-900 text-white px-6 py-2 rounded font-bold hover:bg-slate-800 transition">Go to Dashboard</a>
        @else
            <a href="/login" class="bg-slate-900 text-white px-6 py-2 rounded font-bold hover:bg-slate-800 transition">Login</a>
        @endauth
    </div>
</body>
</html>
