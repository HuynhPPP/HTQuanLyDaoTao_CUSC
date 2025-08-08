@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Tiến trình học tập - {{ $sinhVien->HoTen }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('student.list') }}">Danh sách sinh viên</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('student.show', $sinhVien->MaSV) }}">Thông tin sinh viên</a></div>
                <div class="breadcrumb-item">Tiến trình học tập</div>
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
                                            <td><strong>Họ và tên:</strong></td>
                                            <td>{{ $sinhVien->HoTen }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $sinhVien->Email }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Ngày sinh:</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($sinhVien->NgaySinh)->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Giới tính:</strong></td>
                                            <td>{{ $sinhVien->GioiTinh == 1 ? 'Nam' : 'Nữ' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Số điện thoại:</strong></td>
                                            <td>{{ $sinhVien->Sdt }}</td>
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
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Tổng môn học</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKe['tongMonHoc'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Đã hoàn thành</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKe['monDaHoanThanh'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Đang học</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKe['monDangHoc'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Điểm TB</h4>
                            </div>
                            <div class="card-body">
                                {{ number_format($thongKe['diemTrungBinh'], 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Xếp loại học lực -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-trophy"></i> Xếp loại học lực</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <strong><i class="fas fa-chart-line"></i> Xếp loại hiện tại:</strong> 
                                @php
                                    $xepLoaiColors = [
                                        'Giỏi' => 'success',
                                        'Khá' => 'info',
                                        'Trung bình' => 'warning',
                                        'Yếu' => 'danger',
                                        'Kém' => 'dark',
                                        'Chưa xếp loại' => 'secondary',
                                        'Đạt' => 'primary'
                                    ];
                                    $xepLoaiIcons = [
                                        'Giỏi' => 'fas fa-star',
                                        'Khá' => 'fas fa-star-half-alt',
                                        'Trung bình' => 'fas fa-star',
                                        'Yếu' => 'fas fa-exclamation-triangle',
                                        'Kém' => 'fas fa-times-circle',
                                        'Chưa xếp loại' => 'fas fa-question-circle',
                                        'Đạt' => 'fas fa-check-circle'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $xepLoaiColors[$thongKe['xepLoai']] ?? 'secondary' }}">
                                    <i class="{{ $xepLoaiIcons[$thongKe['xepLoai']] ?? 'fas fa-question-circle' }}"></i>
                                    {{ $thongKe['xepLoai'] }}
                                </span>
                            </div>
                            
                            <!-- Thông tin tiêu chí xếp loại -->
                            @if($tienTrinh->isNotEmpty() && $tienTrinh->first()->MaChuongTrinh)
                                <div class="alert alert-warning">
                                    <strong><i class="fas fa-info-circle"></i> Tiêu chí xếp loại:</strong>
                                    <small class="d-block mt-2">
                                        Xếp loại được tính dựa trên tiêu chí của chương trình đào tạo 
                                        <strong>{{ $tienTrinh->first()->MaChuongTrinh }}</strong>
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bảng tiến trình học tập -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-list-alt"></i> Chi tiết tiến trình học tập</h4>
                            <div class="card-header-action">
                                <button class="btn btn-primary" onclick="window.print()">
                                    <i class="fas fa-print"></i> In báo cáo
                                </button>
                                <button class="btn btn-info ml-2" onclick="exportToExcel()">
                                    <i class="fas fa-file-excel"></i> Xuất Excel
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="table-1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Môn học</th>
                                            <th>Lớp</th>
                                            <th>Điểm lý thuyết</th>
                                            <th>Điểm thực hành</th>
                                            <th>Điểm dự án</th>
                                            <th>Điểm tổng</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày hoàn thành</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tienTrinh as $index => $item)
                                            <tr>
                                                <td>
                                                    <strong>{{ $item->monHoc->TenMH ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted">{{ $item->MaMH }}</small>
                                                </td>
                                                <td>{{ $item->lopHoc->MaLop ?? 'N/A' }}</td>
                                                <td>
                                                    @if($item->DiemLyThuyet !== null)
                                                        {{ number_format($item->DiemLyThuyet, 2) }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->DiemThucHanh !== null)
                                                        {{ number_format($item->DiemThucHanh, 2) }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->DiemDuAn !== null)
                                                        {{ number_format($item->DiemDuAn, 2) }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->DiemTong !== null)
                                                        @php
                                                            $isDat = $item->DiemTong >= 5.0;
                                                            $diemClass = $isDat ? 'success' : 'danger';
                                                            $diemIcon = $isDat ? 'fas fa-check' : 'fas fa-times';
                                                        @endphp
                                                        <span class="badge badge-{{ $diemClass }}">
                                                            <i class="{{ $diemIcon }}"></i>
                                                            {{ number_format($item->DiemTong, 2) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">
                                                            <i class="fas fa-minus"></i>
                                                            -
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $trangThaiColors = [
                                                            'ChuaDangKy' => 'warning',
                                                            'DangKy' => 'secondary',
                                                            'DangHoc' => 'info',
                                                            'DaHoanThanh' => 'success',
                                                            'ChuaHoanThanh' => 'danger'
                                                        ];
                                                        $trangThaiTexts = [
                                                            'ChuaDangKy' => 'Chưa đăng ký',
                                                            'DangKy' => 'Đã đăng ký',
                                                            'DangHoc' => 'Đang học',
                                                            'DaHoanThanh' => 'Đã hoàn thành',
                                                            'ChuaHoanThanh' => 'Chưa hoàn thành'
                                                        ];
                                                        $trangThaiIcons = [
                                                            'ChuaDangKy' => 'fas fa-exclamation-triangle',
                                                            'DangKy' => 'fas fa-user-plus',
                                                            'DangHoc' => 'fas fa-clock',
                                                            'DaHoanThanh' => 'fas fa-check-circle',
                                                            'ChuaHoanThanh' => 'fas fa-times-circle'
                                                        ];
                                                    @endphp
                                                    <span class="badge badge-{{ $trangThaiColors[$item->TrangThai] ?? 'secondary' }}">
                                                        <i class="{{ $trangThaiIcons[$item->TrangThai] ?? 'fas fa-question-circle' }}"></i>
                                                        {{ $trangThaiTexts[$item->TrangThai] ?? $item->TrangThai }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if(isset($item->NgayHoanThanh) && $item->NgayHoanThanh)
                                                        {{ \Carbon\Carbon::parse($item->NgayHoanThanh)->format('d/m/Y') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(isset($item->GhiChu) && $item->GhiChu)
                                                        <span class="text-muted">{{ Str::limit($item->GhiChu, 30) }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle"></i>
                                                        Chưa có dữ liệu tiến trình học tập
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ tiến trình -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Thống kê theo trạng thái</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chart-trang-thai" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Thống kê theo xếp loại</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chart-xep-loai" height="200"></canvas>
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
            // Dữ liệu cho biểu đồ trạng thái
            const trangThaiData = {
                labels: ['Đã hoàn thành', 'Đang học', 'Chưa hoàn thành', 'Đã đăng ký', 'Chưa đăng ký'],
                datasets: [{
                    data: [
                        {{ $tienTrinh->where('TrangThai', 'DaHoanThanh')->count() }},
                        {{ $tienTrinh->where('TrangThai', 'DangHoc')->count() }},
                        {{ $tienTrinh->where('TrangThai', 'ChuaHoanThanh')->count() }},
                        {{ $tienTrinh->where('TrangThai', 'DangKy')->count() }},
                        {{ $tienTrinh->where('TrangThai', 'ChuaDangKy')->count() }}
                    ],
                    backgroundColor: ['#28a745', '#17a2b8', '#dc3545', '#6c757d', '#ffc107']
                }]
            };

            // Dữ liệu cho biểu đồ xếp loại
            const xepLoaiData = {
                labels: ['Giỏi', 'Khá', 'Trung bình', 'Yếu', 'Kém', 'Chưa xếp loại', 'Đạt'],
                datasets: [{
                    data: [
                        {{ $tienTrinh->where('XepLoai', 'Giỏi')->count() }},
                        {{ $tienTrinh->where('XepLoai', 'Khá')->count() }},
                        {{ $tienTrinh->where('XepLoai', 'Trung bình')->count() }},
                        {{ $tienTrinh->where('XepLoai', 'Yếu')->count() }},
                        {{ $tienTrinh->where('XepLoai', 'Kém')->count() }},
                        {{ $tienTrinh->where('XepLoai', 'Chưa xếp loại')->count() }},
                        {{ $tienTrinh->where('XepLoai', 'Đạt')->count() }}
                    ],
                    backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#fd7e14', '#6f42c1', '#6c757d', '#007bff']
                }]
            };

            // Vẽ biểu đồ trạng thái
            new Chart(document.getElementById('chart-trang-thai'), {
                type: 'doughnut',
                data: trangThaiData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Vẽ biểu đồ xếp loại
            new Chart(document.getElementById('chart-xep-loai'), {
                type: 'doughnut',
                data: xepLoaiData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });

        // Function export Excel
        function exportToExcel() {
            // Tạo form để submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("tien-trinh-hoc-tap.xuat-bao-cao") }}';
            
            // Thêm CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Thêm mã sinh viên
            const maSV = document.createElement('input');
            maSV.type = 'hidden';
            maSV.name = 'maSV';
            maSV.value = '{{ $sinhVien->MaSV }}';
            form.appendChild(maSV);
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </script>
@endsection 