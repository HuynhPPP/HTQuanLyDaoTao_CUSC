@extends('layouts.new_app.master')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Thông tin cá nhân</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
            <div class="breadcrumb-item">Thông tin cá nhân</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Chi tiết thông tin</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('student.profile.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mã sinh viên</label>
                                        <input type="text" class="form-control" value="{{ $sinhVien->MaSV }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Họ tên</label>
                                        <input type="text" class="form-control" value="{{ $sinhVien->HoTen }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email cá nhân</label>
                                        <input type="email" name="Email" class="form-control" 
                                               value="{{ $sinhVien->Email }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email CUSC</label>
                                        <input type="email" class="form-control" 
                                               value="{{ $sinhVien->EmailCUSC }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Số điện thoại</label>
                                        <input type="text" name="Sdt" class="form-control" 
                                               value="{{ $sinhVien->Sdt }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Giới tính</label>
                                        <input type="text" class="form-control" 
                                               value="{{ $sinhVien->GioiTinh ? 'Nam' : 'Nữ' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ngày sinh</label>
                                        <input type="date" name="NgaySinh" class="form-control" 
                                               value="{{ $sinhVien->NgaySinh }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Số CCCD</label>
                                        <input type="text" class="form-control" 
                                               value="{{ $sinhVien->SoCCCD }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Địa chỉ</label>
                                        <input type="text" name="DiaChi" class="form-control" 
                                               value="{{ $sinhVien->DiaChi }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tình trạng học tập</label>
                                        <input type="text" class="form-control" 
                                               value="{{ $sinhVien->TinhTrangHocTap }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ngày đăng ký</label>
                                        <input type="date" class="form-control" 
                                               value="{{ $sinhVien->NgayDangKi }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Cập Nhật Thông Tin</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card profile-widget">
                    <div class="profile-widget-header">
                        <img alt="image" src="{{ asset('images/avatar-1.png') }}" 
                             class="rounded-circle profile-widget-picture">
                        <div class="profile-widget-items">
                            <div class="profile-widget-item">
                                <div class="profile-widget-item-label">Mã Nhập Học</div>
                                <div class="profile-widget-item-value">
                                    {{ $sinhVien->MaEnroll ?? 'Chưa xác định' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-widget-description">
                        <div class="profile-widget-name">
                            {{ $sinhVien->HoTen }}
                            <div class="text-muted d-inline font-weight-normal">
                                <div class="slash"></div> 
                                Sinh Viên
                            </div>
                        </div>
                        <p>
                            {{ $sinhVien->DiaChi ? 
                                'Địa chỉ: ' . $sinhVien->DiaChi : 
                                'Chưa cập nhật địa chỉ' 
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom-js')
<script>
    $(document).ready(function() {
        // Thêm validation cho form
        $('form').on('submit', function(e) {
            let email = $('input[name="Email"]').val();
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                e.preventDefault();
                iziToast.error({
                    message: 'Vui lòng nhập địa chỉ email hợp lệ',
                    position: 'topRight'
                });
            }
        });
    });
</script>
@endsection 