<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" 
       href="{{ auth()->user()->isClient() ? route('client.dashboard') : route('dashboard') }}">
        <div class="sidebar-brand-icon {{ !auth()->user()->isClient() ? 'rotate-n-15' : '' }}">
            <i class="fas fa-wifi"></i>
        </div>
        <div class="sidebar-brand-text mx-3">
            {{ auth()->user()->isClient() ? 'Dashboard Klien' : 'Catur-Net' }}
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Dashboard Link -->
    <li class="nav-item {{ request()->routeIs('dashboard') || request()->routeIs('client.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ auth()->user()->isClient() ? route('client.dashboard') : route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    @if(!auth()->user()->isClient())
        <!-- Admin/Owner Menu -->
        <div class="sidebar-heading">Menu Admin</div>

        <li class="nav-item {{ request()->routeIs('klien.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('klien.index') }}">
                <i class="fas fa-fw fa-user"></i>
                <span>Klien</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('paket-internet.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('paket-internet.index') }}">
                <i class="fas fa-fw fa-boxes"></i>
                <span>Paket Internet</span>
            </a>
        </li>

        @if(auth()->user()->isOwner())
            <li class="nav-item {{ request()->routeIs('administrator-aplikasi.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('administrator-aplikasi.index') }}">
                    <i class="fas fa-fw fa-user-shield"></i>
                    <span>Admin</span>
                </a>
            </li>
        @endif

        <li class="nav-item {{ request()->routeIs('tagihan.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('tagihan.index') }}">
                <i class="fas fa-fw fa-money-check"></i>
                <span>Tagihan</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Reports -->
        <div class="sidebar-heading">Laporan</div>

        <li class="nav-item {{ request()->routeIs('laporan.rekap.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('laporan.rekap.index') }}">
                <i class="fas fa-fw fa-money-check"></i>
                <span>Rekap</span>
            </a>
        </li>

        @if(auth()->user()->isOwner())
            <li class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('settings.index') }}">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
        @endif
    @else
        <!-- Client Menu -->
        <div class="sidebar-heading">Menu Klien</div>

        <li class="nav-item {{ request()->routeIs('client.billing') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('client.billing') }}">
                <i class="fas fa-fw fa-file-invoice"></i>
                <span>Tagihan</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('client.payment-history') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('client.payment-history') }}">
                <i class="fas fa-fw fa-history"></i>
                <span>Riwayat Pembayaran</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('client.settings') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('client.settings') }}">
                <i class="fas fa-fw fa-cog"></i>
                <span>Pengaturan</span>
            </a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>