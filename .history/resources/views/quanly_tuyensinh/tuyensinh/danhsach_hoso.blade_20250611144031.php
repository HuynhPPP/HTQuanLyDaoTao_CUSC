@extends('layouts.new_app.master')

@section('title', 'Danh Sách Hồ Sơ Tuyển Sinh')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Danh sách hồ sơ tuyển sinh</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
            <div class="breadcrumb-item"><a href="{{ route('tuyensinh.index') }}">Đợt Tuyển Sinh</a></div>
            <div class="breadcrumb-item">Hồ Sơ</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>
                    Đợt Tuyển Sinh: {{ $dotTuyenSinh->NamTS }} - Đợt {{ $dotTuyenSinh->DotTS }}
                    <small class="text-muted ml-2">
                        Từ {{ date('d/m/Y', strtotime($dotTuyenSinh->NgayBatDau)) }} 
                        đến {{ date('d/m/Y', strtotime($dotTuyenSinh->NgayKetThuc)) }}
                    </small>
                </h4>
                <div class="card-header-action">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalThemHoSo">
                        <i class="fas fa-plus"></i> Thêm Hồ Sơ
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>Mã Hồ Sơ</th>
                                <th>Mã Sinh Viên</th>
                                <th>Họ Tên</th>
                                <th>Ngày Nộp</th>
                                <th>Trạng Thái</th>
                                <th>Hồ Sơ</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hoSos as $hoSo)
                            <tr>
                                <td>{{ $hoSo->MaHoSo }}</td>
                                <td>{{ $hoSo->MaSV }}</td>
                                <td>{{ $hoSo->sinhvien->HoTenSV ?? 'Chưa xác định' }}</td>
                                <td>{{ date('d/m/Y', strtotime($hoSo->NgayNopHS)) }}</td>
                                <td>
                                    @switch($hoSo->TrangThaiHS)
                                        @case('DaNop')
                                            <span class="badge badge-secondary">Đã Nộp</span>
                                            @break
                                        @case('DaXet')
                                            <span class="badge badge-info">Đã Xét</span>
                                            @break
                                        @case('DaTrungTuyen')
                                            <span class="badge badge-success">Trúng Tuyển</span>
                                            @break
                                        @case('KhongTrungTuyen')
                                            <span class="badge badge-danger">Không Trúng Tuyển</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm {{ $hoSo->Hinh3X4 ? 'btn-success' : 'btn-outline-secondary' }}">
                                            <i class="fas fa-image"></i> 3x4
                                        </button>
                                        <button type="button" class="btn btn-sm {{ $hoSo->HinhCCCD ? 'btn-success' : 'btn-outline-secondary' }}">
                                            <i class="fas fa-id-card"></i> CCCD
                                        </button>
                                        <button type="button" class="btn btn-sm {{ $hoSo->ToDangKi ? 'btn-success' : 'btn-outline-secondary' }}">
                                            <i class="fas fa-file-alt"></i> Tờ Đăng Ký
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" 
                                        data-toggle="modal" 
                                        data-target="#modalCapNhatHoSo{{ $hoSo->MaHoSo }}">
                                        <i class="fas fa-edit"></i> Cập Nhật
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Cập Nhật Hồ Sơ -->
                            <div class="modal fade" id="modalCapNhatHoSo{{ $hoSo->MaHoSo }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Cập Nhật Hồ Sơ: {{ $hoSo->MaHoSo }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('tuyensinh.capnhat_hoso', $hoSo->MaHoSo) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Trạng Thái Hồ Sơ</label>
                                                    <select name="TrangThaiHS" class="form-control">
                                                        <option value="DaNop" {{ $hoSo->TrangThaiHS == 'DaNop' ? 'selected' : '' }}>Đã Nộp</option>
                                                        <option value="DaXet" {{ $hoSo->TrangThaiHS == 'DaXet' ? 'selected' : '' }}>Đã Xét</option>
                                                        <option value="DaTrungTuyen" {{ $hoSo->TrangThaiHS == 'DaTrungTuyen' ? 'selected' : '' }}>Trúng Tuyển</option>
                                                        <option value="KhongTrungTuyen" {{ $hoSo->TrangThaiHS == 'KhongTrungTuyen' ? 'selected' : '' }}>Không Trúng Tuyển</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" 
                                                            id="Hinh3X4{{ $hoSo->MaHoSo }}" 
                                                            name="Hinh3X4" 
                                                            value="1" 
                                                            {{ $hoSo->Hinh3X4 ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="Hinh3X4{{ $hoSo->MaHoSo }}">
                                                            Ảnh 3x4
                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" 
                                                            id="HinhCCCD{{ $hoSo->MaHoSo }}" 
                                                            name="HinhCCCD" 
                                                            value="1" 
                                                            {{ $hoSo->HinhCCCD ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="HinhCCCD{{ $hoSo->MaHoSo }}">
                                                            Hình CCCD
                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" 
                                                            id="ToDangKi{{ $hoSo->MaHoSo }}" 
                                                            name="ToDangKi" 
                                                            value="1" 
                                                            {{ $hoSo->ToDangKi ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="ToDangKi{{ $hoSo->MaHoSo }}">
                                                            Tờ Đăng Ký
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Thêm Hồ Sơ -->
<div class="modal fade" id="modalThemHoSo" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Hồ Sơ Tuyển Sinh</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('tuyensinh.tao_hoso') }}" method="POST">
                @csrf
                <input type="hidden" name="MaTS" value="{{ $dotTuyenSinh->MaTS }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Mã Sinh Viên</label>
                        <input type="text" class="form-control" name="MaSV" required>
                    </div>
                    <div class="form-group">
                        <label>Ngày Nộp Hồ Sơ</label>
                        <input type="date" class="form-control" name="NgayNopHS" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm Hồ Sơ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
@endsection