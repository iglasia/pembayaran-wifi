@extends('layouts.client')

@section('title', 'Dashboard Klien')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Selamat Datang, {{ auth('client')->user()->name }}</h5>
    </div>
    <div class="card-body">
        <p>Ini adalah halaman dashboard khusus klien.</p>
    </div>
</div>
@endsection