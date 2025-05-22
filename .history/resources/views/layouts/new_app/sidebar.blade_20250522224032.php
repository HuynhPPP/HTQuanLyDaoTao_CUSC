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
                $nhanSuActive =
                    request()->routeIs('student.*') ||
                    request()->routeIs('giaovien.*') ||
                    request()->routeIs('staff.*');

                $donViLopActive =
                    request()->routeIs('donvi.*') ||
                    request()->routeIs('phonghoc.*') ||
                    request()->routeIs('lophoc.*') ||
                    request()->routeIs('danhsachphong.*');

                $hanhChinhActive =
                    request()->routeIs('taphuan.*') ||
                    request()->routeIs('bangcapcanbo.*') ||
                    request()->routeIs('hocvi.*');

                $TuyenSinhActive = request()->routeIs('tuyensinh.*');

                $DaoTaoActive =
                    request()->routeIs('chuongtrinh.*') ||
                    request()->routeIs('khoadaotao.*') ||
                    request()->routeIs('monhoc.*');

                $dropdownActive =
                    $nhanSuActive || $donViLopActive || $hanhChinhActive || $DaoTaoActive || $TuyenSinhActive;
            @endphp
            <li class="dropdown {{ $dropdownActive ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-server"></i> <span>Dữ liệu hệ
                        thống</span></a>
                <ul class="dropdown-menu">
                    <li class="dropdown {{ $nhanSuActive ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown">Quản lý nhân sự</a>
                        <ul class="dropdown-menu">
                            <li class="{{ request()->routeIs('student.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('student.list') }}">Sinh viên</a>
                            </li>
                            <li class="{{ request()->routeIs('giaovien.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('giaovien.index') }}">Giáo viên</a>
                            </li>
                            <li class="{{ request()->routeIs('staff.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('staff.index') }}">Cán bộ</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown {{ $donViLopActive ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown">Đơn vị & Lớp</a>
                        <ul class="dropdown-menu">
                            <li class="{{ request()->routeIs('donvi.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('donvi.index') }}">Đơn vị</a>
                            </li>
                            <li class="{{ request()->routeIs('phonghoc.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('phonghoc.index') }}">Phòng học</a>
                            </li>
                            <li class="{{ request()->routeIs('lophoc.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('lophoc.index') }}">Lớp học</a>
                            </li>
                            <li class="{{ request()->routeIs('danhsachphong.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('danhsachphong.index') }}">Gán phòng pho lớp</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown {{ $hanhChinhActive ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown">Hành Chính</a>
                        <ul class="dropdown-menu">
                            <li class="{{ request()->routeIs('taphuan.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('taphuan.index') }}">Tập Huấn</a>
                            </li>
                            <li class="{{ request()->routeIs('bangcapcanbo.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('bangcapcanbo.index') }}">Bằng Cấp</a>
                            </li>
                            <li class="{{ request()->routeIs('hocvi.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('hocvi.index') }}">Học Vị</a>
                            </li>

                        </ul>
                    </li>
                    <li class="dropdown {{ $DaoTaoActive ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown">Quản lý đào tạo</a>
                        <ul class="dropdown-menu">
                            <li class="{{ request()->routeIs('chuongtrinh.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('chuongtrinh.index') }}">Chương trình đào tạo</a>
                            </li>
                            <li class="{{ request()->routeIs('khoadaotao.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('khoadaotao.index') }}">Khoá đào tạo</a>
                            </li>
                            <li class="{{ request()->routeIs('monhoc.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('monhoc.index') }}">Môn học</a>
                            </li>
                            <li class="{{ request()->routeIs('hocki.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('hocki.index') }}">Học kỳ</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown {{ $TuyenSinhActive ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown">Quản lý tuyển sinh</a>
                        <ul class="dropdown-menu">
                            <li class="{{ request()->routeIs('tuyensinh.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('tuyensinh.index') }}">Đợt tuyển sinh</a>
                            </li>
                            {{-- <li class="">
                                <a class="nav-link" href="{{ route('tuyensinh.index') }}">Hồ sơ tuyển sinh</a>
                            </li> --}}
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
