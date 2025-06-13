@extends('layouts.new_app.master')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Kết Quả Tra Cứu Điểm</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Thông Tin Sinh Viên</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Mã Sinh Viên:</strong> {{ $sinhVien->MaSV }}</p>
                                <p><strong>Họ Tên:</strong> {{ $sinhVien->HoTen }}</p>
                                <p><strong>Lớp:</strong> {{ $lopHoc }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Bảng Điểm Chi Tiết</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Môn Học</th>
                                        <th>Lớp</th>
                                        <th>Điểm Lý Thuyết</th>
                                        <th>Điểm Thực Hành</th>
                                        <th>Điểm Dự Án</th>
                                        <th>Điểm Tổng</th>
                                        <th>Ghi Chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($danhSachDiem as $diem)
                                        <tr class="{{ $diem->DiemTong === null ? 'table-secondary' : '' }}">
                                            <td>{{ $diem->TenMH }}</td>
                                            <td>{{ $diem->MaLop }}</td>
                                            <td>
                                                @if($diem->DiemLyThuyet !== null)
                                                    {{ number_format($diem->DiemLyThuyet, 2) }}
                                                @else
                                                    <span class="text-muted">Chưa có</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($diem->DiemThucHanh !== null)
                                                    {{ number_format($diem->DiemThucHanh, 2) }}
                                                @else
                                                    <span class="text-muted">Chưa có</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($diem->DiemDuAn !== null)
                                                    {{ number_format($diem->DiemDuAn, 2) }}
                                                @else
                                                    <span class="text-muted">Chưa có</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($diem->DiemTong !== null)
                                                    {{ number_format($diem->DiemTong, 2) }}
                                                @else
                                                    <span class="text-muted">Chưa có</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($diem->GhiChu)
                                                    {{ $diem->GhiChu }}
                                                @else
                                                    <span class="text-muted">Không có</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Không có môn học</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom-js')
<script>
    $(document).ready(function() {
        // Thêm tooltip cho các ô chưa có điểm
        $('.text-muted').tooltip({
            title: 'Chưa có điểm',
            placement: 'top'
        });
    });
</script>
@endsection 