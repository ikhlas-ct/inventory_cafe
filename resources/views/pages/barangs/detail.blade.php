<!-- resources/views/pages/barangs/detail.blade.php -->
@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
    <div class="container py-4">
        <h4>Detail Barang: {{ $barang->nama }}</h4>

        <div class="card mb-3">
            <div class="card-body">
                <p><strong>Kode Barang:</strong> {{ $barang->kode_barang }}</p>
                <p><strong>Supplier:</strong> {{ $barang->supplier->nama ?? 'N/A' }}</p>
                <p><strong>Kategori:</strong> {{ $barang->kategori->nama ?? 'N/A' }}</p>
                <p><strong>Satuan:</strong> {{ $barang->satuan->nama ?? 'N/A' }}</p>
                <p><strong>Deskripsi:</strong> {{ $barang->deskripsi ?? 'Tidak ada' }}</p>
                @if ($barang->foto)
                    <img src="{{ asset('storage/' . $barang->foto) }}" alt="{{ $barang->nama }}" style="max-width: 300px;">
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">Stok per Tanggal Kadaluarsa</div>
            <div class="card-body">
                @if (isset($expiries) && count($expiries) > 0)
                    <table class="table-sm table">
                        <thead>
                            <tr>
                                <th>Tanggal Kadaluarsa</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expiries as $e)
                                <tr>
                                    <td>
                                        @if ($e['tanggal'] === 'Tidak Ada Tanggal')
                                            Tidak Ada
                                        @else
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $e['tanggal'])->translatedFormat('d F Y') }}
                                        @endif
                                    </td>
                                    <td>{{ $e['stok'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="mb-0">Tidak ada stok dengan tanggal kadaluarsa.</p>
                @endif
            </div>
        </div>

        <a href="{{ route('barangs.index') }}" class="btn btn-primary mt-3">Kembali</a>
    </div>
@endsection
