@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Thống kê tham dự môn học</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('giaovien.thamdu.index') }}">Tham dự lớp</a>
                </div>
                <div class="breadcrumb-item">
                    <a href="{{ route('giaovien.thamdu.chitiet-lop', $lopHoc->MaLop) }}">{{ $lopHoc->MaLop }}</a>
                </div>
                <div class="breadcrumb-item active">{{ $monHoc->TenMH }}</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Thông tin môn học -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Thông tin môn học</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Mã môn học:</strong></td>
                                            <td>{{ $monHoc->MaMH }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tên môn học:</strong></td>
                                            <td>{{ $monHoc->TenMH }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Lớp học:</strong></td>
                                            <td>{{ $lopHoc->MaLop }} - {{ $lopHoc->TenLop }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Tổng sinh viên:</strong></td>
                                            <td><span
                                                    class="badge badge-primary">{{ $thongKeTongQuan['tong_sinh_vien'] }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tỷ lệ tham dự TB:</strong></td>
                                            <td>
                                                <div class="progress" style="height: 25px;">
                                                    <div class="progress-bar 
                                                    @if ($thongKeTongQuan['ty_le_tham_du_tb'] >= 80) bg-success
                                                    @elseif($thongKeTongQuan['ty_le_tham_du_tb'] >= 60) bg-warning
                                                    @else bg-danger @endif"
                                                        role="progressbar"
                                                        style="width: {{ $thongKeTongQuan['ty_le_tham_du_tb'] }}%">
                                                        {{ $thongKeTongQuan['ty_le_tham_du_tb'] }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê tổng quan -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Tham dự tốt</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKeTongQuan['tham_du_tot'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Tham dự TB</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKeTongQuan['tham_du_trung_binh'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Tham dự yếu</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKeTongQuan['tham_du_yeu'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Tổng sinh viên</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKeTongQuan['tong_sinh_vien'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ thống kê -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Biểu đồ tham dự môn học</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="attendanceChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bảng chi tiết -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Chi tiết tham dự sinh viên</h4>
                            <div class="card-header-action">
                                <a href="{{ route('giaovien.thamdu.xuat-bao-cao', $lopHoc->MaLop) }}?maMH={{ $monHoc->MaMH }}"
                                    class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Xuất Excel
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="attendanceTable">
                                    <thead>
                                        <tr>
                                            <th>Mã SV</th>
                                            <th>Họ tên</th>
                                            <th>Tỷ lệ tham dự</th>
                                            <th>Xếp loại</th>
                                            <th>Số lần có điểm</th>
                                            <th>Điểm môn</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($thongKeThamDu as $item)
                                            <tr
                                                class="@if ($item['TyLeThamDu'] >= 80) table-success @elseif($item['TyLeThamDu'] >= 60) table-warning @else table-danger @endif">
                                                <td><strong>{{ $item['MaSV'] }}</strong></td>
                                                <td>{{ $item['HoTen'] }}</td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar 
                                                    @if ($item['TyLeThamDu'] >= 80) bg-success
                                                    @elseif($item['TyLeThamDu'] >= 60) bg-warning
                                                    @else bg-danger @endif"
                                                            role="progressbar" style="width: {{ $item['TyLeThamDu'] }}%">
                                                            {{ $item['TyLeThamDu'] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($item['XepLoaiThamDu'] == 'Xuất sắc')
                                                        <span
                                                            class="badge badge-success">{{ $item['XepLoaiThamDu'] }}</span>
                                                    @elseif($item['XepLoaiThamDu'] == 'Tốt')
                                                        <span
                                                            class="badge badge-primary">{{ $item['XepLoaiThamDu'] }}</span>
                                                    @elseif($item['XepLoaiThamDu'] == 'Khá')
                                                        <span class="badge badge-info">{{ $item['XepLoaiThamDu'] }}</span>
                                                    @elseif($item['XepLoaiThamDu'] == 'Trung bình')
                                                        <span
                                                            class="badge badge-warning">{{ $item['XepLoaiThamDu'] }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-danger">{{ $item['XepLoaiThamDu'] }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item['SoLanCoDiem'] ?? 0 }}</td>
                                                <td>
                                                    @if ($item['DiemTong'] !== null)
                                                        <span
                                                            class="badge 
                                                    @if ($item['DiemTong'] >= 8.5) badge-success
                                                    @elseif($item['DiemTong'] >= 7) badge-primary
                                                    @elseif($item['DiemTong'] >= 5) badge-warning
                                                    @else badge-danger @endif">
                                                            {{ $item['DiemTong'] }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">Chưa có điểm</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('giaovien.thamdu.chitiet-sinhvien', [$lopHoc->MaLop, $item['MaSV']]) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> Chi tiết SV
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phân tích điểm số -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Phân tích điểm số</h4>
                        </div>
                        <div class="card-body">
                            @php
                                $diemCao = $thongKeThamDu->where('DiemTong', '>=', 8.5)->count();
                                $diemKha = $thongKeThamDu
                                    ->where('DiemTong', '>=', 7)
                                    ->where('DiemTong', '<', 8.5)
                                    ->count();
                                $diemTB = $thongKeThamDu
                                    ->where('DiemTong', '>=', 5)
                                    ->where('DiemTong', '<', 7)
                                    ->count();
                                $diemYeu = $thongKeThamDu->where('DiemTong', '<', 5)->whereNotNull('DiemTong')->count();
                                $chuaCoDiem = $thongKeThamDu->whereNull('DiemTong')->count();
                                $diemTrungBinh = $thongKeThamDu->whereNotNull('DiemTong')->avg('DiemTong');
                            @endphp

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Phân bố điểm số:</h6>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Điểm cao (8.5-10):</span>
                                            <span class="">{{ $diemCao }}</span>
                                        </div>
                                        <div class="progress mb-2" style="height: 20px;">
                                            <div class="progress-bar bg-success"
                                                style="width: {{ $thongKeThamDu->count() > 0 ? ($diemCao / $thongKeThamDu->count()) * 100 : 0 }}%">
                                                {{ $thongKeThamDu->count() > 0 ? round(($diemCao / $thongKeThamDu->count()) * 100, 1) : 0 }}%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Điểm khá (7-8.4):</span>
                                            <span>{{ $diemKha }}</span>
                                        </div>
                                        <div class="progress mb-2" style="height: 20px;">
                                            <div class="progress-bar bg-primary"
                                                style="width: {{ $thongKeThamDu->count() > 0 ? ($diemKha / $thongKeThamDu->count()) * 100 : 0 }}%">
                                                {{ $thongKeThamDu->count() > 0 ? round(($diemKha / $thongKeThamDu->count()) * 100, 1) : 0 }}%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Điểm TB (5-6.9):</span>
                                            <span>{{ $diemTB }}</span>
                                        </div>
                                        <div class="progress mb-2" style="height: 20px;">
                                            <div class="progress-bar bg-warning"
                                                style="width: {{ $thongKeThamDu->count() > 0 ? ($diemTB / $thongKeThamDu->count()) * 100 : 0 }}%">
                                                {{ $thongKeThamDu->count() > 0 ? round(($diemTB / $thongKeThamDu->count()) * 100, 1) : 0 }}%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Điểm yếu (<5):< /span>
                                                    <span class="">{{ $diemYeu }}</span>
                                        </div>
                                        <div class="progress mb-2" style="height: 20px;">
                                            <div class="progress-bar bg-danger"
                                                style="width: {{ $thongKeThamDu->count() > 0 ? ($diemYeu / $thongKeThamDu->count()) * 100 : 0 }}%">
                                                {{ $thongKeThamDu->count() > 0 ? round(($diemYeu / $thongKeThamDu->count()) * 100, 1) : 0 }}%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Chưa có điểm:</span>
                                            <span class="">{{ $chuaCoDiem }}</span>
                                        </div>
                                        <div class="progress mb-2" style="height: 20px;">
                                            <div class="progress-bar bg-secondary"
                                                style="width: {{ $thongKeThamDu->count() > 0 ? ($chuaCoDiem / $thongKeThamDu->count()) * 100 : 0 }}%">
                                                {{ $thongKeThamDu->count() > 0 ? round(($chuaCoDiem / $thongKeThamDu->count()) * 100, 1) : 0 }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6>Thống kê tổng quan:</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Điểm trung bình:</strong></td>
                                            <td>
                                                @if ($diemTrungBinh)
                                                    <span
                                                        @if ($diemTrungBinh >= 8.5) @elseif($diemTrungBinh >= 7) 
                                                    @elseif($diemTrungBinh >= 5) @endif>
                                                        {{ round($diemTrungBinh, 2) }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Chưa có điểm</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tỷ lệ đạt:</strong></td>
                                            <td>
                                                @php
                                                    $tyLeDat = $thongKeThamDu
                                                        ->where('DiemTong', '>=', 5)
                                                        ->whereNotNull('DiemTong')
                                                        ->count();
                                                    $tongCoDiem = $thongKeThamDu->whereNotNull('DiemTong')->count();
                                                    $tyLeDatPhanTram =
                                                        $tongCoDiem > 0 ? ($tyLeDat / $tongCoDiem) * 100 : 0;
                                                @endphp
                                                <span
                                                    @if ($tyLeDatPhanTram >= 80) @elseif($tyLeDatPhanTram >= 60) 
                                                @else badge-danger @endif>
                                                    {{ round($tyLeDatPhanTram, 1) }}%
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tỷ lệ tham dự TB:</strong></td>
                                            <td>
                                                <span
                                                    @if ($thongKeTongQuan['ty_le_tham_du_tb'] >= 80) @elseif($thongKeTongQuan['ty_le_tham_du_tb'] >= 60) @endif>
                                                    {{ $thongKeTongQuan['ty_le_tham_du_tb'] }}%
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
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
        $(document).ready(function() {
            // Tạo biểu đồ
            var ctx = document.getElementById('attendanceChart').getContext('2d');

            // Lấy dữ liệu từ API
            $.ajax({
                url: '{{ route('giaovien.thamdu.api-thong-ke') }}',
                method: 'GET',
                data: {
                    maLop: '{{ $lopHoc->MaLop }}',
                    maMH: '{{ $monHoc->MaMH }}'
                },
                success: function(response) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: response,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        callback: function(value) {
                                            return value + '%';
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Tham dự: ' + context.parsed.y + '%';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
