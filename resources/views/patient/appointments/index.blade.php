<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Miadi | {{ config('app.name') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased font-sans text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-950">
  @include('partials.dashboard-header')
  @include('partials.dashboard-sidebar')

  <div class="lg:pl-72">
    <main class="mx-auto max-w-screen-xl px-4 lg:px-6 py-8">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Miadi yangu</h1>
        <div class="flex items-center gap-2">
          <a href="#" class="hidden sm:inline-flex items-center px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Export</a>
          <a href="{{ route('patient.appointments.create') }}" class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800" wire:navigate>
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Ongeza miadi
          </a>
        </div>
      </div>

      <!-- Toolbar -->
      <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 mb-4">
        <div class="flex flex-wrap items-center gap-2">
          <form method="GET" class="flex items-stretch gap-2 grow">
            <div class="relative grow max-w-md">
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Tafuta..." class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500 ps-9">
              <svg class="absolute left-2 top-2.5 h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16zm8-2l-3.5-3.5"/></svg>
            </div>
            <button class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Search</button>
          </form>
          <div class="flex items-center gap-2">
            <div class="relative">
              <details class="group">
                <summary class="list-none inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer">
                  <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 8h10M10 12h4M12 16h0"/></svg>
                  Chuja
                </summary>
                <div class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-800 shadow-lg p-3 space-y-2 z-50">
                  <a class="block text-sm px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-800" href="{{ route('patient.appointments.index') }}">Zote</a>
                  <a class="block text-sm px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-800" href="{{ route('patient.appointments.index', ['status' => 'pending']) }}">Zinasubiri</a>
                  <a class="block text-sm px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-800" href="{{ route('patient.appointments.index', ['status' => 'in_progress']) }}">Zinaoendelea</a>
                  <a class="block text-sm px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-800" href="{{ route('patient.appointments.index', ['status' => 'successful']) }}">Zilizo fanikiwa</a>
                  <a class="block text-sm px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-800" href="{{ route('patient.appointments.index', ['status' => 'cancelled']) }}">Zilizo batilishwa</a>
                </div>
              </details>
            </div>
            <a href="#" class="inline-flex items-center px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Mipangilio</a>
          </div>
        </div>
        <div class="mt-3 text-xs text-gray-500">Onyesha: 
          <a href="{{ route('patient.appointments.index') }}" class="ms-2 {{ request('status') ? '' : 'font-semibold text-primary-700' }}">Zote</a>
          <a href="{{ route('patient.appointments.index', ['status' => 'pending']) }}" class="ms-3 {{ request('status')==='pending' ? 'font-semibold text-primary-700' : '' }}">Zinasubiri</a>
          <a href="{{ route('patient.appointments.index', ['status' => 'in_progress']) }}" class="ms-3 {{ request('status')==='in_progress' ? 'font-semibold text-primary-700' : '' }}">Zinaoendelea</a>
          <a href="{{ route('patient.appointments.index', ['status' => 'successful']) }}" class="ms-3 {{ request('status')==='successful' ? 'font-semibold text-primary-700' : '' }}">Zilizo fanikiwa</a>
          <a href="{{ route('patient.appointments.index', ['status' => 'cancelled']) }}" class="ms-3 {{ request('status')==='cancelled' ? 'font-semibold text-primary-700' : '' }}">Zilizo batilishwa</a>
        </div>
      </div>

      <!-- Table -->
      <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
            <tr>
              <th class="px-4 py-3 text-left font-medium">Kichwa</th>
              <th class="px-4 py-3 text-left font-medium">Tarehe/ Saa</th>
              <th class="px-4 py-3 text-left font-medium">Hali</th>
              <th class="px-4 py-3 text-left font-medium">Daktari</th>
              <th class="px-4 py-3 text-right font-medium">Vitendo</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse ($appointments as $a)
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60">
                <td class="px-4 py-3">{{ $a->title }}</td>
                <td class="px-4 py-3">{{ $a->scheduled_at->timezone(config('calendar.timezone'))->format('d M Y, H:i') }}</td>
                <td class="px-4 py-3">
                  @php
                    $map = [
                      'pending' => 'bg-amber-50 text-amber-700',
                      'in_progress' => 'bg-blue-50 text-blue-700',
                      'successful' => 'bg-green-50 text-green-700',
                      'cancelled' => 'bg-red-50 text-red-700',
                    ];
                    $cls = $map[$a->status] ?? 'bg-gray-100 text-gray-700';
                  @endphp
                  <span class="px-2 py-0.5 rounded text-xs {{ $cls }}">{{ str_replace('_',' ', $a->status) }}</span>
                </td>
                <td class="px-4 py-3">{{ optional($a->doctor)->name ?: '-' }}</td>
                <td class="px-4 py-3 text-right">
                  <a href="{{ route('patient.appointments.show', $a) }}" class="text-primary-700 hover:underline text-xs" wire:navigate>Tazama</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-500">Hakuna miadi bado</td>
              </tr>
            @endforelse
          </tbody>
        </table>
        <div class="p-3">{{ $appointments->links() }}</div>
      </div>
    </main>
  </div>
</body>
</html>
