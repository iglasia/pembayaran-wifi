<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;

class ClientPaymentController extends Controller
{
    public function index()
    {
        return view('client.payment');
    }

    public function token(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = config('midtrans.isSanitized');
        Config::$is3ds = config('midtrans.is3ds');

        // Buat parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => 1000,
            ],
            'customer_details' => [
                'first_name' => 'Adlan',
                'email' => 'adlan@example.com',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        return response()->json(['token' => $snapToken]);
    }
}
