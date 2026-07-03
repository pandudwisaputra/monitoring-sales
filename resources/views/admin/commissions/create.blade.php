{{-- Form Tambah Komisi Admin --}}
@extends('layouts.admin')

@section('title', 'Tambah Komisi')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Komisi</h1>
        <a href="{{ route('admin.commissions.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.commissions.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="user_id">Sales</label>
                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                        <option value="">Pilih Sales</option>
                        @foreach ($sales as $sale)
                            <option value="{{ $sale->id }}" {{ old('user_id') == $sale->id ? 'selected' : '' }}>{{ $sale->nama }} ({{ $sale->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="periode">Periode</label>
                    <input type="month" name="periode" id="periode" class="form-control @error('periode') is-invalid @enderror" value="{{ old('periode') }}" required>
                    @error('periode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="total_penjualan">Total Penjualan</label>
                    <input type="number" name="total_penjualan" id="total_penjualan" class="form-control @error('total_penjualan') is-invalid @enderror" value="{{ old('total_penjualan') }}" min="0" step="0.01" required>
                    @error('total_penjualan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection
