<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'KPI Bubut')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.min.css') }}">
</head>
<body class="bg-gray-100 text-gray-800">

<div class="min-h-screen flex">

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6">
        @yield('content')
    </main>

</div>

</body>
</html>
