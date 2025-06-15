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
            {{-- Thẻ tổng quan --}}
            <div class="row">
                @php $cardClass = 'col-md-6 col-lg-4 mb-4'; @endphp

                <div class="{{ $cardClass }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary"><i class="fas fa-user-graduate"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Tổng Sinh Viên</h4></div>
                            <div class="card-body">
                                {{ $tongSinhVien }}
                                <small class="text-muted d-block">Nam: {{ $tongNam }} | Nữ: {{ $tongNu }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="{{ $cardClass }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Tổng Giảng Viên</h4></div>
                            <div class="card-body">
                                {{ $tongGiaoVien }}
                                <small class="text-muted d-block">Nam: {{ $tongNamGV }} | Nữ: {{ $tongNuGV }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="{{ $cardClass }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success"><i class="fas fa-star-half-alt"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Điểm Trung Bình</h4></div>
                            <div class="card-body">{{ $diemTrungBinh }}</div>
                        </div>
                    </div>
                </div>

                <div class="{{ $cardClass }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning"><i class="fas fa-percentage"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Tỷ Lệ Đạt</h4></div>
                            <div class="card-body">{{ $tyLeDat }}%</div>
                        </div>
                    </div>
                </div>

                <div class="{{ $cardClass }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger"><i class="fas fa-book"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Tổng Khóa Học</h4></div>
                            <div class="card-body">{{ $tongKhoaHoc }}</div>
                        </div>
                    </div>
                </div>

                <div class="{{ $cardClass }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-secondary"><i class="fas fa-balance-scale"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Điểm TB Khóa</h4></div>
                            <div class="card-body">{{ $diemTrungBinhKhoaHoc }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Biểu đồ thống kê --}}
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header"><h4>Giới Tính Sinh Viên</h4></div>
                        <div class="card-body">
                            <canvas id="chartGioiTinhSinhVien" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header"><h4>Giới Tính Giảng Viên</h4></div>
                        <div class="card-body">
                            <canvas id="chartGioiTinhGiangVien" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header"><h4>Sinh Viên Theo CTĐT</h4></div>
                        <div class="card-body">
                            <canvas id="chartSinhVienTheoChuongTrinh" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header"><h4>Tình Trạng Sinh Viên</h4></div>
                        <div class="card-body">
                            <canvas id="chartTinhTrangSinhVien" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chi tiết bảng thống kê --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h4>Sinh Viên Theo Lớp</h4></div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead><tr><th>Lớp</th><th>Số Lượng</th></tr></thead>
                                <tbody>
                                    @foreach ($sinhVienTheoLop as $lop)
                                        <tr><td>{{ $lop->TenLop }}</td><td>{{ $lop->so_luong }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h4>Môn Học Theo CTĐT</h4></div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead><tr><th>Chương Trình</th><th>Số Môn Học</th></tr></thead>
                                <tbody>
                                    @foreach ($monHocTheoChuongTrinh as $ct)
                                        <tr><td>{{ $ct->TenChuongTrinh }}</td><td>{{ $ct->so_mon_hoc }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h4>Tình Trạng Sinh Viên</h4></div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead><tr><th>Tình Trạng</th><th>Số Lượng</th></tr></thead>
                                <tbody>
                                    @foreach ($tinhTrangSinhVien as $tinhTrang)
                                        <tr><td>{{ $tinhTrang->ten_tinh_trang }}</td><td>{{ $tinhTrang->so_luong }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
