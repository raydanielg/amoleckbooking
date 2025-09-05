<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $q = Appointment::where('patient_id', $user->id)
            ->with('doctor')
            ->when($request->filled('status'), fn($qq) => $qq->where('status', $request->string('status')))
            ->when($request->filled('search'), function ($qq) use ($request) {
                $s = '%' . $request->string('search') . '%';
                $qq->where(function ($w) use ($s) {
                    $w->where('title', 'like', $s)->orWhere('notes', 'like', $s);
                });
            })
            ->orderByDesc('scheduled_at');

        $appointments = $q->paginate(10)->withQueryString();
        return view('patient.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $doctors = User::where('role', User::ROLE_DOCTOR)->orderBy('name')->get(['id','name','phone','email']);
        return view('patient.appointments.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'scheduled_at' => ['required','date'],
            'notes' => ['nullable','string'],
            'doctor_id' => ['required', Rule::exists('users','id')->where('role', User::ROLE_DOCTOR)],
            'duration_minutes' => ['nullable','integer','min:15','max:240'],
        ]);
        $data['patient_id'] = $user->id;
        $data['status'] = Appointment::STATUS_PENDING;
        $data['duration_minutes'] = $data['duration_minutes'] ?? 60;

        // Conflict overlap check for the selected doctor (same day window)
        $start = \Carbon\Carbon::parse($data['scheduled_at']);
        $end = (clone $start)->addMinutes($data['duration_minutes']);
        $existing = Appointment::where('doctor_id', $data['doctor_id'])
            ->whereDate('scheduled_at', $start->toDateString())
            ->where('status', '!=', Appointment::STATUS_CANCELLED)
            ->get();
        foreach ($existing as $ex) {
            $exStart = $ex->scheduled_at;
            $exEnd = (clone $exStart)->addMinutes($ex->duration_minutes ?? 60);
            $overlap = $start->lt($exEnd) && $end->gt($exStart);
            if ($overlap) {
                return back()->withErrors(['scheduled_at' => 'Muda huu daktari ameshajazwa (miadi nyingine imepangwa karibu na muda huu). Tafadhali chagua muda mwingine.'])->withInput();
            }
        }

        Appointment::create($data);

        return redirect()->route('patient.appointments.index')->with('status', 'Miadi imeongezwa');
    }

    public function show(Appointment $appointment)
    {
        $this->authorizeView($appointment);
        $appointment->load(['doctor','messages.sender']);
        return view('patient.appointments.show', compact('appointment'));
    }

    protected function authorizeView(Appointment $appointment): void
    {
        if ($appointment->patient_id !== Auth::id()) {
            abort(403);
        }
    }

    public function sendMessage(Request $request, Appointment $appointment)
    {
        $this->authorizeView($appointment);
        $data = $request->validate([
            'body' => ['required','string','max:5000'],
        ]);
        $sender = Auth::user();
        $recipientId = $appointment->doctor_id;
        if (!$recipientId) {
            // Fallback to first admin if no doctor yet
            $recipientId = \App\Models\User::where('role', \App\Models\User::ROLE_ADMIN)->value('id');
        }
        if (!$recipientId) {
            return back()->withErrors(['body' => 'Hakuna mpokeaji aliyeainishwa. Tafadhali jaribu tena baadaye.']);
        }
        $msg = \App\Models\Message::create([
            'appointment_id' => $appointment->id,
            'sender_id' => $sender->id,
            'recipient_id' => $recipientId,
            'body' => $data['body'],
        ]);

        // Create a notification for the recipient
        \App\Models\UserNotification::create([
            'user_id' => $recipientId,
            'type' => 'message',
            'title' => 'Ujumbe mpya kuhusu miadi',
            'body' => mb_strimwidth($data['body'], 0, 120, '...'),
            'data' => [
                'appointment_id' => $appointment->id,
                'message_id' => $msg->id,
            ],
        ]);

        return back()->with('status', 'Ujumbe umetumwa');
    }
}
