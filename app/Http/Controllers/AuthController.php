<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Sugeng rawuh! Berhasil mlebu.');
        }

        return back()->withErrors([
            'email' => 'Email utawa password salah, dulur.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'wallet_balance' => 100000.00, // Bonus saldo awal Rp 100.000
            'role' => 'user',
            'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80',
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Akun kasil digawe! Sampeyan oleh bonus saldo Dompet Bahari Rp 100.000 nggo jajal nonton film & ngrungokna musik.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('info', 'Sampeyan wis metu saka sistem. Matur suwun!');
    }

    public function switchDemoUser($id)
    {
        $user = User::findOrFail($id);
        Auth::login($user);
        return back()->with('success', "Ganti profil berhasil dadi: {$user->name} ({$user->role}" . ($user->hasVipAccess() ? ' | VIP Aktif' : ' | Reguler') . ')');
    }
}
