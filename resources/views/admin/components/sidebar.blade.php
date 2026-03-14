<div class="main-nav">
     <!-- Sidebar Logo -->
     <div class="logo-box">
          <a href="{{ route('admin.dashboard') }}" class="logo-dark">
               <h4 class="mt-3 text-primary">Dokkan Admin</h4>
          </a>
     </div>

     <div class="scrollbar" data-simplebar>
          <ul class="navbar-nav" id="navbar-nav">
               <li class="menu-title">Menu</li>

               <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:widget-5-broken"></iconify-icon>
                         </span>
                         <span class="nav-text"> Dashboard </span>
                    </a>
               </li>

               <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.admins') ? 'active' : '' }}" href="{{ route('admin.admins') }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:users-group-two-rounded-broken"></iconify-icon>
                         </span>
                         <span class="nav-text"> Admins </span>
                    </a>
               </li>

               <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.roles') ? 'active' : '' }}" href="{{ route('admin.roles') }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:shield-keyhole-broken"></iconify-icon>
                         </span>
                         <span class="nav-text"> Roles & Permissions </span>
                    </a>
               </li>

               <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:settings-broken"></iconify-icon>
                         </span>
                         <span class="nav-text"> Settings </span>
                    </a>
               </li>
               
               <li class="menu-title mt-2">SaaS (Coming Soon)</li>
               <li class="nav-item opacity-50">
                    <a class="nav-link" href="javascript:void(0);">
                         <span class="nav-icon"><iconify-icon icon="solar:cart-broken"></iconify-icon></span>
                         <span class="nav-text"> Subscribers </span>
                    </a>
               </li>
          </ul>
     </div>
</div>
