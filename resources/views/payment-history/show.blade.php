@extends('layouts.app', [
    'title' => 'Detail Riwayat Pembayaran'
])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran Klien: {{ $client->name }}</h6>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5>Informasi Klien:</h5>
                    <p><strong>Nama:</strong> {{ $client->name }}</p>
                    <p><strong>Paket Internet:</strong> {{ $client->internet_package->name }} - {{ indonesian_currency($client->internet_package->price) }}</p>
                    <p><strong>Nomor Handphone:</strong> {{ $client->phone_number }}</p>
                    <p><strong>Alamat IP:</strong> {{ $client->ip_address }}</p>
                    <p><strong>Mulai Berlangganan:</strong> {{ $client->created_at->locale('id')->isoFormat('D MMMM Y') }}</p>
                    <p>
                        <strong>Status Pembayaran Bulan Ini:</strong>
                        @php
                            $currentMonth = \Carbon\Carbon::now()->format('m');
                            $currentYear = \Carbon\Carbon::now()->format('Y');
                            $startMonth = $client->created_at->format('m');
                            $startYear = $client->created_at->format('Y');
                            
                            // Cek apakah bulan ini adalah bulan pertama berlangganan
                            $isFirstMonth = ($currentMonth == $startMonth && $currentYear == $startYear);
                            
                            // Cek apakah sudah ada pembayaran untuk bulan ini
                            $hasPaid = $client->payments()
                                ->where('month', $currentMonth)
                                ->where('year', $currentYear)
                                ->exists();
                        @endphp
                        @if($isFirstMonth)
                            <span class="badge badge-info">Bulan Pertama Berlangganan</span>
                        @elseif($hasPaid)
                            <span class="badge badge-success">Sudah Bayar</span>
                        @else
                            <span class="badge badge-danger">Belum Bayar</span>
                        @endif
                    </p>
                </div>

                <hr>

                <h5>Detail Transaksi:</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%"
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Jumlah Bayar</th>
                                <th>Bulan Tagihan</th>
                                <th>Tahun Tagihan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($client->payments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payment->created_at->locale('id')->isoFormat('D MMMM Y') }}</td>
                                <td>{{ indonesian_currency($payment->amount) }}</td>
                                <td>{{ \Carbon\Carbon::createFromDate($payment->year, $payment->month, $payment->day)->locale('id')->isoFormat('MMMM') }}</td>
                                <td>{{ $payment->year }}</td>
                                <td class="text-center">
                                    @php
                                        $paymentMonth = $payment->month;
                                        $paymentYear = $payment->year;
                                        $startMonth = $client->created_at->format('m');
                                        $startYear = $client->created_at->format('Y');
                                        
                                        // Cek apakah pembayaran ini adalah untuk bulan pertama berlangganan
                                        $isFirstMonthPayment = ($paymentMonth == $startMonth && $paymentYear == $startYear);
                                    @endphp
                                    @if($isFirstMonthPayment)
                                        <span class="badge badge-info">Pembayaran Pertama</span>
                                    @else
                                        <span class="badge badge-success">Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('invoice.show', $payment) }}" class="btn btn-sm btn-info" target="_blank">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada riwayat pembayaran untuk klien ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection