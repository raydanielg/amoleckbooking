<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Ujumbe & Arifa | {{ config('app.name') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased font-sans text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-950">
  @include('partials.dashboard-header')
  @include('partials.dashboard-sidebar')

  <div class="lg:pl-72">
    <main class="mx-auto max-w-screen-xl px-4 lg:px-6 py-8">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Ujumbe & Arifa</h1>
        <div class="flex items-center gap-2">
          <a href="#" class="inline-flex items-center px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Weka kimya</a>
          <a href="#" class="inline-flex items-center px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Tuma ujumbe</a>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Notifications list -->
        <div class="lg:col-span-2 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
          <div class="p-4 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-2">
              <div class="relative grow max-w-md">
                <input type="text" placeholder="Tafuta ujumbe..." class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500 ps-9">
                <svg class="absolute left-2 top-2.5 h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16zm8-2l-3.5-3.5"/></svg>
              </div>
              <button class="px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Chuja</button>
            </div>
          </div>
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
                <button class="text-xs text-primary-700 hover:underline">Tazama</button>
              </li>
            @empty
              <!-- Skeleton loaders -->
              @for ($i = 0; $i < 4; $i++)
                <li class="p-4 animate-pulse">
                  <div class="flex items-start gap-3">
                    <div class="h-9 w-9 rounded-full bg-gray-200 dark:bg-gray-800"></div>
                    <div class="grow min-w-0">
                      <div class="h-3 w-40 bg-gray-200 dark:bg-gray-800 rounded mb-2"></div>
                      <div class="h-3 w-72 bg-gray-200 dark:bg-gray-800 rounded"></div>
                    </div>
                  </div>
                </li>
              @endfor
            @endforelse
          </ul>
          <div class="p-3">{{ method_exists($notifications, 'links') ? $notifications->links() : '' }}</div>
        </div>

        <!-- Chat/Compose -->
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
          <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">Tuma ujumbe</h2>
          <form class="space-y-3">
            <div>
              <label class="text-xs text-gray-500">Kwa</label>
              <select class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500">
                <option>Daktari</option>
                <option>Huduma kwa wateja</option>
              </select>
            </div>
            <div>
              <label class="text-xs text-gray-500">Ujumbe</label>
              <textarea rows="4" class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Andika ujumbe..."></textarea>
            </div>
            <div class="flex items-center justify-end">
              <button class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Tuma</button>
            </div>
          </form>

          <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200 mt-6 mb-3">Mazungumzo ya karibuni</h2>
          @if(isset($recentMessages) && $recentMessages->count())
            <ul class="space-y-2 text-sm">
              @foreach($recentMessages as $rm)
                <li class="flex items-center justify-between p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                  <span class="truncate">{{ $rm->sender?->name ?? 'Mtumiaji' }} — "{{ Str::limit($rm->body, 40) }}"</span>
                  <span class="text-[11px] text-gray-500">{{ $rm->created_at->diffForHumans() }}</span>
                </li>
              @endforeach
            </ul>
          @else
            <!-- Skeletons for recent messages -->
            <div class="space-y-2">
              @for ($i = 0; $i < 3; $i++)
                <div class="p-2 rounded animate-pulse">
                  <div class="h-3 w-64 bg-gray-200 dark:bg-gray-800 rounded mb-1"></div>
                  <div class="h-3 w-28 bg-gray-200 dark:bg-gray-800 rounded"></div>
                </div>
              @endfor
            </div>
          @endif
        </div>
      </div>
    </main>
  </div>
</body>
</html>
