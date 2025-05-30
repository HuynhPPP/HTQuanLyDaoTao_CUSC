@extends('layouts.new_app.master')

@section('title', 'Danh Sách Sinh Viên Dự Thi')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Danh sách sinh viên dự thi</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <div class="card-header-action">
                    <a href="{{ route('sinhvien.duthi.xuat-excel', $lichThi->MaLichThi) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Xuất Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Môn Thi:</strong> {{ $lichThi->TenMH }}</p>
                        <p><strong>Lớp:</strong> {{ $lichThi->MaLop }}</p>
                        <p><strong>Ngày Thi:</strong> {{ $lichThi->NgayThi }}</p>
                        <p><strong>Giờ Thi:</strong> {{ $lichThi->KhungGio }}</p>
                        <p><strong>Phòng Thi:</strong> {{ $lichThi->PhongThi }}</p>
                    </div>
                </div>

                <form action="{{ route('sinhvien.duthi.luu') }}" method="POST">
                    @csrf
                    <input type="hidden" name="MaLichThi" value="{{ $lichThi->MaLichThi }}">
                    
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-sinh-vien">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" id="check-all" class="custom-control-input">
                                            <label for="check-all" class="custom-control-label">Chọn</label>
                                        </div>
                                    </th>
                                    <th>Mã SV</th>
                                    <th>Họ Tên</th>
                                    <th>Email</th>
                                    <th>Số ĐT</th>
                                    <th>Trạng Thái</th>
                                    <th>Ghi Chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($danhSachSinhVien as $sinhVien)
                                    <tr>
                                        <td>
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" 
                                                       name="sinhvien[{{ $sinhVien->MaSV }}][DuThi]" 
                                                       id="sv-{{ $sinhVien->MaSV }}" 
                                                       class="custom-control-input"
                                                       {{ $sinhVien->TrangThaiDuThi ? 'checked' : '' }}>
                                                <label for="sv-{{ $sinhVien->MaSV }}" class="custom-control-label"></label>
                                            </div>
                                        </td>
                                        <td>{{ $sinhVien->MaSV }}</td>
                                        <td>{{ $sinhVien->HoTen }}</td>
                                        <td>{{ $sinhVien->Email }}</td>
                                        <td>{{ $sinhVien->Sdt }}</td>
                                        <td>
                                            <select name="sinhvien[{{ $sinhVien->MaSV }}][TrangThaiDuThi]" class="form-control">
                                                <option value="DangKy" {{ $sinhVien->TrangThaiDuThi == 'DangKy' ? 'selected' : '' }}>Đăng Ký</option>
                                                <option value="DuThi" {{ $sinhVien->TrangThaiDuThi == 'DuThi' ? 'selected' : '' }}>Dự Thi</option>
                                                <option value="VangMat" {{ $sinhVien->TrangThaiDuThi == 'VangMat' ? 'selected' : '' }}>Vắng Mặt</option>
                                                <option value="KhongDuThi" {{ $sinhVien->TrangThaiDuThi == 'KhongDuThi' ? 'selected' : '' }}>Không Dự Thi</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   name="sinhvien[{{ $sinhVien->MaSV }}][GhiChu]" 
                                                   class="form-control" 
                                                   value="{{ $sinhVien->GhiChu }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Lưu Danh Sách</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Chọn tất cả sinh viên
        $('#check-all').on('change', function() {
            $('input[type="checkbox"][name^="sinhvien"]').prop('checked', $(this).prop('checked'));
        });
    });
</script>
@endpush