<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $patientId = $user->id;

        // Stats
        $total = Appointment::where('patient_id', $patientId)->count();
        $successful = Appointment::where('patient_id', $patientId)
            ->where('status', Appointment::STATUS_SUCCESSFUL)
            ->count();
        $inProgress = Appointment::where('patient_id', $patientId)
            ->where('status', Appointment::STATUS_IN_PROGRESS)
            ->count();

        // Calendar data: simple date list for current +/- 2 months
        $start = now()->startOfMonth()->subMonths(2);
        $end = now()->endOfMonth()->addMonths(2);
        $events = Appointment::where('patient_id', $patientId)
            ->whereBetween('scheduled_at', [$start, $end])
            ->orderBy('scheduled_at')
            ->get(['title', 'scheduled_at'])
            ->map(function ($a) {
                // Format to YYYY-MM-DD (Dar es Salaam TZ)
                $date = $a->scheduled_at->setTimezone(config('calendar.timezone', 'Africa/Dar_es_Salaam'));
                return [
                    'date' => $date->format('Y-m-d'),
                    'title' => $a->title ?? 'Miadi',
                ];
            })
            ->values();

        return view('patient.dashboard', [
            'totalAppointments' => $total,
            'successfulAppointments' => $successful,
            'inProgressAppointments' => $inProgress,
            'appointments' => $events,
        ]);
    }
}
