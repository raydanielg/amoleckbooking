@extends('admin.layout')

@section('title', 'Messages')

@section('content')
  <h1 class="text-2xl font-semibold mb-4">Broadcast Messages</h1>

  @if (session('status'))
    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md p-3">{{ session('status') }}</div>
  @endif

  <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 mb-6">
    <form method="POST" action="{{ route('admin.messages.broadcast') }}" class="space-y-3">
      @csrf
      <div>
        <label class="text-xs text-gray-500">Audience</label>
        <div class="flex flex-wrap gap-3 mt-1">
          <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="audience[]" value="all" class="rounded"> All</label>
          <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="audience[]" value="patients" class="rounded"> Patients</label>
          <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="audience[]" value="doctors" class="rounded"> Doctors</label>
          <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="audience[]" value="admins" class="rounded"> Admins</label>
        </div>
        <p class="text-xs text-gray-500 mt-1">Tip: Selecting "All" will ignore other selections.</p>
      </div>
      <div>
        <label class="text-xs text-gray-500">Message</label>
        <textarea name="body" rows="6" class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Write your announcement..."></textarea>
        @error('body')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>
      <div class="flex items-center justify-end gap-2">
        <button type="submit" class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Send broadcast</button>
      </div>
    </form>
    <p class="text-xs text-gray-500 mt-3">This sends an in-app notification to the selected audience. We can later extend to SMS/Email based on user preferences.</p>
  </div>

  <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-800">
      <h2 class="text-sm font-medium">Send History</h2>
    </div>
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
        <tr>
          <th class="px-4 py-3 text-left font-medium">When</th>
          <th class="px-4 py-3 text-left font-medium">By</th>
          <th class="px-4 py-3 text-left font-medium">Audience</th>
          <th class="px-4 py-3 text-left font-medium">Snippet</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
        @forelse($broadcasts as $b)
          <tr>
            <td class="px-4 py-3">{{ $b->created_at->diffForHumans() }}</td>
            <td class="px-4 py-3">{{ $b->user?->name }}</td>
            <td class="px-4 py-3">
              @php $aud = is_array($b->audiences) ? $b->audiences : []; @endphp
              <span class="text-xs">{{ implode(', ', $aud) }}</span>
            </td>
            <td class="px-4 py-3">{{ Str::limit($b->body, 80) }}</td>
          </tr>
        @empty
          @for ($i = 0; $i < 4; $i++)
            <tr class="animate-pulse">
              <td class="px-4 py-3"><div class="h-3 w-32 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
              <td class="px-4 py-3"><div class="h-3 w-40 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
              <td class="px-4 py-3"><div class="h-3 w-28 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
              <td class="px-4 py-3"><div class="h-3 w-64 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
            </tr>
          @endfor
        @endforelse
      </tbody>
    </table>
    <div class="p-3">{{ $broadcasts->links() }}</div>
  </div>
@endsection
