<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class PaymentHistoryController extends Controller
{
    public function index()
    {
        // Mengambil semua data klien
        $clients = Client::with('internet_package')->get();
        
        return view('payment-history.index', compact('clients'));
    }

    public function show($id)
    {
        // Mengambil data klien beserta transaksinya
        $client = Client::with(['internet_package', 'payments' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        
        return view('payment-history.show', compact('client'));
    }
}
