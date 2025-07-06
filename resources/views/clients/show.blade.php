@extends('layouts.app', [
    'title' => 'Halaman Detail Klien',
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
                                @if ($client->subscription_status === 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                        </div>
                    </div>
                    @if ($client->subscription_status === 'inactive')
                        <div class="col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="subscription_ended_at">Tanggal Berhenti</label>
                                <input type="text" class="form-control"
                                    value="{{ $client->subscription_ended_at->locale('id')->isoFormat('D MMMM Y') }}"
                                    disabled>
                            </div>
                        </div>
                    @endif
                    @if ($client->subscription_reactivated_at)
                        <div class="col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="subscription_reactivated_at">Tanggal Aktif Kembali</label>
                                <input type="text" class="form-control"
                                    value="{{ $client->subscription_reactivated_at->locale('id')->isoFormat('D MMMM Y') }}"
                                    disabled>
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
                                <img src="{{ asset($client->house_image) }}" class="img-thumbnail"
                                    style="max-width: 100%; height: auto; max-height: 300px;" alt="Foto Rumah">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="map">Lokasi Rumah</label>
                            <div id="map" style="height: 300px; width: 100%;" class="img-thumbnail"></div>
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
                        <button type="button" class="btn btn-secondary "
                            onclick="window.location.href='{{ route('klien.index') }}'">
                            Kembali
                        </button>

                        <button type="button" class="btn btn-success me-2"
                            onclick="window.location.href='{{ route('klien.edit', $client->id) }}'">
                            Ubah
                        </button>

                        <form action="{{ route('klien.toggle-subscription', $client->id) }}" method="POST"
                            class="d-inline-block">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="btn {{ $client->subscription_status === 'active' ? 'btn-danger' : 'btn-success' }}">
                                {{ $client->subscription_status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    @endsection

    @push('js')
        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // Check if latitude and longitude are available
                @if ($client->latitude && $client->longitude)
                    console.log('Koordinat lokasi tersedia untuk klien: {{ $client->name }}');

                    var latitude = {{ $client->latitude }};
                    var longitude = {{ $client->longitude }};

                    console.log('Latitude:', latitude);
                    console.log('Longitude:', longitude);
                    // Initialize the map
                    var map = L.map('map').setView([longitude, latitude], 15);

                    // Add OpenStreetMap tiles
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    // Add a marker
                    L.marker([longitude, latitude]).addTo(map)
                        .bindPopup('Lokasi Rumah Klien ')
                        .openPopup();
                @else
                    // Show a message if coordinates are not available
                    document.getElementById('map').innerHTML =
                        '<div class="text-center p-4">Koordinat lokasi tidak tersedia</div>';
                @endif
            });
        </script>
    @endpush
