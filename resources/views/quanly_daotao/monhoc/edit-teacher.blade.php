@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Chỉnh sửa phân công giảng viên cho môn học {{ $monhoc->TenMH }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('monhoc.index') }}">Danh sách môn học</a></div>
                <div class="breadcrumb-item">Chỉnh sửa phân công giảng viên</div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Chỉnh sửa thông tin giảng viên</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('monhoc.update-teacher', ['MaMH' => $monhoc->MaMH, 'maGV' => $currentTeacher->MaGV]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="MaGV">Giảng viên</label>
                                        <select name="MaGV" id="MaGV" 
                                                class="form-control @error('MaGV') is-invalid @enderror select2">
                                            <option value="">-- Chọn giảng viên --</option>
                                            @foreach ($giaoviens as $gv)
                                                <option value="{{ $gv->MaGV }}" 
                                                    {{ $currentTeacher->MaGV == $gv->MaGV ? 'selected' : '' }}>
                                                    {{ $gv->MaGV }} - {{ $gv->HoTenGV }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('MaGV')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Lớp Học</label>
                                        <select name="MaLop[]" 
                                                class="form-control @error('MaLop') is-invalid @enderror select2" 
                                                multiple>
                                            @foreach ($lops as $lop)
                                                <option value="{{ $lop->MaLop }}"
                                                    {{ isset($assignedLops) && in_array($lop->MaLop, $assignedLops) ? 'selected' : '' }}>
                                                    {{ $lop->MaLop }} - {{ $lop->TenLop }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('MaLop')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="NgayBatDau">Ngày bắt đầu giảng dạy</label>
                                        <input type="date" 
                                               class="form-control @error('NgayBatDau') is-invalid @enderror" 
                                               id="NgayBatDau" 
                                               name="NgayBatDau" 
                                               value="{{ old('NgayBatDau', $currentTeacher->NgayBatDau) }}">
                                        @error('NgayBatDau')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="NgayKetThuc">Ngày kết thúc giảng dạy</label>
                                        <input type="date" 
                                               class="form-control @error('NgayKetThuc') is-invalid @enderror" 
                                               id="NgayKetThuc" 
                                               name="NgayKetThuc" 
                                               value="{{ old('NgayKetThuc', $currentTeacher->NgayKetThuc) }}">
                                        @error('NgayKetThuc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="GhiChu">Ghi chú</label>
                                <textarea class="form-control" 
                                          id="GhiChu" 
                                          name="GhiChu" 
                                          rows="5" 
                                          placeholder="Nhập thông tin bổ sung về phân công giảng dạy">{{ old('GhiChu', $currentTeacher->GhiChu) }}</textarea>
                            </div>

                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle mr-2"></i>
                                Lưu ý: Một giảng viên có thể được phân công giảng dạy nhiều lớp cho cùng một môn học
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('monhoc.add-teacher', $monhoc->MaMH) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cập nhật phân công
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection