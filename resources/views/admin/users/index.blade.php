@extends('admin.layout')

@section('title', 'Users')

@section('content')
  <h1 class="text-2xl font-semibold mb-4">User Management</h1>

  <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 mb-4">
    <form method="GET" class="flex flex-wrap items-center gap-2">
      <div class="relative grow max-w-md">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500 ps-9">
        <svg class="absolute left-2 top-2.5 h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16zm8-2l-3.5-3.5"/></svg>
      </div>
      <select name="role" class="rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500">
        <option value="">All roles</option>
        <option value="admin" @selected(request('role')==='admin')>Admin</option>
        <option value="doctor" @selected(request('role')==='doctor')>Doctor</option>
        <option value="patient" @selected(request('role')==='patient')>Patient</option>
      </select>
      <button class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Search</button>
      <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Reset</a>
      <button type="button" class="ms-auto px-3 py-2 text-sm rounded-md bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">Add user</button>
    </form>
  </div>

  <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
        <tr>
          <th class="px-4 py-3 text-left font-medium">User</th>
          <th class="px-4 py-3 text-left font-medium">Phone</th>
          <th class="px-4 py-3 text-left font-medium">Email</th>
          <th class="px-4 py-3 text-left font-medium">Role</th>
          <th class="px-4 py-3 text-right font-medium">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
        @forelse ($users as $u)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60">
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                @php
                  $avatar = $u->profile_photo_path ? Storage::url($u->profile_photo_path) : null;
                  $initials = collect(explode(' ', trim($u->name ?? 'User')))->map(fn($p)=>mb_substr($p,0,1))->join('');
                @endphp
                @if($avatar)
                  <img src="{{ $avatar }}" class="h-8 w-8 rounded-full object-cover border border-gray-200 dark:border-gray-800" alt="avatar">
                @else
                  <div class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xs">{{ $initials }}</div>
                @endif
                <div>
                  <div class="font-medium">{{ $u->name }}</div>
                  <div class="text-xs text-gray-500">ID #{{ $u->id }}</div>
                </div>
              </div>
            </td>
            <td class="px-4 py-3">{{ $u->phone ?: '-' }}</td>
            <td class="px-4 py-3">{{ $u->email ?: '-' }}</td>
            <td class="px-4 py-3">
              @php
                $roleMap = [
                  'admin' => 'bg-purple-50 text-purple-700',
                  'doctor' => 'bg-blue-50 text-blue-700',
                  'patient' => 'bg-green-50 text-green-700',
                ];
                $cls = $roleMap[$u->role] ?? 'bg-gray-100 text-gray-700';
              @endphp
              <span class="px-2 py-0.5 rounded text-xs {{ $cls }}">{{ ucfirst($u->role) }}</span>
            </td>
            <td class="px-4 py-3 text-right">
              <a href="#" class="text-primary-700 hover:underline text-xs">View</a>
            </td>
          </tr>
        @empty
          @for ($i = 0; $i < 6; $i++)
            <tr class="animate-pulse">
              <td class="px-4 py-3"><div class="h-3 w-40 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
              <td class="px-4 py-3"><div class="h-3 w-24 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
              <td class="px-4 py-3"><div class="h-3 w-56 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
              <td class="px-4 py-3"><div class="h-3 w-16 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
              <td class="px-4 py-3 text-right"><div class="h-3 w-20 ms-auto bg-gray-200 dark:bg-gray-800 rounded"></div></td>
            </tr>
          @endfor
        @endforelse
      </tbody>
    </table>
    <div class="p-3">{{ $users->links() }}</div>
  </div>
@endsection
