<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class ClientPaymentHistoryController extends Controller
{
    public function index()
    {
        $client = auth('client')->user();

        // Ambil riwayat pembayaran yang sudah dibayar (misal status = 'paid')
        $riwayat = Transaction::where('client_id', $client->id)
                    
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Kirim $client dan $riwayat ke view
        return view('client.payment-history', compact('riwayat', 'client'));
    }
}