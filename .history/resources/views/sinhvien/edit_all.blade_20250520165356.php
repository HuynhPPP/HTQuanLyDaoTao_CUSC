@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Chỉnh sửa thông tin sinh viên</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('student.show', $sinhVien->MaSV) }}">Thông tin sinh viên</a></div>
                <div class="breadcrumb-item">Chỉnh sửa thông tin sinh viên</div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Chỉnh sửa thông tin sinh viên</h5>
                            <div>
                                <a href="{{ route('student.show', $sinhVien->MaSV) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('student.update_all', $sinhVien->MaSV) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="mb-3">Thông tin cá nhân</h6>
                                        <div class="form-group">
                                            <label>Mã sinh viên</label>
                                            <input type="text" class="form-control @error('MaSV') is-invalid @enderror"
                                                name="MaSV" value="{{ old('MaSV', $sinhVien->MaSV) }}" required>
                                            @error('MaSV')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Họ và tên</label>
                                            <input type="text" class="form-control @error('HoTen') is-invalid @enderror"
                                                name="HoTen" value="{{ old('HoTen', $sinhVien->HoTen) }}" required>
                                            @error('HoTen')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Ngày sinh</label>
                                            <input type="date" class="form-control" name="NgaySinh"
                                                value="{{ $sinhVien->NgaySinh }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Giới tính</label>
                                            <select class="form-control" name="GioiTinh" required>
                                                <option value="1" {{ $sinhVien->GioiTinh == 1 ? 'selected' : '' }}>Nam
                                                </option>
                                                <option value="0" {{ $sinhVien->GioiTinh == 0 ? 'selected' : '' }}>Nữ
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Số CCCD</label>
                                            <input type="text" class="form-control" name="SoCCCD"
                                                value="{{ $sinhVien->SoCCCD }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Ngày cấp</label>
                                            <input type="date" class="form-control" name="NgayCap"
                                                value="{{ $sinhVien->NgayCap }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Nơi cấp</label>
                                            <input type="text" class="form-control" name="NoiCap"
                                                value="{{ $sinhVien->NoiCap }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="mb-3">Thông tin liên hệ</h6>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="Email"
                                                value="{{ $sinhVien->Email }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Email CUSC</label>
                                            <input type="email" class="form-control" name="EmailCUSC"
                                                value="{{ $sinhVien->EmailCUSC }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Số điện thoại</label>
                                            <input type="text" class="form-control" name="Sdt"
                                                value="{{ $sinhVien->Sdt }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Địa chỉ</label>
                                            <textarea class="form-control" name="DiaChi" rows="3">{{ $sinhVien->DiaChi }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Zalo</label>
                                            <input type="text" class="form-control" name="Zalo"
                                                value="{{ $sinhVien->Zalo }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h6 class="mb-3">Thông tin người thân</h6>
                                        <div class="form-group">
                                            <label>Họ tên người thân</label>
                                            <input type="text" class="form-control" name="HoTenNguoiThan"
                                                value="{{ $sinhVien->HoTenNguoiThan }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Mối quan hệ</label>
                                            <input type="text" class="form-control" name="MoiQuanHe"
                                                value="{{ $sinhVien->MoiQuanHe }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Số điện thoại</label>
                                            <input type="text" class="form-control" name="SdtNguoiThan"
                                                value="{{ $sinhVien->SdtNguoiThan }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="EmailNguoiThan"
                                                value="{{ $sinhVien->EmailNguoiThan }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Zalo</label>
                                            <input type="text" class="form-control" name="ZaloNguoiThan"
                                                value="{{ $sinhVien->ZaloNguoiThan }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Lưu thay đổi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
