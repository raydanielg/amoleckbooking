<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\AdminBroadcast;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index(Request $request)
    {
        $broadcasts = AdminBroadcast::with('user')->orderByDesc('created_at')->paginate(12);
        return view('admin.messages.index', compact('broadcasts'));
    }

    public function broadcast(Request $request)
    {
        $data = $request->validate([
            'audience' => ['required','array'],
            'audience.*' => ['required','in:all,patients,doctors,admins'],
            'body' => ['required','string','max:5000'],
        ]);

        $aud = $data['audience'];
        // If 'all' is in selection, ignore others
        if (in_array('all', $aud, true)) {
            $aud = ['all'];
        }

        $ids = collect();
        if (in_array('all', $aud, true)) {
            $ids = User::pluck('id');
        } else {
            foreach ($aud as $a) {
                if ($a === 'patients') {
                    $ids = $ids->merge(User::where('role', User::ROLE_PATIENT)->pluck('id'));
                } elseif ($a === 'doctors') {
                    $ids = $ids->merge(User::where('role', User::ROLE_DOCTOR)->pluck('id'));
                } elseif ($a === 'admins') {
                    $ids = $ids->merge(User::where('role', User::ROLE_ADMIN)->pluck('id'));
                }
            }
        }
        $recipients = $ids->unique()->values();

        foreach ($recipients as $uid) {
            UserNotification::create([
                'user_id' => $uid,
                'type' => 'broadcast',
                'title' => 'Ujumbe kutoka Admin',
                'body' => mb_strimwidth($data['body'], 0, 120, '...'),
                'data' => [ 'audience' => $aud ],
            ]);
        }

        AdminBroadcast::create([
            'user_id' => $request->user()->id,
            'audiences' => $aud,
            'body' => $data['body'],
        ]);

        return redirect()->route('admin.messages.index')->with('status', 'Broadcast sent to '.count($recipients).' users');
    }
}
