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
        <ul class="sidebar-menu">
            <li class="dropdown">
                <a href="{{ route('about') }}" class="nav-link" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Giới thiệu</span></a>
            </li>
            <li class="dropdown active">
                <a href="{{ route('home') }}" class="nav-link"><i class="fas fa-fire"></i><span>Quản lý đào tạo</span></a>
            </li>
        </ul>
    </aside>
</div>
