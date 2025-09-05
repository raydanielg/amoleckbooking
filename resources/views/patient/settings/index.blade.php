<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Mipangilio | {{ config('app.name') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased font-sans text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-950">
  @include('partials.dashboard-header')
  @include('partials.dashboard-sidebar')

  <div class="lg:pl-72">
    <main class="mx-auto max-w-screen-lg px-4 lg:px-6 py-8">
      <h1 class="text-xl font-semibold mb-4">Mipangilio ya akaunti</h1>

      @if (session('status'))
        <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md p-3">{{ session('status') }}</div>
      @endif

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile card -->
        <div class="lg:col-span-2 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
          <div class="flex items-start gap-4">
            <div class="shrink-0">
              @php
                $avatarUrl = $user->profile_photo_path ? Storage::url($user->profile_photo_path) : null;
                // cache-busting query to avoid stale image after upload
                if ($avatarUrl) { $avatarUrl .= '?t=' . $user->updated_at?->timestamp; }
                $initials = collect(explode(' ', trim($user->name)))->map(fn($p)=>mb_substr($p,0,1))->join('');
              @endphp
              @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="Avatar" class="h-20 w-20 rounded-full object-cover border border-gray-200 dark:border-gray-800">
              @else
                <div class="h-20 w-20 rounded-full bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 flex items-center justify-center text-lg font-semibold border border-gray-200 dark:border-gray-800">{{ $initials }}</div>
              @endif
            </div>
            <div class="grow">
              <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200">Maelezo ya profaili</h2>
              <dl class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <dt class="text-xs text-gray-500">Jina</dt>
                  <dd class="text-sm font-medium">{{ $user->name }}</dd>
                </div>
                <div>
                  <dt class="text-xs text-gray-500">Namba ya simu</dt>
                  <dd class="text-sm font-medium">{{ $user->phone }}</dd>
                </div>
                <div>
                  <dt class="text-xs text-gray-500">Barua pepe</dt>
                  <dd class="text-sm font-medium">{{ $user->email ?: '-' }}</dd>
                </div>
                <div>
                  <dt class="text-xs text-gray-500">Jukumu</dt>
                  <dd class="text-sm font-medium capitalize">{{ $user->role }}</dd>
                </div>
              </dl>
              <div class="mt-4 flex items-center gap-3">
                <a href="{{ route('profile') }}" class="text-sm text-primary-700 hover:underline" wire:navigate>Hariri profaili</a>
                <form method="POST" action="{{ route('patient.settings.photo.upload') }}" enctype="multipart/form-data" class="inline-flex items-center gap-2">
                  @csrf
                  <label class="text-sm inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h3l2-3h6l2 3h3v13H3z"/></svg>
                    <span>Pakia picha</span>
                    <input type="file" name="photo" accept="image/*" class="hidden" onchange="this.form.submit()">
                  </label>
                </form>
                @if($user->profile_photo_path)
                  <form method="POST" action="{{ route('patient.settings.photo.delete') }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="text-sm inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-red-200 text-red-700 hover:bg-red-50">Ondoa picha</button>
                  </form>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Notification toggles -->
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
          <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">Arifa & ujumbe</h2>
          <form method="POST" action="{{ route('patient.settings.update') }}" class="space-y-3">
            @csrf
            <label class="flex items-center justify-between py-2">
              <span class="text-sm">SMS arifa</span>
              <input type="checkbox" name="notify_sms" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" {{ ($settings['notify_sms'] ?? false) ? 'checked' : '' }}>
            </label>
            <label class="flex items-center justify-between py-2">
              <span class="text-sm">Barua pepe arifa</span>
              <input type="checkbox" name="notify_email" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" {{ ($settings['notify_email'] ?? true) ? 'checked' : '' }}>
            </label>
            <label class="flex items-center justify-between py-2">
              <span class="text-sm">Arifa za ujumbe</span>
              <input type="checkbox" name="notify_messages" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" {{ ($settings['notify_messages'] ?? true) ? 'checked' : '' }}>
            </label>

            <div class="pt-2 border-t border-gray-200 dark:border-gray-800">
              <label class="flex items-center justify-between py-2">
                <span class="text-sm">Premium features</span>
                <input type="checkbox" name="premium_enabled" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" {{ ($settings['premium_enabled'] ?? false) ? 'checked' : '' }}>
              </label>
              <p class="text-xs text-gray-500 mt-2">Ukipokea Premium: arifa za papo kwa papo, historia ndefu ya ujumbe, na kalenda inayojiunganisha kiotomatiki.</p>
            </div>

            <div class="flex items-center justify-end">
              <button class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Hifadhi</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
