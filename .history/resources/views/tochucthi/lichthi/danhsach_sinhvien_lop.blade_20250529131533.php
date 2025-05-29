@extends('layouts.new_app.master')

@section('title', 'Danh Sách Sinh Viên Lớp ' . $lop->MaLop)

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Danh Sách Sinh Viên Lớp {{ $lop->MaLop }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('lichthi.index') }}">Lịch Thi</a></div>
            <div class="breadcrumb-item">Danh Sách Sinh Viên Lớp</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Thông Tin Lớp</h4>
                <div class="card-header-action">
                    <a href="{{ route('lichthi.xuat.sinhvien.excel', $lop->MaLop) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Xuất Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã Lớp:</strong> {{ $lop->MaLop }}</p>
                        <p><strong>Tên Lớp:</strong> {{ $lop->TenLop ?? 'Chưa cập nhật' }}</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã Sinh Viên</th>
                                <th>Họ Tên</th>
                                <th>Ngày Sinh</th>
                                <th>Giới Tính</th>
                                <th>Email</th>
                                <th>Số Điện Thoại</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($danhSachSinhVien as $index => $sinhVien)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $sinhVien->MaSV }}</td>
                                    <td>{{ $sinhVien->HoTen }}</td>
                                    <td>{{ $sinhVien->NgaySinh }}</td>
                                    <td>{{ $sinhVien->GioiTinh == 1 ? 'Nam' : 'Nữ' }}</td>
                                    <td>{{ $sinhVien->Email }}</td>
                                    <td>{{ $sinhVien->Sdt }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                                Thao Tác
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('sinhvien.chitiet', $sinhVien->MaSV) }}">
                                                    <i class="fas fa-eye"></i> Chi Tiết
                                                </a>
                                                <a class="dropdown-item" href="{{ route('sinhvien.edit', $sinhVien->MaSV) }}">
                                                    <i class="fas fa-edit"></i> Chỉnh Sửa
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Danh Sách Lịch Thi Của Lớp</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Môn Thi</th>
                                    <th>Ngày Thi</th>
                                    <th>Giờ Thi</th>
                                    <th>Phòng Thi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($danhSachLichThi as $lichThi)
                                    <tr>
                                        <td>{{ $lichThi->TenMH }}</td>
                                        <td>{{ $lichThi->NgayThi }}</td>
                                        <td>{{ $lichThi->KhungGio }}</td>
                                        <td>{{ $lichThi->PhongThi }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
