@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Quản lý phòng học</h1>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Chỉnh sửa phòng học</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('phonghoc.update', $phonghoc->TenPhong) }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="LoaiPhong" class="form-label">Loại phòng <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('LoaiPhong') is-invalid @enderror"
                                        id="LoaiPhong" name="LoaiPhong"
                                        value="{{ old('LoaiPhong', $phonghoc->LoaiPhong) }}">
                                    @error('LoaiPhong')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="SucChua" class="form-label">Sức chứa</label>
                                    <input type="number" class="form-control @error('SucChua') is-invalid @enderror"
                                        id="SucChua" name="SucChua" value="{{ old('SucChua', $phonghoc->SucChua) }}">
                                    @error('SucChua')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('phonghoc.index') }}" class="btn btn-secondary">
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
