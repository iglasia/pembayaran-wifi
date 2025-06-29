<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Client;
use App\Models\InternetPackage;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionRepository $transactionRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      
        $transactions = Transaction::with('client', 'user')
        ->select('id', 'client_id', 'user_id', 'day', 'month', 'year','status')
        ->orderBy('created_at', 'asc')
        ->get();
        $clients = Client::select('id', 'name', 'ip_address')->orderBy('name')->get();

        $amount_this_month = indonesian_currency($this->transactionRepository->sumAmount(month: date('m')));
        $amount_this_year = indonesian_currency($this->transactionRepository->sumAmount(year: date('Y')));

        return view('transactions.index', compact('transactions', 'clients', 'amount_this_month', 'amount_this_year'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request)
    {
        $client = Client::findOrFail($request->client_id);

        $transactionData = [
            'client_id' => $request->client_id,
            'user_id' => auth()->id(),
            'day' => $request->day,
            'month' => $request->month,
            'year' => $request->year,
            'amount' => $client->internet_package->price,
            'status' => $request->status,
        ];

        if ($request->status === 'Lunas') {
            $transactionDate = Carbon::createFromDate($request->year, $request->month, $request->day);
            $transactionData['created_at'] = $transactionDate;
            
            // Generate order_id dengan tanggal transaksi
            $transactionData['order_id'] = $this->getIncrementTransactionId($request->client_id, $request->month);

        }

        Transaction::create($transactionData);

        return redirect()->route('tagihan.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransactionRequest $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $client = Client::findOrFail($request->client_id);

        $transactionData = [
            'client_id' => $request->client_id,
            'user_id' => auth()->id(),
            'day' => $request->day,
            'month' => $request->month,
            'year' => $request->year,
            'amount' => $client->internet_package->price,
            'status' => $request->status,
        ];

        if ($request->status === 'Lunas') {
            $transactionDate = Carbon::createFromDate($request->year, $request->month, $request->day);
            $transactionData['created_at'] = $transactionDate;
            
            // Generate order_id baru jika sebelumnya belum Lunas
            if ($transaction->status !== 'Lunas') {
                $transactionData['order_id'] = $this->getIncrementTransactionId($request->client_id, $request->month);
            }
        } else {
            // Reset jika status diubah dari Lunas ke non-Lunas
            if ($transaction->status === 'Lunas') {
                $transactionData['order_id'] = null;
                $transactionData['created_at'] = null;
            }
        }

        $transaction->update($transactionData);

        return redirect()->route('tagihan.index')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Transaction::findOrFail($id)->delete();

        return redirect()->route('tagihan.index')->with('success', 'Data berhasil dihapus!');
    }


    /**
 * Generate increment transaction ID
 * Format: INV/YYYYMMDD/XXXX (XXXX adalah increment per hari)
 * 
 * @param string|null $date Tanggal transaksi (format Y-m-d), null untuk hari ini
 * @return string
 */
   protected function getIncrementTransactionId($clientId,$month)
    {
        // Ambil ID terakhir
        $lastTransaction = Transaction::where('client_id', $clientId)
            ->where('month', $month)
            ->first();

        // Ambil timestamp saat ini
        $timestamp = time();
        
        // Gabungkan increment dan timestamp
        return $lastTransaction->id . '-' . $timestamp;
    }
}
