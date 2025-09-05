<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Ujumbe wa daktari | {{ config('app.name') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased font-sans text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-950">
  @include('partials.dashboard-header')
  @include('partials.dashboard-sidebar')

  <div class="lg:pl-72">
    <main class="mx-auto max-w-screen-xl px-4 lg:px-6 py-8">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Ujumbe</h1>
      </div>

      <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
        <ul role="list" class="divide-y divide-gray-100 dark:divide-gray-800">
          @forelse($notifications as $n)
            <li class="p-4 flex items-start gap-3 hover:bg-gray-50 dark:hover:bg-gray-800/60">
              <div class="h-9 w-9 rounded-full flex items-center justify-center {{ $n->read_at ? 'bg-gray-100 text-gray-500' : 'bg-primary-50 text-primary-700' }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26c.67.45 1.55.45 2.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              </div>
              <div class="grow min-w-0">
                <div class="flex items-center justify-between">
                  <p class="text-sm font-medium truncate">{{ $n->title }}</p>
                  <span class="text-[11px] text-gray-500">{{ $n->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ $n->body }}</p>
              </div>
              @php $aid = data_get($n->data, 'appointment_id'); @endphp
              @if($aid)
                <a href="{{ route('doctor.appointments.show', $aid) }}" class="text-xs text-primary-700 hover:underline">Fungua</a>
              @endif
            </li>
          @empty
            <li class="p-8 text-center text-gray-500">Hakuna arifa kwa sasa</li>
          @endforelse
        </ul>
        <div class="p-3">{{ $notifications->links() }}</div>
      </div>
    </main>
  </div>
</body>
</html>
