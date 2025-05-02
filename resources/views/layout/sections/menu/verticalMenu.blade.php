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
                <li class="menu-item">
                    {{-- <a href="javascript:void(0)" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"> --}}
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                        <div>Dashboard</div>
                        {{-- <div class="badge bg-primary rounded-pill ms-auto">1</div> --}}
                    </a>

                    {{-- submenu --}}
                    {{-- @isset($menu->submenu)
                        @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
                    @endisset --}}
                </li>

    </ul>

</aside>
