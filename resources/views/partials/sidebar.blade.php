   <nav class="sidebar sidebar-offcanvas" id="sidebar">
       <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
           <a class="sidebar-brand brand-logo" href="index.html"><img src="{{ asset('src/assets/images/logo.svg') }}"
                   alt="logo" /></a>
           <a class="sidebar-brand brand-logo-mini" href="index.html"><img
                   src="{{ asset('src/assets/images/logo-mini.svg') }}" alt="logo" /></a>
       </div>
       <ul class="nav">
           <li class="nav-item profile">
               <div class="profile-desc">
                   <div class="profile-pic">
                       <div class="count-indicator">
                           <img class="img-xs rounded-circle"
                               src="{{ asset(
                                   'storage/' .
                                       (optional(Auth::user()->karyawan)->foto ?? (optional(Auth::user()->manajer)->foto ?? 'default/user.png')),
                               ) }}"
                               alt="Foto User">
                           <span class="count bg-success"></span>
                       </div>
                       <div class="profile-name">
                           <h5 class="font-weight-normal mb-0">
                               {{ Auth::user()->karyawan->nama ?? (Auth::user()->manajer->nama ?? '-') }}
                           </h5>
                           <span>{{ Auth::user()->role }}</span>
                       </div>
                   </div>
                   <a href="#" id="profile-dropdown" data-bs-toggle="dropdown"><i
                           class="mdi mdi-dots-vertical"></i></a>
                   <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list"
                       aria-labelledby="profile-dropdown">
                       <a href="#" class="dropdown-item preview-item">
                           <div class="preview-thumbnail">
                               <div class="preview-icon bg-dark rounded-circle">
                                   <i class="mdi mdi-cog text-primary"></i>
                               </div>
                           </div>
                           <div class="preview-item-content">
                               <p class="preview-subject ellipsis text-small mb-1">Account settings</p>
                           </div>
                       </a>
                       <div class="dropdown-divider"></div>
                       <a href="#" class="dropdown-item preview-item">
                           <div class="preview-thumbnail">
                               <div class="preview-icon bg-dark rounded-circle">
                                   <i class="mdi mdi-onepassword text-info"></i>
                               </div>
                           </div>
                           <div class="preview-item-content">
                               <p class="preview-subject ellipsis text-small mb-1">Change Password</p>
                           </div>
                       </a>
                       <div class="dropdown-divider"></div>
                       <a href="#" class="dropdown-item preview-item">
                           <div class="preview-thumbnail">
                               <div class="preview-icon bg-dark rounded-circle">
                                   <i class="mdi mdi-calendar-today text-success"></i>
                               </div>
                           </div>
                           <div class="preview-item-content">
                               <p class="preview-subject ellipsis text-small mb-1">To-do list</p>
                           </div>
                       </a>
                   </div>
               </div>
           </li>
           @can('ismanajer', Auth::user())
               <li class="nav-item nav-category">
                   <span class="nav-link">Data Master</span>
               </li>

               <li class="nav-item menu-items">
                   <a class="nav-link" href="index.html">
                       <span class="menu-icon">
                           <i class="mdi mdi-speedometer"></i>
                       </span>
                       <span class="menu-title">Dashboard</span>
                   </a>
               </li>
               <li class="nav-item menu-items">
                   <a class="nav-link" href="{{ route('manajers.index') }}">
                       <span class="menu-icon">
                           <i class="mdi mdi-account-tie"></i>
                       </span>
                       <span class="menu-title">Manajer</span>
                       <i class="menu-arrow"></i>
                   </a>
               </li>

               <li class="nav-item menu-items">
                   <a class="nav-link" href="{{ route('karyawans.index') }}">
                       <span class="menu-icon">
                           <i class="mdi mdi-account-group"></i>
                       </span>
                       <span class="menu-title">Karyawan</span>
                       <i class="menu-arrow"></i>
                   </a>
               </li>

               <li class="nav-item menu-items">
                   <a class="nav-link" href="{{ route('satuans.index') }}">
                       <span class="menu-icon">
                           <i class="mdi mdi-ruler-square"></i>
                       </span>
                       <span class="menu-title">Satuan</span>
                       <i class="menu-arrow"></i>
                   </a>
               </li>

               <li class="nav-item menu-items">
                   <a class="nav-link" href="{{ route('kategoris.index') }}">
                       <span class="menu-icon">
                           <i class="mdi mdi-tag-multiple"></i>
                       </span>
                       <span class="menu-title">Kategori</span>
                       <i class="menu-arrow"></i>
                   </a>
               </li>
               <li class="nav-item menu-items">
                   <a class="nav-link" href="{{ route('suppliers.index') }}">
                       <span class="menu-icon">
                           <i class="mdi mdi-truck"></i>
                       </span>
                       <span class="menu-title">Supplier</span>
                       <i class="menu-arrow"></i>
                   </a>
               </li>
               <li class="nav-item menu-items">
                   <a class="nav-link" href="{{ route('barangs.index') }}">
                       <span class="menu-icon">
                           <i class="mdi mdi-package-variant"></i>
                       </span>
                       <span class="menu-title">Barang</span>
                       <i class="menu-arrow"></i>
                   </a>
               </li>
           @endcan
           <li class="nav-item nav-category">
               <span class="nav-link">Data Operasional</span>
           </li>
           <li class="nav-item menu-items">
               <a class="nav-link" href="{{ route('barangmasuks.index') }}">
                   <span class="menu-icon">
                       <i class="mdi mdi-arrow-up-bold"></i>
                   </span>
                   <span class="menu-title">Barang Masuk</span>
                   <i class="menu-arrow"></i>
               </a>
           </li>
           <li class="nav-item menu-items">
               <a class="nav-link" href="{{ route('barangkeluars.index') }}">
                   <span class="menu-icon">
                       <i class="mdi mdi-arrow-down-bold"></i>
                   </span>
                   <span class="menu-title">Barang Keluar</span>
                   <i class="menu-arrow"></i>
               </a>
           </li>

       </ul>
   </nav>
