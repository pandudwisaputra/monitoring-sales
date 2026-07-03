{{-- Detail Pencairan Komisi (Disbursement) Admin --}}
@extends('layouts.admin')

@section('title', 'Detail Disbursement')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Disbursement</h1>
        <a href="{{ route('admin.disbursements.index') }}" class="btn btn-sm btn-secondary shadow-sm">Kembali</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Sales</label>
                        <p class="form-control-plaintext">{{ optional($disbursement->commission->user)->nama ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Komisi</label>
                        <p class="form-control-plaintext">Rp{{ number_format($disbursement->commission->total_pembayaran ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal Bayar</label>
                        <p class="form-control-plaintext">{{ optional($disbursement->tanggal_bayar)->format('d M Y') ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jumlah</label>
                        <p class="form-control-plaintext">Rp{{ number_format($disbursement->jumlah, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Flip ID</label>
                        <p class="form-control-plaintext">{{ $disbursement->flip_disbursement_id ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Bank / Rekening</label>
                        <p class="form-control-plaintext">{{ strtoupper($disbursement->bank_code ?? '-') }} — {{ $disbursement->account_number ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Pemilik Rekening</label>
                        <p class="form-control-plaintext">{{ $disbursement->account_holder ?? $disbursement->recipient_name ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fee</label>
                        <p class="form-control-plaintext">Rp{{ number_format($disbursement->fee ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status Disbursement</label>
                        <p class="form-control-plaintext">{{ ucfirst($disbursement->disbursement_status ?? '-') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
