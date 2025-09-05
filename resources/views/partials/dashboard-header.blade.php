<header class="sticky top-0 z-40">
  <nav class="bg-primary-700 text-white border-b border-primary-800/60">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-6 h-14 flex items-center justify-between">
      <!-- Mobile sidebar toggle -->
      <div class="flex items-center gap-2">
        <button type="button" class="inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/40 lg:hidden" data-sidebar-toggle="dashboard-sidebar" aria-label="Open sidebar">
          <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
        </button>
        <!-- Brand -->
        <a href="/" class="flex items-center gap-2" wire:navigate>
          <img src="/logo.png" class="h-6 w-auto" alt="{{ config('app.name') }}" />
          <span class="text-sm sm:text-base font-semibold">{{ config('app.name') }}</span>
        </a>
      </div>
      <!-- Profile -->
      <div class="ml-3 relative">
        <button type="button" data-dropdown-toggle="user-menu" aria-expanded="false"
          class="flex items-center gap-2 rounded-full focus:outline-none focus:ring-2 focus:ring-white/40 px-2 py-1">
          @php $u = auth()->user(); @endphp
          <span class="hidden sm:block text-sm font-medium">{{ $u?->name }}</span>
          @php
            $initials = '';
            if ($u?->name) {
              $parts = preg_split('/\s+/', trim($u->name));
              $initials = strtoupper(mb_substr($parts[0] ?? '', 0, 1) . mb_substr($parts[1] ?? '', 0, 1));
            }
          @endphp
          <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-white/15 text-white text-sm font-semibold">
            {{ $initials ?: 'U' }}
          </span>
          <svg class="h-4 w-4 opacity-80" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
        </button>
        <!-- Dropdown -->
        <div id="user-menu" class="hidden absolute right-0 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none z-50">
          <div class="py-1">
            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" wire:navigate>Profile</a>
            <a href="{{ route('password.confirm') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" wire:navigate>Change password</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Sign out</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>
