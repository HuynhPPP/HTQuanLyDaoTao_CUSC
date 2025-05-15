<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/banner_cusc.png') }}" alt="Logo" style="height:40px;">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/banner_cusc.png') }}" alt="Logo nhỏ" style="height:30px;">
            </a>
        </div>
        {{-- <ul class="sidebar-menu">
            <li class="dropdown {{ request()->routeIs('about') ? 'active' : '' }}">
                <a href="{{ route('about') }}" class="nav-link">
                    <i class="fas fa-columns"></i>
                    <span>Giới thiệu</span>
                </a>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="far fa-file-alt"></i> <span>Dữ liệu hệ thống</span></a>
                <ul class="dropdown-menu" style="display: none;">
                  <li><a class="nav-link" href="forms-advanced-form.html">Quản lý sinh viên</a></li>
                  <li><a class="nav-link" href="forms-editor.html">Quản lý cán bộ</a></li>
                </ul>
              </li>
            <li class="dropdown {{ request()->routeIs('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="fas fa-fire"></i>
                    <span>Quản lý đào tạo</span>
                </a>
            </li>
        </ul> --}}
        <ul class="sidebar-menu">
            <li class="dropdown {{ request()->routeIs('about') ? 'active' : '' }}">
                <a href="{{ route('about') }}" class="nav-link">
                    <i class="fas fa-columns"></i>
                    <span>Giới thiệu</span>
                </a>
            </li>
        
            @auth
                @if(auth()->user()->hasRole(['admin', 'staff']))
                <li class="dropdown {{ request()->routeIs('sinhvien.*') || request()->routeIs('canbo.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="far fa-file-alt"></i> 
                        <span>Dữ liệu hệ thống</span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        @if(auth()->user()->hasRole(['admin', 'staff']))
                        <li class="{{ request()->routeIs('sinhvien.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('sinhvien.index') }}">
                                <i class="fas fa-user-graduate"></i> Quản lý sinh viên
                            </a>
                        </li>
                        @endif
                        
                        @if(auth()->user()->hasRole('admin'))
                        <li class="{{ request()->routeIs('canbo.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('canbo.index') }}">
                                <i class="fas fa-user-tie"></i> Quản lý cán bộ
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
            
                @if(auth()->user()->hasRole(['admin', 'staff', 'teacher']))
                <li class="dropdown {{ request()->routeIs('daotao.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Quản lý đào tạo</span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        @if(auth()->user()->hasRole(['admin', 'staff']))
                        <li class="{{ request()->routeIs('daotao.lophoc.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('daotao.lophoc.index') }}">
                                <i class="fas fa-chalkboard"></i> Quản lý lớp học
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('daotao.monhoc.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('daotao.monhoc.index') }}">
                                <i class="fas fa-book"></i> Quản lý môn học
                            </a>
                        </li>
                        @endif
            
                        @if(auth()->user()->hasRole(['admin', 'staff', 'teacher']))
                        <li class="{{ request()->routeIs('daotao.diem.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('daotao.diem.index') }}">
                                <i class="fas fa-star"></i> Quản lý điểm
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
            
                @if(auth()->user()->hasRole('student'))
                <li class="dropdown {{ request()->routeIs('sinhvien.thongtin.*') ? 'active' : '' }}">
                    <a href="{{ route('sinhvien.thongtin.index') }}" class="nav-link">
                        <i class="fas fa-user"></i>
                        <span>Thông tin cá nhân</span>
                    </a>
                </li>
                <li class="dropdown {{ request()->routeIs('sinhvien.diem.*') ? 'active' : '' }}">
                    <a href="{{ route('sinhvien.diem.index') }}" class="nav-link">
                        <i class="fas fa-star"></i>
                        <span>Xem điểm</span>
                    </a>
                </li>
                @endif
            
                @if(auth()->user()->hasRole('admin'))
                <li class="dropdown {{ request()->routeIs('hethong.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-cogs"></i>
                        <span>Hệ thống</span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        <li class="{{ request()->routeIs('hethong.nguoidung.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('hethong.nguoidung.index') }}">
                                <i class="fas fa-users"></i> Quản lý người dùng
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('hethong.vaitro.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('hethong.vaitro.index') }}">
                                <i class="fas fa-user-tag"></i> Quản lý vai trò
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('hethong.cauhinh.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('hethong.cauhinh.index') }}">
                                <i class="fas fa-sliders-h"></i> Cấu hình hệ thống
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            @endauth
        </ul>
    </aside>
</div>
