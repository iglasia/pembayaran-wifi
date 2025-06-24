@extends('layouts.client')

@section('title', 'Data Tagihan')

@section('content')

    {{-- Bagian Informasi Klien --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5>Informasi Klien</h5>
        </div>
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $client->name }}</p>
            <p><strong>Paket Internet:</strong> {{ $client->internet_package->name }}
                ({{ indonesian_currency($client->internet_package->price) }})</p>
            <p><strong>Nomor Handphone:</strong> {{ $client->phone_number }}</p>
            <p><strong>Alamat IP:</strong> {{ $client->ip_address }}</p>
            <p><strong>Mulai Berlangganan:</strong>
                {{ \Carbon\Carbon::parse($client->subscription_reactivated_at ?? $client->created_at)->translatedFormat('d F Y') }}
            </p>
        </div>
    </div>


    {{-- Bagian Daftar Tagihan --}}
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Riwayat dan Data Tagihan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Bulan/Tahun</th>
                            <th>Tagihan</th>
                            <th>Status Bayar</th>
                            <th>Tanggal Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tagihan as $key => $t)
                            <tr class="text-center">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ \Carbon\Carbon::create($t['year'], $t['month'], 1)->translatedFormat('F Y') }}</td>
                                <td>{{ indonesian_currency($t['amount']) }}</td>
                                <td>
                                    @if ($t['status'] == 'Lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-danger">Belum Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $t['created_at'] ? \Carbon\Carbon::parse($t['created_at'])->translatedFormat('d F Y') : '-' }}
                                </td>
                                <td>
                                    <a href="{{ route('client.invoice', $t['id']) }}" class="btn btn-info btn-sm"
                                        target="_blank">Lihat Invoice</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data tagihan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
