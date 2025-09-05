<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Miadi #{{ $appointment->id }} | {{ config('app.name') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased font-sans text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-950">
  @include('partials.dashboard-header')
  @include('partials.dashboard-sidebar')

  <div class="lg:pl-72">
    <main class="mx-auto max-w-screen-lg px-4 lg:px-6 py-8">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Miadi #{{ $appointment->id }}</h1>
        <a href="{{ route('patient.appointments.index') }}" class="text-sm text-gray-600 hover:underline" wire:navigate>Rudi</a>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Details card -->
        <div class="lg:col-span-2 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
          <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
            <div>
              <dt class="text-sm text-gray-500">Kichwa</dt>
              <dd class="text-base font-medium">{{ $appointment->title }}</dd>
            </div>
            <div>
              <dt class="text-sm text-gray-500">Hali</dt>
              @php
                $map = [
                  'pending' => 'bg-amber-50 text-amber-700',
                  'in_progress' => 'bg-blue-50 text-blue-700',
                  'successful' => 'bg-green-50 text-green-700',
                  'cancelled' => 'bg-red-50 text-red-700',
                ];
                $cls = $map[$appointment->status] ?? 'bg-gray-100 text-gray-700';
              @endphp
              <dd class="text-base font-medium"><span class="px-2 py-0.5 rounded text-xs {{ $cls }}">{{ str_replace('_',' ', $appointment->status) }}</span></dd>
            </div>
            <div>
              <dt class="text-sm text-gray-500">Tarehe/Saa</dt>
              <dd class="text-base font-medium">{{ $appointment->scheduled_at->timezone(config('calendar.timezone'))->format('d M Y, H:i') }}</dd>
            </div>
            <div>
              <dt class="text-sm text-gray-500">Daktari</dt>
              <dd class="text-base font-medium">{{ optional($appointment->doctor)->name ?: '-' }}</dd>
            </div>
            <div class="sm:col-span-2">
              <dt class="text-sm text-gray-500">Maelezo</dt>
              <dd class="text-base font-medium whitespace-pre-line">{{ $appointment->notes ?: '-' }}</dd>
            </div>
          </dl>
        </div>

        <!-- Thread & Reply -->
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 flex flex-col">
          <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">Ujumbe wa miadi</h2>
          <div class="grow min-h-[240px] max-h-[420px] overflow-y-auto space-y-3 pr-1">
            @forelse($appointment->messages as $m)
              <div class="flex {{ $m->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] rounded-lg px-3 py-2 text-sm {{ $m->sender_id === auth()->id() ? 'bg-primary-50 text-primary-900 dark:bg-primary-900/30 dark:text-primary-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' }}">
                  <div class="text-[11px] opacity-70 mb-1">{{ $m->sender?->name }} • {{ $m->created_at->timezone(config('calendar.timezone'))->format('d M Y, H:i') }}</div>
                  <div class="whitespace-pre-line">{{ $m->body }}</div>
                </div>
              </div>
            @empty
              <p class="text-sm text-gray-500">Hakuna ujumbe bado. Anzisha mawasiliano hapa chini.</p>
            @endforelse
          </div>
          <form method="POST" action="{{ route('patient.appointments.messages.send', $appointment) }}" class="mt-3 space-y-2">
            @csrf
            @if (session('status'))
              <div class="text-xs text-green-700 bg-green-50 border border-green-200 rounded-md p-2">{{ session('status') }}</div>
            @endif
            <textarea name="body" rows="3" required class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Andika ujumbe..."></textarea>
            @error('body')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            <div class="flex items-center justify-end">
              <button class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Tuma</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
