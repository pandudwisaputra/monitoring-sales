@extends('layouts.admin')

@section('title', 'Detail Produk')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Produk</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-secondary shadow-sm">Kembali</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <p class="form-control-plaintext">{{ $product->nama_produk }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Harga</label>
                        <p class="form-control-plaintext">Rp{{ number_format($product->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
