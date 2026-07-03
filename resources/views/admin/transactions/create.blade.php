{{-- Form Tambah Transaksi Admin --}}
@extends('layouts.admin')

@section('title', 'Tambah Transaksi')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Transaksi</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Transaksi</h6>
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

            <form action="{{ route('admin.transactions.store') }}" method="POST" id="transaction-form">
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="user_id">Sales</label>
                            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Sales --</option>
                                @foreach ($sales as $sale)
                                    <option value="{{ $sale->id }}" {{ old('user_id') == $sale->id ? 'selected' : '' }}>
                                        {{ $sale->nama }} ({{ $sale->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="customer_id">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->nama_customer }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tanggal_transaksi">Tanggal Transaksi</label>
                            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                                value="{{ old('tanggal_transaksi', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required>
                            @error('tanggal_transaksi')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="card border mb-4">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-primary">Item Transaksi</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="items-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Produk</th>
                                        <th style="width: 15%;">Qty</th>
                                        <th style="width: 20%;">Harga</th>
                                        <th style="width: 20%;">Subtotal</th>
                                        <th style="width: 15%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (old('items'))
                                        @foreach (old('items') as $index => $item)
                                            <tr>
                                                <td>
                                                    <select name="items[{{ $index }}][product_id]" class="form-control product-select" required>
                                                        <option value="">-- Pilih Produk --</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}" {{ ($item['product_id'] ?? '') == $product->id ? 'selected' : '' }}>
                                                                {{ $product->nama_produk }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[{{ $index }}][jumlah]" class="form-control item-qty" min="1" value="{{ $item['jumlah'] ?? 1 }}" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[{{ $index }}][harga]" class="form-control item-price" step="0.01" min="0" value="{{ $item['harga'] ?? 0 }}" required readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control item-subtotal" readonly value="0">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm btn-remove-item">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>
                                                <select name="items[0][product_id]" class="form-control product-select" required>
                                                    <option value="">-- Pilih Produk --</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="items[0][jumlah]" class="form-control item-qty" min="1" value="1" required>
                                            </td>
                                            <td>
                                                <input type="number" name="items[0][harga]" class="form-control item-price" step="0.01" min="0" value="0" required readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control item-subtotal" readonly value="0">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm btn-remove-item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-item">
                            <i class="fas fa-plus"></i> Tambah Item
                        </button>

                        <div class="mt-4 text-right">
                            <h5>Total Harga: <span id="grand-total">Rp0</span></h5>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Transaksi
                    </button>
                </div>
            </form>

            <template id="item-row-template">
                <tr>
                    <td>
                        <select name="product_id" class="form-control product-select" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="jumlah" class="form-control item-qty" min="1" value="1" required>
                    </td>
                    <td>
                        <input type="number" name="harga" class="form-control item-price" step="0.01" min="0" value="0" required readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control item-subtotal" readonly value="0">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btn-remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </template>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const productPrices = @json($products->pluck('harga', 'id'));

    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID').format(value);
    }

    function recalculateRow($row) {
        const qty = Number($row.find('.item-qty').val() || 0);
        const price = Number($row.find('.item-price').val() || 0);
        const subtotal = qty * price;
        $row.find('.item-subtotal').val(formatRupiah(subtotal));
    }

    function recalculateTotal() {
        let total = 0;
        $('#items-table tbody tr').each(function () {
            const qty = Number($(this).find('.item-qty').val() || 0);
            const price = Number($(this).find('.item-price').val() || 0);
            total += qty * price;
        });
        $('#grand-total').text('Rp' + formatRupiah(total));
    }

    function bindRowEvents($row) {
        $row.find('.product-select').on('change', function () {
            const productId = $(this).val();
            const price = productPrices[productId] ?? 0;
            $(this).closest('tr').find('.item-price').val(price);
            recalculateRow($(this).closest('tr'));
            recalculateTotal();
        });

        // Ensure price is set from productPrices on row bind (initial load)
        (function() {
            const prodId = $row.find('.product-select').val();
            if (prodId) {
                $row.find('.item-price').val(productPrices[prodId] ?? 0);
            }
            // only qty is editable by user; price is readonly
            $row.find('.item-qty').on('input', function () {
                recalculateRow($(this).closest('tr'));
                recalculateTotal();
            });
        })();

        $row.find('.btn-remove-item').on('click', function () {
            $(this).closest('tr').remove();
            renumberRows();
            recalculateTotal();
        });
    }

    function renumberRows() {
        $('#items-table tbody tr').each(function (index) {
            $(this).find('select.product-select').attr('name', `items[${index}][product_id]`);
            $(this).find('.item-qty').attr('name', `items[${index}][jumlah]`);
            $(this).find('.item-price').attr('name', `items[${index}][harga]`);
        });
    }

    function addItemRow() {
        const template = $('#item-row-template').html();
        const $row = $(template);
        bindRowEvents($row);
        $('#items-table tbody').append($row);
        renumberRows();
    }

    $(function () {
        $('#items-table tbody tr').each(function () {
            bindRowEvents($(this));
            recalculateRow($(this));
        });

        $('#btn-add-item').on('click', function () {
            addItemRow();
        });

        if ($('#items-table tbody tr').length === 0) {
            addItemRow();
        }

        recalculateTotal();
    });
</script>
@endpush
