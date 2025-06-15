@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Bảng điều khiển thống kê</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="#">Trang chủ</a></div>
                <div class="breadcrumb-item">Thống kê</div>
            </div>
        </div>

        <div class="section-body">
            {{-- Cards thống kê --}}
            <div class="row">
                @php
                    $cardClass = 'col-lg-4 col-md-6 col-12 mb-4';
                @endphp

                {{-- Sinh viên --}}
                <div class="{{ $cardClass }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Tổng Sinh Viên</h4>
                            </div>
                            <div class="card-body">
                                {{ $tongSinhVien }}
                                <small class="text-muted d-block">Nam: {{ $tongNam }} | Nữ: {{ $tongNu }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Giáo viên --}}
                <div class="{{ $cardClass }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Tổng Giảng Viên</h4>
                            </div>
                            <div class="card-body">
                                {{ $tongGiaoVien }}
                                <small class="text-muted d-block">Nam: {{ $tongNamGV }} | Nữ: {{ $tongNuGV }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Biểu đồ --}}
            <div class="row">

                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Biểu Đồ Giới Tính Sinh Viên</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartGioiTinh" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
