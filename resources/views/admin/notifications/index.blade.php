@extends('admin.layout')

@section('title', 'Notifications')

@section('content')
  <h1 class="text-2xl font-semibold mb-4">Notifications</h1>

  @if (session('status'))
    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md p-3">{{ session('status') }}</div>
  @endif

  <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 mb-4">
    <form method="GET" class="flex flex-wrap items-end gap-3">
      <div>
        <label class="text-xs text-gray-500">Type</label>
        <select name="type" class="rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500">
          <option value="">All</option>
          <option value="message" @selected(request('type')==='message')>Message</option>
          <option value="appointment_status" @selected(request('type')==='appointment_status')>Appointment Status</option>
          <option value="appointment_reschedule" @selected(request('type')==='appointment_reschedule')>Appointment Reschedule</option>
          <option value="broadcast" @selected(request('type')==='broadcast')>Broadcast</option>
        </select>
      </div>
      <div>
        <label class="text-xs text-gray-500">Status</label>
        <select name="status" class="rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500">
          <option value="">All</option>
          <option value="unread" @selected(request('status')==='unread')>Unread</option>
          <option value="read" @selected(request('status')==='read')>Read</option>
        </select>
      </div>
      <div class="flex gap-2 ms-auto">
        <button class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Filter</button>
        <a href="{{ route('admin.notifications.index') }}" class="px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Reset</a>
        <form method="POST" action="{{ route('admin.notifications.mark_all') }}">
          @csrf
          <button class="px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50">Mark all as read</button>
        </form>
      </div>
    </form>
  </div>

  <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
    <ul role="list" class="divide-y divide-gray-100 dark:divide-gray-800">
      @forelse($notifications as $n)
        <li class="p-4 flex items-start gap-3">
          <div class="h-9 w-9 rounded-full flex items-center justify-center {{ $n->read_at ? 'bg-gray-100 text-gray-500' : 'bg-primary-50 text-primary-700' }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
          </div>
          <div class="grow min-w-0">
            <div class="flex items-center justify-between">
              <p class="text-sm font-medium truncate">{{ $n->title }}</p>
              <span class="text-[11px] text-gray-500">{{ $n->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $n->body }}</p>
            @if($n->data && isset($n->data['appointment_id']))
              <a href="{{ route('admin.appointments.show', $n->data['appointment_id']) }}" class="text-xs text-primary-700 hover:underline" data-preview>Open appointment</a>
            @endif
          </div>
          @if(!$n->read_at)
            <form method="POST" action="{{ route('admin.notifications.mark', $n) }}">
              @csrf
              <button class="text-xs px-2 py-1 rounded border border-gray-200 dark:border-gray-700 hover:bg-gray-50">Mark read</button>
            </form>
          @endif
        </li>
      @empty
        @for ($i = 0; $i < 6; $i++)
          <li class="p-4 flex items-start gap-3 animate-pulse">
            <div class="h-9 w-9 rounded-full bg-gray-200 dark:bg-gray-800"></div>
            <div class="grow min-w-0 space-y-2">
              <div class="h-3 w-48 bg-gray-200 dark:bg-gray-800 rounded"></div>
              <div class="h-3 w-80 bg-gray-200 dark:bg-gray-800 rounded"></div>
            </div>
          </li>
        @endfor
      @endforelse
    </ul>
    <div class="p-3">{{ $notifications->links() }}</div>
  </div>
@endsection
