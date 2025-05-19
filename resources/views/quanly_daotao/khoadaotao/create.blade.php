@extends('layouts.new_app.master')

@section('title', 'Thêm mới khoá đào tạo')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Thêm mới khoá đào tạo</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('khoadaotao.index') }}">Danh sách khoá đào tạo</a></div>
                <div class="breadcrumb-item">Thêm mới khoá đào tạo</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Nhập thông tin khoá đào tạo</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('khoadaotao.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Tên khóa đào tạo</label>
                            <input type="text" name="TenKhoaDaoTao" class="form-control @error('TenKhoaDaoTao') is-invalid @enderror" value="{{ old('TenKhoaDaoTao') }}">
                            @error('TenKhoaDaoTao')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Thời gian đào tạo</label>
                            <input type="text" name="ThoiGianDaoTao" class="form-control @error('ThoiGianDaoTao') is-invalid @enderror" value="{{ old('ThoiGianDaoTao') }}">
                            @error('ThoiGianDaoTao')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <a href="{{ route('khoadaotao.index') }}" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection