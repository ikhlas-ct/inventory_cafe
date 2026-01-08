@extends('layouts.app')

@section('title', 'Barang')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Table Barangs</h4>
                    @include('partials.alert') <!-- Include the alert partial here -->

                    <!-- Search Form -->
                    <form action="{{ route('barangs.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by nama or deskripsi" value="{{ $search ?? '' }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    <a href="{{ route('barangs.create') }}" class="btn btn-primary mb-3">
                        Tambah Barang
                    </a>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>Supplier</th>
                                    <th>Harga</th>
                                    <th>Deskripsi</th>
                                    <th>Stok Sekarang</th>
                                    <th>Foto</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangs as $key => $barang)
                                    <tr>
                                        <td>{{ $barangs->firstItem() + $key }}</td>
                                        <td>{{ $barang->nama }}</td>
                                        <td>{{ $barang->kategori->nama ?? '-' }}</td>
                                        <td>{{ $barang->satuan->nama ?? '-' }}</td>
                                        <td>{{ $barang->supplier->nama }}</td>
                                        <td>{{ $barang->harga }}</td>
                                        <td>{{ $barang->deskripsi ?? '-' }}</td>
                                        <td>{{ $barang->stok ?? 0 }}</td>
                                        <td>
                                            @if ($barang->foto)
                                <img src="{{ asset('storage/' . $barang->foto) }}" alt="Foto Barang" width="100" class="mt-2">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('barangs.edit', $barang->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('barangs.destroy', $barang->id) }}" method="POST" style="display: inline;">
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

                    <!-- Pagination Links -->
                    <div class="mt-3">
                        {{ $barangs->links('pagination::bootstrap-5') }} <!-- Use Bootstrap 5 pagination -->
                    </div>

                    <!-- New Table for Expiring Stocks -->
                    <h4 class="card-title mt-5">Stok Barang yang Akan Habis Masa Kadaluarsa</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Tanggal Kadaluarsa</th>
                                    <th>Jumlah Stok</th>
                                    <th>Sisa Hari</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expiringStocks as $key => $item)
                                    <tr @if ($item['sisa_hari'] < 0) class="bg-primary text-white" @elseif ($item['sisa_hari'] < 5) class="bg-warning text-dark" @endif>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item['barang']->nama }}</td>
                                        <td>{{ $item['kadaluarsa']->format('d-m-Y') }}</td>
                                        <td>{{ $item['stok'] }}</td>
                                        <td>{{ $item['sisa_hari'] }} hari</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada stok yang mendekati kadaluarsa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
