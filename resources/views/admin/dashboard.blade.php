@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
  <h1 class="text-2xl font-semibold mb-6">Admin Dashboard</h1>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-5 bg-white dark:bg-gray-900">
      <p class="text-sm text-gray-500">Jumla ya Watumiaji</p>
      <p class="mt-1 text-2xl font-semibold">{{ \App\Models\User::count() }}</p>
    </div>
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-5 bg-white dark:bg-gray-900">
      <p class="text-sm text-gray-500">Miadi yote</p>
      <p class="mt-1 text-2xl font-semibold">{{ \App\Models\Appointment::count() }}</p>
    </div>
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-5 bg-white dark:bg-gray-900">
      <p class="text-sm text-gray-500">Ujumbe Uliotumwa</p>
      <p class="mt-1 text-2xl font-semibold">{{ \App\Models\Message::count() }}</p>
    </div>
  </div>

  <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-5 bg-white dark:bg-gray-900">
    <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">Haraka</h2>
    <div class="flex flex-wrap gap-2">
      <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Watumiaji</a>
      <a href="{{ route('admin.doctors.index') }}" class="inline-flex items-center px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Madaktari</a>
      <a href="{{ route('admin.appointments.index') }}" class="inline-flex items-center px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Miadi</a>
      <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Ujumbe</a>
      <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Arifa</a>
      <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Mipangilio</a>
    </div>
    <p class="text-xs text-gray-500 mt-3">Tutatengeneza paneli maalum ya usimamizi (users, doctors, appointments, reports) kwenye hatua inayofuata.</p>
  </div>
@endsection
