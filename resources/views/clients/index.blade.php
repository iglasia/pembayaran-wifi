@extends('layouts.app', [
'title' => 'Halaman Klien'
])

@section('content')
<div class="row">
    <div class="col-md-12">
        @include('components.alert-message')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Klien</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <form action="{{ route('klien.index') }}" method="GET" class="form-inline">
                            <div class="form-group mr-2">
                                <select name="payment_status" class="form-control">
                                    <option value="">Status Pembayaran</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Sudah Bayar</option>
                                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <input type="month" name="month" class="form-control" value="{{ request('month') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </form>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('klien.create') }}" class="btn btn-primary">
                            Tambah Data
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                        <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>Nama Klien</th>
                                <th>Paket Internet</th>
                                <th>Nomor Handphone</th>
                                <th>Alamat IP</th>
                                <th>Status Berlangganan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $client->name }}</td>
                                <td>
                                    <span class="badge badge-pill badge-primary px-2 py-2 w-100">
                                        {{ $client->internet_package->name }} -
                                        {{ indonesian_currency($client->internet_package->price) }}
                                    </span>
                                </td>
                                <td>{{ $client->phone_number }}</td>
                                <td>
                                    <span class="badge badge-pill badge-secondary px-2 py-2 w-100">
                                        {{ $client->ip_address }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($client->subscription_status === 'active')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('klien.toggle-subscription', $client->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm {{ $client->subscription_status === 'active' ? 'btn-danger' : 'btn-success' }}">
                                            {{ $client->subscription_status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('klien.show', $client->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-fw fa-eye"></i>
                                    </a>
                                    <a href="{{ route('klien.edit', $client->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-fw fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection