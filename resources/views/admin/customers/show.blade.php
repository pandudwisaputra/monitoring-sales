{{-- Detail Customer Admin --}}
@extends('layouts.admin')

@section('title', 'Detail Customer')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Customer</h1>
        <div>
            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-sm btn-warning shadow-sm">Edit</a>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-secondary shadow-sm">Kembali</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pelanggan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="font-weight-bold text-gray-800">Nama Customer</label>
                        <div>{{ $customer->nama_customer }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold text-gray-800">No. HP</label>
                        <div>{{ $customer->no_hp }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold text-gray-800">Alamat</label>
                        <div>{{ $customer->alamat }}</div>
                    </div>
                    <div class="mb-0">
                        <label class="font-weight-bold text-gray-800">Terdaftar Sejak</label>
                        <div>{{ optional($customer->created_at)->format('d M Y H:i') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi</h6>
                </div>
                <div class="card-body">
                    @if ($customer->salesTransactions->isEmpty())
                        <p class="text-muted mb-0">Belum ada riwayat transaksi untuk customer ini.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID Transaksi</th>
                                        <th>Sales</th>
                                        <th>Total Harga</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer->salesTransactions as $transaction)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.transactions.show', $transaction->id) }}">
                                                    {{ $transaction->id }}
                                                </a>
                                            </td>
                                            <td>{{ $transaction->user->nama ?? '-' }}</td>
                                            <td>Rp{{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                                            <td>{{ optional($transaction->tanggal_transaksi)->format('d M Y') ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
