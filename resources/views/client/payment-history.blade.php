@extends('layouts.client')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Riwayat Pembayaran Klien: {{ $client->name }}</h5>
    </div>
    <div class="card-body">
        <p><strong>Paket Internet:</strong> {{ $client->internet_package->name }} - {{ indonesian_currency($client->internet_package->price) }}</p>
        <p><strong>Nomor Handphone:</strong> {{ $client->phone_number }}</p>
        <p><strong>Alamat IP:</strong> {{ $client->ip_address }}</p>
        <hr>
        <h6>Detail Transaksi:</h6>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal Pembayaran</th>
                        <th>Jumlah Bayar</th>
                        <th>Bulan Tagihan</th>
                        <th>Tahun Tagihan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $key => $trx)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d F Y') }}</td>
                        <td>{{ indonesian_currency($trx->amount) }}</td>
                        <td>{{ \Carbon\Carbon::create()->month($trx->month)->translatedFormat('F') }}</td>
                        <td>{{ $trx->year }}</td>
                        <td>
                            @if($key == 0)
                                <span class="badge bg-info">Pembayaran Pertama</span>
                            @else
                                <span class="badge bg-success">Lunas</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada riwayat pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection