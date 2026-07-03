{{-- Detail Komisi Admin --}}
@extends('layouts.admin')

@section('title', 'Detail Komisi')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Komisi</h1>
        <a href="{{ route('admin.commissions.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $commission->id }}</dd>

                <dt class="col-sm-3">Sales</dt>
                <dd class="col-sm-9">{{ optional($commission->user)->nama ?? 'N/A' }} ({{ optional($commission->user)->email ?? 'N/A' }})</dd>

                <dt class="col-sm-3">Periode</dt>
                <dd class="col-sm-9">{{ $commission->periode }}</dd>

                <dt class="col-sm-3">Total Penjualan</dt>
                <dd class="col-sm-9">Rp{{ number_format($commission->total_penjualan, 0, ',', '.') }}</dd>

                <dt class="col-sm-3">Persentase Komisi</dt>
                <dd class="col-sm-9">{{ $commission->persentase_komisi }}%</dd>

                <dt class="col-sm-3">Total Pembayaran</dt>
                <dd class="col-sm-9">Rp{{ number_format($commission->total_pembayaran, 0, ',', '.') }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ ucfirst($commission->status) }}</dd>

                <dt class="col-sm-3">Dibuat pada</dt>
                <dd class="col-sm-9">{{ $commission->created_at->format('d M Y H:i') }}</dd>

                <dt class="col-sm-3">Terakhir diperbarui</dt>
                <dd class="col-sm-9">{{ $commission->updated_at->format('d M Y H:i') }}</dd>
            </dl>

            @if ($commission->payments->isNotEmpty())
                <hr>
                <h5>Pembayaran Terkait</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Tanggal Bayar</th>
                                <th>Jumlah</th>
                                <th>Status Disbursement</th>
                                <th>Metode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($commission->payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ optional($payment->tanggal_bayar)->format('d M Y') ?? '-' }}</td>
                                    <td>Rp{{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                                    <td>{{ $payment->disbursement_status ?? '-' }}</td>
                                    <td>{{ $payment->remark ?? 'Flip' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
