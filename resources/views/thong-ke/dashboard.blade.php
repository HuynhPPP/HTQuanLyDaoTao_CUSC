@extends('layouts.new_app.master')

@section('page-title', 'Bảng Điều Khiển Thống Kê')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Bảng thống kê</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item active">Bảng thống kê</div>
            </div>
        </div>

        <div class="section-body">
            {{-- Các card thống kê tổng quan --}}
            <div class="row">
                {{-- Tổng Sinh Viên --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Số lượng sinh viên</h4>
                            </div>
                            <div class="card-body">
                                {{ $tongSinhVien }}
                                <small class="text-muted d-block">Nam: {{ $tongNam }} | Nữ: {{ $tongNu }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tổng Giảng Viên --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Số lượng giảng viên</h4>
                            </div>
                            <div class="card-body">
                                {{ $tongGiaoVien }}
                                <small class="text-muted d-block">Nam: {{ $tongNamGV }} | Nữ: {{ $tongNuGV }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Chương Trình Đào Tạo --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Chương trình đào tạo</h4>
                            </div>
                            <div class="card-body">
                                {{ count($SoChuongTrinhDaoTao) }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tổng số môn học --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Môn học</h4>
                            </div>
                            <div class="card-body">
                                {{ count($SoMonHoc) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Biểu đồ thống kê --}}
            <div class="row">
                {{-- Sinh Viên Theo Chương Trình --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Số lượng sinh viên thuộc lớp</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartSinhVienTheoChuongTrinh" height="250"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Tình Trạng Sinh Viên --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Thống kê tình trạng học tập sinh viên</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartTinhTrangSinhVien" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Widget Đánh Giá Học Tập --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>📊 Đánh giá kết quả học tập</h4>
                            <a href="{{ route('ranking.index') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-chart-bar"></i> Xem chi tiết
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="card border-left-primary">
                                        <div class="card-body text-center">
                                            <i class="fas fa-graduation-cap fa-2x text-primary mb-2"></i>
                                            <h5>Đánh giá theo lớp</h5>
                                            <p class="text-muted">Xem kết quả học tập từng lớp</p>
                                            <a href="{{ route('ranking.index') }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-list"></i> Chọn lớp
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="card border-left-success">
                                        <div class="card-body text-center">
                                            <i class="fas fa-star fa-2x text-success mb-2"></i>
                                            <h5>Sinh viên xuất sắc</h5>
                                            <p class="text-muted">Danh sách sinh viên có thành tích tốt</p>
                                            <a href="{{ route('ranking.top') }}" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-chart-line"></i> Xem danh sách
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="card border-left-info">
                                        <div class="card-body text-center">
                                            <i class="fas fa-balance-scale fa-2x text-info mb-2"></i>
                                            <h5>So sánh hiệu suất</h5>
                                            <p class="text-muted">Phân tích hiệu suất giữa các lớp</p>
                                            <a href="{{ route('ranking.so-sanh-lop') }}" class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-analytics"></i> So sánh
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chi Tiết Thống Kê --}}
            <div class="row">

                {{-- Môn Học Theo Chương Trình --}}
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Thống kê môn học thuộc chương trình đào tạo</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Chương Trình</th>
                                            <th>Số Môn Học</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($monHocTheoChuongTrinh as $chuongTrinh)
                                        <tr>
                                            <td>{{ $chuongTrinh->TenChuongTrinh }}</td>
                                            <td>{{ $chuongTrinh->so_mon_hoc }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Biểu đồ Sinh Viên Theo Chương Trình
        var ctxSinhVienTheoChuongTrinh = document.getElementById('chartSinhVienTheoChuongTrinh').getContext('2d');
        new Chart(ctxSinhVienTheoChuongTrinh, {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($sinhVienTheoChuongTrinh as $chuongTrinh)
                        "{{ $chuongTrinh->TenChuongTrinh }}",
                    @endforeach
                ],
                datasets: [{
                    label: 'Số Lượng Sinh Viên',
                    data: [
                        @foreach ($sinhVienTheoChuongTrinh as $chuongTrinh)
                            {{ $chuongTrinh->so_luong }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Biểu đồ Tình Trạng Sinh Viên
        var ctxTinhTrangSinhVien = document.getElementById('chartTinhTrangSinhVien').getContext('2d');
        new Chart(ctxTinhTrangSinhVien, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach ($tinhTrangSinhVien as $tinhTrang)
                        "{{ $tinhTrang->ten_tinh_trang }}",
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach ($tinhTrangSinhVien as $tinhTrang)
                            {{ $tinhTrang->so_luong }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#28a745', '#dc3545', '#ffc107', '#17a2b8'
                    ]
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
@endsection
