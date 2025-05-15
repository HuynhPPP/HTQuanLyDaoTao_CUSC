<div class="main-sidebar sidebar-style-2" style="position: absolute; top: 0; left: 0; height: 500px">
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
            @php
                $dropdownActive =
                    request()->routeIs('student.list') ||
                    request()->routeIs('staff.*') ||
                    request()->routeIs('bangcapcanbo.*');
            @endphp
            <li class="dropdown {{ $dropdownActive ? 'active' : '' }}">
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
                        <a class="nav-link" href="{{ route('staff.index') }}">Quản lý cán bộ</a>
                    </li>
                    <li class="{{ request()->routeIs('bangcapcanbo.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('bangcapcanbo.index') }}">Quản lý bằng cấp cán bộ</a>
                    </li>
                    <li class="{{ request()->routeIs('phonghoc.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('phonghoc.index') }}">Quản lý phòng học</a>
                    </li>
                    <li class="{{ request()->routeIs('lophoc.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('lophoc.index') }}">Quản lý lớp học</a>
                    </li>
                    <li class="{{ request()->routeIs('danhsachphong.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('danhsachphong.index') }}">Gán phòng cho lớp</a>
                    </li>
                    <li class="">
                        <a class="nav-link" href="">Danh sách phản hồi</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown {{ request()->routeIs('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="fas fa-fire"></i>
                    <span>Hệ thống lập lịch</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
