@extends('admin.layout')

@section('title', 'Appointments')

@section('content')
  <h1 class="text-2xl font-semibold mb-4">Appointments</h1>

  @if (session('status'))
    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md p-3">{{ session('status') }}</div>
  @endif

  <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 mb-4 sticky top-16 z-10 backdrop-blur supports-backdrop-blur:bg-white/80 dark:supports-backdrop-blur:bg-gray-900/80">
    <form method="GET" class="flex flex-wrap items-end gap-3">
      <div class="relative min-w-[220px] grow sm:max-w-md">
        <label class="text-xs text-gray-500">Search</label>
        <input aria-label="Search" type="text" name="search" value="{{ request('search') }}" placeholder="Patient, doctor, title..." class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500 ps-9">
        <svg class="absolute left-2 bottom-3 h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16zm8-2l-3.5-3.5"/></svg>
      </div>
      <div class="min-w-[160px]">
        <label class="text-xs text-gray-500">Status</label>
        <select name="status" class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500">
          <option value="">All</option>
          <option value="pending" @selected(request('status')==='pending')>Pending</option>
          <option value="in_progress" @selected(request('status')==='in_progress')>In Progress</option>
          <option value="successful" @selected(request('status')==='successful')>Successful</option>
          <option value="cancelled" @selected(request('status')==='cancelled')>Cancelled</option>
        </select>
      </div>
      <div class="min-w-[150px]">
        <label class="text-xs text-gray-500">From</label>
        <input aria-label="From date" type="date" name="from" value="{{ request('from') }}" class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500">
      </div>
      <div class="min-w-[150px]">
        <label class="text-xs text-gray-500">To</label>
        <input aria-label="To date" type="date" name="to" value="{{ request('to') }}" class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500">
      </div>
      <div class="flex gap-2 ms-auto">
        <button class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Filter</button>
        <a href="{{ route('admin.appointments.index') }}" class="px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Reset</a>
      </div>
    </form>
  </div>

  <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
        <tr>
          <th class="px-4 py-3 text-left font-medium">Patient</th>
          <th class="px-4 py-3 text-left font-medium">Doctor</th>
          <th class="px-4 py-3 text-left font-medium">Title</th>
          <th class="px-4 py-3 text-left font-medium">Date/Time</th>
          <th class="px-4 py-3 text-left font-medium">Status</th>
          <th class="px-4 py-3 text-right font-medium">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
        @forelse($appointments as $a)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60">
            <td class="px-4 py-3">{{ optional($a->patient)->name ?: '-' }}</td>
            <td class="px-4 py-3">{{ optional($a->doctor)->name ?: '-' }}</td>
            <td class="px-4 py-3">{{ $a->title }}</td>
            <td class="px-4 py-3">{{ $a->scheduled_at?->timezone(config('calendar.timezone'))->format('d M Y, H:i') }}</td>
            <td class="px-4 py-3">
              @php
                $map = [
                  'pending' => 'bg-amber-50 text-amber-700',
                  'in_progress' => 'bg-blue-50 text-blue-700',
                  'successful' => 'bg-green-50 text-green-700',
                  'cancelled' => 'bg-red-50 text-red-700',
                ];
                $cls = $map[$a->status] ?? 'bg-gray-100 text-gray-700';
              @endphp
              <span class="px-2 py-0.5 rounded text-xs {{ $cls }}">{{ str_replace('_',' ', $a->status) }}</span>
            </td>
            <td class="px-4 py-3 text-right align-middle">
              <div class="inline-flex items-center gap-2" data-row="{{ $a->id }}">
                <a href="{{ route('admin.appointments.show', $a) }}" class="text-primary-700 hover:underline text-xs" data-preview>View</a>
                <button type="button" class="text-xs px-2 py-1 rounded border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800" data-action="reschedule" data-id="{{ $a->id }}" data-title="{{ $a->title }}">Reschedule</button>
                <button type="button" class="text-xs px-2 py-1 rounded border border-red-200 text-red-700 hover:bg-red-50" data-action="cancel" data-id="{{ $a->id }}" data-title="{{ $a->title }}">Cancel</button>
                <form method="POST" action="{{ route('admin.appointments.status', $a) }}" class="inline" data-status-form>
                  @csrf
                  <select name="status" class="text-xs rounded border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900" onchange="this.closest('[data-row]').classList.add('opacity-60','pointer-events-none'); this.form.submit()">
                    <option value="pending" @selected($a->status==='pending')>Pending</option>
                    <option value="in_progress" @selected($a->status==='in_progress')>In Progress</option>
                    <option value="successful" @selected($a->status==='successful')>Successful</option>
                    <option value="cancelled" @selected($a->status==='cancelled')>Cancelled</option>
                  </select>
                </form>
              </div>
            </td>
          </tr>
        @empty
          @for ($i = 0; $i < 6; $i++)
            <tr class="animate-pulse">
              <td class="px-4 py-3"><div class="h-3 w-40 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
              <td class="px-4 py-3"><div class="h-3 w-40 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
              <td class="px-4 py-3"><div class="h-3 w-56 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
              <td class="px-4 py-3"><div class="h-3 w-32 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
              <td class="px-4 py-3"><div class="h-3 w-20 bg-gray-200 dark:bg-gray-800 rounded"></div></td>
              <td class="px-4 py-3 text-right"><div class="h-3 w-20 ms-auto bg-gray-200 dark:bg-gray-800 rounded"></div></td>
            </tr>
          @endfor
        @endforelse
      </tbody>
    </table>
    <div class="p-3">{{ $appointments->links() }}</div>
  </div>

  <!-- Cancel Modal -->
  <div id="cancel-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" data-close-cancel></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
      <div class="w-full max-w-md rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h3 class="text-base font-semibold mb-2">Cancel appointment</h3>
        <p class="text-sm text-gray-600 mb-3" id="cancel-title"></p>
        <form id="cancel-form" method="POST">
          @csrf
          <input type="hidden" name="status" value="cancelled">
          <label class="text-xs text-gray-500">Reason</label>
          <textarea name="cancel_reason" rows="3" class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Provide a reason..."></textarea>
          <div class="mt-4 flex items-center justify-end gap-2">
            <button type="button" class="px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700" data-close-cancel>Close</button>
            <button class="px-3 py-2 text-sm rounded-md bg-red-600 text-white hover:bg-red-700">Confirm cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Reschedule Modal -->
  <div id="reschedule-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" data-close-reschedule></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
      <div class="w-full max-w-md rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h3 class="text-base font-semibold mb-2">Reschedule appointment</h3>
        <p class="text-sm text-gray-600 mb-3" id="reschedule-title"></p>
        <form id="reschedule-form" method="POST">
          @csrf
          <label class="text-xs text-gray-500">New date & time</label>
          <input type="datetime-local" name="scheduled_at" class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500 mb-3">
          <label class="text-xs text-gray-500">Duration (mins)</label>
          <input type="number" name="duration_minutes" min="15" max="240" value="60" class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500">
          <div class="mt-4 flex items-center justify-end gap-2">
            <button type="button" class="px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700" data-close-reschedule>Close</button>
            <button class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Preview Modal -->
  <div id="preview-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" data-close-preview></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
      <div class="w-full max-w-3xl rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden shadow-xl">
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200 dark:border-gray-800">
          <h3 class="text-base font-semibold">Appointment preview</h3>
          <button class="text-sm px-2 py-1 rounded border border-gray-200 dark:border-gray-700" data-close-preview>Close</button>
        </div>
        <div id="preview-body" class="max-h-[70vh] overflow-y-auto">
          <!-- Loading skeleton -->
          <div class="p-5 animate-pulse space-y-3">
            <div class="h-5 w-48 bg-gray-200 dark:bg-gray-800 rounded"></div>
            <div class="h-3 w-80 bg-gray-200 dark:bg-gray-800 rounded"></div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <div class="h-40 bg-gray-200 dark:bg-gray-800 rounded"></div>
              <div class="h-40 bg-gray-200 dark:bg-gray-800 rounded"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const cancelModal = document.getElementById('cancel-modal');
      const resModal = document.getElementById('reschedule-modal');
      const cancelTitle = document.getElementById('cancel-title');
      const resTitle = document.getElementById('reschedule-title');
      const cancelForm = document.getElementById('cancel-form');
      const resForm = document.getElementById('reschedule-form');

      document.querySelectorAll('[data-action="cancel"]').forEach(btn => {
        btn.addEventListener('click', () => {
          const id = btn.getAttribute('data-id');
          const title = btn.getAttribute('data-title');
          cancelTitle.textContent = '“' + title + '”';
          cancelForm.action = '{{ url('/admin/appointments') }}' + '/' + id + '/status';
          cancelModal.classList.remove('hidden');
        });
      });
      document.querySelectorAll('[data-close-cancel]').forEach(el => el.addEventListener('click', () => cancelModal.classList.add('hidden')));

      document.querySelectorAll('[data-action="reschedule"]').forEach(btn => {
        btn.addEventListener('click', () => {
          const id = btn.getAttribute('data-id');
          const title = btn.getAttribute('data-title');
          resTitle.textContent = '“' + title + '”';
          resForm.action = '{{ url('/admin/appointments') }}' + '/' + id + '/reschedule';
          resModal.classList.remove('hidden');
        });
      });
      document.querySelectorAll('[data-close-reschedule]').forEach(el => el.addEventListener('click', () => resModal.classList.add('hidden')));

      // Preview modal
      const previewModal = document.getElementById('preview-modal');
      const previewBody = document.getElementById('preview-body');
      document.querySelectorAll('[data-preview]').forEach(link => {
        link.addEventListener('click', async (e) => {
          e.preventDefault();
          const url = link.getAttribute('href');
          // show modal + skeleton
          previewModal.classList.remove('hidden');
          previewBody.innerHTML = `
            <div class="p-5 animate-pulse space-y-3">
              <div class="h-5 w-48 bg-gray-200 dark:bg-gray-800 rounded"></div>
              <div class="h-3 w-80 bg-gray-200 dark:bg-gray-800 rounded"></div>
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="h-40 bg-gray-200 dark:bg-gray-800 rounded"></div>
                <div class="h-40 bg-gray-200 dark:bg-gray-800 rounded"></div>
              </div>
            </div>`;
          try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const html = await res.text();
            previewBody.innerHTML = html;
          } catch (err) {
            previewBody.innerHTML = '<div class="p-5 text-sm text-red-600">Failed to load preview.</div>';
          }
        });
      });
      document.querySelectorAll('[data-close-preview]').forEach(el => el.addEventListener('click', () => previewModal.classList.add('hidden')));
    });
  </script>
@endsection
