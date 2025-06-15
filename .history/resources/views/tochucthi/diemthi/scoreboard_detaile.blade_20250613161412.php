@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Bảng điểm chi tiết</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Thông tin lớp học</h4>
                    <div class="card-header-action">
                        <a href="{{ route('diemthi.xuat-bang-diem-chi-tiet', [
                            'maLop' => $lopHoc->MaLop,
                            'maMH' => $monHoc->MaMH,
                        ]) }}"
                            class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Xuất bảng điểm
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Mã Lớp:</strong> {{ $lopHoc->MaLop }}</p>
                            <p><strong>Chương Trình:</strong> {{ $chuongTrinh->TenChuongTrinh }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Môn Học:</strong> {{ $monHoc->TenMH }}</p>
                            <p><strong>Mã Môn Học:</strong> {{ $monHoc->MaMH }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Bảng điểm chi tiết</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Mã SV</th>
                                    <th>Họ tên</th>
                                    <th>Điểm Lý Thuyết</th>
                                    <th>Điểm Thực Hành</th>
                                    <th>Điểm Dự Án</th>
                                    <th>Điểm trung bình</th>
                                    <th>Xếp Loại</th>
                                    <th>Ghi Chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($danhSachDiem as $diem)
                                    <tr>
                                        <td>{{ $diem->MaSV }}</td>
                                        <td>{{ $diem->HoTen }}</td>
                                        <td>{{ number_format($diem->DiemLyThuyet ?? 0, 2) }}</td>
                                        <td>{{ number_format($diem->DiemThucHanh ?? 0, 2) }}</td>
                                        <td>{{ number_format($diem->DiemDuAn ?? 0, 2) }}</td>
                                        <td>{{ number_format($diem->DiemTong, 2) }}</td>
                                        <td>{{ $diem->XepLoai }}</td>
                                        <td>{{ $diem->GhiChu }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom-js')
@endsection
