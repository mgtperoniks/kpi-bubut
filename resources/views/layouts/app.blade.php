<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'KPI Bubut')</title>

    {{-- Tailwind (base utility) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.min.css') }}">

    {{-- UI Modern (global enhancement) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/ui-modern.css') }}">
</head>
<body class="bg-gray-100 text-gray-800">
<div class="min-h-screen flex">

    @include('layouts.sidebar')

    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            @yield('content')
        </div>
    </main>

</div>
</body>
</html>
