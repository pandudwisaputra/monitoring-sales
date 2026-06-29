@extends('layouts.admin')

@section('title', 'Detail Sales - ' . $sale->nama)

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Sales</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $sale->nama }}</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $sale->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama:</strong></td>
                            <td>{{ $sale->nama }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $sale->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Rekening:</strong></td>
                            <td>{{ $sale->nama_rekening }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nomor Rekening:</strong></td>
                            <td>{{ $sale->nomor_rekening }}</td>
                        </tr>
                        <tr>
                            <td><strong>Bank:</strong></td>
                            <td>{{ $sale->bank }}</td>
                        </tr>
                        <tr>
                            <td><strong>Role:</strong></td>
                            <td><span class="badge badge-primary">{{ $sale->role }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Terdaftar:</strong></td>
                            <td>{{ $sale->created_at ? $sale->created_at->format('d M Y H:i') : '-' }}</td>
                        </tr>
                    </table>

                    <div class="mt-4">
                        <a href="{{ route('admin.sales.edit', $sale->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ringkasan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Transaksi Penjualan</small>
                        <h4>{{ $sale->salesTransactions()->count() ?? 0 }}</h4>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Total Komisi</small>
                        <h4>Rp{{ number_format($sale->commissions()->sum('total_pembayaran') ?? 0, 0, ',', '.') }}</h4>
                    </div>
                    <div>
                        <small class="text-muted">Komisi Terbayar</small>
                        <h4>Rp{{ number_format($sale->commissions()->where('status', 'paid')->sum('total_pembayaran') ?? 0, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
