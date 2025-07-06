@extends('layouts.new_app.master')

@section('page-title', 'Chỉnh Sửa Tài Khoản')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Chỉnh Sửa Tài Khoản Sinh Viên</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{ route('ldap.account.list') }}">Danh Sách Tài Khoản</a>
            </div>
            <div class="breadcrumb-item">Chỉnh Sửa Tài Khoản</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Thông Tin Tài Khoản</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ldap.account.update', $ldapAccount->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                    Mã Tài Khoản
                                </label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" 
                                           value="{{ $ldapAccount->MaTaiKhoan }}" 
                                           readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                    Họ Tên
                                </label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" 
                                           value="{{ $ldapAccount->full_name }}" 
                                           readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                    Tên Đăng Nhập
                                </label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" name="username" 
                                           class="form-control @error('username') is-invalid @enderror" 
                                           value="{{ old('username', $ldapAccount->username) }}">
                                    @error('username')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                    Email
                                </label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="email" name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $ldapAccount->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                    Trạng Thái
                                </label>
                                <div class="col-sm-12 col-md-7">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="is_active" 
                                               class="custom-control-input" 
                                               id="is_active"
                                               {{ $ldapAccount->is_active ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Kích hoạt tài khoản
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 col-md-7 offset-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Lưu Thay Đổi
                                    </button>
                                    <a href="{{ route('ldap.account.list') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Hủy
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
