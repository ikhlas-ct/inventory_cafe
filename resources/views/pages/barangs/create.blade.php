@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Barang</h4>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('barangs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="id_supplier" class="form-label">Supplier</label>
                            <select class="form-control" id="id_supplier" name="id_supplier" required>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_supplier')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="barang-table">
                                <thead>
                                    <tr>
                                        <th>Kode Barang</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th>Harga</th>
                                        <th>Deskripsi</th>
                                        <th>Foto</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="barang-row">
                                        <td>
                                            <input type="text" class="form-control" name="barangs[0][kode_barang]" required maxlength="255">
                                            @error('barangs.0.kode_barang')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="barangs[0][nama]" required maxlength="255">
                                            @error('barangs.0.nama')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <select class="form-control" name="barangs[0][id_kategori]" required>
                                                @foreach ($kategoris as $kategori)
                                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('barangs.0.id_kategori')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <select class="form-control" name="barangs[0][id_satuan]" required>
                                                @foreach ($satuans as $satuan)
                                                    <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('barangs.0.id_satuan')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control" name="barangs[0][harga]" required min="0">
                                            @error('barangs.0.harga')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <textarea class="form-control" name="barangs[0][deskripsi]" maxlength="1000"></textarea>
                                            @error('barangs.0.deskripsi')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="file" class="form-control" name="barangs[0][foto]">
                                            @error('barangs.0.foto')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-secondary mt-3" id="add-row">Add Row</button>
                        <button type="submit" class="btn btn-primary mt-3">Tambah</button>
                        <a href="{{ route('barangs.index') }}" class="btn btn-secondary mt-3">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let rowIndex = 1;

        document.getElementById('add-row').addEventListener('click', function() {
            const tableBody = document.querySelector('#barang-table tbody');
            const newRow = tableBody.querySelector('.barang-row').cloneNode(true);

            // Update names with new index
            newRow.querySelectorAll('input, select, textarea').forEach(function(element) {
                const name = element.name.replace(/\[\d+\]/, '[' + rowIndex + ']');
                element.name = name;
                element.value = ''; // Clear values
                if (element.type === 'file') {
                    element.value = null;
                }
            });

            // Add remove button functionality
            newRow.querySelector('.remove-row').addEventListener('click', function() {
                if (tableBody.querySelectorAll('tr').length > 1) {
                    newRow.remove();
                }
            });

            tableBody.appendChild(newRow);
            rowIndex++;
        });

        document.querySelectorAll('.remove-row').forEach(function(button) {
            button.addEventListener('click', function() {
                const row = button.closest('tr');
                const tableBody = document.querySelector('#barang-table tbody');
                if (tableBody.querySelectorAll('tr').length > 1) {
                    row.remove();
                }
            });
        });
    </script>
@endsection
