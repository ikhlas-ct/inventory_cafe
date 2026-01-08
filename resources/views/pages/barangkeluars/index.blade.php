@extends('layouts.app')

@section('title', 'Barang Keluar')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Barang Keluar</h4>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <a href="{{ route('barangkeluars.create') }}" class="btn btn-primary mb-3">Tambah Barang Keluar</a>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Transaksi</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Karyawan</th>
                                    <th>Catatan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangkeluars as $barangkeluar)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $barangkeluar->nomor_transaksi }}</td>
                                        <td>{{ $barangkeluar->tanggal_keluar->format('d-m-Y') }}</td>
                                        <td>{{ $barangkeluar->user->karyawan->nama ?? $barangkeluar->user->username ?? '-' }}</td>
                                        <td>{{ $barangkeluar->catatan ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('barangkeluars.show', $barangkeluar->id) }}" class="btn btn-info">Detail</a>
                                            <a href="{{ route('barangkeluars.edit', $barangkeluar->id) }}" class="btn btn-warning">Edit</a>
                                            <form action="{{ route('barangkeluars.destroy', $barangkeluar->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($barangkeluars->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {{ $barangkeluars->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
