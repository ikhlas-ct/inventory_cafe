@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Table Kategoris</h4> <!-- Ini yang diganti -->
                    @include('partials.alert')

                    <!-- Search Form -->
                    <form action="{{ route('kategoris.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by nama or deskripsi" value="{{ $search ?? '' }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
                        Tambah Kategori <!-- Sudah diubah ke Indo -->
                    </button>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Deskripsi</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategoris as $key => $kategori)
                                    <tr>
                                        <td>{{ $kategoris->firstItem() + $key }}</td>
                                        <td>{{ $kategori->nama }}</td>
                                        <td>{{ $kategori->deskripsi ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('kategoris.edit', $kategori->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('kategoris.destroy', $kategori->id) }}" method="POST" style="display: inline;">
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

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $kategoris->links('pagination::bootstrap-5') }}
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
                <h5 class="modal-title" id="createModalLabel">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kategoris.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required maxlength="255" value="{{ old('nama') }}">
                        @error('nama')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" maxlength="1000">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
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
