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
                    request()->routeIs('monhoc.*') ||
                    request()->routeIs('hocki.*');

                $TienTrinhHocTapActive = request()->routeIs('tien-trinh-hoc-tap.*');

                $dropdownActive =
                    $nhanSuActive ||
                    $donViLopActive ||
                    $hanhChinhActive ||
                    $DaoTaoActive ||
                    $TuyenSinhActive ||
                    $TienTrinhHocTapActive;

                $ThongKeActive = request()->routeIs('thong-ke.*');
                $RankingActive = request()->routeIs('ranking.*');
                $CanhBaoActive = request()->routeIs('canh-bao.*');
            @endphp
            @if (session('role') == 'admin')
                <!-- Thống kê tổng quan -->
                <li class="dropdown {{ request()->routeIs('thong-ke.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('thong-ke.dashboard') }}" class="nav-link">
                        <i class="fas fa-chart-pie"></i>
                        <span>Thống kê tổng quan</span>
                    </a>
                </li>
                <!-- Dữ liệu hệ thống -->
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
                            </ul>
                        </li>
                        <li class="dropdown {{ $donViLopActive ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown">Phòng học & Lớp</a>
                            <ul class="dropdown-menu">
                                <li class="{{ request()->routeIs('phonghoc.*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('phonghoc.index') }}">Phòng học</a>
                                </li>
                                <li class="{{ request()->routeIs('lophoc.*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('lophoc.index') }}">Lớp học</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown {{ $DaoTaoActive ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown">Quản lý đào tạo</a>
                            <ul class="dropdown-menu">
                                <li class="{{ request()->routeIs('chuongtrinh.*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('chuongtrinh.index') }}">Chương trình đào
                                        tạo</a>
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
                    </ul>
                </li>
                <!-- Đánh giá học tập -->
                <li class="dropdown {{ $RankingActive ? 'active' : '' }}">
                    <a href="{{ route('ranking.index') }}" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Đánh giá học tập</span>
                    </a>
                </li>
                <!-- Hệ thống cảnh báo -->
                <li class="dropdown {{ $CanhBaoActive ? 'active' : '' }}">
                    <a href="{{ route('canh-bao.index') }}" class="nav-link">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Hệ thống cảnh báo</span>
                    </a>
                </li>
                <!-- Hệ thống lập lịch -->
                <li class="dropdown {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="far fa-calendar-alt"></i>
                        <span>Tạo báo cáo & lập lịch</span>
                    </a>
                </li>
            @endif

            <!-- Giảng viên -->
            @if (session('role') == 'teacher')
                <li class="dropdown">
                    <a href="{{ route('giaovien.schedule') }}" class="nav-link">
                        <i class="far fa-calendar-alt"></i>
                        <span>Lịch giảng dạy</span>
                    </a>
                </li>
                <li class="dropdown {{ request()->routeIs('giaovien.lichthi.index') ? 'active' : '' }}">
                    <a href="{{ route('giaovien.lichthi.index') }}" class="nav-link">
                        <i class="far fa-calendar-alt"></i>
                        <span>Lịch coi thi</span>
                    </a>
                </li>
                <li class="dropdown {{ request()->routeIs('giaovien.nhapdiemthi.danh-sach-mon') ? 'active' : '' }}">
                    <a href="{{ route('giaovien.nhapdiemthi.danh-sach-mon') }}" class="nav-link">
                        <i class="fas fa-chalkboard"></i>
                        <span>Nhập điểm thi</span>
                    </a>
                </li>
                <li class="dropdown {{ request()->routeIs('giaovien.thamdu.*') ? 'active' : '' }}">
                    <a href="{{ route('giaovien.thamdu.index') }}" class="nav-link">
                        <i class="fas fa-user-check"></i>
                        <span>Thống kê tham dự</span>
                    </a>
                </li>
            @endif

            <!-- Sinh viên -->
            @if (session('role') == 'student')
                <li class="dropdown {{ request()->routeIs('student.calendar.list') ? 'active' : '' }}">
                    <a href="{{ route('student.calendar.list') }}" class="nav-link">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Lịch học theo tuần</span>
                    </a>
                </li>
                <li class="dropdown {{ request()->routeIs('student.progress') ? 'active' : '' }}">
                    <a href="{{ route('student.progress') }}" class="nav-link">
                        <i class="fas fa-tasks"></i>
                        <span>Tiến trình học tập</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="{{ route('tracuu.index') }}" class="nav-link">
                        <i class="fas fa-laptop-code"></i>
                        <span>Kết quả học tập</span>
                    </a>
                </li>
            @endif
        </ul>
    </aside>
</div>
