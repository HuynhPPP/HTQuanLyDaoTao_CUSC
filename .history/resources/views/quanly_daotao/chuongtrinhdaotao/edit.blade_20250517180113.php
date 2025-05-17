@extends('layouts.new_app.master')

@section('title', 'Chỉnh Sửa Chương Trình Đào Tạo')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Chỉnh Sửa Chương Trình Đào Tạo</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('chuongtrinh.index') }}">Chương Trình Đào Tạo</a>
                </div>
                <div class="breadcrumb-item">Chỉnh Sửa</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Cập Nhật Thông Tin Chương Trình</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('chuongtrinh.update', $chuongTrinh->MaChuongTrinh) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mã Chương Trình <span class="text-danger">*</span></label>
                                    <input type="text" name="MaChuongTrinh" class="form-control"
                                        value="{{ $chuongTrinh->MaChuongTrinh }}" readonly>
                                    @error('MaChuongTrinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tên Chương Trình <span class="text-danger">*</span></label>
                                    <input type="text" name="TenChuongTrinh" class="form-control"
                                        value="{{ old('TenChuongTrinh', $chuongTrinh->TenChuongTrinh) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phiên Bản</label>
                                    <input type="text" name="PhienBan" class="form-control"
                                        value="{{ old('PhienBan', $chuongTrinh->PhienBan) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ngày Triển Khai</label>
                                    <input type="date" name="NgayTrienKhaiPB" class="form-control"
                                        value="{{ old('NgayTrienKhaiPB', $chuongTrinh->NgayTrienKhaiPB ? date('Y-m-d', strtotime($chuongTrinh->NgayTrienKhaiPB)) : '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="TenKhoaDaoTao" class="form-label">Khoá đào tạo <span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('TenKhoaDaoTao') is-invalid @enderror" id="TenKhoaDaoTao"
                                    name="TenKhoaDaoTao">
                                    <option value="">-- Chọn khoá đào tạo --</option>
                                    @foreach ($khoadaotaos as $khoadaotao)
                                        <option value="{{ $khoadaotao->TenKhoaDaoTao }}"
                                            {{ old('TenKhoaDaoTao', $khoadaotao->TenKhoaDaoTao) == $khoadaotao->TenKhoaDaoTao ? 'selected' : '' }}>
                                            {{ $khoadaotao->TenKhoaDaoTao }}</option>
                                    @endforeach
                                </select>
                                @error('TenKhoaDaoTao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('chuongtrinh.index') }}" class="btn btn-secondary mr-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Cập Nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
