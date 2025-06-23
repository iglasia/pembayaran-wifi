<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - Catur-Net</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { background: #f5f5f5; }
        .sidebar { background: #b0b0b0; /* abu-abu */ }
        .sidebar .nav-link.active { background: #888; }
        .navbar, .card-header.bg-primary { background: #888 !important; color: #fff; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar p-3">
            <h4 class="text-white">Catur-Net</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link {{ request()->is('client/dashboard') ? 'active' : '' }}" href="{{ route('client.dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('client/tagihan') ? 'active' : '' }}" href="{{ route('client.tagihan') }}">Tagihan</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('client/riwayat') ? 'active' : '' }}" href="{{ route('client.riwayat') }}">Riwayat Pembayaran</a></li>
                <li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-target="#logoutnotif">Logout</a></li>
            </ul>
        </nav>
        <!-- Main Content -->
        <div class="flex-grow-1">
            <nav class="navbar navbar-expand navbar-light">
                <div class="container-fluid">
                    <span class="navbar-brand">Dashboard Klien</span>
                    <div class="ml-auto">{{ auth()->guard('client')->user()->name }}</div>
                </div>
            </nav>
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
