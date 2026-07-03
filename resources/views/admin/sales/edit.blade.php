{{-- Form Edit Sales Admin --}}
@extends('layouts.admin')

@section('title', 'Edit Sales')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Sales</h1>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Sales - {{ $sale->nama }}</h6>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.sales.update', $sale->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama">Nama Sales</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                        name="nama" value="{{ old('nama', $sale->nama) }}" required>
                    @error('nama')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email', $sale->email) }}" required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama_rekening">Nama Rekening</label>
                    <input type="text" class="form-control @error('nama_rekening') is-invalid @enderror"
                        id="nama_rekening" name="nama_rekening" value="{{ old('nama_rekening', $sale->nama_rekening) }}" required>
                    @error('nama_rekening')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nomor_rekening">Nomor Rekening</label>
                    <input type="text" class="form-control @error('nomor_rekening') is-invalid @enderror"
                        id="nomor_rekening" name="nomor_rekening" value="{{ old('nomor_rekening', $sale->nomor_rekening) }}" required>
                    @error('nomor_rekening')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bank">Bank</label>
                    <select class="form-control @error('bank') is-invalid @enderror" id="bank" name="bank" required>
                        <option value="">-- Pilih Bank --</option>
                        @if (!empty($banks))
                            @foreach ($banks as $bank)
                                <option value="{{ $bank['code'] ?? $bank['name'] ?? '' }}" 
                                    {{ old('bank', $sale->bank) === ($bank['code'] ?? $bank['name'] ?? '') ? 'selected' : '' }}>
                                    {{ $bank['name'] ?? '' }}
                                </option>
                            @endforeach
                        @else
                            <option value="">Bank tidak tersedia</option>
                        @endif
                    </select>
                    @error('bank')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="text-muted small">
                        <i class="fas fa-info-circle"></i> Password tidak dapat diubah dari halaman ini
                    </label>
                </div>

                <div class="form-group">
                    <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
