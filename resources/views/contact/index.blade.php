<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact Us | {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css','resources/js/app.js'])
  </head>
  <body class="antialiased font-sans text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-900">
    @include('partials.header')

    <!-- Hero -->
    <section class="bg-white dark:bg-gray-900">
      <div class="py-14 px-4 mx-auto max-w-screen-xl text-center lg:py-20 lg:px-12">
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Wasiliana Nasi</h1>
        <p class="mb-4 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">Tupo hapa kukusaidia kuhusu miadi, huduma za physiotherapy, na usaidizi wa haraka.</p>
      </div>
    </section>

    <!-- Contact Form + Info -->
    <section class="px-4 pb-20">
      <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form -->
        <div class="lg:col-span-2">
          @if (session('status'))
            <div class="mb-4 p-3 rounded-md bg-primary-50 text-primary-800 dark:bg-primary-900/40 dark:text-primary-200">{{ session('status') }}</div>
          @endif
          @if ($errors->any())
            <div class="mb-4 p-3 rounded-md bg-red-50 text-red-700 dark:bg-red-900/40 dark:text-red-200">
              <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('contact.store') }}" class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jina lako</label>
                <input id="name" name="name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500" placeholder="Jina kamili" />
              </div>
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Barua pepe</label>
                <input id="email" name="email" type="email" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500" placeholder="you@example.com" />
              </div>
              <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Simu (hiari)</label>
                <input id="phone" name="phone" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500" placeholder="0712 000 000" />
              </div>
              <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kichwa (hiari)</label>
                <input id="subject" name="subject" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500" placeholder="Mfano: Kuhusu booking" />
              </div>
              <div class="sm:col-span-2">
                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ujumbe</label>
                <textarea id="message" name="message" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500" placeholder="Andika ujumbe wako hapa..."></textarea>
              </div>
            </div>
            <div class="mt-4">
              <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 rounded-md bg-primary-700 hover:bg-primary-800 text-white font-medium">Tuma Ujumbe</button>
            </div>
          </form>
        </div>

        <!-- Contact Info / Address -->
        <aside class="space-y-6">
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
            <h3 class="text-lg font-semibold mb-2">Mawasiliano</h3>
            <ul class="text-gray-700 dark:text-gray-300 space-y-1">
              <li>Barua pepe: <a class="text-primary-700 hover:underline" href="mailto:info@amoleckphysio.tz">info@amoleckphysio.tz</a></li>
              <li>Simu: +255 712 000 000</li>
            </ul>
          </div>
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
            <h3 class="text-lg font-semibold mb-2">Tawi la Kliniki</h3>
            <p class="text-gray-700 dark:text-gray-300">Amoleck Physiotherapy – Kigamboni<br/>Dar es Salaam, Tanzania</p>
          </div>
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
            <h3 class="text-lg font-semibold mb-2">Saa za Kazi</h3>
            <ul class="text-gray-700 dark:text-gray-300 space-y-1">
              <li>Mon–Fri: 08:00 – 17:00</li>
              <li>Sat: 09:00 – 14:00</li>
              <li>Sun: Closed</li>
            </ul>
          </div>
        </aside>
      </div>
    </section>
  </body>
</html>
