{{-- Detail Transaksi Admin --}}
@extends('layouts.admin')

@section('title', 'Detail Transaksi #' . $transaction->id)

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Transaksi #{{ $transaction->id }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Transaksi</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>ID</th>
                            <td>{{ $transaction->id }}</td>
                        </tr>
                        <tr>
                            <th>Sales</th>
                            <td>{{ $transaction->user->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Customer</th>
                            <td>{{ $transaction->customer->nama_customer ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ optional($transaction->tanggal_transaksi)->format('d M Y') }}</td>
                        </tr>
                        {{-- Total Harga removed (duplicated below) --}}
                    </table>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.transactions.edit', $transaction->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Item</h6>
                </div>
                <div class="card-body">
                    @if ($transaction->details->isEmpty())
                        <p class="text-muted">Tidak ada item pada transaksi ini.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaction->details as $detail)
                                        <tr>
                                            <td>{{ $detail->product->nama_produk ?? '-' }}</td>
                                            <td>{{ $detail->jumlah }}</td>
                                            <td>Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                            <td>Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right column (Ringkasan) removed because it duplicated information shown above --}}
    </div>
@endsection