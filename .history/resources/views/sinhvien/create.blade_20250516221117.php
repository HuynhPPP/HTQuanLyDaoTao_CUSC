@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Thêm sinh viên mới</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="#">Danh sách sinh viên</a>
                </div>
                <div class="breadcrumb-item">Thêm sinh viên mới</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Form import Excel - Thiết kế tối giản -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Import danh sách sinh viên từ file Excel</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-info mb-3" data-toggle="modal"
                                            data-target="#importGuideModal">
                                            <i class="fas fa-question-circle"></i> Xem hướng dẫn import
                                        </button>
                                    </div>
                                </div>

                                <form action="{{ route('student.import') }}" method="POST" enctype="multipart/form-data"
                                    class="mb-3">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-file">
                                                <input type="file" name="file" class="custom-file-input"
                                                    id="importFile" accept=".xlsx,.xls,.csv">
                                                <label class="custom-file-label" for="importFile">Chọn file Excel
                                                    (.xlsx, .xls hoặc .csv)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-success mr-2">
                                                <i class="fas fa-file-import"></i> Tải lên file Excel
                                            </button>
                                            <button type="button" class="btn btn-secondary" onclick="downloadTemplate()">
                                                <i class="fas fa-download"></i> Tải file mẫu
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Form nhập sinh viên thủ công -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Nhập thông tin sinh viên mới</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('student.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="MaSV" class="form-label">Mã sinh viên <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('MaSV') is-invalid @enderror"
                                                id="MaSV" name="MaSV" value="{{ old('MaSV') }}">
                                            @error('MaSV')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="HoTen" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('HoTen') is-invalid @enderror"
                                                id="HoTen" name="HoTen" value="{{ old('HoTen') }}">
                                            @error('HoTen')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="NgaySinh" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('NgaySinh') is-invalid @enderror"
                                                id="NgaySinh" name="NgaySinh" value="{{ old('NgaySinh') }}">
                                            @error('NgaySinh')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="GioiTinh" class="form-label">Giới tính <span class="text-danger">*</span></label>
                                            <select class="form-control @error('GioiTinh') is-invalid @enderror" id="GioiTinh" name="GioiTinh">
                                                <option value="">Chọn giới tính</option>
                                                <option value="Nam" {{ old('GioiTinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                                <option value="Nữ" {{ old('GioiTinh') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                            </select>
                                            @error('GioiTinh')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="SoCCCD" class="form-label">Số CCCD <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('SoCCCD') is-invalid @enderror"
                                                id="SoCCCD" name="SoCCCD" value="{{ old('SoCCCD') }}">
                                            @error('SoCCCD')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="Email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('Email') is-invalid @enderror"
                                                id="Email" name="Email" value="{{ old('Email') }}">
                                            @error('Email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="Sdt" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('Sdt') is-invalid @enderror"
                                                id="Sdt" name="Sdt" value="{{ old('Sdt') }}">
                                            @error('Sdt')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="DiaChi" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('DiaChi') is-invalid @enderror"
                                                id="DiaChi" name="DiaChi" value="{{ old('DiaChi') }}">
                                            @error('DiaChi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <a href="{{ route('student.list') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Quay lại
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Lưu thông tin
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hướng dẫn Import Excel -->
    <div class="modal fade" id="importGuideModal" tabindex="-1" role="dialog" aria-labelledby="importGuideModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="importGuideModalLabel">Hướng dẫn import file Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h6 class="font-weight-bold">Hướng dẫn import file Excel:</h6>
                        <ol>
                            <li>Các cột bắt buộc: <code>masv, hoten, ngaysinh, gioitinh, socccd, email, sdt, diachi</code></li>
                            <li>Cột <code>gioitinh</code> chỉ nhận giá trị <code>Nam</code> hoặc <code>Nữ</code></li>
                        </ol>
                    </div>

                    <h6 class="font-weight-bold">Cấu trúc file Excel:</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-sm table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>masv</th>
                                    <th>hoten</th>
                                    <th>ngaysinh</th>
                                    <th>gioitinh</th>
                                    <th>socccd</th>
                                    <th>email</th>
                                    <th>sdt</th>
                                    <th>diachi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>21010001</td>
                                    <td>Nguyễn Văn A</td>
                                    <td>2003-06-12</td>
                                    <td>Nam</td>
                                    <td>123456789</td>
                                    <td>nguyenvana@gmail.com</td>
                                    <td>0912345678</td>
                                    <td>Ninh Kiều, Cần Thơ</td>
                                </tr>
                                <tr>
                                    <td>21010002</td>
                                    <td>Trần Thị B</td>
                                    <td>2003-09-15</td>
                                    <td>Nữ</td>
                                    <td>987654321</td>
                                    <td>tranthib@gmail.com</td>
                                    <td>0987654321</td>
                                    <td>Bình Thủy, Cần Thơ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="copyTemplateToClipboard()">
                        <i class="fas fa-copy"></i> Sao chép dữ liệu mẫu
                    </button>
                    <button type="button" class="btn btn-success" onclick="downloadTemplate()">
                        <i class="fas fa-download"></i> Tải file mẫu
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
            // Hiển thị tên file khi chọn
            $('#importFile').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName ||
                    'Chọn file Excel (.xlsx, .xls hoặc .csv)');

                // Kiểm tra định dạng file
                if (fileName) {
                    let extension = fileName.split('.').pop().toLowerCase();
                    if ($.inArray(extension, ['xlsx', 'xls', 'csv']) == -1) {
                        $(this).addClass('is-invalid');
                        if (!$(this).next().next('.invalid-feedback').length) {
                            $('<div class="invalid-feedback">File phải có định dạng Excel (.xlsx, .xls, .csv)</div>')
                                .insertAfter($(this).next());
                        }
                    } else {
                        $(this).removeClass('is-invalid');
                        $(this).next().next('.invalid-feedback').remove();
                    }
                }
            });

            // Validate form import trước khi submit
            $('form[action="{{ route('student.import') }}"]').on('submit', function(e) {
                if (!$('#importFile').val()) {
                    e.preventDefault();
                    $('#importFile').addClass('is-invalid');
                    if (!$('#importFile').next().next('.invalid-feedback').length) {
                        $('<div class="invalid-feedback">Vui lòng chọn file để import</div>').insertAfter($(
                            '#importFile').next());
                    }

                    // Hiển thị thông báo lỗi
                    iziToast.error({
                        title: 'Lỗi',
                        message: 'Vui lòng chọn file Excel để import',
                        position: 'topRight'
                    });

                    return false;
                }

                return true;
            });
        });

        // Sao chép vào clipboard
        function copyTemplateToClipboard() {
            let templateData = `masv,hoten,ngaysinh,gioitinh,socccd,email,sdt,diachi
21010001,Nguyễn Văn A,2003-06-12,Nam,123456789,nguyenvana@gmail.com,0912345678,Ninh Kiều, Cần Thơ
21010002,Trần Thị B,2003-09-15,Nữ,987654321,tranthib@gmail.com,0987654321,Bình Thủy, Cần Thơ`;

            navigator.clipboard.writeText(templateData).then(() => {
                iziToast.success({
                    message: 'Đã sao chép dữ liệu mẫu vào clipboard!',
                    position: 'topRight'
                });
            }).catch(err => {
                iziToast.error({
                    message: 'Không thể sao chép: ' + err,
                    position: 'topRight'
                });
            });
        }

        // Tải file mẫu
        function downloadTemplate() {
            try {
                const headerRow = ["masv", "hoten", "ngaysinh", "gioitinh", "socccd", "email", "sdt", "diachi"];
                const dataRow1 = ["21010001", "Nguyễn Văn A", "2003-06-12", "Nam", "123456789", "nguyenvana@gmail.com", "0912345678", "Ninh Kiều, Cần Thơ"];
                const dataRow2 = ["21010002", "Trần Thị B", "2003-09-15", "Nữ", "987654321", "tranthib@gmail.com", "0987654321", "Bình Thủy, Cần Thơ"];

                const rows = [headerRow, dataRow1, dataRow2];

                // Tạo worksheet
                const ws = XLSX.utils.aoa_to_sheet(rows);

                // Tạo workbook
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Danh sách sinh viên');

                // Xuất file
                XLSX.writeFile(wb, 'danh_sach_sinh_vien_mau.xlsx');

                // Hiển thị thông báo thành công
                iziToast.success({
                    title: 'Thành công',
                    message: 'Đã tải file Excel mẫu thành công!',
                    position: 'topRight'
                });
            } catch (error) {
                console.error("Lỗi khi tạo file Excel:", error);

                // Phương pháp thay thế: Tạo CSV
                const csvData = `masv,hoten,ngaysinh,gioitinh,socccd,email,sdt,diachi
21010001,Nguyễn Văn A,2003-06-12,Nam,123456789,nguyenvana@gmail.com,0912345678,Ninh Kiều\, Cần Thơ
21010002,Trần Thị B,2003-09-15,Nữ,987654321,tranthib@gmail.com,0987654321,Bình Thủy\, Cần Thơ`;

                let blob = new Blob([csvData], {
                    type: 'text/csv;charset=utf-8;'
                });
                let url = URL.createObjectURL(blob);

                let link = document.createElement("a");
                link.setAttribute("href", url);
                link.setAttribute("download", "sinhvien_template.csv");
                link.style.visibility = 'hidden';

                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Hiển thị thông báo
                iziToast.warning({
                    title: 'Lưu ý',
                    message: 'Không thể tạo file Excel. Đã tải file CSV thay thế. Khi mở trong Excel, hãy chọn "Delimiter: Comma".',
                    position: 'topCenter',
                    timeout: 10000
                });
            }
        }
    </script>
@endsection