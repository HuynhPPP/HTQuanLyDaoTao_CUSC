@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Thống kê tham dự - {{ $lopHoc->MaLop }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('giaovien.thamdu.index') }}">Tham dự lớp</a>
            </div>
            <div class="breadcrumb-item active">{{ $lopHoc->MaLop }}</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Thông tin lớp học -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Thông tin lớp học</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Mã lớp:</strong></td>
                                        <td>{{ $lopHoc->MaLop }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tên lớp:</strong></td>
                                        <td>{{ $lopHoc->TenLop }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ngày bắt đầu:</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($lopHoc->NgayBatDau)->format('d/m/Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Tổng sinh viên:</strong></td>
                                        <td><span class="badge badge-primary">{{ $thongKeTongQuan['tong_sinh_vien'] }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tỷ lệ tham dự TB:</strong></td>
                                        <td>
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar 
                                                    @if($thongKeTongQuan['ty_le_tham_du_tb'] >= 80) bg-success
                                                    @elseif($thongKeTongQuan['ty_le_tham_du_tb'] >= 60) bg-warning
                                                    @else bg-danger
                                                    @endif" 
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

        <!-- Bộ lọc -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Bộ lọc</h4>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('giaovien.thamdu.chitiet-lop', $lopHoc->MaLop) }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Môn học</label>
                                        <select name="maMH" class="form-control">
                                            <option value="">Tất cả môn học</option>
                                            @foreach($monHocs as $monHoc)
                                                <option value="{{ $monHoc->MaMH }}" 
                                                    {{ $maMH == $monHoc->MaMH ? 'selected' : '' }}>
                                                    {{ $monHoc->TenMH }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-filter"></i> Lọc
                                            </button>
                                            <a href="{{ route('giaovien.thamdu.chitiet-lop', $lopHoc->MaLop) }}" 
                                               class="btn btn-secondary">
                                                <i class="fas fa-times"></i> Xóa lọc
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ thống kê -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Biểu đồ tham dự</h4>
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
                            <a href="{{ route('giaovien.thamdu.xuat-bao-cao', $lopHoc->MaLop) }}?maMH={{ $maMH }}" 
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
                                        <th>Tổng buổi học</th>
                                        @if($maMH)
                                            <th>Điểm môn</th>
                                        @endif
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($thongKeThamDu as $item)
                                    <tr class="@if($item['TyLeThamDu'] >= 80) table-success @elseif($item['TyLeThamDu'] >= 60) table-warning @else table-danger @endif">
                                        <td><strong>{{ $item['MaSV'] }}</strong></td>
                                        <td>{{ $item['HoTen'] }}</td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar 
                                                    @if($item['TyLeThamDu'] >= 80) bg-success
                                                    @elseif($item['TyLeThamDu'] >= 60) bg-warning
                                                    @else bg-danger
                                                    @endif" 
                                                    role="progressbar" 
                                                    style="width: {{ $item['TyLeThamDu'] }}%">
                                                    {{ $item['TyLeThamDu'] }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($item['XepLoaiThamDu'] == 'Xuất sắc')
                                                <span class="badge badge-success">{{ $item['XepLoaiThamDu'] }}</span>
                                            @elseif($item['XepLoaiThamDu'] == 'Tốt')
                                                <span class="badge badge-primary">{{ $item['XepLoaiThamDu'] }}</span>
                                            @elseif($item['XepLoaiThamDu'] == 'Khá')
                                                <span class="badge badge-info">{{ $item['XepLoaiThamDu'] }}</span>
                                            @elseif($item['XepLoaiThamDu'] == 'Trung bình')
                                                <span class="badge badge-warning">{{ $item['XepLoaiThamDu'] }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ $item['XepLoaiThamDu'] }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $item['SoLanCoDiem'] ?? 0 }}</td>
                                        <td>{{ $item['TongSoBuoiHoc'] ?? 0 }}</td>
                                        @if($maMH)
                                            <td>
                                                @if(isset($item['DiemTong']) && $item['DiemTong'] !== null)
                                                    <span class="badge 
                                                        @if($item['DiemTong'] >= 8.5) badge-success
                                                        @elseif($item['DiemTong'] >= 7) badge-primary
                                                        @elseif($item['DiemTong'] >= 5) badge-warning
                                                        @else badge-danger
                                                        @endif">
                                                        {{ $item['DiemTong'] }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Chưa có điểm</span>
                                                @endif
                                            </td>
                                        @endif
                                        <td>
                                            <a href="{{ route('giaovien.thamdu.chitiet-sinhvien', [$lopHoc->MaLop, $item['MaSV']]) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Chi tiết
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
        url: '{{ route("giaovien.thamdu.api-thong-ke") }}',
        method: 'GET',
        data: {
            maLop: '{{ $lopHoc->MaLop }}',
            maMH: '{{ $maMH }}'
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
