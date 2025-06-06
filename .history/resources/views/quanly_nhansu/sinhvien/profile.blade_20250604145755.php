@extends('layouts.new_app.master')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Hồ sơ sinh viên</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
            <div class="breadcrumb-item">Hồ sơ cá nhân</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Thông tin chi tiết</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('student.profile.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mã Sinh Viên</label>
                                        <input type="text" class="form-control" value="{{ $sinhVien->MaSV }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Họ Tên</label>
                                        <input type="text" class="form-control" value="{{ $sinhVien->HoTen }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ngày Sinh</label>
                                        <input type="date" class="form-control" value="{{ $sinhVien->NgaySinh }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Giới Tính</label>
                                        <input type="text" class="form-control" 
                                               value="{{ $sinhVien->GioiTinh ? 'Nam' : 'Nữ' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Số CCCD</label>
                                        <input type="text" class="form-control" value="{{ $sinhVien->SoCCCD }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ngày Cấp CCCD</label>
                                        <input type="date" class="form-control" value="{{ $sinhVien->NgayCap }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nơi Cấp CCCD</label>
                                        <input type="text" class="form-control" value="{{ $sinhVien->NoiCap }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tình Trạng Học Tập</label>
                                        <input type="text" class="form-control" 
                                               value="{{ $sinhVien->TinhTrangHocTap }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email Cá Nhân</label>
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
                                        <label>Số Điện Thoại</label>
                                        <input type="text" name="Sdt" class="form-control" 
                                               value="{{ $sinhVien->Sdt }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zalo</label>
                                        <input type="text" name="Zalo" class="form-control" 
                                               value="{{ $sinhVien->Zalo }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Địa Chỉ</label>
                                        <input type="text" name="DiaChi" class="form-control" 
                                               value="{{ $sinhVien->DiaChi }}">
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4">Thông Tin Người Thân</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Họ Tên Người Thân</label>
                                        <input type="text" name="HoTenNguoiThan" class="form-control" 
                                               value="{{ $sinhVien->HoTenNguoiThan }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mối Quan Hệ</label>
                                        <input type="text" name="MoiQuanHe" class="form-control" 
                                               value="{{ $sinhVien->MoiQuanHe }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Số Điện Thoại Người Thân</label>
                                        <input type="text" name="SdtNguoiThan" class="form-control" 
                                               value="{{ $sinhVien->SdtNguoiThan }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email Người Thân</label>
                                        <input type="email" name="EmailNguoiThan" class="form-control" 
                                               value="{{ $sinhVien->EmailNguoiThan }}">
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
                                <div class="profile-widget-item-label">Ngày Đăng Ký</div>
                                <div class="profile-widget-item-value">
                                    {{ $sinhVien->NgayDangKi ?? 'Chưa xác định' }}
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

                @if($sinhVien->danhSachLop && $sinhVien->danhSachLop->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h4>Lớp học đã đăng ký</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            @foreach($sinhVien->danhSachLop as $lopHoc)
                                <li>
                                    <strong>{{ $lopHoc->MaLop }}</strong> - 
                                    {{ $lopHoc->lopHoc->TenLop }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                @if($sinhVien->hosotuyensinh)
                <div class="card">
                    <div class="card-header">
                        <h4>Hồ Sơ Tuyển Sinh</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Ngày Nộp Hồ Sơ:</strong> {{ $sinhVien->hosotuyensinh->NgayNopHoSo }}</p>
                </div>
                @endif
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