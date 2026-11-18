<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Name or username is required.',
            'password.required' => 'Password is required.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('web')->attempt([
            'name' => $validated['login'],
            'password' => $validated['password'],
            'role' => 'admin',
        ], $remember)) {

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back, Administrator!');
        }

        if (Auth::guard('customer')->attempt([
            'username' => $validated['login'],
            'password' => $validated['password'],
            'status' => 'Active',
        ], $remember)) {

            $request->session()->regenerate();

            return redirect()->route('customer.dashboard')
                ->with('success', 'Welcome back!');
        }

        return back()
            ->withErrors([
                'login' => 'Invalid credentials or your account is not active.',
            ])
            ->onlyInput('login');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}
