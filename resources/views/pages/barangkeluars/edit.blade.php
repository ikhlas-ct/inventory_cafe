@extends('layouts.app')

@section('title', 'Edit Barang Keluar')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Barang Keluar</h4>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('barangkeluars.update', $barangkeluar->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                            <input type="date"
                                   class="form-control @error('tanggal_keluar') is-invalid @enderror"
                                   id="tanggal_keluar"
                                   name="tanggal_keluar"
                                   value="{{ old('tanggal_keluar', $barangkeluar->tanggal_keluar?->format('Y-m-d') ?? '') }}"
                                   required>
                            @error('tanggal_keluar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror"
                                      id="catatan"
                                      name="catatan"
                                      maxlength="1000">{{ old('catatan', $barangkeluar->catatan ?? '') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
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
                                    @forelse ($barangkeluar->barangkeluardetail as $index => $detail)
                                        <tr class="detail-row">
                                            <td>
                                                <input type="hidden" name="details[{{ $index }}][id]" value="{{ $detail->id }}">
                                                <select class="form-control @error("details.$index.id_barang") is-invalid @enderror"
                                                        name="details[{{ $index }}][id_barang]" required>
                                                    @foreach ($barangs as $barang)
                                                        <option value="{{ $barang->id }}"
                                                                {{ old("details.$index.id_barang", $detail->id_barang) == $barang->id ? 'selected' : '' }}>
                                                            {{ $barang->nama }} ({{ $barang->kode_barang }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("details.$index.id_barang")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number"
                                                       class="form-control @error("details.$index.jumlah") is-invalid @enderror"
                                                       name="details[{{ $index }}][jumlah]"
                                                       value="{{ old("details.$index.jumlah", $detail->jumlah) }}"
                                                       required min="1">
                                                @error("details.$index.jumlah")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger remove-row">Remove</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="detail-row">
                                            <td>
                                                <select class="form-control" name="details[0][id_barang]" required>
                                                    @foreach ($barangs as $barang)
                                                        <option value="{{ $barang->id }}">{{ $barang->nama }} ({{ $barang->kode_barang }})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="details[0][jumlah]" required min="1">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger remove-row">Remove</button>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-secondary mt-3" id="add-row">Add Row</button>
                        <button type="submit" class="btn btn-primary mt-3">Update</button>
                        <a href="{{ route('barangkeluars.index') }}" class="btn btn-secondary mt-3">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let rowIndex = {{ $barangkeluar->barangkeluardetail->count() ?: 0 }};

        // Add Row
        document.getElementById('add-row').addEventListener('click', function() {
            const tbody = document.querySelector('#detail-table tbody');
            const newRow = document.createElement('tr');
            newRow.className = 'detail-row';
            newRow.innerHTML = `
                <td>
                    <select class="form-control" name="details[${rowIndex}][id_barang]" required>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->nama }} ({{ $barang->kode_barang }})</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" name="details[${rowIndex}][jumlah]" required min="1">
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                </td>
            `;

            tbody.appendChild(newRow);
            rowIndex++;

            // Attach remove event to the new button
            newRow.querySelector('.remove-row').addEventListener('click', removeRowHandler);
        });

        // Remove Row Handler
        function removeRowHandler() {
            const tbody = document.querySelector('#detail-table tbody');
            if (tbody.querySelectorAll('tr').length > 1) {
                this.closest('tr').remove();
            }
        }

        // Attach remove event to existing buttons
        document.querySelectorAll('.remove-row').forEach(btn => {
            btn.addEventListener('click', removeRowHandler);
        });
    </script>
@endsection
