@extends('layouts.new_app.master')

@section('title', 'Chỉnh Sửa Môn Học')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Chỉnh Sửa Môn Học</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('monhoc.index') }}">Môn Học</a></div>
                <div class="breadcrumb-item">Chỉnh Sửa</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Cập Nhật Thông Tin Môn Học</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('monhoc.update', $monhoc->TenMH) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mã Môn Học <span class="text-danger">*</span></label>
                                    <input type="text" name="MaMH" class="form-control"
                                        value="{{ old('MaMH', $monhoc->MaMH) }}">
                                    @error('MaMH')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tên môn học</label>
                                    <input type="text" class="form-control" value="{{ $monhoc->TenMH }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Giờ gốc</label>
                                    <input type="number" name="GioGoc" class="form-control"
                                        value="{{ old('GioGoc', $monhoc->GioGoc) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Giờ triển khai</label>
                                    <input type="number" name="GioTrienKhai" class="form-control"
                                        value="{{ old('GioTrienKhai', $monhoc->GioTrienKhai) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="TietLT" name="TietLT" value="1"
                                            {{ old('TietLT', $monhoc->TietLT) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="TietLT">Tiết Lý Thuyết</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="TietTH" name="TietTH" value="1"
                                            {{ old('TietTH', $monhoc->TietTH) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="TietTH">Tiết Thực Hành</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="TietLTvaTH" name="TietLTvaTH" value="1"
                                            {{ old('TietLTvaTH', $monhoc->TietLTvaTH) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="TietLTvaTH">Tiết LT và TH</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hình thức đánh giá</label>
                                    <select class="form-control" name="MaHTDanhGia">
                                        <option value="">-- Chọn hình thức đánh giá --</option>
                                        @foreach($hinhthucdanhgias as $htdg)
                                            <option value="{{ $htdg->MaHTDanhGia }}"
                                                {{ old('MaHTDanhGia', $monhoc->MaHTDanhGia) == $htdg->MaHTDanhGia ? 'selected' : '' }}>
                                                {{ $htdg->HinhThuc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('monhoc.index') }}" class="btn btn-secondary mr-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Cập Nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection