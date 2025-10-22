<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usn' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(
                Auth::user()->role === 'admin' ? route('admin.dashboard') : route('student.dashboard')
            );
        }

        return back()->withErrors([
            'usn' => 'The provided credentials do not match our records.',
        ])->onlyInput('usn');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'usn' => 'required|string|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'nullable|in:admin,student'
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'usn' => $validated['usn'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'student',
        ]);

        Auth::login($user);

        return redirect()->intended(
            $user->role === 'admin' ? route('admin.dashboard') : route('student.dashboard')
        );
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}