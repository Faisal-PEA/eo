<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan Halaman Login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    /**
     * Proses Login Manual
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Admin!');
            }

            return redirect()->intended(route('home'))->with('success', 'Berhasil masuk ke akun Anda!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan Halaman Register
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /**
     * Proses Register Manual
     */
    public function register(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ];

        // Jika tabel memiliki kolom no_hp, validasi no_hp
        if (Schema::hasColumn('users', 'no_hp')) {
            $rules['no_hp'] = 'required|string|max:20';
        }

        $request->validate($rules);

        $userData = [
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => 'customer',
            'password' => Hash::make($request->password),
        ];

        if (Schema::hasColumn('users', 'no_hp')) {
            $userData['no_hp'] = $request->no_hp;
        }

        $user = User::create($userData);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Pendaftaran akun berhasil! Selamat datang di EventKu.');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Redirect Pengguna ke Halaman Consent Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Callback Balasan dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            // Bypass verifikasi sertifikat lokal (mengatasi cURL error 77 sertifikat di Windows)
            $googleUser = Socialite::driver('google')
                ->setHttpClient(new Client(['verify' => false]))
                ->user();

            // 1. Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if ($user) {
                // Update avatar & id jika sebelumnya mendaftar secara manual
                $updateData = [
                    'google_id' => $googleUser->getId(),
                ];

                if (Schema::hasColumn('users', 'avatar')) {
                    $updateData['avatar'] = $googleUser->getAvatar();
                }

                $user->update($updateData);
            } else {
                // 2. Buat akun customer baru secara dinamis & aman sesuai kolom database yang ada
                $newUserData = [
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'role'      => 'customer',
                    'password'  => Hash::make(Str::random(16)),
                ];

                if (Schema::hasColumn('users', 'avatar')) {
                    $newUserData['avatar'] = $googleUser->getAvatar();
                }

                if (Schema::hasColumn('users', 'no_hp')) {
                    $newUserData['no_hp'] = '-';
                }

                $user = User::create($newUserData);
            }

            // Login-kan user ke sistem
            Auth::login($user);

            // Arahkan ke dashboard jika admin, atau ke home jika customer
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Admin!');
            }

            return redirect()->route('home')->with('success', 'Berhasil masuk dengan akun Google!');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal masuk dengan Google: ' . $e->getMessage());
        }
    }
}