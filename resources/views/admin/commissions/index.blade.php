@extends('layouts.admin')

@section('title', 'Manajemen Komisi')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Komisi</h1>
        <a href="{{ route('admin.commissions.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Komisi
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
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Komisi</h6>
        </div>
        <div class="card-body">
            @if ($commissions->isEmpty())
                <p class="text-muted">Belum ada data komisi.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Sales</th>
                                <th>Periode</th>
                                <th>Total Penjualan</th>
                                <th>Total Pembayaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($commissions as $commission)
                                <tr>
                                    <td>{{ $commission->id }}</td>
                                    <td>{{ optional($commission->user)->nama ?? '-' }}</td>
                                    <td>{{ $commission->periode }}</td>
                                    <td>Rp{{ number_format($commission->total_penjualan, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($commission->total_pembayaran, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $commission->status === 'paid' ? 'success' : ($commission->status === 'disbursed' ? 'info' : 'warning') }}">
                                            {{ ucfirst($commission->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.commissions.show', $commission->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        <a href="{{ route('admin.commissions.edit', $commission->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.commissions.destroy', $commission->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus komisi ini?')">
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
                    {{ $commissions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
