<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $expectedUsername = config('admin.username');
        $expectedPassword = config('admin.password');

        $isValidUser = hash_equals($expectedUsername, $validated['username']);
        $isValidPassword = hash_equals($expectedPassword, $validated['password']);

        if (! $isValidUser || ! $isValidPassword) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Usuario ou senha invalidos.');
        }

        $request->session()->put('admin_authenticated', true);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')->with('success', 'Login realizado com sucesso.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logout realizado com sucesso.');
    }
}
