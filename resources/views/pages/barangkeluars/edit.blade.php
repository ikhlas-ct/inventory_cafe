@extends('layouts.app')

@section('title', 'Edit Barang Keluar')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Barang Keluar</h4>
                    @include('partials.alert')
                    <form action="{{ route('barangkeluars.update', $barangkeluar->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="id_barang" class="form-label">Barang</label>
                            <select class="form-control" id="id_barang" name="id_barang" required>
                                <option value="">Pilih Barang</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}" {{ old('id_barang', $barangkeluar->id_barang) == $barang->id ? 'selected' : '' }}>{{ $barang->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_barang')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required value="{{ old('jumlah', $barangkeluar->jumlah) }}">
                            @error('jumlah')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                            <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" required value="{{ old('tanggal_keluar', $barangkeluar->tanggal_keluar->format('Y-m-d')) }}">
                            @error('tanggal_keluar')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" maxlength="255" required>{{ old('catatan', $barangkeluar->catatan) }}</textarea>
                            @error('catatan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
              

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('barangkeluars.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
