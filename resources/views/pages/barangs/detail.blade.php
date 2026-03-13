@extends('layouts.app')

@section('title', 'Detail Barang - ' . $barang->nama)

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Detail Barang: <strong>{{ $barang->nama }}</strong></h4>

    <!-- INFO BARANG -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <p><strong>Kode Barang:</strong> {{ $barang->kode_barang }}</p>
                    <p><strong>Supplier:</strong> {{ $barang->supplier->nama ?? 'N/A' }}</p>
                    <p><strong>Kategori:</strong> {{ $barang->kategori->nama ?? 'N/A' }}</p>
                    <p><strong>Satuan:</strong> {{ $barang->satuan->nama ?? 'N/A' }}</p>
                    <p><strong>Deskripsi:</strong> {{ $barang->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                </div>
                @if ($barang->foto)
                <div class="col-md-4 text-end">
                    <img src="{{ asset('storage/' . $barang->foto) }}"
                         alt="{{ $barang->nama }}"
                         class="img-fluid rounded" style="max-width: 220px;">
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- TOTAL STOK SAAT INI (PALING MENONjol) -->
    <div class="card mb-4 border-success">
        <div class="card-header bg-success text-white text-center">
            <h5 class="mb-0">STOK SAAT INI</h5>
        </div>
        <div class="card-body text-center">
            <h1 class="display-4 text-success fw-bold mb-0">
                {{ number_format($totalStok) }}
            </h1>
            <p class="fs-4 text-muted">{{ $barang->satuan->nama ?? 'Unit' }}</p>
        </div>
    </div>

    <!-- STOK PER TANGGAL KADALUARSA -->
    <div class="card mb-4">
        <div class="card-header">Stok per Tanggal Kadaluarsa & Tanggal Masuk</div>
        <div class="card-body">
            @if ($expiries->count() > 0)
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                                                        <th>Tanggal Masuk</th>
                            <th>Tanggal Kadaluarsa</th>
                            <th class="text-end">Jumlah Awal</th>
                            <th class="text-end">Jumlah Tersisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expiries as $e)
                            <tr>
                                     <td>
                                    @if ($e['tanggal_masuk'] === 'Tidak Ada Tanggal')
                                        <span class="text-muted">Tidak Ada</span>
                                    @else
                                        {{ \Carbon\Carbon::parse($e['tanggal_masuk'])->translatedFormat('d F Y') }}
                                    @endif
                                </td>
                                <td>
                                    @if ($e['tanggal'] === 'Tidak Ada Tanggal')
                                        <span class="text-muted">Tidak Ada</span>
                                    @else
                                        {{ \Carbon\Carbon::parse($e['tanggal'])->translatedFormat('d F Y') }}
                                    @endif
                                </td>

                                <td class="text-end">{{ number_format($e['jumlah']) }}</td>
                                <td class="text-end fw-bold {{ $e['jumlah_tersisa'] < 10 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($e['jumlah_tersisa']) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted mb-0">Belum ada stok tersisa.</p>
            @endif
        </div>
    </div>

    <!-- HISTORY TRANSAKSI LENGKAP -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <strong>📋 History Transaksi Lengkap (Masuk & Keluar)</strong>
        </div>
        <div class="card-body p-0">
            @if ($histories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Nomor Transaksi</th>
                                <th>Nomor Masuk (Batch)</th>
                                <th class="text-end">Masuk</th>
                                <th class="text-end text-danger">Keluar</th>
                                <th>Kadaluarsa</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $h)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($h->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td>
                                        @if ($h->jenis === 'Masuk')
                                            <span class="badge bg-success">✅ Masuk</span>
                                        @else
                                            <span class="badge bg-danger">⬅️ Keluar</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $h->nomor_transaksi }}</strong></td>
                                    <td>{{ $h->nomor_ref }}</td>
                                    <td class="text-end fw-bold text-success">
                                        {{ $h->jumlah_masuk ? number_format($h->jumlah_masuk) : '-' }}
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        {{ $h->jumlah_keluar ? number_format($h->jumlah_keluar) : '-' }}
                                    </td>
                                    <td>
                                        @if ($h->kadaluarsa)
                                            {{ $h->kadaluarsa->translatedFormat('d F Y') }}
                                        @else
                                            <span class="text-muted">Tidak Ada</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $h->catatan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-4 text-center text-muted">Belum ada transaksi untuk barang ini.</div>
            @endif
        </div>
    </div>

    <a href="{{ route('barangs.index') }}" class="btn btn-secondary">← Kembali ke Daftar Barang</a>
</div>
@endsection
