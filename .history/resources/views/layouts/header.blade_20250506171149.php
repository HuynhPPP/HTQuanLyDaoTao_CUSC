<div class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container d-flex justify-content-center">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo_CTU.png') }}" width="80" height="80" alt="logo_ctu">
        </a>
        <img src="{{ asset('images/banner_cusc.png') }}" width="800" height="120" alt="banner_cusc">
    </div>
</div>
<nav class="navbar navbar-expand-lg sticky-top bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand {{ request()->is('/') ? 'text-primary' : '' }}" href="{{ route('home') }}">
            <i class="fa-solid fa-house"></i>
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('about') ? 'active fw-bold' : '' }}" aria-current="page" href="{{ route('about') }}">Giới thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('ministry') ? 'active fw-bold' : '' }}" href="{{ route('ministry') }}" aria-current="page">Giáo vụ</a>
                </li>
            </ul>
            @if(session('user'))
                <div class="d-flex align-items-center">
                    <span class="navbar-text me-2">{{ session('displayname') }}</span>
                    <a class="nav-link" href="{{ route('logout') }}">Đăng xuất</a>
                </div>
            @else
                <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
            @endif
        </div>
    </div>
</nav>
