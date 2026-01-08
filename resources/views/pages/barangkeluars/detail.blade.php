@extends('layouts.app')

@section('title', 'Detail Barang Keluar')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Detail Barang Keluar</h4>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Nomor Transaksi</label>
                        <p class="form-control-static">{{ $barangkeluar->nomor_transaksi }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Karyawan</label>
                        <p class="form-control-static">{{ $barangkeluar->user->karyawan->nama ?? $barangkeluar->user->username ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Keluar</label>
                        <p class="form-control-static">{{ $barangkeluar->tanggal_keluar->format('d-m-Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <p class="form-control-static">{{ $barangkeluar->catatan ?? '-' }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangkeluar->barangkeluardetail as $detail)
                                    <tr>
                                        <td>{{ $detail->barang->nama }} ({{ $detail->barang->kode_barang }})</td>
                                        <td>{{ $detail->jumlah }}</td>
                                    </tr>
                                @endforeach
                                @if ($barangkeluar->barangkeluardetail->isEmpty())
                                    <tr>
                                        <td colspan="2" class="text-center">Tidak ada detail barang.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('barangkeluars.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
