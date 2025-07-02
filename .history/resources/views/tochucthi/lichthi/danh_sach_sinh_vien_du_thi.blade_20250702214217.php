@extends('layouts.new_app.master')

@section('title', 'Danh Sách Sinh Viên Dự Thi')


@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Lập danh sách sinh viên dự thi - {{ $lichThi->monHoc->TenMH }} - {{ $lichThi->MaLop }}</h1>
        </div>

        <div class="section-body">
            <div class="card card-primary shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">
                        <i class="fas fa-info-circle"></i> Chi tiết lịch thi
                    </h4>
                    <div class="card-header-action">
                        <div>
                            <a href="{{ route('sinhvien.duthi.xuat-excel', $lichThi->MaLichThi) }}"
                                class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel"></i> Xuất file Excel
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong>Môn Thi:</strong>
                                    <span>{{ $lichThi->TenMH }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong>Lớp:</strong>
                                    <span>{{ $lichThi->MaLop }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong>Ngày Thi:</strong>
                                    <span>{{ \Carbon\Carbon::parse($lichThi->NgayThi)->format('d-m-Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong>Giờ Thi:</strong>
                                    <span>{{ $lichThi->KhungGio }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong>Phòng Thi:</strong>
                                    <span>{{ $lichThi->PhongThi }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong>Cán bộ coi thi:</strong>
                                    <span>
                                        @if ($giaoViens && $giaoViens->count())
                                            {{ $giaoViens->map(fn($gv) => $gv->giaoVien->HoTenGV)->implode(', ') }}
                                        @else
                                            <em>Chưa có cán bộ coi thi</em>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('sinhvien.duthi.luu') }}" method="POST" id="formDanhSachDuThi">
                        @csrf
                        <input type="hidden" name="MaLichThi" value="{{ $lichThi->MaLichThi }}">

                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-hover" id="table-1">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Mã SV</th>
                                        <th>Họ Tên</th>
                                        <th>Email</th>
                                        <th>Số ĐT</th>
                                        <th>Trạng Thái</th>
                                        <th>Ghi Chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($danhSachSinhVien as $sinhVien)
                                        <tr>
                                            <td>{{ $sinhVien->MaSV }}</td>
                                            <td>{{ $sinhVien->HoTen }}</td>
                                            <td>{{ $sinhVien->Email }}</td>
                                            <td>{{ $sinhVien->Sdt }}</td>
                                            <td>
                                                <select name="sinhvien[{{ $sinhVien->MaSV }}][TrangThaiDuThi]"
                                                    class="form-control form-control-sm trang-thai-select">
                                                    <option value="ChuaDangKy"
                                                        {{ $sinhVien->TrangThaiDuThi == 'ChuaDangKy' ? 'selected' : '' }}>
                                                        Chưa đăng
                                                        ký</option>
                                                    <option value="DuThi"
                                                        {{ $sinhVien->TrangThaiDuThi == 'DuThi' ? 'selected' : '' }}>Dự thi
                                                    </option>
                                                    {{-- <option value="VangMat"
                                                        {{ $sinhVien->TrangThaiDuThi == 'VangMat' ? 'selected' : '' }}>Vắng
                                                        mặt</option> --}}
                                                    <option value="KhongDuThi"
                                                        {{ $sinhVien->TrangThaiDuThi == 'KhongDuThi' ? 'selected' : '' }}>
                                                        Không dự thi</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="sinhvien[{{ $sinhVien->MaSV }}][GhiChu]"
                                                    class="form-control form-control-sm ghi-chu-input"
                                                    value="{{ $sinhVien->GhiChu }}" placeholder="Ghi chú (nếu có)">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer d-flex justify-content-end align-items-center">
                            <div>
                                <button type="button" class="btn btn-secondary mr-2" id="resetForm">
                                    <i class="fas fa-redo"></i> Đặt Lại
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Lưu Danh Sách
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            // Đặt lại form
            $('#resetForm').on('click', function() {
                $('.sinh-vien-checkbox').prop('checked', false);
                $('.trang-thai-select').val('DangKy');
                $('.ghi-chu-input').val('');
            });
        });
    </script>
@endsection
