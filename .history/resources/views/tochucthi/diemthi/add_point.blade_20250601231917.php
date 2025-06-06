@extends('layouts.new_app.master')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <style>
        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }
        .invalid-feedback {
            color: red;
            display: none;
        }
        .form-control.is-invalid {
            border-color: red;
        }
    </style>
@endsection

@section('main-content')
    <div class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1>Nhập điểm thi - Lớp: {{ $lop->MaLop }} - Môn: {{ $tenMH }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('bangdiem.chonLopVaMon') }}">Chọn lớp & môn</a></div>
                <div class="breadcrumb-item">Nhập điểm</div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('bangdiem.import') }}" method="POST" enctype="multipart/form-data" id="diemForm" novalidate>
                        @csrf
                        <input type="hidden" name="maLop" value="{{ $maLop }}">
                        <input type="hidden" name="tenMH" value="{{ $tenMH }}">

                        <!-- Debug: Hiển thị dữ liệu để kiểm tra -->
                        @if (session('debug'))
                            <div class="alert alert-info">
                                <strong>Debug Info:</strong>
                                <pre>{{ print_r(session('debug'), true) }}</pre>
                            </div>
                        @endif

                        <!-- Tìm kiếm và lọc sinh viên -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" id="searchStudent" class="form-control" placeholder="Tìm kiếm sinh viên (Mã SV, Tên)">
                            </div>
                        </div>

                        <!-- Nhập điểm thủ công -->
                        <div class="table-responsive">
                            <table class="table table-striped" id="diemTable">
                                <thead>
                                    <tr>
                                        <th>Mã SV</th>
                                        <th>Họ Tên</th>
                                        <th>Lần thi</th>
                                        <th>Điểm</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($danhSachSV as $index => $sv)
                                        <tr>
                                            <td>{{ $sv->sinhVien->MaSV }}</td>
                                            <td>{{ $sv->sinhVien->HoTen }}</td>
                                            <td>
                                                <input type="number" name="lanThi[{{ $sv->sinhVien->MaSV }}]"
                                                    class="form-control lan-thi-input"
                                                    value="{{ $sv->diem ? $sv->diem->LanThi : 1 }}" 
                                                    min="1" max="3"
                                                    data-masv="{{ $sv->sinhVien->MaSV }}" 
                                                    required>
                                                <div class="invalid-feedback">Lần thi từ 1-3</div>
                                            </td>
                                            <td>
                                                <input type="number" name="diem[{{ $sv->sinhVien->MaSV }}]"
                                                    class="form-control diem-input" 
                                                    step="0.1" min="0" max="10"
                                                    value="{{ $sv->diem ? $sv->diem->Diem : '' }}"
                                                    data-masv="{{ $sv->sinhVien->MaSV }}" 
                                                    placeholder="Nhập điểm">
                                                <div class="invalid-feedback">Điểm từ 0-10</div>
                                            </td>
                                            <td>
                                                <input type="text" name="ghiChu[{{ $sv->sinhVien->MaSV }}]"
                                                    class="form-control ghi-chu-input"
                                                    value="{{ $sv->diem ? $sv->diem->GhiChu : '' }}"
                                                    data-masv="{{ $sv->sinhVien->MaSV }}" 
                                                    placeholder="Ghi chú (tùy chọn)">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Import Excel -->
                        <div class="form-group mt-3">
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="excelFile" 
                                    accept=".xlsx,.xls" aria-describedby="fileHelp">
                                <label class="custom-file-label" for="excelFile">Chọn file Excel</label>
                                <small id="fileHelp" class="form-text text-muted">
                                    Tải file Excel mẫu <a href="{{ route('bangdiem.export', ['maLop' => $maLop, 'tenMH' => $tenMH]) }}">tại đây</a>
                                </small>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Lưu điểm
                            </button>
                            <a href="{{ route('bangdiem.export', ['maLop' => $maLop, 'tenMH' => $tenMH]) }}"
                                class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Xuất Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            // DataTable
            const table = $('#diemTable').DataTable({
                pageLength: 10,
                language: {
                    lengthMenu: 'Hiển thị _MENU_ dòng',
                    zeroRecords: 'Không tìm thấy sinh viên',
                    info: 'Trang _PAGE_/_PAGES_',
                    infoEmpty: 'Không có dữ liệu',
                    search: 'Tìm kiếm:',
                    paginate: {
                        first: 'Đầu',
                        last: 'Cuối',
                        next: 'Tiếp',
                        previous: 'Trước'
                    }
                }
            });

            // Tìm kiếm sinh viên
            $('#searchStudent').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Validation điểm
            $('.diem-input').on('input', function() {
                const value = parseFloat($(this).val());
                const isValid = value >= 0 && value <= 10;
                
                if (isValid || $(this).val() === '') {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').hide();
                } else {
                    $(this).addClass('is-invalid');
                    $(this).next('.invalid-feedback').show();
                }
            });

            // Validation lần thi
            $('.lan-thi-input').on('input', function() {
                const value = parseInt($(this).val());
                const isValid = value >= 1 && value <= 3;
                
                if (isValid) {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').hide();
                } else {
                    $(this).addClass('is-invalid');
                    $(this).next('.invalid-feedback').show();
                }
            });

            // Ngăn submit khi có lỗi
            $('#diemForm').on('submit', function(e) {
                let hasError = false;
                
                $('.diem-input').each(function() {
                    const value = parseFloat($(this).val());
                    if (value !== '' && (value < 0 || value > 10)) {
                        $(this).addClass('is-invalid');
                        $(this).next('.invalid-feedback').show();
                        hasError = true;
                    }
                });

                $('.lan-thi-input').each(function() {
                    const value = parseInt($(this).val());
                    if (value < 1 || value > 3) {
                        $(this).addClass('is-invalid');
                        $(this).next('.invalid-feedback').show();
                        hasError = true;
                    }
                });

                if (hasError) {
                    e.preventDefault();
                    alert('Vui lòng kiểm tra lại các trường nhập');
                }
            });

            // Hiển thị tên file được chọn
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass('selected').html(fileName);
            });
        });
    </script>
@endsection
