<aside id="dashboard-sidebar" class="fixed top-14 bottom-0 left-0 z-50 w-72 max-w-full transform -translate-x-full lg:translate-x-0 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 shadow-lg lg:shadow-none transition-transform duration-300 ease-out">
  <div class="h-14 border-b border-gray-200 dark:border-gray-800 flex items-center px-4">
    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Akaunti Yangu</span>
  </div>
  <nav class="p-4 space-y-1">
    @php
      $items = [
        ['label' => 'Muhtasari', 'route' => 'patient.dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />'],
        ['label' => 'Miadi', 'route' => 'patient.appointments.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2v-6H3v6a2 2 0 002 2z" />'],
        ['label' => 'Ujumbe', 'route' => 'patient.messages.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h6m8-2a9 9 0 11-18 0 9 9 0 0118 0z" />'],
        ['label' => 'Mipangilio', 'route' => 'patient.settings.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z" />'],
      ];
    @endphp
    @foreach ($items as $item)
      @php
        $active = $item['route'] !== '#'
          ? request()->routeIs($item['route'])
          : false;
      @endphp
      <a href="{{ $item['route'] === '#' ? 'javascript:void(0);' : route($item['route']) }}"
         @if($item['route'] !== '#') wire:navigate @endif
         class="group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                {{ $active ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800' }}">
        <svg class="h-5 w-5 {{ $active ? 'text-primary-600 dark:text-primary-300' : 'text-gray-400 group-hover:text-gray-600 dark:text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $item['icon'] !!}</svg>
        <span>{{ $item['label'] }}</span>
      </a>
    @endforeach
  </nav>
</aside>
<!-- Overlay for mobile sidebar (starts below header) -->
<div id="dashboard-sidebar-overlay" class="fixed top-14 left-0 right-0 bottom-0 z-40 bg-black/40 hidden lg:hidden"></div>
