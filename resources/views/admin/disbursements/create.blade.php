{{-- Form Tambah Pencairan Komisi (Disbursement) Admin --}}
@extends('layouts.admin')

@section('title', 'Tambah Disbursement')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Disbursement</h1>
        <a href="{{ route('admin.disbursements.index') }}" class="btn btn-sm btn-secondary shadow-sm">Kembali</a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pilih Komisi Sales untuk Disbursement</h6>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4">
                Klik ID komisi untuk memproses pembayaran via Flip. Data rekening, nominal, dan inquiry akan diambil otomatis dari komisi yang dipilih.
            </p>

            @if ($commissions->isEmpty())
                <p class="text-muted mb-0">Tidak ada komisi dengan status pending yang siap di-disburse.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Komisi</th>
                                <th>Sales</th>
                                <th>Periode</th>
                                <th>Total Pembayaran</th>
                                <th>Rekening</th>
                                <th>Bank</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($commissions as $commission)
                                <tr>
                                    <td>
                                        <strong>#{{ $commission->id }}</strong>
                                    </td>
                                    <td>{{ optional($commission->user)->nama ?? '-' }}</td>
                                    <td>{{ $commission->periode }}</td>
                                    <td>Rp{{ number_format($commission->total_pembayaran, 0, ',', '.') }}</td>
                                    <td>
                                        @if (optional($commission->user)->nomor_rekening)
                                            {{ $commission->user->nomor_rekening }}
                                        @else
                                            <span class="text-danger">Belum diisi</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (optional($commission->user)->bank)
                                            {{ $commission->user->bank }}
                                        @else
                                            <span class="text-danger">Belum diisi</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (optional($commission->user)->nomor_rekening && optional($commission->user)->bank)
                                            <form action="{{ route('admin.disbursements.store') }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="commission_id" value="{{ $commission->id }}">
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-primary"
                                                    onclick="return confirm('Proses disbursement komisi #{{ $commission->id }} untuk {{ optional($commission->user)->nama ?? 'sales' }}?')"
                                                >
                                                    <i class="fas fa-paper-plane"></i> Disburse
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">Data bank belum lengkap</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
