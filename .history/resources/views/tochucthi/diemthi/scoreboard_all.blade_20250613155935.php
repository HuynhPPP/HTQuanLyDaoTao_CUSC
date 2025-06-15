@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Bảng Điểm Tổng - {{ $chuongTrinh->TenChuongTrinh }}</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Thông Tin Chương Trình</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Mã Chương Trình:</strong> {{ $chuongTrinh->MaChuongTrinh }}</p>
                    <p><strong>Tên Chương Trình:</strong> {{ $chuongTrinh->TenChuongTrinh }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Phiên Bản:</strong> {{ $chuongTrinh->PhienBan }}</p>
                    <p><strong>Ngày Triển Khai:</strong> {{ $chuongTrinh->NgayTrienKhaiPB }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Bảng Điểm Tổng</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th>Mã SV</th>
                            <th>Họ tên</th>
                            @foreach ($danhSachMonHoc as $monHoc)
                                <th>{{ $monHoc->TenMH }}</th>
                            @endforeach
                            <th>Điểm trung bình</th>
                            <th>Xếp loại</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bangDiemTong as $diemSV)
                        <tr>
                            <td>{{ $diemSV['MaSV'] }}</td>
                            <td>{{ $diemSV['HoTen'] }}</td>
                            @foreach ($danhSachMonHoc as $monHoc)
                                <td>{{ number_format($diemSV['DiemChiTiet'][$monHoc->MaMH] ?? 0, 2) }}</td>
                            @endforeach
                            <td>{{ number_format($diemSV['DiemTrungBinh'], 2) }}</td>
                            <td>{{ $diemSV['XepLoai'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
@endsection