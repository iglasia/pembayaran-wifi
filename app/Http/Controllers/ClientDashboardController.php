<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Transaction, Client};

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $client = Client::where('user_id', $user->id)->first();
        
        // Query ini sekarang akan berhasil!
        $unpaid_transactions = Transaction::where('client_id', $client->id)
            ->where('status', 'Belum Lunas')
            ->get();

        return view('client.dashboard', compact('client', 'unpaid_transactions'));
    }
}