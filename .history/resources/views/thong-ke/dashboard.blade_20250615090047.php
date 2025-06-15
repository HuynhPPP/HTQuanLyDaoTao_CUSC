<!-- resources/views/dashboard/modern_dashboard.blade.php -->
@extends('layouts.new_app.master')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1 class="text-2xl font-semibold">Bảng Điều Khiển Thống Kê</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="#">Trang chủ</a></div>
            <div class="breadcrumb-item">Thống kê</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Cards Thống Kê Tổng Quan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <x-dashboard.stat-card icon="fas fa-user-graduate" title="Tổng Sinh Viên" :value="$tongSinhVien" :subtitle="'Nam: '.$tongNam.' | Nữ: '.$tongNu" color="primary" />
            <x-dashboard.stat-card icon="fas fa-chalkboard-teacher" title="Tổng Giảng Viên" :value="$tongGiaoVien" :subtitle="'Nam: '.$tongNamGV.' | Nữ: '.$tongNuGV" color="info" />
            <x-dashboard.stat-card icon="fas fa-book" title="Tổng Khóa Học" :value="$tongKhoaHoc" :subtitle="'Điểm TB: '.$diemTrungBinhKhoaHoc" color="warning" />
            <x-dashboard.stat-card icon="fas fa-chart-line" title="Điểm Trung Bình" :value="$diemTrungBinh" :subtitle="'Tỷ lệ đạt: '.$tyLeDat.'%'" color="success" />
        </div>

        <!-- Tabs Biểu Đồ -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-gender-tab" data-toggle="tab" href="#tab-gender" role="tab">Giới tính</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-program-tab" data-toggle="tab" href="#tab-program" role="tab">Chương trình</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-status-tab" data-toggle="tab" href="#tab-status" role="tab">Tình trạng</a>
                    </li>
                </ul>
            </div>
            <div class="card-body tab-content">
                <div class="tab-pane fade show active" id="tab-gender" role="tabpanel">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <canvas id="chartGioiTinhSinhVien" height="200"></canvas>
                        <canvas id="chartGioiTinhGiangVien" height="200"></canvas>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-program" role="tabpanel">
                    <canvas id="chartSinhVienTheoChuongTrinh" height="250"></canvas>
                </div>
                <div class="tab-pane fade" id="tab-status" role="tabpanel">
                    <canvas id="chartTinhTrangSinhVien" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Bảng Thống Kê Chi Tiết -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <x-dashboard.data-table title="Sinh Viên Theo Lớp" :headers="['Lớp', 'Số Lượng']" :rows="$sinhVienTheoLop->map(fn($row) => [$row->TenLop, $row->so_luong])" />
            <x-dashboard.data-table title="Môn Học Theo Chương Trình" :headers="['Chương Trình', 'Số Môn Học']" :rows="$monHocTheoChuongTrinh->map(fn($row) => [$row->TenChuongTrinh, $row->so_mon_hoc])" />
        </div>

        <div class="mt-6">
            <x-dashboard.data-table title="Tình Trạng Sinh Viên" :headers="['Tình Trạng', 'Số Lượng']" :rows="$tinhTrangSinhVien->map(fn($row) => [$row->ten_tinh_trang, $row->so_luong])" />
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@include('dashboard.scripts.charts')
@endsection

