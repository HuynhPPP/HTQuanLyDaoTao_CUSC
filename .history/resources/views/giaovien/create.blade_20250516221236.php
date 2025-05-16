@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Thêm giáo viên mới</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('staff.index') }}">Danh sách giáo viên</a>
                </div>
                <div class="breadcrumb-item">Thêm giáo viên mới</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Form import Excel - Thiết kế tối giản -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Import danh sách giáo viên từ file Excel</h5>
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

                                <form action="{{ route('giaovien.import') }}" method="POST" enctype="multipart/form-data"
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

                        <!-- Form nhập giáo viên thủ công -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Nhập thông tin giáo viên mới</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('giaovien.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="MaGV" class="form-label">Mã giáo viên <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('MaGV') is-invalid @enderror"
                                                id="MaGV" name="MaGV" value="{{ old('MaGV') }}">
                                            @error('MaGV')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="HoTenGV" class="form-label">Họ và tên <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('HoTenGV') is-invalid @enderror" id="HoTenGV"
                                                name="HoTenGV" value="{{ old('HoTenGV') }}">
                                            @error('HoTenGV')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="GioiTinh" class="form-label">Giới tính <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control @error('GioiTinh') is-invalid @enderror"
                                                id="GioiTinh" name="GioiTinh">
                                                <option value="">Chọn giới tính</option>
                                                <option value="1" {{ old('GioiTinh') == '1' ? 'selected' : '' }}>
                                                    Nam</option>
                                                <option value="0" {{ old('GioiTinh') == '0' ? 'selected' : '' }}>Nữ
                                                </option>
                                            </select>
                                            @error('GioiTinh')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="Email" class="form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('Email') is-invalid @enderror"
                                                id="Email" name="Email" value="{{ old('Email') }}">
                                            @error('Email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="Sdt" class="form-label">Số điện thoại</label>
                                            <input type="text" class="form-control @error('Sdt') is-invalid @enderror"
                                                id="Sdt" name="Sdt" value="{{ old('Sdt') }}">
                                            @error('Sdt')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="LoaiGV" class="form-label">Loại giáo viên <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control @error('LoaiGV') is-invalid @enderror"
                                                id="LoaiGV" name="LoaiGV">
                                                <option value="">Chọn loại giáo viên</option>
                                                <option value="CoHuu" {{ old('LoaiGV') == 'CoHuu' ? 'selected' : '' }}>
                                                    Cơ hữu</option>
                                                <option value="MoiGiang"
                                                    {{ old('LoaiGV') == 'MoiGiang' ? 'selected' : '' }}>
                                                    Mời giảng</option>
                                            </select>
                                            @error('LoaiGV')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="MaHV" class="form-label">Học vị</label>
                                            <select class="form-control @error('MaHV') is-invalid @enderror"
                                                id="MaHV" name="MaHV">
                                                <option value="">Chọn học vị</option>
                                                @foreach ($hocvis as $hv)
                                                    <option value="{{ $hv->MaHV }}"
                                                        {{ old('MaHV') == $hv->MaHV ? 'selected' : '' }}>
                                                        {{ $hv->TenHocVi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('MaHV')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="TenChucVu" class="form-label">Chức vụ</label>
                                            <select class="form-control @error('TenChucVu') is-invalid @enderror"
                                                id="TenChucVu" name="TenChucVu">
                                                <option value="">Chọn chức vụ</option>
                                                @foreach ($chucvus as $cv)
                                                    <option value="{{ $cv->TenChucVu }}"
                                                        {{ old('TenChucVu') == $cv->TenChucVu ? 'selected' : '' }}>
                                                        {{ $cv->TenChucVu }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('TenChucVu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="MaDV" class="form-label">Đơn vị</label>
                                            <select class="form-control @error('MaDV') is-invalid @enderror"
                                                id="MaDV" name="MaDV">
                                                <option value="">Chọn đơn vị</option>
                                                @foreach ($donvis as $dv)
                                                    <option value="{{ $dv->MaDV }}"
                                                        {{ old('MaDV') == $dv->MaDV ? 'selected' : '' }}>
                                                        {{ $dv->TenDVHienTai }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('MaDV')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="MaBang" class="form-label">Bằng cấp</label>
                                            <select class="form-control @error('MaBang') is-invalid @enderror"
                                                id="MaBang" name="MaBang">
                                                <option value="">Chọn bằng cấp</option>
                                                @foreach ($bangcaps as $bc)
                                                    <option value="{{ $bc->MaBang }}"
                                                        {{ old('MaBang') == $bc->MaBang ? 'selected' : '' }}>
                                                        {{ $bc->TenBang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('MaBang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="ChuyenNganh" class="form-label">Chuyên ngành</label>
                                            <input type="text"
                                                class="form-control @error('ChuyenNganh') is-invalid @enderror"
                                                id="ChuyenNganh" name="ChuyenNganh" value="{{ old('ChuyenNganh') }}">
                                            @error('ChuyenNganh')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="NgayBatDauCongTac" class="form-label">Ngày bắt đầu công
                                                tác</label>
                                            <input type="date"
                                                class="form-control @error('NgayBatDauCongTac') is-invalid @enderror"
                                                id="NgayBatDauCongTac" name="NgayBatDauCongTac"
                                                value="{{ old('NgayBatDauCongTac') }}">
                                            @error('NgayBatDauCongTac')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="GhiChu" class="form-label">Ghi chú</label>
                                            <textarea class="form-control @error('GhiChu') is-invalid @enderror" id="GhiChu" name="GhiChu" rows="3">{{ old('GhiChu') }}</textarea>
                                            @error('GhiChu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <a href="{{ route('giaovien.index') }}" class="btn btn-secondary">
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
                            <li>Các cột bắt buộc: <code>magv, hotengv, gioitinh, email, loaigv</code></li>
                            <li>Cột <code>gioitinh</code> chỉ nhận giá trị <code>Nam</code> hoặc <code>Nữ</code></li>
                            <li>Cột <code>loaigv</code> chỉ nhận giá trị <code>CoHuu</code> hoặc <code>MoiGiang</code></li>
                        </ol>
                    </div>

                    <h6 class="font-weight-bold">Cấu trúc file Excel:</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-sm table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>magv</th>
                                    <th>hotengv</th>
                                    <th>gioitinh</th>
                                    <th>email</th>
                                    <th>sdt</th>
                                    <th>loaigv</th>
                                    <th>mahv</th>
                                    <th>tenhocvi</th>
                                    <th>tenchucvu</th>
                                    <th>madv</th>
                                    <th>tendvhientai</th>
                                    <th>mabang</th>
                                    <th>tenbang</th>
                                    <th>chuyennganh</th>
                                    <th>ngaybatdaucongtac</th>
                                    <th>ghichu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>GV001</td>
                                    <td>Nguyễn Văn An</td>
                                    <td>Nam</td>
                                    <td>ngvanan@gmail.com</td>
                                    <td>0912345678</td>
                                    <td>CoHuu</td>
                                    <td>HV001</td>
                                    <td>Tiến sĩ</td>
                                    <td>Giảng viên</td>
                                    <td>DV001</td>
                                    <td>Khoa CNTT</td>
                                    <td>BC001</td>
                                    <td>Bằng TSKH</td>
                                    <td>Khoa học máy tính</td>
                                    <td>2020-01-01</td>
                                    <td>Giảng viên chính</td>
                                </tr>
                                <tr>
                                    <td>GV002</td>
                                    <td>Trần Thị Bình</td>
                                    <td>Nữ</td>
                                    <td>tranthib@gmail.com</td>
                                    <td>0923456789</td>
                                    <td>MoiGiang</td>
                                    <td>HV002</td>
                                    <td>Thạc sĩ</td>
                                    <td>Trưởng khoa</td>
                                    <td>DV002</td>
                                    <td>Khoa Kinh tế</td>
                                    <td>BC002</td>
                                    <td>Bằng ThS</td>
                                    <td>Kinh tế học</td>
                                    <td>2018-05-15</td>
                                    <td>Giảng viên thỉnh giảng</td>
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
            $('form[action="{{ route('giaovien.import') }}"]').on('submit', function(e) {
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
            let templateData = document.getElementById('excelTemplateData').innerText;

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
                // Dữ liệu mẫu cứng
                const headerRow =
                    "magv,hotengv,gioitinh,email,sdt,loaigv,mahv,tenhocvi,tenchucvu,madv,tendvhientai,mabang,tenbang,chuyennganh,ngaybatdaucongtac,ghichu";
                const dataRow1 =
                    "GV001,Nguyễn Văn An,Nam,ngvanan@gmail.com,0912345678,CoHuu,HV001,Tiến sĩ,Giảng viên,DV001,Khoa CNTT,BC001,Bằng TSKH,Khoa học máy tính,2020-01-01,Giảng viên chính";
                const dataRow2 =
                    "GV002,Trần Thị Bình,Nữ,tranthib@gmail.com,0923456789,MoiGiang,HV002,Thạc sĩ,Trưởng khoa,DV002,Khoa Kinh tế,BC002,Bằng ThS,Kinh tế học,2018-05-15,Giảng viên thỉnh giảng";

                const rows = [
                    headerRow.split(','),
                    dataRow1.split(','),
                    dataRow2.split(',')
                ];

                // Tạo worksheet
                const ws = XLSX.utils.aoa_to_sheet(rows);

                // Tạo workbook
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Danh sách giáo viên');

                // Xuất file
                XLSX.writeFile(wb, 'danh_sach_giao_vien_mau.xlsx');

                // Hiển thị thông báo thành công
                iziToast.success({
                    title: 'Thành công',
                    message: 'Đã tải file Excel mẫu thành công!',
                    position: 'topRight'
                });
            } catch (error) {
                console.error("Lỗi khi tạo file Excel:", error);

                // Phương pháp thay thế: Tạo CSV
                const csvData =
                    `magv,hotengv,gioitinh,email,sdt,loaigv,mahv,tenhocvi,tenchucvu,madv,tendvhientai,mabang,tenbang,chuyennganh,ngaybatdaucongtac,ghichu
GV001,Nguyễn Văn An,Nam,ngvanan@gmail.com,0912345678,CoHuu,HV001,Tiến sĩ,Giảng viên,DV001,Khoa CNTT,BC001,Bằng TSKH,Khoa học máy tính,2020-01-01,Giảng viên chính
GV002,Trần Thị Bình,Nữ,tranthib@gmail.com,0923456789,MoiGiang,HV002,Thạc sĩ,Trưởng khoa,DV002,Khoa Kinh tế,BC002,Bằng ThS,Kinh tế học,2018-05-15,Giảng viên thỉnh giảng`;

                let blob = new Blob([csvData], {
                    type: 'text/csv;charset=utf-8;'
                });
                let url = URL.createObjectURL(blob);

                let link = document.createElement("a");
                link.setAttribute("href", url);
                link.setAttribute("download", "giaovien_template.csv");
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
