@extends('layouts.new_app.master')

@section('title', 'Thêm Mới Chương Trình Đào Tạo')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Thêm Mới Chương Trình Đào Tạo</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('chuongtrinh.index') }}">Chương Trình Đào Tạo</a></div>
                <div class="breadcrumb-item">Thêm Mới</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Nhập Thông Tin Chương Trình</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('chuongtrinh.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mã Chương Trình <span class="text-danger">*</span></label>
                                    <input type="text" name="MaChuongTrinh" class="form-control @error('MaChuongTrinh') is-invalid @enderror" 
                                           value="{{ old('MaChuongTrinh') }}" required>
                                    @error('MaChuongTrinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tên Chương Trình</label>
                                    <input type="text" name="TenChuongTrinh" class="form-control" 
                                           value="{{ old('TenChuongTrinh') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phiên Bản</label>
                                    <input type="text" name="PhienBan" class="form-control" 
                                           value="{{ old('PhienBan') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ngày Triển Khai</label>
                                    <input type="date" name="NgayTrienKhaiPB" class="form-control" 
                                           value="{{ old('NgayTrienKhaiPB') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tên Khóa Đào Tạo</label>
                                    <input type="text" name="TenKhoaDaoTao" class="form-control" 
                                           value="{{ old('TenKhoaDaoTao') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Thời Gian Đào Tạo</label>
                                    <input type="text" name="ThoiGianDaoTao" class="form-control" 
                                           value="{{ old('ThoiGianDaoTao') }}">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('chuongtrinh.index') }}" class="btn btn-secondary mr-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Lưu Chương Trình</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection 