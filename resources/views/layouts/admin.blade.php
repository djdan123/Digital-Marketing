<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script type="module" src="{{ asset('js/app.js') }}"></script>
</head>
<body class="bg-gray-100 text-gray-900 antialiased dark:bg-gray-900 dark:text-white">
    <div class="min-h-screen bg-white dark:bg-gray-900">
        @include('components.sidebar')
        <div class="lg:pl-64">
            @include('components.navbar')
            <main class="pt-20 p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    <header class="mb-6">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Administration</p>
                                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">@yield('page_title')</h1>
                            </div>
                            @yield('header_actions')
                        </div>
                    </header>
                    @include('components.flash')
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
