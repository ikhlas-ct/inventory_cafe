@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Table Barang Masuks</h4>
                    @include('partials.alert')

                    <!-- Search Form -->
                    <form action="{{ route('barangmasuks.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by nama barang, nama supplier, nama karyawan, catatan"
                                value="{{ $search ?? '' }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    <a href="{{ route('barangmasuks.create') }}" class="btn btn-primary mb-3">Tambah Barang Masuk</a>
                    <div class="table-responsive">
                        <table class="table-striped table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Karyawan</th>
                                    <th>Nomor Transaksi</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Jumlah</th>
                                    <th>Catatan</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangmasuks as $key => $barangmasuk)
                                    <tr>
                                        <td>{{ $barangmasuks->firstItem() + $key }}</td>
                                        <td>{{ $barangmasuk->user->karyawan->nama ?? ($barangmasuk->user->manajer->nama ?? '-') }}
                                        </td>
                                        <td>{{ $barangmasuk->nomor_transaksi ?? '-' }}</td>
                                        <td>{{ $barangmasuk->tanggal_masuk->format('Y-m-d') }}</td>
                                        <td>{{ $barangmasuk->jenis_count }}</td>
                                        <td>{{ $barangmasuk->catatan ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('barangmasuks.show', $barangmasuk->id) }}"
                                                class="btn btn-primary btn-sm">Detail</a>

                                            <a href="{{ route('barangmasuks.edit', $barangmasuk->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('barangmasuks.destroy', $barangmasuk->id) }}"
                                                method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $barangmasuks->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
