@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Thông tin sinh viên</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('student.list') }}">Danh sách sinh viên</a></div>
                <div class="breadcrumb-item">Thông tin sinh viên</div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Thông tin chi tiết sinh viên</h5>
                            <div>
                                <a href="{{ route('student.edit_all', $sinhVien->MaSV) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </a>
                                <a href="{{ route('student.list') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Thông tin cá nhân</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%">Mã sinh viên</th>
                                            <td>{{ $sinhVien->MaSV }}</td>
                                        </tr>
                                        <tr>
                                            <th>Họ và tên</th>
                                            <td>{{ $sinhVien->HoTen }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ngày sinh</th>
                                            <td>{{ \Carbon\Carbon::parse($sinhVien->NgaySinh)->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Giới tính</th>
                                            <td>{{ $sinhVien->GioiTinh == 1 ? 'Nam' : 'Nữ' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Số CCCD</th>
                                            <td>{{ $sinhVien->SoCCCD }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ngày cấp</th>
                                            <td>{{ $sinhVien->NgayCap ? \Carbon\Carbon::parse($sinhVien->NgayCap)->format('d/m/Y') : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Nơi cấp</th>
                                            <td>{{ $sinhVien->NoiCap ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="mb-3">Thông tin liên hệ</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%">Email</th>
                                            <td>{{ $sinhVien->Email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email CUSC</th>
                                            <td>{{ $sinhVien->EmailCUSC ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Số điện thoại</th>
                                            <td>{{ $sinhVien->Sdt }}</td>
                                        </tr>
                                        <tr>
                                            <th>Địa chỉ</th>
                                            <td>{{ $sinhVien->DiaChi ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Zalo</th>
                                            <td>{{ $sinhVien->Zalo ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6 class="mb-3">Thông tin người thân</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 20%">Họ tên người thân</th>
                                            <td>{{ $sinhVien->HoTenNguoiThan ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Mối quan hệ</th>
                                            <td>{{ $sinhVien->MoiQuanHe ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Số điện thoại</th>
                                            <td>{{ $sinhVien->SdtNguoiThan ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $sinhVien->EmailNguoiThan ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Zalo</th>
                                            <td>{{ $sinhVien->ZaloNguoiThan ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            {{-- <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6 class="mb-3">Tình trạng học tập</h6>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Học kỳ</th>
                                                <th>Trạng thái</th>
                                                <th>Ghi chú</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($sinhVien->tinhTrangHocTap as $tinhTrang)
                                                <tr>
                                                    <td>{{ $tinhTrang->HocKy }}</td>
                                                    <td>{{ $tinhTrang->TrangThai }}</td>
                                                    <td>{{ $tinhTrang->GhiChu ?? 'N/A' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">Chưa có thông tin</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div> --}}

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6 class="mb-3">Danh sách lớp học</h6>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Mã lớp</th>
                                                <th>Tên lớp</th>
                                                <th>Học kỳ</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($sinhVien->danhSachLop as $lop)
                                                <tr>
                                                    <td>{{ $lop->lopHoc->MaLop }}</td>
                                                    <td>{{ $lop->lopHoc->TenLop }}</td>
                                                    <td>{{ $lop->HocKy }}</td>
                                                    <td>{{ $lop->TrangThai }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">Chưa có thông tin</td>
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
        </div>

    </div>
@endsection
