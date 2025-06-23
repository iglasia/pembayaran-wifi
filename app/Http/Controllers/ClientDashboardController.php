<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $client = auth('client')->user();
        
        // Query ini sekarang akan berhasil!
        $unpaid_transactions = Transaction::where('client_id', $client->id)
            ->where('status', 'Belum Lunas')
            ->get();

        return view('client.dashboard', compact('client', 'unpaid_transactions'));
    }
}