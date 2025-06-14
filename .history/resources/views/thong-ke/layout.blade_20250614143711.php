@extends('layouts.new_app.master')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>{{ $title }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('about') }}">Bảng Điều Khiển</a></div>
            <div class="breadcrumb-item">{{ $title }}</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-3">
                @include('thong-ke.menu-sidebar')
            </div>
            <div class="col-9">
                <div class="card">
                    <div class="card-body">
                        @yield('thong-ke-content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('custom-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush