@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush


<!-- Statistik -->
<div class="row">
    <div class="col-md-12 grid-margin transparent">
        <div class="row">
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-tale">
                    <div class="card-body text-center">
                        <div class="icon-box mb-3">
                            <i class="mdi mdi-package-variant-closed" style="font-size: 2.5rem;"></i>
                        </div>
                        <p class="mb-2">Total Barang</p>
                        <p class="fs-30 mb-2">{{ $totalBarang }}</p>
                        <p>{{ $percentBarangText }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-dark-blue">
                    <div class="card-body text-center">
                        <div class="icon-box mb-3">
                            <i class="mdi mdi-arrow-down-box" style="font-size: 2.5rem;"></i>
                        </div>
                        <p class="mb-2">Barang Masuk</p>
                        <p class="fs-30 mb-2">{{ $barangMasukThisMonth }}</p>
                        <p>Bulan ini</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-light-blue">
                    <div class="card-body text-center">
                        <div class="icon-box mb-3">
                            <i class="mdi mdi-arrow-up-box" style="font-size: 2.5rem;"></i>
                        </div>
                        <p class="mb-2">Barang Keluar</p>
                        <p class="fs-30 mb-2">{{ $barangKeluarThisMonth }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-light-danger">
                    <div class="card-body text-center">
                        <div class="icon-box mb-3">
                            <i class="mdi mdi-alert" style="font-size: 2.5rem;"></i>
                        </div>
                        <p class="mb-2">Stok Kritis</p>
                        <p class="fs-30 mb-2">{{ $stokKritisCount }}</p>
                        <p>Perlu perhatian</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title">Perkembangan Stok Bulanan</p>
                <div class="d-flex flex-wrap mb-5">
                    <div class="mr-5 mt-3">
                        <p class="text-muted">Barang Masuk</p>
                        <h3 class="text-primary fs-30 font-weight-medium">{{ $barangMasukThisMonth }}</h3>
                    </div>
                    <div class="mr-5 mt-3">
                        <p class="text-muted">Barang Keluar</p>
                        <h3 class="text-success fs-30 font-weight-medium">{{ $barangKeluarThisMonth }}</h3>
                    </div>
                </div>
                <div style="height: 250px;">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title">Kategori Barang</p>
                <div style="height: 250px;">
                    <canvas id="categoryChart"></canvas>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Tabel dan Aktivitas -->
<div class="row">
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title mb-0">Barang dengan Stok Kritis</p>
                <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Min Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criticalBarangs as $barang)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $barang->foto ? asset('storage/' . $barang->foto) : 'https://via.placeholder.com/40' }}"
                                             class="mr-3" alt="{{ $barang->nama }}" style="width: 40px; height: 40px; border-radius: 5px;">
                                        <div>
                                            <div>{{ $barang->nama }}</div>
                                            <small class="text-muted">{{ $barang->kode_barang }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $barang->kategori->nama ?? 'N/A' }}</td>
                                <td>{{ $barang->stok_sekarang }}</td>
                                <td>10</td> <!-- Hardcode karena tidak ada min_stok -->
                                <td>
                                    <div class="badge {{ $barang->stok_sekarang < 5 ? 'badge-danger' : 'badge-warning' }}">
                                        {{ $barang->stok_sekarang < 5 ? 'KRITIS' : 'RENDAH' }}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @if($criticalBarangs->isEmpty())
                            <tr><td colspan="5">Tidak ada stok kritis</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Aktivitas Terbaru</h4>
                <div class="preview-list">
                    @foreach($activities as $activity)
                    <div class="preview-item {{ $loop->last ? '' : 'border-bottom' }}">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-{{ $activity->type == 'masuk' ? 'success' : 'danger' }}">
                                <i class="mdi mdi-{{ $activity->type == 'masuk' ? 'arrow-down-box' : 'arrow-up-box' }}"></i>
                            </div>
                        </div>
                        <div class="preview-item-content d-sm-flex grow">
                            <div class="grow">
                                <h6 class="preview-subject">Barang {{ $activity->type == 'masuk' ? 'Masuk' : 'Keluar' }}: {{ $activity->nomor_transaksi }}</h6>
                                <p class="text-muted mb-0">{{ date('d M Y ', strtotime($activity->tanggal)) }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if(empty($activities))
                    <div class="preview-item">Tidak ada aktivitas terbaru</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Statistik Cepat</h4>
                <div class="row">
                    <div class="col-md-3">
                        <div class="d-flex align-items-center border-right justify-content-between">
                            <div>
                                <p class="text-muted">Total Supplier</p>
                                <h4 class="mb-0">{{ $totalSupplier }}</h4>
                            </div>
                            <div class="icon-box">
                                <i class="mdi mdi-truck text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center border-right justify-content-between">
                            <div>
                                <p class="text-muted">Total Kategori</p>
                                <h4 class="mb-0">{{ $totalKategori }}</h4>
                            </div>
                            <div class="icon-box">
                                <i class="mdi mdi-tag-multiple text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center border-right justify-content-between">
                            <div>
                                <p class="text-muted">Karyawan Aktif</p>
                                <h4 class="mb-0">{{ $karyawanAktif }}</h4>
                            </div>
                            <div class="icon-box">
                                <i class="mdi mdi-account-group text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted">Transaksi Bulan Ini</p>
                                <h4 class="mb-0">{{ $transaksiBulanIni }}</h4>
                            </div>
                            <div class="icon-box">
                                <i class="mdi mdi-cash text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize stock chart
        const stockCtx = document.getElementById('stockChart').getContext('2d');
        const stockChart = new Chart(stockCtx, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: @json($masukData),
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Barang Keluar',
                        data: @json($keluarData),
                        borderColor: '#2196F3',
                        backgroundColor: 'rgba(33, 150, 243, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Initialize category chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($catLabels),
                datasets: [{
                    data: @json($catData),
                    backgroundColor: [
                        '#ff9800',
                        '#4CAF50',
                        '#2196F3',
                        '#F44336',
                        '#9C27B0',
                        '#757575'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>

<style>
    .card-tale {
        background: linear-gradient(120deg, #ff9800, #ffb74d);
        color: white;
    }

    .card-dark-blue {
        background: linear-gradient(120deg, #1976d2, #64b5f6);
        color: white;
    }

    .card-light-blue {
        background: linear-gradient(120deg, #0288d1, #4fc3f7);
        color: white;
    }

    .card-light-danger {
        background: linear-gradient(120deg, #d32f2f, #f44336);
        color: white;
    }

    .preview-list .preview-item {
        padding: 15px 0;
    }

    .preview-list .preview-item:last-child {
        padding-bottom: 0;
    }

    .preview-thumbnail {
        width: 50px;
        height: 50px;
        margin-right: 15px;
    }

    .preview-icon {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .icon-box {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 50px;
        width: 50px;
        margin: 0 auto;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
    }

    .bg-purple {
        background-color: #9C27B0;
    }
</style>
@endsection
