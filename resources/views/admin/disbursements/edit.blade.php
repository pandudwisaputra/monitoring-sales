{{-- Form Edit Pencairan Komisi (Disbursement) Admin --}}
@extends('layouts.admin')

@section('title', 'Edit Disbursement')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Disbursement</h1>
        <a href="{{ route('admin.disbursements.index') }}" class="btn btn-sm btn-secondary shadow-sm">Kembali</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.disbursements.update', $disbursement->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="tanggal_bayar">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control @error('tanggal_bayar') is-invalid @enderror" value="{{ old('tanggal_bayar', optional($disbursement->tanggal_bayar)->format('Y-m-d')) }}" required>
                    @error('tanggal_bayar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', $disbursement->jumlah) }}" min="0" step="0.01" required>
                    @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="account_holder">Nama Pemilik Rekening</label>
                    <input type="text" name="account_holder" id="account_holder" class="form-control @error('account_holder') is-invalid @enderror" value="{{ old('account_holder', $disbursement->account_holder) }}">
                    @error('account_holder')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="fee">Biaya</label>
                    <input type="number" name="fee" id="fee" class="form-control @error('fee') is-invalid @enderror" value="{{ old('fee', $disbursement->fee) }}" min="0" step="0.01">
                    @error('fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="disbursement_status">Status Disbursement</label>
                    <select name="disbursement_status" id="disbursement_status" class="form-control @error('disbursement_status') is-invalid @enderror" required>
                        <option value="pending" {{ old('disbursement_status', $disbursement->disbursement_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ old('disbursement_status', $disbursement->disbursement_status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ old('disbursement_status', $disbursement->disbursement_status) === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                    @error('disbursement_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
@endsection
