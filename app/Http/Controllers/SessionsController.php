<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create()
    {
        return view('session.login-session');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required' 
        ]);

        if (Auth::attempt($attributes)) {
            $user = Auth::user();

            // ❌ Bloquea el acceso si el usuario está inactivo
            if (!$user->active) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['email' => 'Il tuo account è disattivato. Contatta l\'amministratore.']);
            }

            session()->regenerate();
            return redirect('dashboard')->with(['success' => 'Sei connesso.']);
        } else {
            return back()->withErrors(['email' => 'Email o password non valide.']);
        }
    }
    
    public function destroy()
    {
        Auth::logout();
        return redirect('/login')->with(['success' => 'Sei stato disconnesso.']);
    }
}

