<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-logo demo me-1 my-3">
                @include('_partials.macros')
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="mdi menu-toggle-icon d-xl-block align-middle mdi-20px"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- adding active and open class if child is active --}}
        {{-- main menu --}}
        <li class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin:dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div>Dashboard</div>
                {{-- <div class="badge bg-primary rounded-pill ms-auto">1</div> --}}
            </a>
        </li>

        <li class="menu-item {{ request()->is('admin/manage/*') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-account-outline"></i>
                <div>Manage Users</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('admin/manage/clients') ? 'active' : '' }}"">
                    <a href="{{ route('admin:clients.list') }}" class="menu-link">
                        {{-- <i class="{{ $submenu->icon }}"></i> --}}
                        <div>Clients</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('admin/manage/editors') ? 'active' : '' }}"">
                    <a href="{{ route('admin:editors.list') }}" class="menu-link">
                        <div>Editors</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->is('admin/jobs/*') ? 'active' : '' }}"">
            <a href="{{ route('admin:jobs.list') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-file-sign"></i>
                <div>Jobs</div>
            </a>

        </li>

    </ul>

</aside>
