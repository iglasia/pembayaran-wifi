<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Langkah 1: Cari klien secara manual
        $client = \App\Models\Client::where('email', $credentials['email'])->first();

        // Langkah 2: Periksa apakah klien ada DAN passwordnya cocok (sudah kita buktikan)
        if ($client && Hash::check($credentials['password'], $client->password)) {

            // Langkah 3: Jika cocok, LOGIN-kan klien secara manual
            Auth::guard('client')->login($client, $request->boolean('remember'));

            // Langkah 4: Regenerasi session dan redirect ke dashboard
            $request->session()->regenerate();

            // KODE LAMA (PENYEBAB MASALAH):
            // return redirect()->intended(route('client.dashboard'));

            // KODE BARU (SOLUSI AKHIR):
            // Langsung arahkan ke dashboard klien tanpa keraguan.
            return redirect()->route('client.dashboard');
        }

        // Langkah 5: Jika salah satu gagal, kembali ke form login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Langsung arahkan ke halaman login klien secara eksplisit.
        return redirect()->route('client.login');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}