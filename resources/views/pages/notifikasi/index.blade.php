@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <div class="container py-4 text-white">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">📢 Semua Notifikasi</h4>

            @if (auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifikasi.readAll') }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-primary">
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>

        <div class="card bg-dark border-secondary shadow">
            <div class="card-body p-0">

                @forelse($notifications as $notification)
                    <div
                        class="border-bottom border-secondary {{ is_null($notification->read_at) ? 'bg-secondary bg-opacity-25' : '' }} p-3">

                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong class="text-white">
                                    {{ $notification->data['nama_barang'] ?? 'Notifikasi Barang' }}
                                </strong>

                                <?php
                                    $pesan = $notification->data['pesan'] ?? '-';
                                    // Ekstrak angka desimal dari pesan (misal: "dalam 6.375750306956 hari")
                                    if (preg_match('/dalam (\d+\.\d+) hari/', $pesan, $matches)) {
                                        $days = (float) $matches[1];
                                        $rounded = round($days); // Bulatkan: >=0.5 ke atas, <0.5 ke bawah
                                        // Ganti angka asli dengan yang dibulatkan (sebagai integer)
                                        $pesan = str_replace($matches[1], (int)$rounded, $pesan);
                                    }
                                ?>

                                <p class="text-light mb-1">
                                    {{ $pesan }}
                                </p>

                                @if (isset($notification->data['expired_at']))
                                    <small class="text-warning">
                                        Kadaluarsa:
                                        {{ \Carbon\Carbon::parse($notification->data['expired_at'])->format('d M Y') }}
                                    </small>
                                @endif

                                <br>
                                <small class="text-muted">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>

                            @if (is_null($notification->read_at))
                                <form action="{{ route('notifikasi.read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success">
                                        Tandai Dibaca
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-success">Dibaca</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-muted p-4 text-center">
                        Tidak ada notifikasi
                    </div>
                @endforelse

            </div>
        </div>

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>

    </div>
@endsection
