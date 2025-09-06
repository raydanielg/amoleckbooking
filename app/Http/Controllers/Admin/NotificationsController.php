<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $q = UserNotification::query()
            ->when($request->filled('type'), fn($qq) => $qq->where('type', $request->string('type')->toString()))
            ->when($request->filled('status'), function($qq) use ($request) {
                if ($request->string('status')->toString() === 'unread') {
                    $qq->whereNull('read_at');
                } elseif ($request->string('status')->toString() === 'read') {
                    $qq->whereNotNull('read_at');
                }
            })
            ->when($request->filled('q'), function($qq) use ($request) {
                $s = '%'.$request->string('q')->toString().'%';
                $qq->where(function($w) use ($s) {
                    $w->where('title','like',$s)->orWhere('body','like',$s);
                });
            })
            ->when($request->filled('from'), fn($qq) => $qq->whereDate('created_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn($qq) => $qq->whereDate('created_at', '<=', $request->date('to')))
            ->orderByDesc('created_at');

        $notifications = $q->paginate(15)->withQueryString();
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markRead(Request $request, UserNotification $notification)
    {
        $notification->read_at = now();
        $notification->save();
        return back()->with('status', 'Notification marked as read');
    }

    public function markAll(Request $request)
    {
        UserNotification::whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('status', 'All notifications marked as read');
    }
}
