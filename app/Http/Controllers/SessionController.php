<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{

    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required"]
        ]);

        if (!Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                "credentialsNotMatch" => "Sorry, Your credentials does not match! Please try again."
            ]);
        }
        request()->session()->regenerate();
        return redirect("/blogs");
    }

    public function destroy()
    {

        Auth::logout();
        return redirect('/');
    }
}
