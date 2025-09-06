<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $q = User::query()
            ->when($request->filled('search'), function($qq) use ($request) {
                $s = '%'.$request->string('search')->toString().'%';
                $qq->where(function($w) use ($s) {
                    $w->where('name','like',$s)
                      ->orWhere('email','like',$s)
                      ->orWhere('phone','like',$s);
                });
            })
            ->when($request->filled('role'), fn($qq) => $qq->where('role', $request->string('role')->toString()))
            ->orderByDesc('id');

        $users = $q->paginate(12)->withQueryString();
        return view('admin.users.index', compact('users'));
    }
}
