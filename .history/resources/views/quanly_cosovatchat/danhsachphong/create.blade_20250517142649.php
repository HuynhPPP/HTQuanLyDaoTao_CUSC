@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Thêm gán phòng cho lớp</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
            <div class="breadcrumb-item active"><a href="{{ route('lophoc.index') }}">Danh sách phòng & lớp</a></div>
            <div class="breadcrumb-item">Thêm gán phòng cho lớp</div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('danhsachphong.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="MaLop" class="form-label">Chọn lớp</label>
                                <select class="form-control @error('MaLop') is-invalid @enderror" id="MaLop" name="MaLop">
                                    <option value="">-- Chọn lớp --</option>
                                    @foreach ($lophocs as $lop)
                                        <option value="{{ $lop->MaLop }}" {{ old('MaLop') == $lop->MaLop ? 'selected' : '' }}>{{ $lop->TenLop }} ({{ $lop->MaLop }})</option>
                                    @endforeach
                                </select>
                                @error('MaLop')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="TenPhong" class="form-label">Chọn phòng</label>
                                <select class="form-control @error('TenPhong') is-invalid @enderror" id="TenPhong" name="TenPhong">
                                    <option value="">-- Chọn phòng --</option>
                                    @foreach ($phonghocs as $phong)
                                        <option value="{{ $phong->TenPhong }}" {{ old('TenPhong') == $phong->TenPhong ? 'selected' : '' }}>{{ $phong->TenPhong }} ({{ $phong->LoaiPhong }})</option>
                                    @endforeach
                                </select>
                                @error('TenPhong')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('danhsachphong.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu thông tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 