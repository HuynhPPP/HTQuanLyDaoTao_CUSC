{{-- @extends('layouts.new_app.master')

@section('title', 'Danh Sách Sinh Viên Dự Thi')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Danh sách sinh viên dự thi</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Chi tiết lịch thi</h4>
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
@endpush --}}

@extends('layouts.new_app.master')

@section('title', 'Danh Sách Sinh Viên Dự Thi')


@section('main-content')
<section class="section">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h1 class="text-primary">
            <i class="fas fa-clipboard-list"></i> Danh Sách Sinh Viên Dự Thi
        </h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{ route('lichthi.index') }}">Lịch Thi</a>
            </div>
            <div class="breadcrumb-item">Chi Tiết Dự Thi</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card card-primary shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">
                    <i class="fas fa-info-circle"></i> Chi Tiết Lịch Thi
                </h4>
                <div class="card-header-action">
                    <div class="btn-group" role="group">
                        <a href="{{ route('sinhvien.duthi.xuat-excel', $lichThi->MaLichThi) }}" 
                           class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Xuất Excel
                        </a>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#filterModal">
                            <i class="fas fa-filter"></i> Lọc Danh Sách
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Môn Thi:</strong> 
                                <span class="badge badge-primary badge-pill">{{ $lichThi->TenMH }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Lớp:</strong> 
                                <span class="badge badge-info badge-pill">{{ $lichThi->MaLop }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Ngày Thi:</strong> 
                                <span class="badge badge-success badge-pill">{{ $lichThi->NgayThi }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Giờ Thi:</strong> 
                                <span class="badge badge-warning badge-pill">{{ $lichThi->KhungGio }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Phòng Thi:</strong> 
                                <span class="badge badge-danger badge-pill">{{ $lichThi->PhongThi }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('sinhvien.duthi.luu') }}" method="POST" id="formDanhSachDuThi">
                    @csrf
                    <input type="hidden" name="MaLichThi" value="{{ $lichThi->MaLichThi }}">
                    
                    <div class="table-responsive mt-3">
                        <table class="table table-striped table-hover" id="tableSinhVienDuThi">
                            <thead class="thead-light">
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkAll">
                                            <label class="custom-control-label" for="checkAll"></label>
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
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                       name="sinhvien[{{ $sinhVien->MaSV }}][DuThi]" 
                                                       id="sv-{{ $sinhVien->MaSV }}" 
                                                       class="custom-control-input sinh-vien-checkbox"
                                                       {{ $sinhVien->TrangThaiDuThi ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="sv-{{ $sinhVien->MaSV }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $sinhVien->MaSV }}</td>
                                        <td>{{ $sinhVien->HoTen }}</td>
                                        <td>{{ $sinhVien->Email }}</td>
                                        <td>{{ $sinhVien->Sdt }}</td>
                                        <td>
                                            <select name="sinhvien[{{ $sinhVien->MaSV }}][TrangThaiDuThi]" 
                                                    class="form-control form-control-sm trang-thai-select">
                                                <option value="DangKy" 
                                                    {{ $sinhVien->TrangThaiDuThi == 'DangKy' ? 'selected' : '' }}>
                                                    Đăng Ký
                                                </option>
                                                <option value="DuThi" 
                                                    {{ $sinhVien->TrangThaiDuThi == 'DuThi' ? 'selected' : '' }}>
                                                    Dự Thi
                                                </option>
                                                <option value="VangMat" 
                                                    {{ $sinhVien->TrangThaiDuThi == 'VangMat' ? 'selected' : '' }}>
                                                    Vắng Mặt
                                                </option>
                                                <option value="KhongDuThi" 
                                                    {{ $sinhVien->TrangThaiDuThi == 'KhongDuThi' ? 'selected' : '' }}>
                                                    Không Dự Thi
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   name="sinhvien[{{ $sinhVien->MaSV }}][GhiChu]" 
                                                   class="form-control form-control-sm ghi-chu-input" 
                                                   value="{{ $sinhVien->GhiChu }}"
                                                   placeholder="Ghi chú (nếu có)">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="autoSave">
                            <label class="custom-control-label" for="autoSave">Tự động lưu</label>
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary mr-2" id="resetForm">
                                <i class="fas fa-redo"></i> Đặt Lại
                            </button>
                            <button type="submit" class="btn btn-primary" id="luuDanhSach">
                                <i class="fas fa-save"></i> Lưu Danh Sách
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Modal Lọc Danh Sách -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lọc Danh Sách Sinh Viên</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Trạng Thái</label>
                    <select id="filterTrangThai" class="form-control select2" multiple>
                        <option value="DangKy">Đăng Ký</option>
                        <option value="DuThi">Dự Thi</option>
                        <option value="VangMat">Vắng Mặt</option>
                        <option value="KhongDuThi">Không Dự Thi</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="applyFilter">Áp Dụng</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Khởi tạo DataTable
    const table = $('#tableSinhVienDuThi').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json'
        },
        columnDefs: [
            { orderable: false, targets: 0 }
        ],
        order: [[1, 'asc']]
    });

    // Khởi tạo Select2 cho filter
    $('#filterTrangThai').select2({
        placeholder: "Chọn trạng thái",
        allowClear: true
    });

    // Chọn tất cả sinh viên
    $('#checkAll').on('change', function() {
        $('.sinh-vien-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Áp dụng bộ lọc
    $('#applyFilter').on('click', function() {
        const selectedStatuses = $('#filterTrangThai').val();
        
        table.column(5).search(
            selectedStatuses ? selectedStatuses.join('|') : '', 
            true, 
            false
        ).draw();

        $('#filterModal').modal('hide');
    });

    // Tự động lưu
    let autoSaveTimer;
    $('#autoSave').on('change', function() {
        if ($(this).is(':checked')) {
            autoSaveTimer = setInterval(function() {
                $('#formDanhSachDuThi').submit();
            }, 60000); // Tự động lưu mỗi 1 phút
        } else {
            clearInterval(autoSaveTimer);
        }
    });

    // Đặt lại form
    $('#resetForm').on('click', function() {
        $('.sinh-vien-checkbox').prop('checked', false);
        $('.trang-thai-select').val('DangKy');
        $('.ghi-chu-input').val('');
    });

    // Xử lý submit form
    $('#formDanhSachDuThi').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Lưu Thành Công',
                    text: 'Danh sách sinh viên dự thi đã được cập nhật'
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Không thể lưu danh sách. Vui lòng thử lại'
                });
            }
        });
    });
});
</script>
@endpush