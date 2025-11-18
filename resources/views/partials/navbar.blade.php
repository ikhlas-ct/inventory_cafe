     <nav class="navbar fixed-top d-flex flex-row p-0">
         <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
             <a class="navbar-brand brand-logo-mini" href="index.html"><img
                     src="{{ asset('src/assets/images/logo-mini.svg') }}" alt="logo" /></a>
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
                         <span class="count bg-danger"></span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list"
                         aria-labelledby="notificationDropdown">
                         <h6 class="mb-0 p-3">Notifications</h6>
                         <div class="dropdown-divider"></div>
                         <a class="dropdown-item preview-item">
                             <div class="preview-thumbnail">
                                 <div class="preview-icon bg-dark rounded-circle">
                                     <i class="mdi mdi-calendar text-success"></i>
                                 </div>
                             </div>
                             <div class="preview-item-content">
                                 <p class="preview-subject mb-1">Event today</p>
                                 <p class="text-muted ellipsis mb-0"> Just a reminder that you have an event today </p>
                             </div>
                         </a>
                         <div class="dropdown-divider"></div>
                         <a class="dropdown-item preview-item">
                             <div class="preview-thumbnail">
                                 <div class="preview-icon bg-dark rounded-circle">
                                     <i class="mdi mdi-cog text-danger"></i>
                                 </div>
                             </div>
                             <div class="preview-item-content">
                                 <p class="preview-subject mb-1">Settings</p>
                                 <p class="text-muted ellipsis mb-0"> Update dashboard </p>
                             </div>
                         </a>
                         <div class="dropdown-divider"></div>
                         <a class="dropdown-item preview-item">
                             <div class="preview-thumbnail">
                                 <div class="preview-icon bg-dark rounded-circle">
                                     <i class="mdi mdi-link-variant text-warning"></i>
                                 </div>
                             </div>
                             <div class="preview-item-content">
                                 <p class="preview-subject mb-1">Launch Admin</p>
                                 <p class="text-muted ellipsis mb-0"> New admin wow! </p>
                             </div>
                         </a>
                         <div class="dropdown-divider"></div>
                         <p class="mb-0 p-3 text-center">See all notifications</p>
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
