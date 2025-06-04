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
                        <form action="{{ route('giaovien.profile.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mã giáo viên</label>
                                        <input type="text" class="form-control" value="{{ $giaoVien->MaGV }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Họ tên</label>
                                        <input type="text" class="form-control" value="{{ $giaoVien->HoTenGV }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email cá nhân</label>
                                        <input type="email" name="Email" class="form-control" 
                                               value="{{ $giaoVien->Email }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email CUSC</label>
                                        <input type="email" class="form-control" 
                                               value="{{ $giaoVien->EmailCUSC }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Số điện thoại</label>
                                        <input type="text" name="Sdt" class="form-control" 
                                               value="{{ $giaoVien->Sdt }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Giới tính</label>
                                        <input type="text" class="form-control" 
                                               value="{{ $giaoVien->GioiTinh ? 'Nam' : 'Nữ' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Học Vị</label>
                                        <select name="MaHV" class="form-control">
                                            @foreach($hocvis as $hocvi)
                                                <option value="{{ $hocvi->MaHV }}" 
                                                    {{ $giaoVien->MaHV == $hocvi->MaHV ? 'selected' : '' }}>
                                                    {{ $hocvi->TenHocVi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Chức Vụ</label>
                                        <select name="TenChucVu" class="form-control">
                                            @foreach($chucvus as $chucvu)
                                                <option value="{{ $chucvu->TenChucVu }}" 
                                                    {{ $giaoVien->TenChucVu == $chucvu->TenChucVu ? 'selected' : '' }}>
                                                    {{ $chucvu->TenChucVu }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Đơn Vị</label>
                                        <select name="MaDV" class="form-control">
                                            @foreach($donvis as $donvi)
                                                <option value="{{ $donvi->MaDV }}" 
                                                    {{ $giaoVien->MaDV == $donvi->MaDV ? 'selected' : '' }}>
                                                    {{ $donvi->TenDVHienTai }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Loại Giáo Viên</label>
                                        <input type="text" class="form-control" 
                                               value="{{ $giaoVien->LoaiGV == 'CoHuu' ? 'Cơ Hữu' : 'Mời Giảng' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Chuyên Ngành</label>
                                        <input type="text" name="ChuyenNganh" class="form-control" 
                                               value="{{ $giaoVien->ChuyenNganh }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Ghi Chú</label>
                                        <textarea name="GhiChu" class="form-control" rows="3">{{ $giaoVien->GhiChu }}</textarea>
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
                                <div class="profile-widget-item-label">Ngày Bắt Đầu</div>
                                <div class="profile-widget-item-value">
                                    {{ $giaoVien->NgayBatDauCongTac ? 
                                        \Carbon\Carbon::parse($giaoVien->NgayBatDauCongTac)->format('d/m/Y') : 
                                        'Chưa xác định' 
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-widget-description">
                        <div class="profile-widget-name">
                            {{ $giaoVien->HoTenGV }}
                            <div class="text-muted d-inline font-weight-normal">
                                <div class="slash"></div> 
                                {{ $giaoVien->TenChucVu ?? 'Giảng Viên' }}
                            </div>
                        </div>
                        <p>
                            {{ $giaoVien->ChuyenNganh ? 
                                'Chuyên ngành: ' . $giaoVien->ChuyenNganh : 
                                'Chưa cập nhật chuyên ngành' 
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