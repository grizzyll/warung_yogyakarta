<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <title>@yield('title', 'Pesan - Ayam Yogya')</title>

    @vite(['resources/css/app.css', 'resources/js/customer.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; background-color: #F3F4F6; }</style>
</head>
<body class="text-gray-800 antialiased min-h-screen">
    @yield('content')
</body>
</html>