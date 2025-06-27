<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Setting;
use Carbon\Carbon;

class ClientBillingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('order_id')) {

            $orderIdParts = explode('-', $request->order_id);
            $id = $orderIdParts[0]; // Ambil bagian sebelum "-"

            // Redirect ke route yang sama tapi tanpa query string
            return redirect('/client/invoice/'. $id);
        }
        $client = auth('client')->user();

        // Ambil semua transaksi klien
        $transaksi = Transaction::where('client_id', $client->id)->orderBy('month', 'asc')->get();
        // dd($transaksi->toArray());

        // // Tentukan awal langganan dan bulan sekarang
        // $awalLangganan = Carbon::parse($client->created_at)->startOfMonth();
        // $bulanSekarang = Carbon::now()->startOfMonth();

        // // Buat array daftar bulan-tahun dari awal langganan sampai bulan sekarang
        // $daftarTagihan = [];
        // $periode = $awalLangganan->copy();
        // while ($periode <= $bulanSekarang) {
        //     $bulan = $periode->format('m');
        //     $tahun = $periode->format('Y');
        //     $sudahBayar = $transaksi->where('month', $bulan)->where('year', $tahun)->first();
        //     // dd($sudahBayar->status);

        //     $daftarTagihan[] = [
        //         'bulan' => $bulan,
        //         'tahun' => $tahun,
        //         'status' => $sudahBayar ? 'Lunas' : 'Belum Lunas',
        //         'jumlah' => $client->internet_package->price,
        //         'tanggal_bayar' => $sudahBayar ? $sudahBayar->created_at : null,
        //         'transaction_id' => $sudahBayar ? $sudahBayar->id : null,
        //     ];

        //     $periode->addMonth();
        // }

        // dd($transaksi->toArray());
        return view('client.billing', [
            'tagihan' => $transaksi,
            'client' => $client
        ]);
    }

    public function invoice($id)
    {
        // 1. Ambil data transaksi
        $transaction = Transaction::findOrFail($id);

        // 2. Ambil data setting
        $setting = Setting::first();

        // 3. Pastikan keamanan
        if ($transaction->client_id != auth('client')->id()) {
            abort(403, 'Anda tidak diizinkan untuk melihat invoice ini.');
        }

        // 4. Kirim KEDUA variabel ke view
        return view('invoices.show', compact('transaction', 'setting'));
    }
}
