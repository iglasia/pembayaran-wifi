<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the specified invoice.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        // Load relasi yang diperlukan untuk data invoice
        $transaction->load('client.internet_package', 'user');
        
        // Ambil data pengaturan toko/jasa
        $setting = Setting::first();

        // Kirim semua data ke view invoice
        return view('invoices.show', compact('transaction', 'setting'));
    }
}
