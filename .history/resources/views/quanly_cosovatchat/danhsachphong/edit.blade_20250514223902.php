@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Chỉnh sửa gán phòng cho lớp</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('danhsachphong.update', $danhsachphong->MaLop) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="MaLop" class="form-label">Mã lớp</label>
                                <input type="text" class="form-control" id="MaLop" name="MaLop" value="{{ old('MaLop', $danhsachphong->MaLop) }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="TenPhong" class="form-label">Chọn phòng</label>
                                <select class="form-control @error('TenPhong') is-invalid @enderror" id="TenPhong" name="TenPhong">
                                    <option value="">-- Chọn phòng --</option>
                                    @foreach ($phonghocs as $phong)
                                        <option value="{{ $phong->TenPhong }}" {{ old('TenPhong', $danhsachphong->TenPhong) == $phong->TenPhong ? 'selected' : '' }}>{{ $phong->TenPhong }} ({{ $phong->LoaiPhong }})</option>
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