@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Quản lý lớp học</h1>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Chỉnh sửa lớp học</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('lophoc.update', $lophoc->MaLop) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="MaLop" class="form-label">Mã lớp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="MaLop" name="MaLop" value="{{ old('MaLop', $lophoc->MaLop) }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="TenLop" class="form-label">Tên lớp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('TenLop') is-invalid @enderror" id="TenLop" name="TenLop" value="{{ old('TenLop', $lophoc->TenLop) }}">
                                @error('TenLop')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="NgayBatDau" class="form-label">Ngày bắt đầu</label>
                                <input type="date" class="form-control @error('NgayBatDau') is-invalid @enderror" id="NgayBatDau" name="NgayBatDau" value="{{ old('NgayBatDau', $lophoc->NgayBatDau) }}">
                                @error('NgayBatDau')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="MaChuongTrinh" class="form-label">Mã chương trình</label>
                                <input type="text" class="form-control @error('MaChuongTrinh') is-invalid @enderror" id="MaChuongTrinh" name="MaChuongTrinh" value="{{ old('MaChuongTrinh', $lophoc->MaChuongTrinh) }}">
                                @error('MaChuongTrinh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="MaChuongTrinh" class="form-label">Chương trình đào tạo<span class="text-danger">*</span></label>
                                <select class="form-control @error('MaChuongTrinh') is-invalid @enderror" id="MaChuongTrinh" name="MaChuongTrinh">
                                    <option value="">-- Chọn phòng --</option>
                                    @foreach ($chuongtrinhs as $chuongtrinh)
                                        <option value="{{ $chuongtrinh->TenPhong }}" {{ old('TenPhong') == $phong->TenPhong ? 'selected' : '' }}>{{ $phong->TenPhong }} ({{ $phong->LoaiPhong }})</option>
                                    @endforeach
                                </select>
                                @error('MaChuongTrinh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('lophoc.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 