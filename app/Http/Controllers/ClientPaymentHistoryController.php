<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Transaction, Client};

class ClientPaymentHistoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $client = Client::where('user_id', $user->id)->first();

        // Ambil riwayat pembayaran yang sudah dibayar (misal status = 'paid')
        $riwayat = Transaction::where('client_id', $client->id)
                    ->where('status', 'LUNAS')
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Kirim $client dan $riwayat ke view
        return view('client.payment-history', compact('riwayat', 'client'));
    }
}