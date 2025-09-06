@extends('admin.layout')

@section('title', 'Doctors')

@section('content')
  <h1 class="text-2xl font-semibold mb-4">Doctors Management</h1>

  <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 mb-4">
    <div class="flex flex-wrap items-center gap-2">
      <div class="relative grow max-w-md">
        <input type="text" placeholder="Search doctors..." class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500 ps-9">
        <svg class="absolute left-2 top-2.5 h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16zm8-2l-3.5-3.5"/></svg>
      </div>
      <button class="px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Filter</button>
      <button class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Add doctor</button>
    </div>
  </div>

  <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
        <tr>
          <th class="px-4 py-3 text-left font-medium">Name</th>
          <th class="px-4 py-3 text-left font-medium">Phone</th>
          <th class="px-4 py-3 text-left font-medium">Email</th>
          <th class="px-4 py-3 text-left font-medium">Specialty</th>
          <th class="px-4 py-3 text-right font-medium">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
        @for ($i = 0; $i < 6; $i++)
          <tr class="animate-pulse">
            <td class="px-4 py-3"><div class="h-3 w-40 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
            <td class="px-4 py-3"><div class="h-3 w-24 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
            <td class="px-4 py-3"><div class="h-3 w-56 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
            <td class="px-4 py-3"><div class="h-3 w-24 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
            <td class="px-4 py-3 text-right"><div class="h-3 w-20 ms-auto bg-gray-200 dark:bg-gray-800 rounded"></div></td>
          </tr>
        @endfor
      </tbody>
    </table>
    <div class="p-3 text-xs text-gray-500">Loading...</div>
  </div>
@endsection
