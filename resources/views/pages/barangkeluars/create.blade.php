@extends('layouts.app')

@section('title', 'Tambah Barang Keluar')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Barang Keluar</h4>
                    @include('partials.alert')
                    <form action="{{ route('barangkeluars.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                            <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" value="{{ old('tanggal_keluar', now()->format('Y-m-d')) }}" required>
                            @error('tanggal_keluar')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" maxlength="1000">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="detail-table">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Jumlah</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (old('details'))
                                        @foreach (old('details') as $index => $oldDetail)
                                            <tr class="detail-row">
                                                <td>
                                                    <select class="form-control" name="details[{{ $index }}][id_barang]" required>
                                                        @foreach ($barangs as $barang)
                                                            <option value="{{ $barang->id }}" {{ old("details.$index.id_barang") == $barang->id ? 'selected' : '' }}>{{ $barang->nama }} ({{ $barang->kode_barang }})</option>
                                                        @endforeach
                                                    </select>
                                                    @error("details.$index.id_barang")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="details[{{ $index }}][jumlah]" value="{{ old("details.$index.jumlah") }}" required min="1">
                                                    @error("details.$index.jumlah")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="detail-row">
                                            <td>
                                                <select class="form-control" name="details[0][id_barang]" required>
                                                    @foreach ($barangs as $barang)
                                                        <option value="{{ $barang->id }}">{{ $barang->nama }} ({{ $barang->kode_barang }})</option>
                                                    @endforeach
                                                </select>
                                                @error('details.0.id_barang')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="details[0][jumlah]" required min="1">
                                                @error('details.0.jumlah')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger remove-row">Remove</button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-secondary mt-3" id="add-row">Add Row</button>
                        <button type="submit" class="btn btn-primary mt-3">Tambah</button>
                        <a href="{{ route('barangkeluars.index') }}" class="btn btn-secondary mt-3">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let rowIndex = {{ old('details') ? count(old('details')) : 1 }};

        document.getElementById('add-row').addEventListener('click', function() {
            const tableBody = document.querySelector('#detail-table tbody');
            const newRow = tableBody.querySelector('.detail-row').cloneNode(true);

            newRow.querySelectorAll('input, select').forEach(function(element) {
                const name = element.name.replace(/\[\d+\]/, '[' + rowIndex + ']');
                element.name = name;
                element.value = '';
            });

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
                const tableBody = document.querySelector('#detail-table tbody');
                if (tableBody.querySelectorAll('tr').length > 1) {
                    row.remove();
                }
            });
        });
    </script>
@endsection
