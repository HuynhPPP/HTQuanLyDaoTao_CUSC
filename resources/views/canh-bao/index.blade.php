@extends('layouts.new_app.master')

@section('page-title', 'Gợi ý Sinh viên có Nguy cơ')

@section('main-content')
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1 class="mb-0">🎯 Gợi ý Sinh viên có Nguy cơ</h1>
            <div class="section-header-breadcrumb mb-0">
                <button class="btn btn-primary" onclick="chayCanhBao()">
                    <i class="fas fa-search"></i> Tìm kiếm nguy cơ
                </button>
            </div>
        </div>

        <div class="section-body">
            <!-- Thống kê tổng quan -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Tổng sinh viên có nguy cơ</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKe['tong_canh_bao'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-chart-line-down"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Nguy cơ cao</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKe['muc_do_cao'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Nguy cơ trung bình</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKe['muc_do_trung_binh'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-chart-line-up"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Nguy cơ thấp</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKe['muc_do_thap'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ phân tích -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-chart-pie"></i> Phân loại nguy cơ</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartLoaiCanhBao" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-chart-bar"></i> Mức độ nguy cơ</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartMucDo" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bộ lọc và danh sách -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-filter"></i> Danh sách sinh viên có nguy cơ</h4>
                        </div>
                        <div class="card-body">
                            <!-- Bộ lọc đơn giản -->
                            <form method="GET" action="{{ route('canh-bao.index') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <select name="muc_do" class="form-control">
                                            <option value="">Tất cả mức độ</option>
                                            <option value="cao" {{ $filters['muc_do'] == 'cao' ? 'selected' : '' }}>Nguy
                                                cơ cao</option>
                                            <option value="trung_binh"
                                                {{ $filters['muc_do'] == 'trung_binh' ? 'selected' : '' }}>Nguy cơ trung
                                                bình</option>
                                            <option value="thap" {{ $filters['muc_do'] == 'thap' ? 'selected' : '' }}>Nguy
                                                cơ thấp</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="loai_canh_bao" class="form-control">
                                            <option value="">Tất cả loại</option>
                                            <option value="diem_thap"
                                                {{ $filters['loai_canh_bao'] == 'diem_thap' ? 'selected' : '' }}>Điểm thấp
                                            </option>
                                            <option value="tut_hang"
                                                {{ $filters['loai_canh_bao'] == 'tut_hang' ? 'selected' : '' }}>Tụt hạng
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary" onclick="showFilterMessage()">
                                            <i class="fas fa-search"></i> Lọc
                                        </button>
                                        <a href="{{ route('canh-bao.index') }}" class="btn btn-secondary"
                                            onclick="showClearFilterMessage()">
                                            <i class="fas fa-times"></i> Xóa lọc
                                        </a>
                                    </div>
                                </div>
                            </form>

                            <!-- Danh sách sinh viên có nguy cơ -->
                            @if ($danhSachCanhBao->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Sinh viên</th>
                                                <th>Lớp</th>
                                                <th>Môn học</th>
                                                <th>Loại nguy cơ</th>
                                                <th>Mức độ</th>
                                                <th>Ngày phát hiện</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($danhSachCanhBao as $canhBao)
                                                <tr
                                                    class="@if ($canhBao['MucDo'] == 'cao') table-danger @elseif($canhBao['MucDo'] == 'trung_binh') table-warning @endif">
                                                    <td>
                                                        <strong>{{ $canhBao['MaSV'] }}</strong><br>
                                                        <small>{{ $canhBao['sinhVien']->HoTen ?? 'N/A' }}</small>
                                                    </td>
                                                    <td>{{ $canhBao['lopHoc']->TenLop ?? 'N/A' }}</td>
                                                    <td>
                                                        @if ($canhBao['LoaiCanhBao'] == 'tut_hang')
                                                            <span class="text-muted">Tất cả môn học</span>
                                                        @else
                                                            {{ $canhBao['monHoc']->TenMH ?? 'N/A' }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @switch($canhBao['LoaiCanhBao'])
                                                            @case('diem_thap')
                                                                <span class="badge badge-danger">Điểm thấp</span>
                                                            @break

                                                            @case('tut_hang')
                                                                <span class="badge badge-warning">Tụt hạng</span>
                                                            @break

                                                            @default
                                                                <span
                                                                    class="badge badge-secondary">{{ $canhBao['LoaiCanhBao'] }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        @if ($canhBao['MucDo'] == 'cao')
                                                            <span class="badge badge-danger">Cao</span>
                                                        @elseif($canhBao['MucDo'] == 'trung_binh')
                                                            <span class="badge badge-warning">Trung bình</span>
                                                        @else
                                                            <span class="badge badge-info">Thấp</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small>{{ \Carbon\Carbon::parse($canhBao['NgayTao'])->format('d/m/Y') }}</small>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('canh-bao.chi-tiet', $canhBao['id']) }}"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i> Xem chi tiết
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">Hiển thị {{ $danhSachCanhBao->count() }} sinh viên có
                                            nguy cơ</small>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <h5>Không có sinh viên nào có nguy cơ</h5>
                                    <p class="mb-0">Tất cả sinh viên đều đang học tập tốt!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Kiểm tra nếu có thông báo từ server
        @if (session('error'))
            iziToast.error({
                title: 'Lỗi!',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        @endif

        @if (session('success'))
            iziToast.success({
                title: 'Thành công!',
                message: '{{ session('success') }}',
                position: 'topRight'
            });
        @endif
        // Biểu đồ phân loại nguy cơ
        var ctxLoaiCanhBao = document.getElementById('chartLoaiCanhBao').getContext('2d');
        new Chart(ctxLoaiCanhBao, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach ($thongKe['theo_loai'] as $item)
                        '{{ $item->LoaiCanhBao == 'diem_thap' ? 'Điểm thấp' : 'Tụt hạng' }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach ($thongKe['theo_loai'] as $item)
                            {{ $item->so_luong }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#dc3545', '#ffc107'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Biểu đồ mức độ nguy cơ
        var ctxMucDo = document.getElementById('chartMucDo').getContext('2d');
        new Chart(ctxMucDo, {
            type: 'bar',
            data: {
                labels: ['Cao', 'Trung bình', 'Thấp'],
                datasets: [{
                    label: 'Số lượng',
                    data: [
                        {{ $thongKe['muc_do_cao'] }},
                        {{ $thongKe['muc_do_trung_binh'] }},
                        {{ $thongKe['muc_do_thap'] }}
                    ],
                    backgroundColor: [
                        '#dc3545', '#ffc107', '#17a2b8'
                    ]
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Tìm kiếm nguy cơ
        function chayCanhBao() {
            Swal.fire({
                title: 'Tìm kiếm sinh viên có nguy cơ',
                text: 'Bạn có chắc muốn chạy hệ thống tìm kiếm?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tìm kiếm',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hiển thị loading
                    Swal.fire({
                        title: 'Đang tìm kiếm...',
                        text: 'Vui lòng chờ trong giây lát',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false
                    });

                    $.ajax({
                        url: '{{ route('canh-bao.chay') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Tìm kiếm thành công!',
                                    text: `Đã phát hiện ${response.data.tong_canh_bao} sinh viên có nguy cơ\n` +
                                          `• Nguy cơ cao: ${response.data.canh_bao_cao}\n` +
                                          `• Nguy cơ trung bình: ${response.data.canh_bao_trung_binh}\n` +
                                          `• Nguy cơ thấp: ${response.data.canh_bao_thap}`,
                                    icon: 'success',
                                    confirmButtonText: 'Xem kết quả'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Có lỗi xảy ra!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'Đóng'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Lỗi kết nối!',
                                text: 'Không thể kết nối đến server. Vui lòng thử lại sau.',
                                icon: 'error',
                                confirmButtonText: 'Đóng'
                            });
                        }
                    });
                }
            });
        }

        // Hiển thị thông báo khi lọc
        function showFilterMessage() {
            iziToast.info({
                title: 'Đang lọc dữ liệu...',
                message: 'Vui lòng chờ trong giây lát',
                position: 'topRight',
                timeout: 1000
            });
        }

        // Hiển thị thông báo khi xóa lọc
        function showClearFilterMessage() {
            Swal.fire({
                title: 'Xóa bộ lọc',
                text: 'Bạn có chắc muốn xóa tất cả bộ lọc?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xóa lọc',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('canh-bao.index') }}';
                }
            });
        }

        // Hiển thị thông báo khi không có dữ liệu
        function showNoDataMessage() {
            iziToast.info({
                title: 'Không có dữ liệu',
                message: 'Không tìm thấy sinh viên nào có nguy cơ phù hợp với bộ lọc hiện tại.',
                position: 'topRight'
            });
        }
    </script>
@endsection
