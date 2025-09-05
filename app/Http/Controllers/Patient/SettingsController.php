<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user()->fresh();
        $settings = $user->settings ?? [];
        return view('patient.settings.index', compact('user','settings'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:20','unique:users,phone,'.$user->id],
            'email' => ['nullable','string','lowercase','email','max:255','unique:users,email,'.$user->id],
            'notify_sms' => ['nullable','boolean'],
            'notify_email' => ['nullable','boolean'],
            'notify_messages' => ['nullable','boolean'],
            'premium_enabled' => ['nullable','boolean'],
        ]);
        // Update profile fields if provided
        if (!empty($data['name'])) { $user->name = $data['name']; }
        if (!empty($data['phone'])) { $user->phone = $data['phone']; }
        if (array_key_exists('email', $data)) { $user->email = $data['email']; }
        $settings = $user->settings ?? [];
        $settings['notify_sms'] = (bool)($data['notify_sms'] ?? false);
        $settings['notify_email'] = (bool)($data['notify_email'] ?? true);
        $settings['notify_messages'] = (bool)($data['notify_messages'] ?? true);
        $settings['premium_enabled'] = (bool)($data['premium_enabled'] ?? false);
        $user->settings = $settings;
        $user->save();

        return back()->with('status', 'Mipangilio imehifadhiwa');
    }

    public function uploadPhoto(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'photo' => ['required','image','max:2048'], // 2MB
        ]);
        $path = $request->file('photo')->store('avatars', 'public');
        $user->profile_photo_path = $path;
        $user->save();

        return back()->with('status', 'Picha ya profaili imewekwa');
    }

    public function deletePhoto(Request $request)
    {
        $user = Auth::user();
        if ($user->profile_photo_path) {
            \Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
            $user->save();
        }
        return back()->with('status', 'Picha ya profaili imeondolewa');
    }
}
