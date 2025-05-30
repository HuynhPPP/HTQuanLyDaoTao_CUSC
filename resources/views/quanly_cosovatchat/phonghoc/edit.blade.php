@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Sửa thông tin phòng học</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item"><a href="{{ route('phonghoc.index') }}">Quản lý phòng học</a></div>
                <div class="breadcrumb-item">Sửa thông tin</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('phonghoc.update', $phonghoc->TenPhong) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label>Tên phòng</label>
                                    <input type="text" class="form-control" value="{{ $phonghoc->TenPhong }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label>Loại phòng</label>
                                    <input type="text" name="LoaiPhong"
                                        class="form-control @error('LoaiPhong') is-invalid @enderror"
                                        value="{{ old('LoaiPhong', $phonghoc->LoaiPhong) }}" required>
                                    @error('LoaiPhong')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Sức chứa</label>
                                    <input type="number" name="SucChua"
                                        class="form-control @error('SucChua') is-invalid @enderror"
                                        value="{{ old('SucChua', $phonghoc->SucChua) }}">
                                    @error('SucChua')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select name="TrangThai" class="form-control @error('TrangThai') is-invalid @enderror"
                                        required>
                                        <option value="Trống"
                                            {{ old('TrangThai', $phonghoc->TrangThai) == 'Trống' ? 'selected' : '' }}>Trống
                                        </option>
                                        <option value="Đang sử dụng"
                                            {{ old('TrangThai', $phonghoc->TrangThai) == 'Đang sử dụng' ? 'selected' : '' }}>
                                            Đang sử dụng</option>
                                        <option value="Bảo trì"
                                            {{ old('TrangThai', $phonghoc->TrangThai) == 'Bảo trì' ? 'selected' : '' }}>Bảo
                                            trì</option>
                                    </select>
                                    @error('TrangThai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                    <a href="{{ route('phonghoc.index') }}" class="btn btn-secondary">Hủy</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
