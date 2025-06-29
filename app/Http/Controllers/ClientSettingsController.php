<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientSettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $client = Client::where('user_id', $user->id)->first();
    
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