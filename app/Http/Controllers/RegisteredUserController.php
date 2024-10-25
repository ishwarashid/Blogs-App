<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            "username" => ["required", "min:3"],
            "email" => ["required", "email", "max:254", "unique:users"],
            "password" => ["required", "confirmed"],
            "password_confirmation" => ["required"]
        ]);
        try {
            $user = User::create($attributes);
            Auth::login($user);
            return redirect('/blogs')->with('success', 'Registered successfully!');
        } catch (\Exception $e) {
            return redirect("/register")->with('error', 'Failed to register.');
        }
    }
}
