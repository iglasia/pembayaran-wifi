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

        $id = $request->input('order_id');
        $orderId = $request->input('order_id')."-".time();
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
            'expiry' => [
                'start_time' => date("Y-m-d H:i:s O"),
                'unit' => 'hour',
                'duration' => 1
            ]
        ];

        $snapToken = Snap::getSnapToken($params);
        return response()->json(['token' => $snapToken, 'id'=>$id, 'orderId' => $orderId]);
    }

    public function pembayaranSukses($id, $orderId){
        $transaction = Transaction::findOrFail($id);
        $transaction->status = "Lunas";
        $transaction->order_id = $orderId;
        $transaction->save();
        return redirect('/client/tagihan');

    }

}
