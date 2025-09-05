<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function index(Request $request)
    {
        $doctor = Auth::user();
        $notifications = UserNotification::where('user_id', $doctor->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('doctor.messages.index', compact('notifications'));
    }
}
