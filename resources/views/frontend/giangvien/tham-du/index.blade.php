@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Thống kê tham dự lớp học</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">Giảng viên</div>
            <div class="breadcrumb-item">Tham dự lớp</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Thống kê tổng quan -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Tổng quan các lớp đang giảng dạy</h4>
                    </div>
                    <div class="card-body">
                        @if($thongKeTongQuan->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Lớp học</th>
                                            <th>Tổng SV</th>
                                            <th>Tham dự tốt</th>
                                            <th>Tham dự TB</th>
                                            <th>Tham dự yếu</th>
                                            <th>Tỷ lệ TB</th>
                                            <th>Số môn</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($thongKeTongQuan as $lop)
                                        <tr>
                                            <td>
                                                <strong>{{ $lop['MaLop'] }}</strong><br>
                                                <small>{{ $lop['TenLop'] }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">{{ $lop['tong_sinh_vien'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">{{ $lop['tham_du_tot'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-warning">{{ $lop['tham_du_trung_binh'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-danger">{{ $lop['tham_du_yeu'] }}</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar 
                                                        @if($lop['ty_le_tham_du_tb'] >= 80) bg-success
                                                        @elseif($lop['ty_le_tham_du_tb'] >= 60) bg-warning
                                                        @else bg-danger
                                                        @endif" 
                                                        role="progressbar" 
                                                        style="width: {{ $lop['ty_le_tham_du_tb'] }}%">
                                                        {{ $lop['ty_le_tham_du_tb'] }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $lop['SoMonHoc'] }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('giaovien.thamdu.chitiet-lop', $lop['MaLop']) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Xem chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <h2>Chưa có lớp học</h2>
                                <p class="lead">Bạn chưa được phân công giảng dạy lớp nào.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách lớp học -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Danh sách lớp học</h4>
                    </div>
                    <div class="card-body">
                        @if($danhSachLop->count() > 0)
                            <div class="row">
                                @foreach($danhSachLop as $lop)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4>{{ $lop['MaLop'] }}</h4>
                                            <div class="card-header-action">
                                                <span class="badge badge-primary">{{ $lop['SoMonHoc'] }} môn</span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h6>{{ $lop['TenLop'] }}</h6>
                                            <p class="text-muted">
                                                <i class="fas fa-calendar"></i> 
                                                {{ \Carbon\Carbon::parse($lop['NgayBatDau'])->format('d/m/Y') }} - 
                                                {{ \Carbon\Carbon::parse($lop['NgayKetThuc'])->format('d/m/Y') }}
                                            </p>
                                            <div class="mt-3">
                                                <strong>Môn học:</strong>
                                                <div>
                                                    @foreach($lop['MonHocs'] as $monHoc)
                                                        <span class="badge badge-light mr-1 mt-3">{{ $monHoc->TenMH ?? 'N/A' }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <a href="{{ route('giaovien.thamdu.chitiet-lop', $lop['MaLop']) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-chart-bar"></i> Thống kê tham dự
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <h2>Chưa có lớp học</h2>
                                <p class="lead">Bạn chưa được phân công giảng dạy lớp nào.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-css')
<style>
.empty-state {
    text-align: center;
    padding: 40px 20px;
}

.empty-state-icon {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
    font-size: 0.8rem;
    line-height: 20px;
}
</style>
@endsection
