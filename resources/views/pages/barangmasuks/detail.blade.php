@extends('layouts.app')

@section('title', 'Detail Barang Masuk')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Detail Barang Masuk</h4>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Nomor Transaksi</label>
                        <p class="form-control-static">{{ $barangmasuk->nomor_transaksi }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Masuk</label>
                        <p class="form-control-static">{{ $barangmasuk->tanggal_masuk->format('d-m-Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <p class="form-control-static">{{ $barangmasuk->catatan ?? '-' }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Jumlah Tersisa</th>
                                    <th>Tanggal Kadaluarsa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangmasuk->barangmasukdetail as $detail)
                                    <tr>
                                        <td>{{ $detail->barang->nama }} ({{ $detail->barang->kode_barang }})</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>{{ $detail->jumlah_tersisa }}</td>
                                        <td>{{ $detail->tanggal_kadaluarsa ? $detail->tanggal_kadaluarsa->format('d-m-Y') : '-' }}</td>
                                    </tr>
                                @endforeach
                                @if ($barangmasuk->barangmasukdetail->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada detail barang.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('barangmasuks.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
