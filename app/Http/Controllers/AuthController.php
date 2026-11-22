<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // Login Biasa (Email & Password)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $role = Auth::user()->role;

            return match ($role) {
                'admin' => redirect()->route('admin.dashboard'),
                'kasir' => redirect()->route('kasir.dashboard'),
                default => redirect()->route('pelanggan.menu'),
            };
        }

        return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
    }

    // Registrasi Pelanggan (Email Real)
    public function registerPelanggan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan',
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    // Registrasi Karyawan / Kasir (Email Bisnis)
    public function registerKasir(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users', 'ends_with:@satetaichan.com'],
            'password' => 'required|min:8|confirmed',
        ], [
            'email.ends_with' => 'Karyawan wajib menggunakan email bisnis (@satetaichan.com).',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kasir',
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    // Redirect ke Halaman Login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback Setelah Login Google Berhasil
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => Hash::make(Str::random(16)),
                    'role' => str_ends_with($googleUser->getEmail(), '@satetaichan.com') ? 'kasir' : 'pelanggan',
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user);

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'kasir' => redirect()->route('kasir.dashboard'),
                default => redirect()->route('pelanggan.menu'),
            };
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Gagal masuk dengan Google. Pastikan GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET sudah diisi di .env.',
            ]);
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}