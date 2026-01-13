@extends('layouts.app')

@section('title', 'Barang Keluar')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Table Barang Keluars</h4>
                    @include('partials.alert')

                    <!-- Search Form -->
                    <form action="{{ route('barangkeluars.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by nama barang, nama karyawan, catatan"
                                value="{{ $search ?? '' }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    <!-- Date Filter Form -->
                    <h5>Filter by Tanggal Keluar</h5>
                    <form action="{{ route('barangkeluars.index') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Start Date</label>
                                <input type="date" name="start" class="form-control" value="{{ request('start') }}">
                            </div>
                            <div class="col-md-4">
                                <label>End Date</label>
                                <input type="date" name="end" class="form-control" value="{{ request('end') }}">
                            </div>
                            <div class="col-md-4">
                                <label>&nbsp;</label><br>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <!-- Preset Buttons for Index Filter -->
                    <a href="{{ route('barangkeluars.index', ['start' => now()->format('Y-m-d'), 'end' => now()->format('Y-m-d')]) }}" class="btn btn-info btn-sm mb-3">Hari Ini</a>
                    <a href="{{ route('barangkeluars.index', ['start' => now()->startOfMonth()->format('Y-m-d'), 'end' => now()->endOfMonth()->format('Y-m-d')]) }}" class="btn btn-info btn-sm mb-3">Bulan Ini</a>
                    <a href="{{ route('barangkeluars.index', ['start' => now()->startOfYear()->format('Y-m-d'), 'end' => now()->endOfYear()->format('Y-m-d')]) }}" class="btn btn-info btn-sm mb-3">Tahun Ini</a>

                    <!-- Button to Generate Report for Current Filter -->
                    @if (request('start') && request('end'))
                        <a href="{{ route('laporan.barang_keluar', ['start' => request('start'), 'end' => request('end')]) }}" class="btn btn-success mb-3" target="_blank">Cetak Laporan untuk Periode Ini</a>
                    @else
                        <a href="{{ route('laporan.barang_keluar') }}" class="btn btn-success mb-3" target="_blank">Cetak Laporan (Default Bulan Ini)</a>
                    @endif

                    <a href="{{ route('barangkeluars.create') }}" class="btn btn-primary mb-3">Tambah Barang Keluar</a>
                    <div class="table-responsive">
                        <table class="table-striped table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Karyawan</th>
                                    <th>Nomor Transaksi</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Jumlah</th>
                                    <th>Catatan</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangkeluars as $key => $barangkeluar)
                                    <tr>
                                        <td>{{ $barangkeluars->firstItem() + $key }}</td>
                                        <td>{{ $barangkeluar->user->karyawan->nama ?? ($barangkeluar->user->manajer->nama ?? '-') }}
                                        </td>
                                        <td>{{ $barangkeluar->nomor_transaksi ?? '-' }}</td>
                                        <td>{{ $barangkeluar->tanggal_keluar->format('Y-m-d') }}</td>
                                        <td>{{ $barangkeluar->jenis_count }}</td>
                                        <td>{{ $barangkeluar->catatan ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('barangkeluars.show', $barangkeluar->id) }}"
                                                class="btn btn-primary btn-sm">Detail</a>

                                            <a href="{{ route('barangkeluars.edit', $barangkeluar->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('barangkeluars.destroy', $barangkeluar->id) }}"
                                                method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $barangkeluars->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
