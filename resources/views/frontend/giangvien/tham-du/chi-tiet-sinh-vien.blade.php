@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Chi tiết tham dự sinh viên</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('giaovien.thamdu.index') }}">Tham dự lớp</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{ route('giaovien.thamdu.chitiet-lop', $lopHoc->MaLop) }}">{{ $lopHoc->MaLop }}</a>
            </div>
            <div class="breadcrumb-item active">{{ $sinhVien->MaSV }}</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Thông tin sinh viên -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Thông tin sinh viên</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Mã sinh viên:</strong></td>
                                        <td>{{ $sinhVien->MaSV }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Họ tên:</strong></td>
                                        <td>{{ $sinhVien->HoTen }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $sinhVien->Email ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Lớp học:</strong></td>
                                        <td>{{ $lopHoc->MaLop }} - {{ $lopHoc->TenLop }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tỷ lệ tham dự:</strong></td>
                                        <td>
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar 
                                                    @if($chiTietThamDu['ty_le_tham_du'] >= 80) bg-success
                                                    @elseif($chiTietThamDu['ty_le_tham_du'] >= 60) bg-warning
                                                    @else bg-danger
                                                    @endif" 
                                                    role="progressbar" 
                                                    style="width: {{ $chiTietThamDu['ty_le_tham_du'] }}%">
                                                    {{ $chiTietThamDu['ty_le_tham_du'] }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Xếp loại:</strong></td>
                                        <td>
                                            @if($chiTietThamDu['xep_loai_tham_du'] == 'Xuất sắc')
                                                <span class="badge badge-success">{{ $chiTietThamDu['xep_loai_tham_du'] }}</span>
                                            @elseif($chiTietThamDu['xep_loai_tham_du'] == 'Tốt')
                                                <span class="badge badge-primary">{{ $chiTietThamDu['xep_loai_tham_du'] }}</span>
                                            @elseif($chiTietThamDu['xep_loai_tham_du'] == 'Khá')
                                                <span class="badge badge-info">{{ $chiTietThamDu['xep_loai_tham_du'] }}</span>
                                            @elseif($chiTietThamDu['xep_loai_tham_du'] == 'Trung bình')
                                                <span class="badge badge-warning">{{ $chiTietThamDu['xep_loai_tham_du'] }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ $chiTietThamDu['xep_loai_tham_du'] }}</span>
                                            @endif
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
                    <div class="card-icon bg-info">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Số lần có điểm</h4>
                        </div>
                        <div class="card-body">
                            {{ $chiTietThamDu['so_lan_co_diem'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Tổng buổi học</h4>
                        </div>
                        <div class="card-body">
                            {{ $chiTietThamDu['so_buoi_hoc_du_kien'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Tỷ lệ tham dự</h4>
                        </div>
                        <div class="card-body">
                            {{ $chiTietThamDu['ty_le_tham_du'] }}%
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Số môn học</h4>
                        </div>
                        <div class="card-body">
                            {{ $monHocs->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lịch sử điểm -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Lịch sử điểm số</h4>
                    </div>
                    <div class="card-body">
                        @if($chiTietThamDu['lich_su_diem']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Môn học</th>
                                            <th>Điểm tổng</th>
                                            <th>Điểm lý thuyết</th>
                                            <th>Điểm thực hành</th>
                                            <th>Điểm dự án</th>
                                            <th>Ngày cập nhật</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($chiTietThamDu['lich_su_diem'] as $diem)
                                        <tr>
                                            <td>
                                                <strong>{{ $diem->monHoc->TenMH ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $diem->MaMH }}</small>
                                            </td>
                                            <td>
                                                @if($diem->DiemTong !== null)
                                                    <span class="badge 
                                                        @if($diem->DiemTong >= 8.5) badge-success
                                                        @elseif($diem->DiemTong >= 7) badge-primary
                                                        @elseif($diem->DiemTong >= 5) badge-warning
                                                        @else badge-danger
                                                        @endif">
                                                        {{ $diem->DiemTong }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Chưa có điểm</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($diem->DiemLyThuyet !== null)
                                                    <span class="badge badge-info">{{ $diem->DiemLyThuyet }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($diem->DiemThucHanh !== null)
                                                    <span class="badge badge-info">{{ $diem->DiemThucHanh }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($diem->DiemDuAn !== null)
                                                    <span class="badge badge-info">{{ $diem->DiemDuAn }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ \Carbon\Carbon::parse($diem->updated_at)->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $diem->GhiChu ?? '-' }}</small>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h2>Chưa có điểm số</h2>
                                <p class="lead">Sinh viên chưa có điểm số nào trong lớp học này.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Lịch học -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Lịch học lớp</h4>
                    </div>
                    <div class="card-body">
                        @if($chiTietThamDu['lich_hoc']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tên TKB</th>
                                            <th>Học kỳ</th>
                                            <th>Ngày học</th>
                                            <th>Loại ngày</th>
                                            <th>Phiên bản</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($chiTietThamDu['lich_hoc'] as $lich)
                                        <tr>
                                            <td>{{ $lich->TenTKB }}</td>
                                            <td>{{ $lich->MaHK }}</td>
                                            <td>{{ \Carbon\Carbon::parse($lich->NgayHoc)->format('d/m/Y') }}</td>
                                            <td>{{ $lich->ngayHocType ?? '-' }}</td>
                                            <td>{{ $lich->NgayPhienBan ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <h2>Chưa có lịch học</h2>
                                <p class="lead">Lớp học chưa có lịch học nào được thiết lập.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Môn học của sinh viên -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Môn học của sinh viên</h4>
                    </div>
                    <div class="card-body">
                        @if($monHocs->count() > 0)
                            <div class="row">
                                @foreach($monHocs as $monHoc)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card card-primary">
                                        <div class="card-body">
                                            <h6>{{ $monHoc->TenMH }}</h6>
                                            <p class="text-muted">{{ $monHoc->MaMH }}</p>
                                            <a href="{{ route('giaovien.thamdu.chitiet-mon-hoc', [$lopHoc->MaLop, $monHoc->MaMH]) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Xem thống kê môn
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <h2>Chưa có môn học</h2>
                                <p class="lead">Sinh viên chưa có môn học nào trong lớp này.</p>
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
    line-height: 25px;
}
</style>
@endsection
