<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserNotification;
use App\Models\Message;

class MessagesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $notifications = UserNotification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        // Recent conversations: latest 5 messages involving this user
        $recentMessages = Message::with('sender')
            ->where(function($q) use ($user) {
                $q->where('sender_id', $user->id)
                  ->orWhere('recipient_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('patient.messages.index', compact('notifications','recentMessages'));
    }
}
