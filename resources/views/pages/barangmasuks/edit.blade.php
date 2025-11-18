@extends('layouts.app')

@section('title', 'Edit Barang Masuk')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Barang Masuk</h4>
                    @include('partials.alert')
                    <form action="{{ route('barangmasuks.update', $barangmasuk->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="id_barang" class="form-label">Barang</label>
                            <select class="form-control" id="id_barang" name="id_barang" required>
                                <option value="">Pilih Barang</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}" {{ old('id_barang', $barangmasuk->id_barang) == $barang->id ? 'selected' : '' }}>{{ $barang->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_barang')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required value="{{ old('jumlah', $barangmasuk->jumlah) }}">
                            @error('jumlah')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required value="{{ old('tanggal_masuk', $barangmasuk->tanggal_masuk->format('Y-m-d')) }}">
                            @error('tanggal_masuk')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" maxlength="255" required>{{ old('catatan', $barangmasuk->catatan) }}</textarea>
                            @error('catatan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="harga_beli" class="form-label">Harga Beli</label>
                            <input type="number" step="0.01" class="form-control" id="harga_beli" name="harga_beli" min="0" required value="{{ old('harga_beli', $barangmasuk->harga_beli) }}">
                            @error('harga_beli')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="id_supplier" class="form-label">Supplier</label>
                            <select class="form-control" id="id_supplier" name="id_supplier" required>
                                <option value="">Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('id_supplier', $barangmasuk->id_supplier) == $supplier->id ? 'selected' : '' }}>{{ $supplier->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_supplier')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('barangmasuks.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
