<div class="p-4">
  <div class="mb-4">
    <h3 class="text-lg font-semibold">{{ $appointment->title }}</h3>
    <p class="text-sm text-gray-600">Patient: <span class="font-medium">{{ $appointment->patient?->name }}</span> • Doctor: <span class="font-medium">{{ $appointment->doctor?->name }}</span></p>
    <p class="text-sm text-gray-600">When: <span class="font-medium">{{ $appointment->scheduled_at->timezone(config('calendar.timezone'))->format('d M Y, H:i') }}</span> ({{ $appointment->duration_minutes ?? 60 }} mins)</p>
    <p class="text-sm">Status:
      @php
        $map = [
          'pending' => 'bg-amber-50 text-amber-700',
          'in_progress' => 'bg-blue-50 text-blue-700',
          'successful' => 'bg-green-50 text-green-700',
          'cancelled' => 'bg-red-50 text-red-700',
        ];
        $cls = $map[$appointment->status] ?? 'bg-gray-100 text-gray-700';
      @endphp
      <span class="px-2 py-0.5 rounded text-xs {{ $cls }}">{{ str_replace('_',' ', $appointment->status) }}</span>
    </p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="rounded-lg border border-gray-200 dark:border-gray-800 p-4 bg-white dark:bg-gray-900">
      <div class="flex items-center justify-between">
        <h4 class="text-sm font-medium mb-2">Details</h4>
        <div class="flex items-center gap-2">
          <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">
            @csrf
            <input type="hidden" name="status" value="in_progress">
            <button class="text-xs px-2 py-1 rounded border border-gray-200 dark:border-gray-700 hover:bg-gray-50">Mark In Progress</button>
          </form>
          <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}" onsubmit="return confirm('Mark successful?')">
            @csrf
            <input type="hidden" name="status" value="successful">
            <button class="text-xs px-2 py-1 rounded border border-green-200 text-green-700 hover:bg-green-50">Mark Successful</button>
          </form>
          <button type="button" class="text-xs px-2 py-1 rounded border border-gray-200 dark:border-gray-700 hover:bg-gray-50" onclick="window.dispatchEvent(new CustomEvent('open-reschedule-from-preview',{detail:{id:{{ $appointment->id }},title:'{{ addslashes($appointment->title) }}'}}))">Reschedule</button>
          <button type="button" class="text-xs px-2 py-1 rounded border border-red-200 text-red-700 hover:bg-red-50" onclick="window.dispatchEvent(new CustomEvent('open-cancel-from-preview',{detail:{id:{{ $appointment->id }},title:'{{ addslashes($appointment->title) }}'}}))">Cancel</button>
        </div>
      </div>
      <h4 class="sr-only">Details</h4>
      <dl class="text-sm space-y-2">
        <div class="flex justify-between"><dt class="text-gray-500">Patient</dt><dd class="font-medium">{{ $appointment->patient?->name }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500">Doctor</dt><dd class="font-medium">{{ $appointment->doctor?->name }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500">Phone</dt><dd class="font-medium">{{ $appointment->patient?->phone }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="font-medium">{{ $appointment->patient?->email ?: '-' }}</dd></div>
      </dl>
      <div class="mt-3">
        <h5 class="text-xs text-gray-500">Notes</h5>
        <p class="text-sm whitespace-pre-line">{{ $appointment->notes ?: '-' }}</p>
      </div>
    </div>

    <div class="rounded-lg border border-gray-200 dark:border-gray-800 p-4 bg-white dark:bg-gray-900">
      <h4 class="text-sm font-medium mb-2">Recent conversation</h4>
      <div class="max-h-56 overflow-y-auto space-y-2 pr-1">
        @forelse($appointment->messages->take(8) as $m)
          <div class="text-sm {{ $m->sender_id === $appointment->patient_id ? 'text-gray-800' : 'text-blue-800' }}">
            <span class="text-[11px] text-gray-500">{{ $m->created_at->diffForHumans() }} · {{ $m->sender?->name }}</span>
            <div class="whitespace-pre-line">{{ $m->body }}</div>
          </div>
        @empty
          <p class="text-sm text-gray-500">No messages yet.</p>
        @endforelse
      </div>
      <form method="POST" action="{{ route('admin.appointments.messages.send', $appointment) }}" class="mt-3 space-y-2">
        @csrf
        <div class="flex items-center gap-2">
          <select name="recipient" class="rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
            <option value="patient">To patient</option>
            <option value="doctor">To doctor</option>
          </select>
          <button class="px-3 py-2 text-sm rounded-md bg-primary-700 text-white hover:bg-primary-800">Send</button>
        </div>
        <textarea name="body" rows="3" class="w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Write a message..."></textarea>
      </form>
    </div>
  </div>

  <div class="mt-4 rounded-lg border border-gray-200 dark:border-gray-800 p-4 bg-white dark:bg-gray-900">
    <div class="flex items-center justify-between mb-2">
      <h4 class="text-sm font-medium">History</h4>
      <form method="POST" action="{{ route('admin.appointments.history.add', $appointment) }}" class="flex items-center gap-2">
        @csrf
        <input name="description" placeholder="Add note..." class="w-64 rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
        <button class="px-3 py-2 text-xs rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50">Add</button>
      </form>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
          <tr>
            <th class="px-3 py-2 text-left font-medium">When</th>
            <th class="px-3 py-2 text-left font-medium">By</th>
            <th class="px-3 py-2 text-left font-medium">Type</th>
            <th class="px-3 py-2 text-left font-medium">Description</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
          @forelse($appointment->histories as $h)
            <tr>
              <td class="px-3 py-2">{{ $h->created_at->timezone(config('calendar.timezone'))->format('d M Y, H:i') }}</td>
              <td class="px-3 py-2">{{ $h->user?->name ?: '-' }}</td>
              <td class="px-3 py-2 capitalize">{{ str_replace('_',' ', $h->type) }}</td>
              <td class="px-3 py-2">{{ $h->description }}</td>
            </tr>
          @empty
            <tr><td colspan="4" class="px-3 py-6 text-center text-gray-500">No history yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
