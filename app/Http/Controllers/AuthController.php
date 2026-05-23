<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:150',
            'email'             => 'required|email|unique:users',
            'password'          => ['required', 'confirmed', Password::min(6)],
            'department'        => 'nullable|string|max:100',
            'department_custom' => 'nullable|string|max:100',
            'phone'             => 'nullable|string|max:20',
            'role'              => 'required|in:admin,support',
        ]);

        // Resolve "Other" department
        $department = ($data['department'] === '__other__')
            ? ($data['department_custom'] ?? null)
            : ($data['department'] ?? null);

        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'department' => $department,
            'phone'      => $data['phone'] ?? null,
            'role'       => $data['role'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Welcome, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
