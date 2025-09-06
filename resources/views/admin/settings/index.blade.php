@extends('admin.layout')

@section('title', 'Settings')

@section('content')
  <h1 class="text-2xl font-semibold mb-4">System Settings</h1>

  @if (session('status'))
    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md p-3">{{ session('status') }}</div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Maintenance Mode -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
      <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Maintenance Mode</h2>
      <p class="text-sm mb-3">Current state: <span class="font-medium {{ $isDown ? 'text-red-600' : 'text-green-700' }}">{{ $isDown ? 'Enabled' : 'Disabled' }}</span></p>
      <form method="POST" action="{{ route('admin.settings.maintenance.enable') }}" class="space-y-3">
        @csrf
        <div>
          <label class="text-xs text-gray-500">Secret (optional)</label>
          <input name="secret" placeholder="e.g. admin-secret" class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm" {{ $isDown ? 'disabled' : '' }}>
          <p class="text-[11px] text-gray-500 mt-1">Use a secret to bypass maintenance via /?secret=your-secret</p>
        </div>
        <div>
          <label class="text-xs text-gray-500">Allow IPs (comma or space separated)</label>
          <input name="allow_ips" placeholder="127.0.0.1, 192.168.1.10" class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm" {{ $isDown ? 'disabled' : '' }}>
        </div>
        <div class="flex items-center gap-2">
          <button class="px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50" {{ $isDown ? 'disabled' : '' }}>Enable</button>
          <form method="POST" action="{{ route('admin.settings.maintenance.disable') }}">
            @csrf
            <button class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800" {{ $isDown ? '' : 'disabled' }}>Disable</button>
          </form>
        </div>
      </form>
      <p class="text-xs text-gray-500 mt-3">When enabled, the application shows a maintenance page to users. You can allow your IPs or use a secret to access the site.</p>
    </div>

    <!-- Branding -->
    <div class="lg:col-span-2 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
      <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Branding</h2>
      <form method="POST" action="{{ route('admin.settings.branding') }}" enctype="multipart/form-data" class="space-y-3">
        @csrf
        <div>
          <label class="text-xs text-gray-500">Site name</label>
          <input name="site_name" value="{{ $branding['site_name'] }}" placeholder="{{ config('app.name') }}" class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="text-xs text-gray-500">Logo</label>
            <input type="file" name="logo" accept="image/*" class="block w-full text-sm">
            @php $logoUrl = !empty($branding['site_logo_path']) ? Storage::url($branding['site_logo_path']) : null; @endphp
            @if($logoUrl)
              <img src="{{ $logoUrl }}" alt="Logo" class="mt-2 h-10 object-contain">
            @endif
          </div>
          <div>
            <label class="text-xs text-gray-500">Favicon</label>
            <input type="file" name="favicon" accept="image/*" class="block w-full text-sm">
            @php $favUrl = !empty($branding['site_favicon_path']) ? Storage::url($branding['site_favicon_path']) : null; @endphp
            @if($favUrl)
              <img src="{{ $favUrl }}" alt="Favicon" class="mt-2 h-6 w-6">
            @endif
          </div>
        </div>
        <div class="flex items-center justify-end gap-2">
          <button class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Save branding</button>
        </div>
      </form>
    </div>

    <!-- App Info -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 lg:col-span-3">
      <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Application Info</h2>
      <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
        <div><dt class="text-gray-500">Name</dt><dd class="font-medium">{{ $app['name'] }}</dd></div>
        <div><dt class="text-gray-500">Environment</dt><dd class="font-medium">{{ $app['env'] }}</dd></div>
        <div><dt class="text-gray-500">Debug</dt><dd class="font-medium">{{ $app['debug'] ? 'On' : 'Off' }}</dd></div>
        <div><dt class="text-gray-500">URL</dt><dd class="font-medium">{{ $app['url'] }}</dd></div>
        <div><dt class="text-gray-500">Timezone</dt><dd class="font-medium">{{ $app['timezone'] }}</dd></div>
      </dl>
    </div>

    <!-- Tools -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 lg:col-span-3">
      <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Maintenance Tools</h2>
      <form method="POST" action="{{ route('admin.settings.index') }}" onsubmit="event.preventDefault(); document.getElementById('clear-caches-form').submit();">
        @csrf
        <button class="px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50">Clear caches (config/route/view)</button>
      </form>
      <form id="clear-caches-form" method="POST" action="{{ url('/admin/settings/maintenance/disable') }}" style="display:none"></form>
      <p class="text-xs text-gray-500 mt-2">We can expose dedicated buttons for cache clears; currently this is a placeholder. If you want, I can wire a route to trigger real cache clears securely.</p>
    </div>
  </div>
@endsection
