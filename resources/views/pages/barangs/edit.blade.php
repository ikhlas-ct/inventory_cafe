@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Barang</h4>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('barangs.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $barang->nama }}" required maxlength="255">
                            @error('nama')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="id_kategori" class="form-label">Kategori</label>
                            <select class="form-control" id="id_kategori" name="id_kategori" required>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ $barang->id_kategori == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_kategori')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="id_satuan" class="form-label">Satuan</label>
                            <select class="form-control" id="id_satuan" name="id_satuan" required>
                                @foreach ($satuans as $satuan)
                                    <option value="{{ $satuan->id }}" {{ $barang->id_satuan == $satuan->id ? 'selected' : '' }}>{{ $satuan->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_satuan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" step="0.01" class="form-control" id="harga" name="harga" value="{{ $barang->harga }}" required min="0">
                            @error('harga')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" maxlength="1000">{{ $barang->deskripsi }}</textarea>
                            @error('deskripsi')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto">
                            @if ($barang->foto)
                                <img src="{{ asset('storage/' . $barang->foto) }}" alt="Foto Barang" width="100" class="mt-2">
                            @endif
                            @error('foto')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('barangs.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
