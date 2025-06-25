<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
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

        $orderId = $request->input('order_id');
        $grossAmount = $request->input('gross_amount');
        $firstName = $request->input('first_name');
        $email = $request->input('email');

        // Buat parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $firstName,
                'email' => $email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        return response()->json(['token' => $snapToken]);
    }

    public function pembayaranSukses($id){
        $transaction = Transaction::findOrFail($id);
        $transaction->status = "Lunas";
        $transaction->save();
        return redirect('/client/dashboard');

    }

}
