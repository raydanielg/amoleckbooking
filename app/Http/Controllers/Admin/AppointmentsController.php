<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\UserNotification;
use App\Models\Message;
use App\Models\AppointmentHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentsController extends Controller
{
    public function index(Request $request)
    {
        $q = Appointment::query()->with(['patient','doctor']);

        if ($request->filled('search')) {
            $s = '%'.$request->string('search')->toString().'%';
            $q->where(function($w) use ($s) {
                $w->where('title','like',$s)
                  ->orWhereHas('patient', fn($p) => $p->where('name','like',$s))
                  ->orWhereHas('doctor', fn($d) => $d->where('name','like',$s));
            });
        }
        if ($request->filled('status')) {
            $q->where('status', $request->string('status')->toString());
        }
        if ($request->filled('from')) {
            $q->where('scheduled_at', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $q->where('scheduled_at', '<=', $request->date('to'));
        }
        if ($request->filled('doctor_id')) {
            $q->where('doctor_id', $request->integer('doctor_id'));
        }
        if ($request->filled('patient_id')) {
            $q->where('patient_id', $request->integer('patient_id'));
        }

        $appointments = $q->orderByDesc('scheduled_at')->paginate(15)->withQueryString();

        return view('admin.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient','doctor','messages.sender','histories.user']);
        return view('admin.appointments._preview', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'status' => ['required','in:pending,in_progress,successful,cancelled'],
            'cancel_reason' => ['nullable','string','max:500'],
        ]);
        $oldStatus = $appointment->status;
        $appointment->status = $data['status'];
        if ($data['status'] === Appointment::STATUS_CANCELLED && !empty($data['cancel_reason'])) {
            // Append cancel reason to notes (non-destructive)
            $prefix = "\n".(now()->format('Y-m-d H:i'))." Admin Cancel: ";
            $appointment->notes = trim(($appointment->notes ?? '').$prefix.$data['cancel_reason']);
        }
        $appointment->save();

        // History log
        AppointmentHistory::create([
            'appointment_id' => $appointment->id,
            'user_id' => $request->user()->id,
            'type' => 'status_change',
            'description' => "Status from {$oldStatus} to {$data['status']}" . (!empty($data['cancel_reason']) ? (". Reason: ".$data['cancel_reason']) : ''),
        ]);

        // Notify patient and doctor
        $title = 'Mabadiliko ya hali ya miadi';
        $body = "Hali imebadilika kutoka {$oldStatus} hadi {$data['status']}";
        if ($data['status'] === Appointment::STATUS_CANCELLED && !empty($data['cancel_reason'])) {
            $body .= ": ".$data['cancel_reason'];
        }
        foreach ([$appointment->patient_id, $appointment->doctor_id] as $uid) {
            if ($uid) {
                UserNotification::create([
                    'user_id' => $uid,
                    'type' => 'appointment_status',
                    'title' => $title,
                    'body' => $body,
                    'data' => [
                        'appointment_id' => $appointment->id,
                        'status' => $appointment->status,
                    ],
                ]);
            }
        }

        return back()->with('status', 'Appointment status updated to '.$data['status']);
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'scheduled_at' => ['required','date'],
            'duration_minutes' => ['nullable','integer','min:15','max:240'],
        ]);
        $duration = $data['duration_minutes'] ?? ($appointment->duration_minutes ?? 60);
        $start = Carbon::parse($data['scheduled_at']);
        $end = (clone $start)->addMinutes($duration);

        // Conflict check for doctor, exclude current appointment
        $existing = Appointment::where('doctor_id', $appointment->doctor_id)
            ->whereDate('scheduled_at', $start->toDateString())
            ->where('id', '!=', $appointment->id)
            ->where('status', '!=', Appointment::STATUS_CANCELLED)
            ->get();
        foreach ($existing as $ex) {
            $exStart = $ex->scheduled_at;
            $exEnd = (clone $exStart)->addMinutes($ex->duration_minutes ?? 60);
            if ($start->lt($exEnd) && $end->gt($exStart)) {
                return back()->withErrors(['scheduled_at' => 'Muda huu daktari ameshajazwa. Tafadhali chagua muda mwingine.'])->withInput();
            }
        }

        $appointment->scheduled_at = $start;
        $appointment->duration_minutes = $duration;
        $appointment->save();

        // Notify patient and doctor
        $title = 'Miadi imerekebishwa';
        $body = 'Miadi yako imepangiwa tena: '. $appointment->scheduled_at->timezone(config('calendar.timezone'))->format('d M Y, H:i');
        foreach ([$appointment->patient_id, $appointment->doctor_id] as $uid) {
            if ($uid) {
                UserNotification::create([
                    'user_id' => $uid,
                    'type' => 'appointment_reschedule',
                    'title' => $title,
                    'body' => $body,
                    'data' => [
                        'appointment_id' => $appointment->id,
                        'scheduled_at' => $appointment->scheduled_at->toIso8601String(),
                        'duration_minutes' => $appointment->duration_minutes,
                    ],
                ]);
            }
        }

        return back()->with('status', 'Miadi imepangiwa tena');
    }

    public function sendMessage(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'recipient' => ['required','in:patient,doctor'],
            'body' => ['required','string','max:5000'],
        ]);
        $toId = $data['recipient'] === 'patient' ? $appointment->patient_id : $appointment->doctor_id;
        if (!$toId) {
            return back()->withErrors(['body' => 'Recipient not available for this appointment.']);
        }
        $msg = Message::create([
            'appointment_id' => $appointment->id,
            'sender_id' => $request->user()->id,
            'recipient_id' => $toId,
            'body' => $data['body'],
        ]);
        UserNotification::create([
            'user_id' => $toId,
            'type' => 'message',
            'title' => 'Ujumbe mpya kuhusu miadi',
            'body' => mb_strimwidth($data['body'], 0, 120, '...'),
            'data' => [
                'appointment_id' => $appointment->id,
                'message_id' => $msg->id,
            ],
        ]);

        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $appointment->load(['patient','doctor','messages.sender']);
            return response()->view('admin.appointments._preview', compact('appointment'));
        }

        return back()->with('status', 'Ujumbe umetumwa');
    }

    public function addHistory(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'description' => ['required','string','max:2000'],
        ]);
        AppointmentHistory::create([
            'appointment_id' => $appointment->id,
            'user_id' => $request->user()->id,
            'type' => 'note',
            'description' => $data['description'],
        ]);

        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $appointment->load(['patient','doctor','messages.sender','histories.user']);
            return response()->view('admin.appointments._preview', compact('appointment'));
        }

        return back()->with('status', 'History added');
    }
}
