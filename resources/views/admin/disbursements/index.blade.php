{{-- Daftar Pencairan Komisi (Disbursement) Admin --}}
@extends('layouts.admin')

@section('title', 'Disbursement')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Disbursement</h1>
        <a href="{{ route('admin.disbursements.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Disbursement
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
            <form method="GET" action="{{ route('admin.disbursements.index') }}" class="form-inline">
                <select name="user_id" class="form-control mr-2">
                    <option value="">-- Semua Sales --</option>
                    @foreach($sales as $sale)
                        <option value="{{ $sale->id }}" {{ request('user_id') == $sale->id ? 'selected' : '' }}>{{ $sale->nama }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-control mr-2">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                @if(request()->anyFilled(['user_id', 'status']))
                    <a href="{{ route('admin.disbursements.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Disbursement</h6>
        </div>
        <div class="card-body">
            @if ($payments->isEmpty())
                <p class="text-muted">Belum ada data disbursement.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Sales</th>
                                <th>Komisi</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ optional($payment->commission->user)->nama ?? '-' }}</td>
                                    <td>Rp{{ number_format($payment->commission->total_pembayaran ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                                    <td>{{ ucfirst($payment->disbursement_status ?? '-') }}</td>
                                    <td>{{ optional($payment->tanggal_bayar)->format('d M Y') ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.disbursements.show', $payment->id) }}" class="btn btn-sm btn-info">Lihat</a>
                                        <a href="{{ route('admin.disbursements.edit', $payment->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.disbursements.destroy', $payment->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus disbursement ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.Echo) {
            window.Echo.channel('payments')
                .listen('.payment.success', (e) => {
                    console.log('Payment success event received', e);
                    window.location.reload();
                });
        }
    });
</script>
@endpush


