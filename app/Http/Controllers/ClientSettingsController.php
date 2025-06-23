<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientSettingsController extends Controller
{
    public function index()
    {
        $client = auth('client')->user();
        return view('client.settings', compact('client'));
    }

    public function update(Request $request)
    {
        $client = auth('client')->user();

        $data = $request->validate([
            'nik' => 'nullable|string|size:16',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            $client->password = bcrypt($request->password);
        }
        if ($request->filled('nik')) {
            $client->nik = $request->nik;
        }
        $client->save();

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}