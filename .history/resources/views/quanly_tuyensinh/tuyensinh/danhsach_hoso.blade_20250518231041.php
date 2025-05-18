@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Danh sách hồ sơ tuyển sinh - Đợt {{ $dotTuyenSinh->DotTS }}/{{ $dotTuyenSinh->NamTS }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('tuyensinh.index') }}">Quản lý tuyển sinh</a></div>
                <div class="breadcrumb-item">Danh sách hồ sơ</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Danh sách hồ sơ</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalThemHoSo">
                                    <i class="fas fa-plus"></i> Thêm hồ sơ
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Mã hồ sơ</th>
                                            <th>Mã SV</th>
                                            <th>Họ tên</th>
                                            <th>Ngày nộp</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hoSo as $hs)
                                            <tr>
                                                <td>{{ $hs->MaHoSo }}</td>
                                                <td>{{ $hs->MaSV }}</td>
                                                <td>{{ $hs->sinhVien->HoTen ?? 'N/A' }}</td>
                                                <td>{{ $hs->NgayNopHS }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $hs->TrangThaiHS == 'DaTrungTuyen' ? 'success' : ($hs->TrangThaiHS == 'KhongTrungTuyen' ? 'danger' : ($hs->TrangThaiHS == 'DaXet' ? 'info' : 'warning')) }}">
                                                        @switch($hs->TrangThaiHS)
                                                            @case('DaNop')
                                                                Đã nộp
                                                            @break

                                                            @case('DaXet')
                                                                Đã xét
                                                            @break

                                                            @case('DaTrungTuyen')
                                                                Đã trúng tuyển
                                                            @break

                                                            @case('KhongTrungTuyen')
                                                                Không trúng tuyển
                                                            @break

                                                            @default
                                                                {{ $hs->TrangThaiHS }}
                                                        @endswitch
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-warning btn-sm"
                                                        onclick="capNhatTrangThai('{{ $hs->MaHoSo }}')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Hồ Sơ -->
    <div class="modal fade" id="modalThemHoSo" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm hồ sơ tuyển sinh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('tuyensinh.tao_hoso') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="MaTS" value="{{ $dotTuyenSinh->MaTS }}">
                        <div class="form-group">
                            <label>Mã sinh viên <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control @error('MaSV') is-invalid @enderror" name="MaSV"
                                value="{{ old('MaSV') }}">
                            @error('MaSV')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Ngày nộp <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('NgayNopHS') is-invalid @enderror"
                                name="NgayNopHS" value="{{ old('NgayNopHS') }}">
                            @error('NgayNopHS')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cập Nhật Trạng Thái -->
    <div class="modal fade" id="modalCapNhatTrangThai" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật trạng thái hồ sơ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCapNhatTrangThai" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select class="form-control" name="TrangThaiHS">
                                <option value="DaNop">Đã nộp</option>
                                <option value="DaXet">Đã xét</option>
                                <option value="DaTrungTuyen">Đã trúng tuyển</option>
                                <option value="KhongTrungTuyen">Không trúng tuyển</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        @if ($errors->any())
            $(document).ready(function() {
                $('#modalThemHoSo').modal('show');
            });
        @endif
        function capNhatTrangThai(maHoSo) {
            $('#modalCapNhatTrangThai').modal('show');
            $('#formCapNhatTrangThai').attr('action', `/tuyensinh/capnhat-trangthai/${maHoSo}`);
        }
    </script>
@endsection
