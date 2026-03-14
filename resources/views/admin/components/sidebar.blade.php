<div class="main-nav">
     <!-- Sidebar Logo -->
     <div class="logo-box">
          <a href="{{ route('admin.dashboard') }}" class="logo-dark">
               <h4 class="mt-3 text-primary">دكان - المسؤول</h4>
          </a>
     </div>

     <div class="scrollbar" data-simplebar>
          <ul class="navbar-nav" id="navbar-nav">
               <li class="menu-title">القائمة</li>

               <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:widget-5-broken"></iconify-icon>
                         </span>
                         <span class="nav-text"> لوحة التحكم </span>
                    </a>
               </li>

               <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.admins') ? 'active' : '' }}" href="{{ route('admin.admins') }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:users-group-two-rounded-broken"></iconify-icon>
                         </span>
                         <span class="nav-text"> المشرفين </span>
                    </a>
               </li>

               <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.roles') ? 'active' : '' }}" href="{{ route('admin.roles') }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:shield-keyhole-broken"></iconify-icon>
                         </span>
                         <span class="nav-text"> الأدوار والصلاحيات </span>
                    </a>
               </li>

               <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:settings-broken"></iconify-icon>
                         </span>
                         <span class="nav-text"> الإعدادات </span>
                    </a>
               </li>
               
               <li class="menu-title mt-2">نظام SaaS (قريباً)</li>
               <li class="nav-item opacity-50">
                    <a class="nav-link" href="javascript:void(0);">
                         <span class="nav-icon"><iconify-icon icon="solar:cart-broken"></iconify-icon></span>
                         <span class="nav-text"> المشتركين </span>
                    </a>
               </li>
          </ul>
     </div>
</div>
