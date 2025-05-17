@extends('layouts.new_app.master')
@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Quản lý phòng học</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
            <div class="breadcrumb-item active"><a href="{{ route('phonghoc.index') }}">Danh sách phòng học</a></div>
            <div class="breadcrumb-item">Thêm mới</div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thêm phòng học</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('phonghoc.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="TenPhong" class="form-label">Tên phòng</label>
                            <input type="text" class="form-control" id="TenPhong" name="TenPhong" value="{{ old('TenPhong') }}">
                        </div>
                        <div class="mb-3">
                            <label for="LoaiPhong" class="form-label">Loại phòng</label>
                            <input type="text" class="form-control" id="LoaiPhong" name="LoaiPhong" value="{{ old('LoaiPhong') }}">
                        </div>
                        <div class="mb-3">
                            <label for="SucChua" class="form-label">Sức chứa</label>
                            <input type="number" class="form-control" id="SucChua" name="SucChua" value="{{ old('SucChua') }}">
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('phonghoc.index') }}" class="btn btn-secondary">
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