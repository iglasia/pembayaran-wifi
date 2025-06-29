@extends('layouts.app', [
    'title' => 'Dashboard Klien',
]   )

@section('title', 'Dashboard Klien')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Selamat Datang, {{ $client->name }}</h5>
    </div>
    <div class="card-body">
        <p>Ini adalah halaman dashboard khusus klien.</p>
    </div>
</div>
@endsection