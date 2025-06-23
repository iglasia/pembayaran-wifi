@extends('layouts.app', [
'title' => 'Halaman Detail Klien'
])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card px-3 py-3">
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="form-group">
                        <label for="name">Nama Klien</label>
                        <input type="text" class="form-control" value="{{ $client->name }}" disabled>
                    </div>
                </div>

                <div class="col-md-12 col-lg-6">
                    <div class="form-group">
                        <label for="ip_address">Alamat IP</label>
                        <input type="text" class="form-control" value="{{ $client->ip_address }}" disabled>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="internet_package_name">Nama Paket Internet</label>
                            <input type="text" class="form-control" value="{{ $client->internet_package->name }}"
                                disabled>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-6">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="internet_package_price">Harga Paket Internet</label>
                            <input type="text" class="form-control"
                                value="{{ indonesian_currency($client->internet_package->price) }}" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="form-group">
                        <label for="subscription_status">Status Berlangganan</label>
                        <td class="text-center">
                            @if($client->subscription_status === 'active')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                    </div>
                </div>
                @if($client->subscription_status === 'inactive')
                <div class="col-md-12 col-lg-6">
                    <div class="form-group">
                        <label for="subscription_ended_at">Tanggal Berhenti</label>
                        <input type="text" class="form-control" value="{{ $client->subscription_ended_at->locale('id')->isoFormat('D MMMM Y') }}" disabled>
                    </div>
                </div>
                @endif
                @if($client->subscription_reactivated_at)
                <div class="col-md-12 col-lg-6">
                    <div class="form-group">
                        <label for="subscription_reactivated_at">Tanggal Aktif Kembali</label>
                        <input type="text" class="form-control" value="{{ $client->subscription_reactivated_at->locale('id')->isoFormat('D MMMM Y') }}" disabled>
                    </div>
                </div>
                @endif
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="form-group">
                        <label for="phone_number">Nomor Handphone</label>
                        <input type="text" class="form-control" value="{{ $client->phone_number }}" disabled>
                    </div>
                </div>

                <div class="col-md-12 col-lg-6">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" value="{{ $client->email }}" disabled>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="house_image">Foto Rumah</label>
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset($client->house_image) }}" class="img-thumbnail">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="address">Alamat Lengkap</label>
                        <textarea class="form-control" rows="5" disabled>{{ $client->address }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <a href="{{ route('klien.index') }}" class="btn btn-secondary">Kembali</a>
                    <a href="{{ route('klien.edit', $client->id) }}" class="btn btn-success">Ubah</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <form action="{{ route('klien.toggle-subscription', $client->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm {{ $client->subscription_status === 'active' ? 'btn-danger' : 'btn-success' }}">
                            {{ $client->subscription_status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('js')
    @include('clients.script')
    @endpush