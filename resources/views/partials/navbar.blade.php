    @php
        $user = auth()->user();
        $unreadCount = $user?->unreadNotifications()->count() ?? 0;
        $notifications = $user?->unreadNotifications()->latest()->take(5)->get() ?? collect();
    @endphp

    <nav class="navbar fixed-top d-flex flex-row p-0">
        <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="index.html"><img src="{{ asset('default/home.jpg') }}"
                    alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch flex-grow">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="mdi mdi-menu"></span>
            </button>
            <ul class="navbar-nav w-100">
                <li class="nav-item w-100">
                    <form class="nav-link mt-md-0 d-none d-lg-flex search mt-2">
                        <input type="text" class="form-control" placeholder="Search products">
                    </form>
                </li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">

                <li class="nav-item dropdown border-left">
                    <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                        data-bs-toggle="dropdown">
                        <i class="mdi mdi-bell"></i>

                        @if ($unreadCount > 0)
                            <span class="count bg-danger">{{ $unreadCount }}</span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list"
                        aria-labelledby="notificationDropdown">

                        <h6 class="mb-0 p-3">Notifications</h6>
                        <div class="dropdown-divider"></div>

                        {{-- LOOP NOTIFIKASI --}}
                        @forelse($notifications as $notif)
                            <a href="{{ route('notifikasi.index') }}" class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <!-- Icon bisa dibedakan per jenis nanti, tapi sekarang tetap sama -->
                                        <i class="mdi mdi-alert-circle text-warning"></i>
                                    </div>
                                </div>

                                <div class="preview-item-content">
                                    <!-- Judul (nama_barang atau message atau fallback) -->
                                    @if (isset($notif->data['nama_barang']))
                                        <p class="preview-subject mb-1">
                                            {{ $notif->data['nama_barang'] }}
                                        </p>
                                    @elseif (isset($notif->data['message']))
                                        <p class="preview-subject mb-1">
                                            {{ $notif->data['message'] }}
                                        </p>
                                    @else
                                        <p class="preview-subject mb-1">
                                            Notifikasi Sistem
                                        </p>
                                    @endif

                                    <!-- Pesan utama dengan link jika ada barang_masuk_id atau karyawan_id atau url -->
                                    @if (isset($notif->data['pesan']))
                                        @php
                                            $pesan = $notif->data['pesan'];
                                            // Ekstrak dan bulatkan angka desimal dari pesan (misal: "dalam 6.375750306956 hari")
                                            if (preg_match('/dalam (\d+\.\d+) hari/', $pesan, $matches)) {
                                                $days = (float) $matches[1];
                                                $rounded = round($days); // Bulatkan: >=0.5 ke atas, <0.5 ke bawah
                                                // Ganti angka asli dengan yang dibulatkan (sebagai integer)
                                                $pesan = str_replace($matches[1], (int) $rounded, $pesan);
                                            }
                                        @endphp
                                        <p class="text-muted ellipsis mb-0">
                                            {{ $pesan }}
                                        </p>
                                    @elseif (isset($notif->data['message']))
                                        <p class="text-muted ellipsis mb-0">
                                            @if (isset($notif->data['url']))
                                                <a href="{{ $notif->data['url'] }}"
                                                    class="text-muted text-decoration-underline">
                                                    {{ $notif->data['message'] }}
                                                </a>
                                            @elseif (isset($notif->data['id_barang_masuk']))
                                                <a href="{{ route('barangmasuks.show', $notif->data['id_barang_masuk']) }}"
                                                    class="text-muted text-decoration-underline">
                                                    {{ $notif->data['message'] }}
                                                </a>
                                            @elseif (isset($notif->data['karyawan_id']))
                                                <a href="{{ route('karyawans.show', $notif->data['karyawan_id']) }}"
                                                    class="text-muted text-decoration-underline">
                                                    {{ $notif->data['message'] }}
                                                </a>
                                            @else
                                                {{ $notif->data['message'] }}
                                            @endif
                                        </p>
                                    @else
                                        <p class="text-muted ellipsis fst-italic mb-0">
                                            Tidak ada deskripsi
                                        </p>
                                    @endif

                                    <!-- Tambahan: Dibuat oleh dan Nomor Transaksi (khusus untuk notif Barang Masuk) -->
                                    @if (isset($notif->data['nomor_transaksi']) || isset($notif->data['id_barang_masuk']))
                                        <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                            Nomor Transaksi: {{ $notif->data['nomor_transaksi'] ?? 'N/A' }}
                                        </small>
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">
                                            Dibuat oleh: {{ $notif->data['created_by'] ?? 'User' }}
                                        </small>
                                    @endif

                                    <!-- Waktu -->
                                    <small class="text-muted">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </small>

                                    <!-- Opsional: tambah info kadaluarsa kalau ada (tapi tetap ringkas di dropdown) -->
                                    @if (isset($notif->data['expired_at']))
                                        <small class="text-warning d-block mt-1" style="font-size: 0.75rem;">
                                            Kadaluarsa:
                                            {{ \Carbon\Carbon::parse($notif->data['expired_at'])->format('d M Y') }}
                                        </small>
                                    @endif
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                        @empty
                            <div class="dropdown-item text-muted py-3 text-center">
                                Tidak ada notifikasi baru
                            </div>
                        @endforelse

                        <p class="mb-0 p-3 text-center">
                            <a href="{{ route('notifikasi.index') }}">See all notifications</a>
                        </p>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
                        <div class="navbar-profile">
                            <img class="img-xs rounded-circle"
                                src="{{ asset(
                                    'storage/' .
                                        (optional(Auth::user()->karyawan)->foto ?? (optional(Auth::user()->manajer)->foto ?? 'default/user.png')),
                                ) }}"
                                alt="Foto User">
                            <p class="d-none d-sm-block navbar-profile-name mb-0">
                                {{ Auth::user()->karyawan->nama ?? (Auth::user()->manajer->nama ?? '-') }}
                            </p>
                            <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list"
                        aria-labelledby="profileDropdown">
                        <h6 class="mb-0 p-3">Profile</h6>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi mdi-cog text-success"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject mb-1">Settings</p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item preview-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form-navbar').submit();">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi mdi-logout text-danger"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject mb-1">Log out</p>
                            </div>
                        </a>

                        <form id="logout-form-navbar" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
                <span class="mdi mdi-format-line-spacing"></span>
            </button>
        </div>
    </nav>
