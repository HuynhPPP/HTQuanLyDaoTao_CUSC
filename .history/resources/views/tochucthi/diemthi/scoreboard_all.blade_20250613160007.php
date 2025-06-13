@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Bảng điểm tổng - {{ $chuongTrinh->TenChuongTrinh }}</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Thông tin chương trình</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Mã chương trình:</strong> {{ $chuongTrinh->MaChuongTrinh }}</p>
                    <p><strong>Tên chương trình:</strong> {{ $chuongTrinh->TenChuongTrinh }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Phiên bản:</strong> {{ $chuongTrinh->PhienBan }}</p>
                    <p><strong>Ngày triển khai:</strong> {{ $chuongTrinh->NgayTrienKhaiPB }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Bảng điểm tổng</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th>Mã sinh viên</th>
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