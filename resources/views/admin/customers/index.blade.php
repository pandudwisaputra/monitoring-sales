{{-- Daftar Customer Admin --}}
@extends('layouts.admin')

@section('title', 'Manajemen Customer')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Customer</h1>
        <a href="{{ route('admin.customers.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Customer
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

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.customers.index') }}" class="form-inline">
                <input type="text" name="search" class="form-control mr-2" placeholder="Cari nama, no hp, alamat..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                @if(request()->anyFilled(['search']))
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Customer</h6>
        </div>
        <div class="card-body">
            @if ($customers->isEmpty())
                <p class="text-muted">Belum ada customer.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th style="width: 25%">Nama Customer</th>
                                <th style="width: 20%">No. HP</th>
                                <th style="width: 30%">Alamat</th>
                                <th style="width: 20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->nama_customer }}</td>
                                    <td>{{ $customer->no_hp }}</td>
                                    <td>{{ $customer->alamat }}</td>
                                    <td>
                                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus customer ini?')" title="Hapus">
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
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
