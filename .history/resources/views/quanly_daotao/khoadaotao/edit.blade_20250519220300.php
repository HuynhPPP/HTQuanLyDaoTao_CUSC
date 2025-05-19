@extends('layouts.new_app.master')

@section('title', 'Chỉnh thông tin khoá đào tạo')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Chỉnh thông tin khoá đào tạo</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('monhoc.index') }}">Danh sách khoá đào tạo</a></div>
                <div class="breadcrumb-item">Chỉnh thông tin khoá đào tạo</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Cập nhật thông tin khoá đào tạo</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('khoadaotao.update', $khoadaotao->TenKhoaDaoTao) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Tên khóa đào tạo</label>
                            <input type="text" name="TenKhoaDaoTao" class="form-control @error('TenKhoaDaoTao') is-invalid @enderror" value="{{ old('TenKhoaDaoTao', $khoadaotao->TenKhoaDaoTao) }}">
                            @error('TenKhoaDaoTao')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Thời gian đào tạo</label>
                            <input type="text" name="ThoiGianDaoTao" class="form-control @error('ThoiGianDaoTao') is-invalid @enderror" value="{{ old('ThoiGianDaoTao', $khoadaotao->ThoiGianDaoTao) }}">
                            @error('ThoiGianDaoTao')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('khoadaotao.index') }}" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection