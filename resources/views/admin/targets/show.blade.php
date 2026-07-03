{{-- Detail Target Sales Admin --}}
@extends('layouts.admin')

@section('title', 'Detail Target')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Target</h1>
        <a href="{{ route('admin.targets.index') }}" class="btn btn-sm btn-secondary shadow-sm">Kembali</a>
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
            <dl class="row">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $target->id }}</dd>

                <dt class="col-sm-3">Sales</dt>
                <dd class="col-sm-9">{{ optional($target->user)->nama ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Periode</dt>
                <dd class="col-sm-9">{{ $target->periode }}</dd>

                <dt class="col-sm-3">Target Nominal</dt>
                <dd class="col-sm-9">Rp{{ number_format($target->target_nominal, 0, ',', '.') }}</dd>
            </dl>

            @php
                $commissionExists = \App\Models\Commission::where('user_id', $target->user_id)
                    ->where('periode', $target->periode)
                    ->exists();
            @endphp

            @if (! $commissionExists)
                <form action="{{ route('admin.targets.generate', $target->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Generate Komisi</button>
                </form>
            @else
                <span class="badge badge-success">Komisi sudah digenerate</span>
            @endif
        </div>
    </div>
@endsection
