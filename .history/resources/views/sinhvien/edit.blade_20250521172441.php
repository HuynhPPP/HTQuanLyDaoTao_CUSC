@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Quản lý sinh viên</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('student.list') }}">Danh sách sinh viên</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('student.show', $sinhVien->MaSV) }}">Thông tin sinh viên</a></div>
                <div class="breadcrumb-item">Chỉnh sửa thông tin sinh viên</div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Chỉnh sửa thông tin sinh viên</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('student.update', $sinhVien->MaSV) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="MaSV" class="form-label">Mã sinh viên</label>
                                    <input type="text" class="form-control" id="MaSV" name="MaSV"
                                        value="{{ $sinhVien->MaSV }}">

                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="HoTen" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control @error('HoTen') is-invalid @enderror"
                                        id="HoTen" name="HoTen" value="{{ old('HoTen', $sinhVien->HoTen) }}">
                                    @error('HoTen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="NgaySinh" class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control @error('NgaySinh') is-invalid @enderror"
                                        id="NgaySinh" name="NgaySinh" value="{{ old('NgaySinh', $sinhVien->NgaySinh) }}">
                                    @error('NgaySinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="GioiTinh" class="form-label">Giới tính</label>
                                    <select class="form-control @error('GioiTinh') is-invalid @enderror" id="GioiTinh"
                                        name="GioiTinh">
                                        <option value="">Chọn giới tính</option>
                                        <option value="1"
                                            {{ old('GioiTinh', $sinhVien->GioiTinh) == '1' ? 'selected' : '' }}>Nam</option>
                                        <option value="0"
                                            {{ old('GioiTinh', $sinhVien->GioiTinh) == '0' ? 'selected' : '' }}>Nữ</option>
                                    </select>
                                    @error('GioiTinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="SoCCCD" class="form-label">Số CCCD</label>
                                    <input type="text" class="form-control @error('SoCCCD') is-invalid @enderror"
                                        id="SoCCCD" name="SoCCCD" value="{{ old('SoCCCD', $sinhVien->SoCCCD) }}">
                                    @error('SoCCCD')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="Email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('Email') is-invalid @enderror"
                                        id="Email" name="Email" value="{{ old('Email', $sinhVien->Email) }}">
                                    @error('Email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Sdt" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control @error('Sdt') is-invalid @enderror"
                                        id="Sdt" name="Sdt" value="{{ old('Sdt', $sinhVien->Sdt) }}">
                                    @error('Sdt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="DiaChi" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control @error('DiaChi') is-invalid @enderror"
                                        id="DiaChi" name="DiaChi" value="{{ old('DiaChi', $sinhVien->DiaChi) }}">
                                    @error('DiaChi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('student.list') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
