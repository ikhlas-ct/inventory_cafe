@extends('layouts.app')

@section('title', 'Satuan')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Table Satuans</h4>
                    @include('partials.alert') <!-- Include the alert partial here -->

                    <!-- Search Form -->
                    <form action="{{ route('satuans.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by nama or kode satuan" value="{{ $search ?? '' }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
                        Add Satuan
                    </button>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kode Satuan</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($satuans as $key => $satuan)
                                    <tr>
                                        <td>{{ $satuans->firstItem() + $key }}</td> <!-- Adjust numbering for pagination -->
                                        <td>{{ $satuan->nama }}</td>
                                        <td>{{ $satuan->kode_satuan ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('satuans.edit', $satuan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('satuans.destroy', $satuan->id) }}" method="POST" style="display: inline;">
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
                        {{ $satuans->links('pagination::bootstrap-5') }}
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
                    <h5 class="modal-title" id="createModalLabel">Create Satuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('satuans.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required maxlength="100">
                            @error('nama')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="kode_satuan" class="form-label">Kode Satuan</label>
                            <input type="text" class="form-control" id="kode_satuan" name="kode_satuan" maxlength="255">
                            @error('kode_satuan')
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
