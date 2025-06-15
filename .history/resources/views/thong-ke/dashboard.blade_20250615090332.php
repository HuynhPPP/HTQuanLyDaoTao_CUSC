@extends('layouts.new_app.master')

@section('main-content')
    <div class="container-fluid">
        {{-- Tiêu đề trang --}}
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="page-title">Bảng Điều Khiển Thống Kê</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thống Kê</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Các card thống kê tổng quan --}}
        <div class="row">
            {{-- Tổng Sinh Viên --}}
            <div class="col-xl-3 col-md-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Tổng Sinh Viên</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $tongSinhVien }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-success mr-2"><i class="fa fa-mars"></i> Nam: {{ $tongNam }}</span>
                            <span class="text-danger"><i class="fa fa-venus"></i> Nữ: {{ $tongNu }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tổng Giảng Viên --}}
            <div class="col-xl-3 col-md-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Tổng Giảng Viên</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $tongGiaoVien }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-success mr-2"><i class="fa fa-mars"></i> Nam: {{ $tongNamGV }}</span>
                            <span class="text-danger"><i class="fa fa-venus"></i> Nữ: {{ $tongNuGV }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Chương Trình Đào Tạo --}}
            <div class="col-xl-3 col-md-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Chương Trình Đào Tạo</h5>
                                <span class="h2 font-weight-bold mb-0">{{ count($sinhVienTheoChuongTrinh) }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                    <i class="fas fa-book-open"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span>Tổng số chương trình đang hoạt động</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tình Trạng Sinh Viên --}}
            <div class="col-xl-3 col-md-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Tình Trạng Sinh Viên</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $tongSinhVien }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            @foreach($tinhTrangSinhVien as $tinhTrang)
                                <span class="mr-2">{{ $tinhTrang->ten_tinh_trang }}: {{ $tinhTrang->so_luong }}</span>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Biểu đồ và bảng chi tiết --}}
        <div class="row mt-4">
            {{-- Biểu đồ Sinh Viên Theo Chương Trình --}}
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Tổng Quan</h6>
                                <h5 class="h3 mb-0">Sinh Viên Theo Chương Trình</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="chartSinhVienTheoChuongTrinh" height="350"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Biểu đồ Tình Trạng Sinh Viên --}}
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Phân Loại</h6>
                                <h5 class="h3 mb-0">Tình Trạng Sinh Viên</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="chartTinhTrangSinhVien" height="350"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chi Tiết Thống Kê --}}
        <div class="row mt-4">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h3 mb-0">Sinh Viên Theo Lớp</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Lớp</th>
                                        <th>Số Lượng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sinhVienTheoLop as $lop)
                                    <tr>
                                        <td>{{ $lop->TenLop }}</td>
                                        <td>{{ $lop->so_luong }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h3 mb-0">Môn Học Theo Chương Trình</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-light">
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
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Biểu đồ Sinh Viên Theo Chương Trình Đào Tạo
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
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số Lượng Sinh Viên'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Sinh Viên Theo Chương Trình Đào Tạo'
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
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Tình Trạng Sinh Viên'
                    }
                }
            }
        });
    </script>
@endsection
