<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Message;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentsController extends Controller
{
    public function show(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);
        $appointment->load(['patient','messages.sender']);
        return view('doctor.appointments.show', compact('appointment'));
    }

    public function reply(Request $request, Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);
        $data = $request->validate([
            'body' => ['required','string','max:5000'],
        ]);
        $doctor = Auth::user();
        $patientId = $appointment->patient_id;

        $msg = Message::create([
            'appointment_id' => $appointment->id,
            'sender_id' => $doctor->id,
            'recipient_id' => $patientId,
            'body' => $data['body'],
        ]);

        UserNotification::create([
            'user_id' => $patientId,
            'type' => 'message',
            'title' => 'Ujumbe mpya kutoka kwa daktari',
            'body' => mb_strimwidth($data['body'], 0, 120, '...'),
            'data' => [
                'appointment_id' => $appointment->id,
                'message_id' => $msg->id,
            ],
        ]);

        return back()->with('status', 'Ujumbe umetumwa');
    }

    protected function authorizeDoctor(Appointment $appointment): void
    {
        if ($appointment->doctor_id !== Auth::id()) {
            abort(403);
        }
    }
}
