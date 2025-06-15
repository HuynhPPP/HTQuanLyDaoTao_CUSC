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
                {{-- Biểu đồ Giới Tính Sinh Viên --}}
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Biểu Đồ Giới Tính Sinh Viên</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartGioiTinhSinhVien" height="250"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Biểu đồ Giới Tính Giảng Viên --}}
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Biểu Đồ Giới Tính Giảng Viên</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartGioiTinhGiangVien" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thống kê chi tiết --}}
            <div class="row">
                {{-- Sinh Viên Theo Chương Trình Đào Tạo --}}
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Sinh Viên Theo Chương Trình Đào Tạo</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartSinhVienTheoChuongTrinh" height="250"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Tình Trạng Sinh Viên --}}
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tình Trạng Sinh Viên</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartTinhTrangSinhVien" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thống kê chi tiết --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Chi Tiết Thống Kê</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Sinh Viên Theo Lớp</h5>
                                    <table class="table table-striped">
                                        <thead>
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
                                <div class="col-md-6">
                                    <h5>Môn Học Theo Chương Trình</h5>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Tình Trạng Sinh Viên</h5>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tình Trạng</th>
                                                <th>Số Lượng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tinhTrangSinhVien as $tinhTrang)
                                                <tr>
                                                    <td>{{ $tinhTrang->ten_tinh_trang }}</td>
                                                    <td>{{ $tinhTrang->so_luong }}</td>
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
        </div>
    </section>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('custom-js')
    <script>
        // Biểu đồ Giới Tính Sinh Viên
        var ctxGioiTinhSinhVien = document.getElementById('chartGioiTinhSinhVien').getContext('2d');
        new Chart(ctxGioiTinhSinhVien, {
            type: 'pie',
            data: {
                labels: ['Nam', 'Nữ'],
                datasets: [{
                    data: [{{ $tongNam }}, {{ $tongNu }}],
                    backgroundColor: ['#007bff', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Tỷ Lệ Giới Tính Sinh Viên'
                }
            }
        });

        // Biểu đồ Giới Tính Giảng Viên
        var ctxGioiTinhGiangVien = document.getElementById('chartGioiTinhGiangVien').getContext('2d');
        new Chart(ctxGioiTinhGiangVien, {
            type: 'pie',
            data: {
                labels: ['Nam', 'Nữ'],
                datasets: [{
                    data: [{{ $tongNamGV }}, {{ $tongNuGV }}],
                    backgroundColor: ['#007bff', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Tỷ Lệ Giới Tính Giảng Viên'
                }
            }
        });

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
                    backgroundColor: 'rgba(54, 162, 235, 0.6)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                title: {
                    display: true,
                    text: 'Sinh Viên Theo Chương Trình Đào Tạo'
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
                title: {
                    display: true,
                    text: 'Tình Trạng Sinh Viên'
                }
            }
        });
    </script>
@endsection
