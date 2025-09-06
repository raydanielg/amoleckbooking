<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin') | {{ config('app.name') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased font-sans text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-950">
  @include('partials.dashboard-header')
  @include('partials.admin-sidebar')
  <div class="lg:pl-72">
    <main class="mx-auto max-w-screen-xl px-4 lg:px-6 py-8">
      @yield('content')
    </main>
  </div>
</body>
</html>
