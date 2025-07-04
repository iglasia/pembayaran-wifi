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
            $query->
            whereNotNull('created_at')
                ->where('status', 'LUNAS') // Hanya mengambil transaksi yang berhasil
                ->orderBy('created_at', 'asc');
        }])->findOrFail($id);

        // dd($client->payments->toArray());

        return view('payment-history.show', compact('client'));
    }
}
