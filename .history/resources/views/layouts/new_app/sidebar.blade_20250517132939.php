<div class="main-sidebar sidebar-style-2" style="position: absolute; top: 0; left: 0;">
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
                    <i class="fas fa-home"></i>
                    <span>Giới thiệu</span>
                </a>
            </li>
            @php
                $dropdownActive =
                    request()->routeIs('student.*') ||
                    request()->routeIs('staff.*') ||
                    request()->routeIs('giaovien.*') ||
                    request()->routeIs('donvi.*') ||
                    request()->routeIs('lophoc.*') ||
                    request()->routeIs('phonghoc.*');
            @endphp
            <li class="dropdown {{ $dropdownActive ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-server"></i> <span>Quản Lý</span></a>
                <ul class="dropdown-menu">
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown">Nhân Sự</a>
                        <ul class="dropdown-menu">
                            <li class="{{ request()->routeIs('student.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('student.list') }}">Sinh Viên</a>
                            </li>
                            <li class="{{ request()->routeIs('giaovien.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('giaovien.index') }}">Giáo Viên</a>
                            </li>
                            <li class="{{ request()->routeIs('staff.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('staff.index') }}">Cán Bộ</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown">Đơn Vị & Lớp</a>
                        <ul class="dropdown-menu">
                            <li class="{{ request()->routeIs('donvi.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('donvi.index') }}">Đơn Vị</a>
                            </li>
                            <li class="{{ request()->routeIs('phonghoc.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('phonghoc.index') }}">Phòng Học</a>
                            </li>
                            <li class="{{ request()->routeIs('lophoc.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('lophoc.index') }}">Lớp Học</a>
                            </li>
                            <li class="{{ request()->routeIs('danhsachphong.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('danhsachphong.index') }}">Gán Phòng Cho Lớp</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown">Hành Chính</a>
                        <ul class="dropdown-menu">
                            <li class="{{ request()->routeIs('taphuan.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('taphuan.index') }}">Tập Huấn</a>
                            </li>
                            <li class="{{ request()->routeIs('bangcapcanbo.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('bangcapcanbo.index') }}">Bằng Cấp</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="dropdown {{ request()->routeIs('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="far fa-calendar-alt"></i>
                    <span>Hệ thống lập lịch</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
