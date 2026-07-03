{{-- Dashboard Admin --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Admin</h1>
    </div>

    <div class="row">
        @foreach($summaryCards as $card)
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ $card['label'] }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $card['value'] }}</div>
                                <div class="text-xs text-muted mt-2">{{ $card['hint'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi Terbaru</h6>
                </div>
                <div class="card-body">
                    @if($recentSales->isEmpty())
                        <p class="text-muted">Belum ada transaksi terbaru.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Sales</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSales as $sale)
                                        <tr>
                                            <td>{{ $sale->id }}</td>
                                            <td>{{ optional($sale->user)->nama ?? 'N/A' }}</td>
                                            <td>{{ optional($sale->customer)->nama_customer ?? 'N/A' }}</td>
                                            <td>{{ number_format($sale->total_harga ?? 0, 0, ',', '.') }}</td>
                                            <td>{{ $sale->tanggal_transaksi ? $sale->tanggal_transaksi->format('d M Y') : $sale->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Komisi Terbaru</h6>
                </div>
                <div class="card-body">
                    @if($recentCommissions->isEmpty())
                        <p class="text-muted">Belum ada komisi baru.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Sales</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCommissions as $commission)
                                        <tr>
                                            <td>{{ $commission->id }}</td>
                                            <td>{{ optional($commission->user)->nama ?? 'N/A' }}</td>
                                            <td>{{ number_format($commission->total_pembayaran ?? 0, 0, ',', '.') }}</td>
                                            <td>{{ ucfirst($commission->status ?? 'n/a') }}</td>
                                            <td>{{ $commission->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ranking Total Penjualan Sales</h6>
                </div>
                <div class="card-body">
                    @if($salesRanking->isEmpty())
                        <p class="text-muted">Tidak ada data penjualan.</p>
                    @else
                        <div class="chart-container" style="position: relative; height:350px;">
                            <canvas id="salesRankingChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function() {
            const salesData = @json($salesRanking->map(function($row) { return [ 'name' => optional($row->user)->nama ?? 'N/A', 'total' => (float) $row->total ]; }));
            if (!salesData || !salesData.length) return;

            const labels = salesData.map(r => r.name);
            const totals = salesData.map(r => r.total);

            const ctx = document.getElementById('salesRankingChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Penjualan (Rp)',
                        data: totals,
                        backgroundColor: 'rgba(1, 0, 100, 0.7)',
                        borderColor: 'rgba(1, 0, 100, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                            }
                        }
                    }
                }
            });
        })();
    </script>
@endpush
