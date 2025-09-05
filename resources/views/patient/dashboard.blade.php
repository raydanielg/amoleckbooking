<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Dashboard | {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css','resources/js/app.js'])
  </head>
  <body class="antialiased font-sans text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-950">
    @include('partials.dashboard-header')
    @include('partials.dashboard-sidebar')

    <div class="lg:pl-72">
      <main class="mx-auto max-w-screen-xl px-4 lg:px-6 py-8">
        <h1 class="text-2xl font-semibold mb-6">Karibu, {{ auth()->user()->name }} 👋</h1>

        <!-- Stats cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-5 bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-500">Miadi yote</p>
                <p class="mt-1 text-2xl font-semibold">{{ $totalAppointments ?? 0 }}</p>
              </div>
              <div class="h-10 w-10 rounded-lg bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 flex items-center justify-center">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2v-6H3v6a2 2 0 002 2z"/></svg>
              </div>
            </div>
          </div>
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-5 bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-500">Zilizo fanikiwa</p>
                <p class="mt-1 text-2xl font-semibold">{{ $successfulAppointments ?? 0 }}</p>
              </div>
              <div class="h-10 w-10 rounded-lg bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300 flex items-center justify-center">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              </div>
            </div>
          </div>
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-5 bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-500">Zinaoendelea</p>
                <p class="mt-1 text-2xl font-semibold">{{ $inProgressAppointments ?? 0 }}</p>
              </div>
              <div class="h-10 w-10 rounded-lg bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 flex items-center justify-center">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3M12 22a10 10 0 110-20 10 10 0 010 20z"/></svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Calendar and Quick actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Calendar -->
          <div class="lg:col-span-2 rounded-xl border border-gray-200 dark:border-gray-800 p-5 bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200">Kalenda ya miadi</h2>
              @php
                $tz = config('calendar.timezone', 'Africa/Dar_es_Salaam');
                $mode = config('calendar.google.mode', 'month');
                $calendarId = trim((string) config('calendar.google.calendar_id', ''));
                $embedUrlCfg = trim((string) config('calendar.google.embed_url', ''));
                $embedUrl = '';
                if ($embedUrlCfg !== '') {
                  $embedUrl = $embedUrlCfg;
                } elseif ($calendarId !== '') {
                  $params = http_build_query([
                    'src' => $calendarId,
                    'ctz' => $tz,
                    'mode' => $mode,
                    'hl' => 'sw_TZ',
                    'showPrint' => 0,
                    'showTabs' => 0,
                    'showCalendars' => 0,
                    'showTitle' => 0,
                  ]);
                  $embedUrl = 'https://calendar.google.com/calendar/embed?' . $params;
                }
              @endphp
              @if($embedUrl)
                <div class="inline-flex rounded-md shadow-sm" role="group">
                  <button type="button" data-cal-tab="internal" class="px-3 py-1.5 text-xs font-medium border border-gray-200 dark:border-gray-700 rounded-l-md bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">Ndani</button>
                  <button type="button" data-cal-tab="google" class="px-3 py-1.5 text-xs font-medium border-t border-b border-r border-gray-200 dark:border-gray-700 rounded-r-md hover:bg-gray-50 dark:hover:bg-gray-800">Google</button>
                </div>
              @else
                <a href="#" class="text-sm text-primary-700 hover:underline">Unganisha na Google Calendar</a>
              @endif
            </div>
            <div id="patient-calendar" class="min-h-[520px]" data-appointments='@json($appointments ?? [])'>
              <div class="h-full w-full flex items-center justify-center text-sm text-gray-400 select-none">
                Inapakia kalenda...
              </div>
            </div>
            @if($embedUrl)
              <div id="google-calendar-embed" class="mt-4 hidden">
                <iframe src="{{ $embedUrl }}" style="border: 0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
              </div>
              <script>
                document.addEventListener('DOMContentLoaded', () => {
                  const tabInternal = document.querySelector('[data-cal-tab="internal"]');
                  const tabGoogle = document.querySelector('[data-cal-tab="google"]');
                  const internal = document.getElementById('patient-calendar');
                  const google = document.getElementById('google-calendar-embed');
                  if (tabInternal && tabGoogle && internal && google) {
                    const activate = (which) => {
                      if (which === 'google') {
                        internal.classList.add('hidden');
                        google.classList.remove('hidden');
                        tabInternal.classList.remove('bg-primary-50','text-primary-700','dark:bg-primary-900/30','dark:text-primary-300');
                        tabGoogle.classList.add('bg-primary-50','text-primary-700','dark:bg-primary-900/30','dark:text-primary-300');
                      } else {
                        google.classList.add('hidden');
                        internal.classList.remove('hidden');
                        tabGoogle.classList.remove('bg-primary-50','text-primary-700','dark:bg-primary-900/30','dark:text-primary-300');
                        tabInternal.classList.add('bg-primary-50','text-primary-700','dark:bg-primary-900/30','dark:text-primary-300');
                      }
                    };
                    tabInternal.addEventListener('click', () => activate('internal'));
                    tabGoogle.addEventListener('click', () => activate('google'));
                  }
                });
              </script>
            @endif
          </div>

          <!-- Quick shortcuts -->
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-5 bg-white dark:bg-gray-900">
            <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">Njia za mkato</h2>
            <div class="space-y-3">
              <a href="{{ route('patient.appointments.create') }}" class="flex items-center justify-between px-3 py-2 rounded-lg bg-primary-50 text-primary-800 dark:bg-primary-900/30 dark:text-primary-200" wire:navigate>
                <span class="text-sm font-medium">Weka miadi mpya</span>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
              </a>
              <a href="{{ route('patient.appointments.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg bg-gray-50 text-gray-800 dark:bg-gray-800 dark:text-gray-200" wire:navigate>
                <span class="text-sm font-medium">Tazama miadi yote</span>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </a>
              <a href="#" class="flex items-center justify-between px-3 py-2 rounded-lg bg-gray-50 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                <span class="text-sm font-medium">Tuma ujumbe kwa kliniki</span>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h6m8-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </a>
              <a href="{{ route('profile') }}" class="flex items-center justify-between px-3 py-2 rounded-lg bg-gray-50 text-gray-800 dark:bg-gray-800 dark:text-gray-200" wire:navigate>
                <span class="text-sm font-medium">Hariri profaili</span>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5h2m-1 14v-4m-7 4h14M5 9l7-7 7 7v8a2 2 0 01-2 2H7a2 2 0 01-2-2V9z"/></svg>
              </a>
            </div>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
