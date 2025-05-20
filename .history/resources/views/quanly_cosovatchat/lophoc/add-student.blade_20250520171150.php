@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Thêm Sinh Viên Vào Lớp {{ $lophoc->TenLop }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
            <div class="breadcrumb-item active"><a href="{{ route('lophoc.index') }}">Danh sách lớp học</a></div>
            <div class="breadcrumb-item">Thêm sinh viên</div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Chọn sinh viên</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('lophoc.store-student', $lophoc->MaLop) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="MaSV">Sinh viên</label>
                            <select name="MaSV" id="MaSV" class="form-control @error('MaSV') is-invalid @enderror select2">
                                <option value="">-- Chọn sinh viên --</option>
                                @foreach($sinhviens as $sv)
                                    @if(!in_array($sv->MaSV, $existingStudents))
                                        <option value="{{ $sv->MaSV }}">{{ $sv->MaSV }} - {{ $sv->HoTen }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('MaSV')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('lophoc.show', $lophoc->MaLop) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Thêm sinh viên
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection