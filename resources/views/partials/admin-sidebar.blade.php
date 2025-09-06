<aside id="admin-sidebar" class="fixed top-14 bottom-0 left-0 z-50 w-72 max-w-full transform -translate-x-full lg:translate-x-0 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 shadow-lg lg:shadow-none transition-transform duration-300 ease-out">
  <div class="h-14 border-b border-gray-200 dark:border-gray-800 flex items-center px-4">
    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Admin Panel</span>
  </div>
  <nav class="p-4 space-y-1">
    @php
      $items = [
        ['label' => 'Home', 'route' => 'admin.dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />'],
        ['label' => 'User Management', 'route' => 'admin.users.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0 0a4 4 0 100-8 4 4 0 000 8z" />'],
        ['label' => 'Doctors', 'route' => 'admin.doctors.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422A12.083 12.083 0 0112 21.5 12.083 12.083 0 015.84 10.578L12 14z" />'],
        ['label' => 'Appointments', 'route' => 'admin.appointments.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2v-6H3v6a2 2 0 002 2z" />'],
        ['label' => 'Messages', 'route' => 'admin.messages.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h6m8-2a9 9 0 11-18 0 9 9 0 0118 0z" />'],
        ['label' => 'Notifications', 'route' => 'admin.notifications.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />'],
        ['label' => 'Settings', 'route' => 'admin.settings.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z" />'],
      ];
    @endphp
    @foreach ($items as $item)
      @php
        $active = request()->routeIs($item['route']);
      @endphp
      <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ $active ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $item['icon'] !!}</svg>
        <span class="text-sm">{{ $item['label'] }}</span>
      </a>
    @endforeach
  </nav>
</aside>
