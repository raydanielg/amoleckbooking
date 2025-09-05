<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Madaktari | {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css','resources/js/app.js'])
  </head>
  <body class="antialiased font-sans text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-900">
    @include('partials.header')

    <!-- Doctors Hero -->
    <section class="bg-white dark:bg-gray-900">
      <div class="py-14 px-4 mx-auto max-w-screen-xl text-center lg:py-20 lg:px-12">
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Explore our doctors</h1>
        <p class="mb-4 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">Chagua daktari wa physiotherapy kulingana na utaalamu, uzoefu, na upatikanaji. Bonyeza "View profile" kujua zaidi kabla ya kufanya booking.</p>
      </div>
    </section>

    <!-- Doctors Grid -->
    <section class="py-4 pb-16 px-4">
      <div class="mx-auto max-w-7xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          @php
            $doctors = [
              ['id' => 1, 'name' => 'Dr. Asha M.', 'title' => 'Physiotherapist', 'photo' => 'https://images.unsplash.com/photo-1550831107-1553da8c8464?q=80&w=600&auto=format&fit=crop'],
              ['id' => 2, 'name' => 'Dr. John K.', 'title' => 'Sports Physio', 'photo' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=600&auto=format&fit=crop'],
              ['id' => 3, 'name' => 'Dr. Neema P.', 'title' => 'Rehab Specialist', 'photo' => 'https://images.unsplash.com/photo-1547425260-76bcadfb4f2c?q=80&w=600&auto=format&fit=crop'],
            ];
          @endphp

          @foreach ($doctors as $doc)
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden shadow-sm hover:shadow-md transition">
            <div class="aspect-[4/3] bg-gray-100 dark:bg-gray-800">
              <img src="{{ $doc['photo'] }}" alt="{{ $doc['name'] }}" class="w-full h-full object-cover" />
            </div>
            <div class="p-5">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $doc['name'] }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-300">{{ $doc['title'] }}</p>
              <div class="mt-4 flex items-center gap-3">
                <a href="{{ route('doctors.show', $doc['id']) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium" wire:navigate>View profile</a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-accent-300 hover:bg-accent-100 text-gray-900 dark:text-white dark:border-accent-700 dark:hover:bg-accent-800/40 text-sm font-medium" wire:navigate>Fanya booking</a>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>
  </body>
</html>
