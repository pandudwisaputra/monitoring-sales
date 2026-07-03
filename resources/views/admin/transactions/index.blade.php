@extends('layouts.admin')

@section('title', 'Manajemen Transaksi')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Transaksi</h1>
        <a href="{{ route('admin.transactions.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Transaksi
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="form-inline">
                <select name="user_id" class="form-control mr-2">
                    <option value="">-- Semua Sales --</option>
                    @foreach($sales as $sale)
                        <option value="{{ $sale->id }}" {{ request('user_id') == $sale->id ? 'selected' : '' }}>{{ $sale->nama }}</option>
                    @endforeach
                </select>
                <select name="customer_id" class="form-control mr-2">
                    <option value="">-- Semua Pelanggan --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->nama_customer }}</option>
                    @endforeach
                </select>
                <input type="date" name="tanggal" class="form-control mr-2" value="{{ request('tanggal') }}">
                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                @if(request()->anyFilled(['user_id', 'customer_id', 'tanggal']))
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
        </div>
        <div class="card-body">
            @if ($transactions->isEmpty())
                <p class="text-muted">Belum ada transaksi.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Sales</th>
                                <th>Customer</th>
                                <th>Total Harga</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->user->nama ?? '-' }}</td>
                                    <td>{{ $transaction->customer->nama_customer ?? '-' }}</td>
                                    <td>Rp{{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                                    <td>{{ optional($transaction->tanggal_transaksi)->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        <a href="{{ route('admin.transactions.edit', $transaction->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $transactions->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .pagination {
        font-size: 0.85rem;
    }

    .pagination .page-link {
        padding: 0.35rem 0.65rem;
        min-width: auto;
    }

    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        padding: 0.35rem 0.5rem;
    }
</style>
@endpush
