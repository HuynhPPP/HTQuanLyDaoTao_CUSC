<div class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container d-flex justify-content-center">
      <a class="navbar-brand" href="{{ route("home") }}">
        <img src="{{ asset('storage/logo_ctu.png') }}" width="80" height="80" alt="logo_ctu">
      </a>
      <img src="{{ asset('storage/banner_cusc.png') }}" width="800" height="120" alt="banner_cusc">
    </div>  
</div >
<nav class="navbar navbar-expand-lg sticky-top bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand {{ request()->is('/') ? 'text-primary' : '' }}" href="{{ route("home") }}"><i class="fa-solid fa-house"></i></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ request()->is('about') ? 'active fw-bold' : '' }}" aria-current="page" href="{{ route('about') }}">Giới thiệu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('ministry') ? 'active fw-bold' : '' }}" href="{{ route('ministry') }}" aria-current="page">
            Giáo vụ
          </a>
        </li>
      </ul>
      @if(session('user'))
              <a class="nav-link" href="{{ route('logout') }}">Đăng xuất</a>
      @else
              <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
      @endif
    </div>
  </div>
</nav>
