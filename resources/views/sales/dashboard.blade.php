{{-- Dashboard Sales --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.PNG') }}">
    <title>Dashboard Sales – Monitoring Sales</title>

    <link href="{{ asset('vendor/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('vendor/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        /* ── Brand Colors ── */
        :root { --brand: #010064; --brand-light: #0d0a8f; }

        html, body { 
            height: 100vh;
            height: 100dvh; /* Mobile browser address bar fix */
            margin: 0; 
            overflow: hidden; 
            display: flex; 
            flex-direction: column; 
            background-color: #f8f9fc;
        }

        /* ── Top Header ── */
        .app-header {
            flex-shrink: 0;
            background: linear-gradient(90deg, var(--brand) 0%, var(--brand-light) 100%);
            box-shadow: 0 2px 10px rgba(1,0,100,0.15);
            padding: 0.8rem 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 999;
        }
        .app-header .brand { color:#fff; font-weight:700; font-size:1.1rem; }
        .app-header .user-info { color:rgba(255,255,255,0.85); font-size:.85rem; }

        /* ── Main Scrollable Area ── */
        .main-wrapper {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            scroll-behavior: smooth;
            padding-bottom: 70px; /* Space for fixed navbar */
        }

        /* ── Tab Nav ── */
        .tab-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            background: #fff;
            border-top: 1px solid #e3e6f0;
            z-index: 998;
            box-shadow: 0 -4px 10px rgba(0,0,0,0.04);
        }
        .tab-link {
            flex: 1;
            background: transparent;
            border: none;
            border-top: 3px solid transparent;
            padding: 0.6rem 0 0.4rem;
            color: #858796;
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .tab-link:focus { outline: none; }
        .tab-link.active { 
            border-top-color: var(--brand);
            color: var(--brand);
        }
        .tab-link i { display:block; font-size:1.2rem; margin-bottom:3px; }

        /* ── Tab Panels ── */
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* ── Cards ── */
        .stat-card {
            border-left: 4px solid var(--brand);
            border-radius: 0.75rem;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 1.25rem;
            transition: transform 0.2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card .label { font-size:0.75rem; font-weight:800; text-transform:uppercase; color:#858796; letter-spacing: 0.5px; }
        .stat-card .value { font-size:1.6rem; font-weight:800; color: var(--brand); margin-top: 0.3rem; margin-bottom: 0.1rem; word-break: break-word; line-height: 1.1; }
        .stat-card .hint  { font-size:0.8rem; color:#b7b9cc; margin-top: auto; padding-top: 0.5rem; }

        /* ── Progress ── */
        .progress { height:10px; border-radius:5px; }
        .progress-bar { background: linear-gradient(90deg, var(--brand), var(--brand-light)); }

        /* ── Transaction table ── */
        .table th { font-size:.78rem; text-transform:uppercase; color:#858796; }
        .badge-pending    { background:#fff3cd; color:#856404; }
        .badge-paid       { background:#d1e7dd; color:#0f5132; }
        .badge-cancelled  { background:#f8d7da; color:#842029; }

        /* ── Transaksi Item ── */
        .transaksi-item {
            background:#fff;
            border:1px solid #e3e6f0;
            border-radius:0.75rem;
            padding:1.25rem;
            margin-bottom:1rem;
            box-shadow:0 2px 8px rgba(0,0,0,.04);
        }

        /* ── Footer ── */
        .sticky-footer { background:#fff; border-top:1px solid #e3e6f0; padding:1rem 0; margin-top:2rem; }
        .sticky-footer span { font-size:.85rem; color:#858796; }

        /* ── Alerts ── */
        .flash-alert { position:fixed; top:80px; right:16px; z-index:9999; min-width:280px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; }
    </style>
</head>

<body>

    {{-- ══ Header ══ --}}
    <div class="app-header">
        <span class="brand"><i class="fas fa-chart-line mr-2"></i>Monitoring Sales</span>
        <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-outline-light mr-3 border-0" onclick="window.location.reload()" title="Refresh Data" style="background: rgba(255,255,255,0.15);">
                <i class="fas fa-sync-alt"></i>
            </button>
            <span class="user-info mr-3">
                <i class="fas fa-user-circle mr-1"></i>{{ $user->nama ?? $user->email }}
            </span>
            <form method="POST" action="{{ route('sales.logout') }}" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="main-wrapper">
        <div class="container-fluid py-4">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show flash-alert" role="alert">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if (session('success_customer'))
            <div class="alert alert-info alert-dismissible fade show flash-alert" role="alert">
                <i class="fas fa-user-plus mr-2"></i>{{ session('success_customer') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-warning alert-dismissible fade show flash-alert" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- ══════════════════════════════════════════ --}}
        {{-- TAB: DASHBOARD                            --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="tab-panel active" id="panelDashboard">

            {{-- Stat Cards --}}
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <div class="stat-card">
                        <div class="label">Total Transaksi</div>
                        <div class="value">{{ $transactions->count() }}</div>
                        <div class="hint">Semua periode</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="stat-card">
                        <div class="label">Penjualan Bulan Ini</div>
                        <div class="value">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                        <div class="hint">{{ now()->translatedFormat('F Y') }}</div>
                    </div>
                </div>
            </div>

            {{-- Target Progress --}}
            @if ($target)
                @php
                    $pct = $target->target_nominal > 0
                        ? min(100, round(($totalPenjualan / $target->target_nominal) * 100, 1))
                        : 0;
                @endphp
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 0.75rem;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="font-weight-bold" style="color:var(--brand); font-size: 1.1rem;">
                                <i class="fas fa-bullseye mr-2"></i>Target {{ now()->translatedFormat('F Y') }}
                            </span>
                            <span class="font-weight-bold text-gray-800" style="font-size: 1.1rem;">{{ $pct }}%</span>
                        </div>
                        <div class="progress mb-3" style="height: 12px; border-radius: 6px; background-color: #eaecf4;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: {{ $pct }}%; background-color: var(--brand);"></div>
                        </div>
                        <div class="d-flex justify-content-between text-muted" style="font-size: 0.85rem; font-weight: 600;">
                            <span>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
                            <span>Rp {{ number_format($target->target_nominal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Target bulan ini belum diatur oleh admin.
                </div>
            @endif

            {{-- Ranking Penjualan --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 0.75rem;">
                <div class="card-header py-3 bg-white" style="border-bottom: 1px solid #e3e6f0;">
                    <h6 class="m-0 font-weight-bold" style="color:var(--brand); letter-spacing: 0.5px;">
                        <i class="fas fa-trophy mr-2" style="color: #f6c23e;"></i>Ranking Penjualan Bulan Ini
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush" style="border-radius: 0.75rem;">
                        @foreach($topSales as $index => $s)
                            <li class="list-group-item d-flex justify-content-between align-items-center {{ $s->id === $user->id ? 'bg-light' : '' }}">
                                <div>
                                    <span class="badge badge-pill badge-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'info') }} mr-2">
                                        #{{ $index + 1 }}
                                    </span>
                                    <span class="font-weight-bold text-gray-800">{{ $s->nama ?? $s->email }}</span>
                                    @if($s->id === $user->id)
                                        <span class="badge badge-success ml-1" style="font-size: 0.65rem;">Anda</span>
                                    @endif
                                </div>
                                <span class="text-success font-weight-bold small">
                                    Rp {{ number_format($s->sales_transactions_sum_total_harga ?? 0, 0, ',', '.') }}
                                </span>
                            </li>
                        @endforeach
                        
                        @if($myRank > 3)
                            <li class="list-group-item bg-light text-center border-top-0 py-1">
                                <i class="fas fa-ellipsis-v text-muted" style="font-size: 0.7rem;"></i>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                <div>
                                    <span class="badge badge-pill badge-dark mr-2">
                                        #{{ $myRank }}
                                    </span>
                                    <span class="font-weight-bold text-gray-800">{{ $user->nama ?? $user->email }}</span>
                                    <span class="badge badge-success ml-1" style="font-size: 0.65rem;">Anda</span>
                                </div>
                                <span class="text-success font-weight-bold small">
                                    Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                                </span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Daftar Transaksi Terbaru --}}
            <div class="card shadow-sm border-0 mb-3" style="border-radius: 0.75rem;">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('sales.dashboard') }}" class="form-inline">
                        <input type="hidden" name="tab" value="dashboard">
                        <select name="customer_id" class="form-control form-control-sm mr-2 mb-2">
                            <option value="">-- Semua Pelanggan --</option>
                            @foreach($customers as $cust)
                                <option value="{{ $cust->id }}" {{ request('customer_id') == $cust->id ? 'selected' : '' }}>{{ $cust->nama_customer }}</option>
                            @endforeach
                        </select>
                        <input type="date" name="tanggal_transaksi" class="form-control form-control-sm mr-2 mb-2" value="{{ request('tanggal_transaksi') }}">
                        <button type="submit" class="btn btn-sm btn-primary mr-2 mb-2">Filter</button>
                        @if(request()->anyFilled(['customer_id', 'tanggal_transaksi']))
                            <a href="{{ route('sales.dashboard') }}?tab=dashboard" class="btn btn-sm btn-secondary mb-2">Reset</a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4" style="border-radius: 0.75rem; overflow: hidden;">
                <div class="card-header py-3" style="background:var(--brand);">
                    <h6 class="m-0 font-weight-bold text-white" style="letter-spacing: 0.5px;">
                        <i class="fas fa-history mr-2"></i>Transaksi Terbaru
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if ($transactions->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3 d-block text-gray-300"></i>Belum ada transaksi.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                                <thead style="background-color: #f8f9fc;">
                                    <tr>
                                        <th class="border-0 font-weight-bold text-gray-600">#</th>
                                        <th class="border-0 font-weight-bold text-gray-600">Pelanggan</th>
                                        <th class="border-0 font-weight-bold text-gray-600">Total</th>
                                        <th class="border-0 font-weight-bold text-gray-600">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $trx)
                                        <tr>
                                            <td class="align-middle text-gray-600">{{ $trx->id }}</td>
                                            <td class="align-middle font-weight-bold text-gray-800">{{ optional($trx->customer)->nama_customer ?? '-' }}</td>
                                            <td class="align-middle text-success font-weight-bold">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                            <td class="align-middle text-muted">{{ optional($trx->tanggal_transaksi)->format('d M Y') ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 border-top">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- TAB: KOMISI                               --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="tab-panel" id="panelKomisi">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-weight-bold mb-0" style="color:var(--brand);">
                    <i class="fas fa-money-bill-wave mr-2"></i>Komisi Saya
                </h5>
            </div>

            <div class="card shadow-sm border-0 mb-3" style="border-radius: 0.75rem;">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('sales.dashboard') }}" class="form-inline">
                        <input type="hidden" name="tab" value="komisi">
                        <select name="status_komisi" class="form-control form-control-sm mr-2 mb-2">
                            <option value="">-- Semua Status --</option>
                            <option value="pending" {{ request('status_komisi') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status_komisi') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ request('status_komisi') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary mr-2 mb-2">Filter</button>
                        @if(request()->anyFilled(['status_komisi']))
                            <a href="{{ route('sales.dashboard') }}?tab=komisi" class="btn btn-sm btn-secondary mb-2">Reset</a>
                        @endif
                    </form>
                </div>
            </div>

            @if ($commissions->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                    <p class="font-weight-bold mb-1">Belum Ada Komisi</p>
                    <small>Data komisi Anda akan muncul di sini.</small>
                </div>
            @else
                @foreach ($commissions as $kom)
                    @php
                        $badgeClass = match($kom->status) {
                            'paid'      => 'badge-paid',
                            'cancelled' => 'badge-cancelled',
                            default     => 'badge-pending',
                        };
                    @endphp
                    <div class="card shadow border-0 mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="font-weight-bold text-gray-800">Periode: {{ $kom->periode }}</div>
                                    <div class="small text-muted">Total Penjualan: Rp {{ number_format($kom->total_penjualan, 0, ',', '.') }}</div>
                                    <div class="small text-muted">Komisi ({{ $kom->persentase_komisi }}%):
                                        <strong>Rp {{ number_format($kom->total_pembayaran, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                                <span class="badge {{ $badgeClass }} px-2 py-1" style="font-size:.75rem; border-radius:.4rem;">
                                    {{ ucfirst($kom->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="mt-3">
                    {{ $commissions->links() }}
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- TAB: TRANSAKSI (FORM)                     --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="tab-panel" id="panelTransaksi">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-weight-bold mb-0" style="color:var(--brand);">
                    <i class="fas fa-shopping-cart mr-2"></i>Input Transaksi Baru
                </h5>
            </div>

            @if ($errors->any() && old('_form') === 'transaksi')
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (! $target)
                <div class="alert alert-warning">
                    <i class="fas fa-lock mr-2"></i>
                    Target bulan ini belum diatur. Anda tidak dapat menginput transaksi.
                </div>
            @else
                <div class="card shadow border-0 mb-4">
                    <div class="card-body">
                        <form id="formTransaksi" method="POST" action="{{ route('sales.transactions.store') }}">
                            @csrf
                            <input type="hidden" name="_form" value="transaksi">

                            {{-- Pelanggan --}}
                            <div class="form-group mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label class="font-weight-bold text-gray-800 mb-0">
                                        Pelanggan <span class="text-danger">*</span>
                                    </label>
                                    <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#modalAddCustomer">
                                        <i class="fas fa-user-plus mr-1"></i>Tambah Pelanggan
                                    </button>
                                </div>
                                <select class="form-control" name="customer_id" required>
                                    <option value="" disabled selected>Pilih Pelanggan...</option>
                                    @foreach ($customers as $cust)
                                        <option value="{{ $cust->id }}" {{ old('customer_id') == $cust->id ? 'selected' : '' }}>
                                            {{ $cust->nama_customer }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Daftar Produk --}}
                            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                <h6 class="font-weight-bold m-0 text-gray-800">Daftar Produk</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddItem">
                                    <i class="fas fa-plus mr-1"></i>Tambah Produk
                                </button>
                            </div>

                            <div id="transaksiItemsWrap">
                                {{-- Item awal --}}
                                <div class="transaksi-item" data-index="0">
                                    <div class="row">
                                        <div class="col-12 mb-2 pr-5 position-relative">
                                            <label class="small font-weight-bold text-gray-700 mb-1">Produk</label>
                                            <select class="form-control form-control-sm item-product" name="items[0][product_id]" required>
                                                <option value="" disabled selected>Pilih Produk...</option>
                                                @foreach ($products as $prod)
                                                    <option value="{{ $prod->id }}" data-harga="{{ $prod->harga }}">
                                                        {{ $prod->nama_produk }} (Rp {{ number_format($prod->harga, 0, ',', '.') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-gray-700 mb-1">Harga</label>
                                            <input type="number" class="form-control form-control-sm item-harga"
                                                   name="items[0][harga]" min="0" readonly required>
                                        </div>
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-gray-700 mb-1">Qty</label>
                                            <input type="number" class="form-control form-control-sm item-qty"
                                                   name="items[0][jumlah]" min="1" value="1" required>
                                        </div>
                                        <div class="col-12 mt-2 pt-2 border-top text-right">
                                            <span class="small text-muted">Subtotal: </span>
                                            <span class="font-weight-bold text-gray-800 item-subtotal">Rp 0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Grand Total --}}
                            <div class="d-flex justify-content-between align-items-center mt-4 p-3 rounded shadow-sm"
                                 style="background-color: #e8e8ff; border: 1px solid rgba(1,0,100,0.1);">
                                <span class="font-weight-bold text-gray-800">Total Keseluruhan</span>
                                <span class="h5 font-weight-bold m-0" style="color:var(--brand);" id="transaksiGrandTotal">Rp 0</span>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-block btn-lg" id="btnSubmitTransaksi">
                                    <i class="fas fa-save mr-2"></i>Simpan Transaksi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

    </div>{{-- end container --}}

    {{-- ══════════════════════════════════════════ --}}
    {{-- MODAL: TAMBAH PELANGGAN                   --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="modal fade" id="modalAddCustomer" tabindex="-1" role="dialog"
         aria-labelledby="modalAddCustomerLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white"
                     style="background: linear-gradient(90deg, var(--brand) 0%, var(--brand-light) 100%);">
                    <h5 class="modal-title" id="modalAddCustomerLabel">
                        <i class="fas fa-user-plus mr-2"></i>Tambah Pelanggan Baru
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('sales.customers.store') }}">
                    @csrf
                    <div class="modal-body">
                        @if ($errors->has('nama_customer') || $errors->has('no_hp') || $errors->has('alamat'))
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach (['nama_customer','no_hp','alamat'] as $field)
                                        @error($field)<li>{{ $message }}</li>@enderror
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <label class="font-weight-bold text-gray-800">Nama Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_customer') is-invalid @enderror"
                                   name="nama_customer" value="{{ old('nama_customer') }}"
                                   placeholder="Contoh: Budi Santoso" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-gray-800">No. HP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                   name="no_hp" value="{{ old('no_hp') }}"
                                   placeholder="Contoh: 081234567890" required>
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-gray-800">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror"
                                      name="alamat" rows="3"
                                      placeholder="Contoh: Jl. Merdeka No. 10, Madiun" required>{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i>Simpan Pelanggan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </div>{{-- end container-fluid --}}

    {{-- ══ Tab Nav ══ --}}
    <div class="tab-nav">
        <button class="tab-link active" id="tabLinkDashboard" onclick="switchTab('dashboard', this)">
            <i class="fas fa-tachometer-alt"></i>Dashboard
        </button>
        <button class="tab-link" id="tabLinkKomisi" onclick="switchTab('komisi', this)">
            <i class="fas fa-money-bill-wave"></i>Komisi
        </button>
        <button class="tab-link" id="tabLinkTransaksi" onclick="switchTab('transaksi', this)">
            <i class="fas fa-shopping-cart"></i>Transaksi
        </button>
    </div>

    <script src="{{ asset('vendor/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('vendor/js/sb-admin-2.min.js') }}"></script>

    <script>
    // ── Tab Switching ────────────────────────────────────────
    function switchTab(name, el) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
        document.getElementById('panel' + name.charAt(0).toUpperCase() + name.slice(1)).classList.add('active');
        el.classList.add('active');

        // Simpan state tab aktif di URL parameter (tanpa reload halaman)
        const url = new URL(window.location);
        url.searchParams.set('tab', name);
        window.history.pushState({}, '', url);
    }

    // Auto-switch tab berdasarkan parameter URL saat load
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        let activeTab = urlParams.get('tab');

        // Otomatis pindah tab berdasarkan parameter pagination
        if (urlParams.has('commissions_page')) {
            activeTab = 'komisi';
        } else if (urlParams.has('transactions_page')) {
            activeTab = 'dashboard';
        }

        if (activeTab) {
            const btnMap = {
                'dashboard': 'tabLinkDashboard',
                'komisi': 'tabLinkKomisi',
                'transaksi': 'tabLinkTransaksi'
            };
            const btnId = btnMap[activeTab];
            if (btnId) {
                const btn = document.getElementById(btnId);
                if (btn) switchTab(activeTab, btn);
            }
        }
    });

    // ── Format Rupiah ────────────────────────────────────────
    function formatRp(val) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(val || 0);
    }

    // ── Hitung Total Transaksi ───────────────────────────────
    function calculateTotal() {
        let grand = 0;
        document.querySelectorAll('.transaksi-item').forEach(function(item) {
            const h = parseFloat(item.querySelector('.item-harga').value) || 0;
            const q = parseInt(item.querySelector('.item-qty').value)     || 0;
            const sub = h * q;
            item.querySelector('.item-subtotal').textContent = formatRp(sub);
            grand += sub;
        });
        document.getElementById('transaksiGrandTotal').textContent = formatRp(grand);
    }

    // ── Auto-fill harga saat pilih produk ───────────────────
    $(document).on('change', '.item-product', function() {
        const harga = $(this).find('option:selected').data('harga') || 0;
        $(this).closest('.transaksi-item').find('.item-harga').val(harga);
        calculateTotal();
    });

    $(document).on('input', '.item-qty', function() {
        calculateTotal();
    });

    // ── Hapus item ───────────────────────────────────────────
    $(document).on('click', '.btn-remove-item', function() {
        $(this).closest('.transaksi-item').remove();
        reindexItems();
        calculateTotal();
    });

    // ── Tambah baris produk ──────────────────────────────────
    let itemIndex = 1;

    $('#btnAddItem').on('click', function() {
        const products = @json($products->map(fn($p) => ['id'=>$p->id,'nama'=>$p->nama_produk,'harga'=>$p->harga]));

        let opts = '<option value="" disabled selected>Pilih Produk...</option>';
        products.forEach(p => {
            opts += `<option value="${p.id}" data-harga="${p.harga}">${p.nama} (${formatRp(p.harga)})</option>`;
        });

        const html = `
        <div class="transaksi-item" data-index="${itemIndex}">
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item float-right mb-2" title="Hapus">
                <i class="fas fa-times"></i>
            </button>
            <div class="row">
                <div class="col-12 mb-2">
                    <label class="small font-weight-bold text-gray-700 mb-1">Produk</label>
                    <select class="form-control form-control-sm item-product" name="items[${itemIndex}][product_id]" required>
                        ${opts}
                    </select>
                </div>
                <div class="col-6">
                    <label class="small font-weight-bold text-gray-700 mb-1">Harga</label>
                    <input type="number" class="form-control form-control-sm item-harga"
                           name="items[${itemIndex}][harga]" min="0" readonly required>
                </div>
                <div class="col-6">
                    <label class="small font-weight-bold text-gray-700 mb-1">Qty</label>
                    <input type="number" class="form-control form-control-sm item-qty"
                           name="items[${itemIndex}][jumlah]" min="1" value="1" required>
                </div>
                <div class="col-12 mt-2 pt-2 border-top text-right">
                    <span class="small text-muted">Subtotal: </span>
                    <span class="font-weight-bold text-gray-800 item-subtotal">Rp 0</span>
                </div>
            </div>
        </div>`;

        $('#transaksiItemsWrap').append(html);
        itemIndex++;
        calculateTotal();
    });

    function reindexItems() {
        document.querySelectorAll('.transaksi-item').forEach(function(item, i) {
            item.querySelectorAll('[name]').forEach(function(el) {
                el.name = el.name.replace(/items\[\d+\]/, 'items[' + i + ']');
            });
        });
        itemIndex = document.querySelectorAll('.transaksi-item').length;
    }

    // ── Auto-dismiss flash alerts ─────────────────────────────
    setTimeout(function() {
        document.querySelectorAll('.flash-alert').forEach(function(el) {
            $(el).alert('close');
        });
    }, 4000);

    // ── Buka tab Transaksi jika ada session success_customer ──
    @if (session('success_customer'))
        switchTab('transaksi', document.getElementById('tabLinkTransaksi'));
    @endif

    // ── Hitung total awal saat halaman dimuat ─────────────────
    calculateTotal();
    </script>

</body>
</html>
