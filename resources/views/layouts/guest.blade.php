<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-indigo-50 via-white to-pink-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-800">
            <div class="flex flex-col items-center gap-4">
                <a href="/" wire:navigate class="inline-flex items-center gap-2">
                    <x-application-logo class="w-12 h-12 fill-current text-indigo-600 dark:text-indigo-400" />
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 tracking-tight">{{ config('app.name', 'Laravel') }}</h1>
            </div>

            <div class="w-full max-w-md mt-6 px-6 py-6 bg-white/90 backdrop-blur-sm dark:bg-gray-900/80 border border-gray-200/70 dark:border-gray-700 shadow-xl shadow-indigo-100/40 dark:shadow-black/20 rounded-2xl">
                {{ $slot }}
            </div>

            <div class="mt-6 text-xs text-gray-500/80 dark:text-gray-400/70">
                <span>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}</span>
            </div>
        </div>
    </body>
</html>
