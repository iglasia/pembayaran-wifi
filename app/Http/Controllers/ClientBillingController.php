<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Transaction,User,Client};
use App\Models\Setting;
use Carbon\Carbon;

class ClientBillingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $client = Client::where('user_id', $user->id)->first();

        // Ambil semua transaksi klien
        $transaksi = Transaction::where('client_id', $client->id)->orderBy('month', 'asc')->get();
       
        return view('client.billing', [
            'tagihan' => $transaksi,
            'client' => $client
        ]);
    }

    public function invoice($id)
    {
        // 1. Ambil data transaksi
        $transaction = Transaction::findOrFail($id);
        
        // 2. Ambil data setting
        $setting = Setting::first();

         $user = auth()->user();

        $client = Client::where('user_id', $user->id)->first();

        // 3. Pastikan keamanan
        if ($transaction->client_id != $client->id) {
            abort(403, 'Anda tidak diizinkan untuk melihat invoice ini.');
        }

        // 4. Kirim KEDUA variabel ke view
        return view('invoices.show', compact('transaction', 'setting'));
    }
}