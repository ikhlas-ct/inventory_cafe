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

                        <div class="d-flex justify-content-between align-items-start gap-3">

                            <div class="flex-grow-1">
                                <!-- Judul / Title -->
                                @if (isset($notification->data['nama_barang']))
                                    <strong class="d-block mb-1 text-white">
                                        {{ $notification->data['nama_barang'] }}
                                    </strong>
                                @else
                                    <strong class="d-block mb-1 text-white">
                                        {{ $notification->data['message'] ?? 'Notifikasi Sistem' }}
                                    </strong>
                                @endif

                                <!-- Pesan utama dengan link jika ada barang_masuk_id atau karyawan_id atau url -->
                                @if (isset($notification->data['pesan']))
                                    @php
                                        $pesan = $notification->data['pesan'];
                                        // Ekstrak dan bulatkan angka desimal dari pesan (misal: "dalam 6.375750306956 hari")
                                        if (preg_match('/dalam (\d+\.\d+) hari/', $pesan, $matches)) {
                                            $days = (float) $matches[1];
                                            $rounded = round($days); // Bulatkan: >=0.5 ke atas, <0.5 ke bawah
                                            // Ganti angka asli dengan yang dibulatkan (sebagai integer)
                                            $pesan = str_replace($matches[1], (int) $rounded, $pesan);
                                        }
                                    @endphp
                                    <p class="text-light mb-1">
                                        {{ $pesan }}
                                    </p>
                                @elseif (isset($notification->data['message']))
                                    <p class="text-light mb-1">
                                        @if (isset($notification->data['url']))
                                            <a href="{{ $notification->data['url'] }}"
                                                class="text-light text-decoration-underline">
                                                {{ $notification->data['message'] }}
                                            </a>
                                        @elseif (isset($notification->data['id_barang_masuk']))
                                            <a href="{{ route('barangmasuks.show', $notification->data['id_barang_masuk']) }}"
                                                class="text-light text-decoration-underline">
                                                {{ $notification->data['message'] }}
                                            </a>
                                        @elseif (isset($notification->data['karyawan_id']))
                                            <a href="{{ route('karyawans.show', $notification->data['karyawan_id']) }}"
                                                class="text-light text-decoration-underline">
                                                {{ $notification->data['message'] }}
                                            </a>
                                        @elseif (isset($notification->data['barang_id']))
                                            <!-- TAMBAHAN BARU -->
                                            <a href="{{ route('barangs.show', $notification->data['barang_id']) }}"
                                                class="btn btn-sm btn-outline-primary align-self-start mt-1">
                                                Lihat Detail
                                            </a>
                                        @else
                                            {{ $notification->data['message'] }}
                                        @endif
                                    </p>
                                @else
                                    <p class="text-light fst-italic mb-1">
                                        Tidak ada deskripsi
                                    </p>
                                @endif

                                <!-- Tambahan: Dibuat oleh dan Nomor Transaksi (khusus untuk notif Barang Masuk) -->
                                @if (isset($notification->data['nomor_transaksi']) || isset($notification->data['id_barang_masuk']))
                                    <small class="text-muted d-block" style="font-size: 0.85rem;">
                                        Nomor Transaksi: {{ $notification->data['nomor_transaksi'] ?? 'N/A' }}
                                    </small>
                                    <small class="text-muted d-block" style="font-size: 0.85rem;">
                                        Dibuat oleh: {{ $notification->data['created_by'] ?? 'User' }}
                                    </small>
                                @endif

                                <!-- Info kadaluarsa (khusus barang) -->
                                @if (isset($notification->data['expired_at']))
                                    <small class="text-warning d-block">
                                        Kadaluarsa:
                                        {{ \Carbon\Carbon::parse($notification->data['expired_at'])->format('d M Y') }}
                                    </small>
                                @endif

                                <!-- Waktu -->
                                <small class="text-muted d-block mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>

                            <!-- Tombol / Status dengan link di sebelahnya jika ada url -->
                            <div class="d-flex align-items-start flex-shrink-0 gap-2">
                                @if (is_null($notification->read_at))
                                    <form action="{{ route('notifikasi.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success">
                                            Tandai Dibaca
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-success align-self-start mt-1">
                                        Dibaca
                                    </span>
                                @endif

                                <!-- Tambahan: Link ke detail jika ada url -->
                                @if (isset($notification->data['url']))
                                    <a href="{{ $notification->data['url'] }}"
                                        class="btn btn-sm btn-outline-primary align-self-start mt-1">
                                        Lihat Detail
                                    </a>
                                @elseif (isset($notification->data['id_barang_masuk']))
                                    <a href="{{ route('barangmasuks.show', $notification->data['id_barang_masuk']) }}"
                                        class="btn btn-sm btn-outline-primary align-self-start mt-1">
                                        Lihat Detail
                                    </a>
                                @elseif (isset($notification->data['karyawan_id']))
                                    <a href="{{ route('karyawans.show', $notification->data['karyawan_id']) }}"
                                        class="btn btn-sm btn-outline-primary align-self-start mt-1">
                                        Lihat Detail
                                    </a>
                                @endif
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-muted p-5 text-center">
                        <i class="mdi mdi-bell-off-outline mdi-48px d-block mb-3 opacity-50"></i>
                        Tidak ada notifikasi saat ini
                    </div>
                @endforelse

            </div>
        </div>

        <div class="mt-4">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>

    </div>
@endsection
