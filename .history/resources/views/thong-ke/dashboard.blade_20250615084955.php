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
                                            @foreach($sinhVienTheoLop as $lop)
                                            <tr>
                                                <td>{{ $lop->ten_lop }}</td>
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
                                            @foreach($monHocTheoChuongTrinh as $chuongTrinh)
                                            <tr>
                                                <td>{{ $chuongTrinh->ten_chuong_trinh }}</td>
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
        </div>
    </section>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
