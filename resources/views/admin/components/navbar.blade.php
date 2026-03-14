<header class="topbar">
     <div class="container-fluid">
          <div class="navbar-header">
               <div class="d-flex align-items-center gap-2">
                    <!-- Menu Toggle Button -->
                    <div class="topbar-item">
                         <button type="button" class="button-toggle-menu topbar-button">
                              <iconify-icon icon="solar:hamburger-menu-broken" class="fs-24 align-middle"></iconify-icon>
                         </button>
                    </div>

                    <!-- Title -->
                    <div class="topbar-item">
                         <h4 class="fw-bold mb-0">@yield('title', 'Dashboard')</h4>
                    </div>
               </div>

               <div class="d-flex align-items-center gap-1">
                    <!-- User -->
                    <div class="dropdown topbar-item">
                         <button type="button" class="topbar-button" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <span class="d-flex align-items-center">
                                   <img class="rounded-circle" src="https://ui-avatars.com/api/?name={{ urlencode(auth('admin')->user()->name ?? 'Admin') }}&color=7F9CF5&background=EBF4FF" alt="Header Avatar" width="32" height="32">
                              </span>
                         </button>
                         <div class="dropdown-menu dropdown-menu-end">
                              <!-- item-->
                              <h6 class="dropdown-header">Welcome {{ auth('admin')->user()->name ?? 'Admin' }}!</h6>
                              <a class="dropdown-item" href="#">
                                   <i class="bx bx-user-circle font-size-16 align-middle me-1"></i>
                                   <span>Profile</span>
                              </a>
                              <div class="dropdown-divider"></div>
                              <form action="{{ route('admin.logout') }}" method="POST">
                                   @csrf
                                   <button type="submit" class="dropdown-item text-danger">
                                        <i class="bx bx-log-out font-size-16 align-middle me-1"></i>
                                        <span>Logout</span>
                                   </button>
                              </form>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</header>
