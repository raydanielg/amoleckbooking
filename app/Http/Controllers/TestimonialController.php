<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'body' => ['required', 'string', 'min:10'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $user = Auth::user();

        Testimonial::create([
            'user_id' => $user->id,
            'title' => $data['title'] ?? null,
            'body' => $data['body'],
            'rating' => $data['rating'] ?? 5,
            // For now, auto-approve to make it visible immediately. Later add admin moderation.
            'approved' => true,
        ]);

        return back()->with('status', 'Asante kwa maoni yako! Tumeyapokea.');
    }
}
