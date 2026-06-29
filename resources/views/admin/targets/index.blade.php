@extends('layouts.admin')

@section('title', 'Manajemen Target')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Target</h1>
        <a href="{{ route('admin.targets.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Target
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
            <h6 class="m-0 font-weight-bold text-primary">Daftar Target</h6>
        </div>
        <div class="card-body">
            @if ($targets->isEmpty())
                <p class="text-muted">Belum ada data target.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Sales</th>
                                <th>Periode</th>
                                <th>Target Nominal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($targets as $target)
                                <tr>
                                    <td>{{ $target->id }}</td>
                                    <td>{{ optional($target->user)->nama ?? '-' }}</td>
                                    <td>{{ $target->periode }}</td>
                                    <td>Rp{{ number_format($target->target_nominal, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('admin.targets.show', $target->id) }}" class="btn btn-sm btn-info">Lihat</a>
                                        <a href="{{ route('admin.targets.edit', $target->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.targets.destroy', $target->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus target ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $targets->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
