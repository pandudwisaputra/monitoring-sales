<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon rounded-circle bg-white p-2" style="width:60px; height:60px; display:flex; align-items:center; justify-content:center;">
            <img src="{{ asset('vendor/img/logo.png') }}" alt="Toko Tenang Baru" style="max-height:70px; max-width:70px;">
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Menu</div>

    <li class="nav-item {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.sales.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Manajemen Sales</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.transactions.index') }}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Manajemen Transaksi</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.products.index') }}">
            <i class="fas fa-fw fa-box"></i>
            <span>Manajemen Produk</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.disbursements.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.disbursements.index') }}">
            <i class="fas fa-fw fa-credit-card"></i>
            <span>Disbursement</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.targets.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.targets.index') }}">
            <i class="fas fa-fw fa-bullseye"></i>
            <span>Manajemen Target</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-flex">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>