@extends('layouts.app')

@section('title', 'Edit Barang Masuk')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Barang Masuk</h4>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('barangmasuks.update', $barangmasuk->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="{{ $barangmasuk->tanggal_masuk->format('Y-m-d') }}" required>
                            @error('tanggal_masuk')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" maxlength="1000">{{ $barangmasuk->catatan }}</textarea>
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
                                        <th>Tanggal Kadaluarsa</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barangmasuk->barangmasukdetail as $index => $detail)
                                        <tr class="detail-row">
                                            <td>
                                                <input type="hidden" name="details[{{ $index }}][id]" value="{{ $detail->id }}">
                                                <select class="form-control" name="details[{{ $index }}][id_barang]" required>
                                                    @foreach ($barangs as $barang)
                                                        <option value="{{ $barang->id }}" {{ $barang->id == $detail->id_barang ? 'selected' : '' }}>{{ $barang->nama }} ({{ $barang->kode_barang }})</option>
                                                    @endforeach
                                                </select>
                                                @error("details.$index.id_barang")
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="details[{{ $index }}][jumlah]" value="{{ $detail->jumlah }}" required min="1">
                                                @error("details.$index.jumlah")
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="date" class="form-control" name="details[{{ $index }}][tanggal_kadaluarsa]" value="{{ $detail->tanggal_kadaluarsa ? $detail->tanggal_kadaluarsa->format('Y-m-d') : '' }}">
                                                @error("details.$index.tanggal_kadaluarsa")
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger remove-row">Remove</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($barangmasuk->barangmasukdetail->isEmpty())
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
                                                <input type="date" class="form-control" name="details[0][tanggal_kadaluarsa]">
                                                @error('details.0.tanggal_kadaluarsa')
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
                        <button type="submit" class="btn btn-primary mt-3">Update</button>
                        <a href="{{ route('barangmasuks.index') }}" class="btn btn-secondary mt-3">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let rowIndex = {{ $barangmasuk->barangmasukdetail->count() || 1 }};

        document.getElementById('add-row').addEventListener('click', function() {
            const tableBody = document.querySelector('#detail-table tbody');
            const newRow = tableBody.querySelector('.detail-row').cloneNode(true);

            // Remove hidden id if present
            const hiddenId = newRow.querySelector('input[type="hidden"]');
            if (hiddenId) {
                hiddenId.remove();
            }

            // Update names with new index
            newRow.querySelectorAll('input, select').forEach(function(element) {
                const name = element.name.replace(/\[\d+\]/, '[' + rowIndex + ']');
                element.name = name;
                element.value = ''; // Clear values
                if (element.tagName === 'SELECT') {
                    element.selectedIndex = 0;
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
                const tableBody = document.querySelector('#detail-table tbody');
                if (tableBody.querySelectorAll('tr').length > 1) {
                    row.remove();
                }
            });
        });
    </script>
@endsection
