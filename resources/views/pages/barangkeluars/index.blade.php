@extends('layouts.app')

@section('title', 'Barang Keluar')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Table Barang Masuks</h4>
                    @include('partials.alert')

                    <!-- Search Form -->
                    <form action="{{ route('barangkeluars.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by nama barang, nama karyawan, catatan" value="{{ $search ?? '' }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
                        Tambah Barang Keluar
                    </button>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Catatan</th>
                                    <th>Karyawan</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangkeluars as $key => $barangkeluar)
                                    <tr>
                                        <td>{{ $barangkeluars->firstItem() + $key }}</td>
                                        <td>{{ $barangkeluar->barang->nama ?? '-' }}</td>
                                        <td>{{ $barangkeluar->jumlah }}</td>
                                        <td>{{ $barangkeluar->tanggal_keluar->format('Y-m-d') }}</td>
                                        <td>{{ $barangkeluar->catatan ?? '-' }}</td>
                                        <td>{{ $barangkeluar->karyawan->nama ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('barangkeluars.edit', $barangkeluar->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('barangkeluars.destroy', $barangkeluar->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
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

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Barang Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('barangkeluars.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="id_barang" class="form-label">Barang</label>
                            <select class="form-control" id="id_barang" name="id_barang" required>
                                <option value="">Pilih Barang</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}" {{ old('id_barang') == $barang->id ? 'selected' : '' }}>{{ $barang->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_barang')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required value="{{ old('jumlah') }}">
                            @error('jumlah')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                            <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" required value="{{ old('tanggal_keluar', now()->format('Y-m-d')) }}">
                            @error('tanggal_keluar')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" maxlength="255" required>{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
