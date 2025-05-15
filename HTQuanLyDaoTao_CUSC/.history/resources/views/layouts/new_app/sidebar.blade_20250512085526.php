<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('about') }}">
                <img src="{{ asset('images/banner_cusc.png') }}" alt="Logo" style="height:40px;">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('about') }}">
                <img src="{{ asset('images/banner_cusc.png') }}" alt="Logo nhỏ" style="height:30px;">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="dropdown {{ request()->routeIs('about') ? 'active' : '' }}">
                <a href="{{ route('about') }}" class="nav-link">
                    <i class="fas fa-columns"></i>
                    <span>Giới thiệu</span>
                </a>
            </li>
            <li
                class="dropdown {{ request()->routeIs('student.list') || request()->routeIs('staff.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="far fa-file-alt"></i> <span>Dữ liệu hệ
                        thống</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('student.list') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('student.list') }}">Quản lý sinh viên</a>
                    </li>
                    <li class="">
                        <a class="nav-link" href="">Quản lý giáo viên</a>
                    </li>
                    <li class="{{ request()->routeIs('staff.*') ? 'active' : '' }}">
                        <a class="nav-link" href="">Quản lý cán bộ</a>
                    </li>
                    <li class="">
                        <a class="nav-link" href="">Quản lý bằng cấp</a>
                    </li>
                    <li class="">
                        <a class="nav-link" href="">Quản lý cơ sở vật chất</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown {{ request()->routeIs('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="fas fa-fire"></i>
                    <span>Tổ chức đào tạo</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
