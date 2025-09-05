<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Ongeza miadi | {{ config('app.name') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased font-sans text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-950">
  @include('partials.dashboard-header')
  @include('partials.dashboard-sidebar')

  <div class="lg:pl-72">
    <main class="mx-auto max-w-screen-sm px-4 lg:px-6 py-8">
      <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Ongeza miadi</h1>
        <a href="{{ route('patient.appointments.index') }}" class="text-sm text-gray-600 hover:underline" wire:navigate>Rudi kwenye orodha</a>
      </div>

      @if (session('status'))
        <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md p-3">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('patient.appointments.store') }}" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Kichwa cha miadi</label>
          <input name="title" value="{{ old('title') }}" required class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Mf. Kikao cha physiotherapy">
          @error('title')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Chagua daktari</label>
          <select name="doctor_id" required class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500">
            <option value="">-- Chagua --</option>
            @foreach($doctors as $doc)
              <option value="{{ $doc->id }}" @selected(old('doctor_id') == $doc->id)>{{ $doc->name }} @if($doc->phone) ({{ $doc->phone }}) @endif</option>
            @endforeach
          </select>
          @error('doctor_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Tarehe na saa</label>
          <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" required class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500">
          <p class="text-xs text-gray-500 mt-1">Muda wa Dar es Salaam ({{ config('calendar.timezone', 'Africa/Dar_es_Salaam') }}). Mfumo utathibitisha kama daktari yuko busy muda huo.</p>
          @error('scheduled_at')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Maelezo (hiari)</label>
          <textarea name="notes" rows="4" class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Maelezo ya ziada"></textarea>
          @error('notes')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center justify-end gap-2">
          <a href="{{ route('patient.appointments.index') }}" class="px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800" wire:navigate>Ghairi</a>
          <button class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Hifadhi miadi</button>
        </div>
      </form>
    </main>
  </div>
</body>
</html>
