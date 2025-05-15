@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Thêm cán bộ mới</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-body">
                            <!-- Form import Excel - Thiết kế tối giản -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Import danh sách cán bộ từ file Excel</h5>
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

                                    <form action="{{ route('staff.import') }}" method="POST" enctype="multipart/form-data"
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
                                                    <i class="fas fa-file-import"></i> Import dữ liệu
                                                </button>
                                                <button type="button" class="btn btn-secondary"
                                                    onclick="downloadTemplate()">
                                                    <i class="fas fa-download"></i> Tải file mẫu
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    {{-- <div class="alert alert-warning mt-3">
                                        <i class="fas fa-exclamation-triangle"></i> <strong>Lưu ý:</strong> Trước khi import dữ liệu cán bộ, hãy đảm bảo các dữ liệu liên quan (học vị, chức vụ, đơn vị...) đã được thêm vào hệ thống.
                                    </div> --}}
                                </div>
                            </div>

                            <!-- Hiển thị thông báo lỗi import -->
                            @if (session('warning'))
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                                </div>
                                @if (session('import_errors'))
                                    <div class="card mb-4">
                                        <div class="card-header bg-danger text-white">
                                            <h6 class="mb-0">Danh sách lỗi khi import</h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group">
                                                @foreach (session('import_errors') as $error)
                                                    <li class="list-group-item list-group-item-danger">{{ $error }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <!-- Form nhập cán bộ thủ công -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Nhập thông tin cán bộ mới</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="MaCB" class="form-label">Mã cán bộ <span
                                                        class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('MaCB') is-invalid @enderror" id="MaCB"
                                                    name="MaCB" value="{{ old('MaCB') }}">
                                                @error('MaCB')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="HoTenCB" class="form-label">Họ và tên <span
                                                        class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('HoTenCB') is-invalid @enderror"
                                                    id="HoTenCB" name="HoTenCB" value="{{ old('HoTenCB') }}">
                                                @error('HoTenCB')
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
                                                <input type="email"
                                                    class="form-control @error('Email') is-invalid @enderror" id="Email"
                                                    name="Email" value="{{ old('Email') }}">
                                                @error('Email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="Sdt" class="form-label">Số điện thoại <span
                                                        class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('Sdt') is-invalid @enderror" id="Sdt"
                                                    name="Sdt" value="{{ old('Sdt') }}">
                                                @error('Sdt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

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
                                        </div>

                                        <div class="row">
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

                                            <div class="col-md-6 mb-3">
                                                <label for="CongViecPhuTrach" class="form-label">Công việc phụ
                                                    trách</label>
                                                <input type="text"
                                                    class="form-control @error('CongViecPhuTrach') is-invalid @enderror"
                                                    id="CongViecPhuTrach" name="CongViecPhuTrach"
                                                    value="{{ old('CongViecPhuTrach') }}">
                                                @error('CongViecPhuTrach')
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
                                                <label for="MaTapHuan" class="form-label">Khóa tập huấn</label>
                                                <select class="form-control @error('MaTapHuan') is-invalid @enderror"
                                                    id="MaTapHuan" name="MaTapHuan">
                                                    <option value="">Chọn khóa tập huấn</option>
                                                    @foreach ($taphuans as $th)
                                                        <option value="{{ $th->MaTapHuan }}"
                                                            {{ old('MaTapHuan') == $th->MaTapHuan ? 'selected' : '' }}>
                                                            {{ $th->TenKhoaTapHuan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('MaTapHuan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="ThoiGianBDCongTacCUSC" class="form-label">Thời gian bắt đầu
                                                    công tác</label>
                                                <input type="date"
                                                    class="form-control @error('ThoiGianBDCongTacCUSC') is-invalid @enderror"
                                                    id="ThoiGianBDCongTacCUSC" name="ThoiGianBDCongTacCUSC"
                                                    value="{{ old('ThoiGianBDCongTacCUSC') }}">
                                                @error('ThoiGianBDCongTacCUSC')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="ThoiGianKTCongTacCUSC" class="form-label">Thời gian kết thúc
                                                    công tác</label>
                                                <input type="date"
                                                    class="form-control @error('ThoiGianKTCongTacCUSC') is-invalid @enderror"
                                                    id="ThoiGianKTCongTacCUSC" name="ThoiGianKTCongTacCUSC"
                                                    value="{{ old('ThoiGianKTCongTacCUSC') }}">
                                                @error('ThoiGianKTCongTacCUSC')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between mt-4">
                                            <a href="{{ route('staff.index') }}" class="btn btn-secondary">
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
                            <li>Các cột bắt buộc: <code>macb, hotencb, gioitinh, email, sdt</code></li>
                            <li>Cột <code>gioitinh</code> chỉ nhận giá trị <code>Nam</code> hoặc <code>Nữ</code></li>
                            <li>Các cột ngày tháng phải có định dạng <code>YYYY-MM-DD</code> (VD: 2023-01-31)</li>
                        </ol>
                    </div>

                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <strong>Chức năng mới:</strong> Hệ thống sẽ tự động tạo các dữ
                        liệu liên quan (học vị, chức vụ, đơn vị...) nếu chưa tồn tại!
                    </div>

                    <h6 class="font-weight-bold">Cấu trúc file Excel:</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-sm table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>macb</th>
                                    <th>hotencb</th>
                                    <th>gioitinh</th>
                                    <th>email</th>
                                    <th>sdt</th>
                                    <th>mahv</th>
                                    <th>tenhocvi</th>
                                    <th>tenchucvu</th>
                                    <th>congviecphutrach</th>
                                    <th>madv</th>
                                    <th>tendvhientai</th>
                                    <th>mabang</th>
                                    <th>tenbang</th>
                                    <th>mataphuan</th>
                                    <th>tenkhoataphuan</th>
                                    <th>thoigianbdcongtaccusc</th>
                                    <th>thoigianktcongtaccusc</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>CB001</td>
                                    <td>Nguyễn Văn An</td>
                                    <td>Nam</td>
                                    <td>ngvanan@gmail.com</td>
                                    <td>0912345678</td>
                                    <td>HV001</td>
                                    <td>Tiến sĩ</td>
                                    <td>Giảng viên</td>
                                    <td>Giảng dạy CNTT</td>
                                    <td>DV001</td>
                                    <td>Khoa CNTT</td>
                                    <td>BC001</td>
                                    <td>Bằng TSKH</td>
                                    <td>TH001</td>
                                    <td>Khóa đào tạo GV</td>
                                    <td>2020-01-01</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CB002</td>
                                    <td>Trần Thị Bình</td>
                                    <td>Nữ</td>
                                    <td>tranthib@gmail.com</td>
                                    <td>0923456789</td>
                                    <td>HV002</td>
                                    <td>Thạc sĩ</td>
                                    <td>Trưởng khoa</td>
                                    <td>Quản lý khoa CNTT</td>
                                    <td>DV002</td>
                                    <td>Khoa Kinh tế</td>
                                    <td>BC002</td>
                                    <td>Bằng ThS</td>
                                    <td>TH002</td>
                                    <td>Khóa QLNN</td>
                                    <td>2018-05-15</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="excelTemplateData" style="display: none;">
                        macb,hotencb,gioitinh,email,sdt,mahv,tenhocvi,tenchucvu,congviecphutrach,madv,tendvhientai,mabang,tenbang,mataphuan,tenkhoataphuan,thoigianbdcongtaccusc,thoigianktcongtaccusc
                        CB001,Nguyễn Văn An,Nam,ngvanan@gmail.com,0912345678,HV001,Tiến sĩ,Giảng viên,Giảng dạy
                        CNTT,DV001,Khoa CNTT,BC001,Bằng TSKH,TH001,Khóa đào tạo GV,2020-01-01,
                        CB002,Trần Thị Bình,Nữ,tranthib@gmail.com,0923456789,HV002,Thạc sĩ,Trưởng khoa,Quản lý khoa
                        CNTT,DV002,Khoa Kinh tế,BC002,Bằng ThS,TH002,Khóa QLNN,2018-05-15,
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
            $('form[action="{{ route('staff.import') }}"]').on('submit', function(e) {
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

            // Validate form thêm cán bộ trước khi submit
            $('#addStaffForm').on('submit', function(e) {
                let isValid = true;

                // Kiểm tra mã cán bộ
                if ($('#MaCB').val() === '') {
                    $('#MaCB').addClass('is-invalid');
                    if (!$('#MaCB').next('.invalid-feedback').length) {
                        $('<div class="invalid-feedback">Vui lòng nhập mã cán bộ</div>').insertAfter(
                            '#MaCB');
                    }
                    isValid = false;
                } else {
                    $('#MaCB').removeClass('is-invalid');
                }

                // Kiểm tra họ tên
                if ($('#HoTenCB').val() === '') {
                    $('#HoTenCB').addClass('is-invalid');
                    if (!$('#HoTenCB').next('.invalid-feedback').length) {
                        $('<div class="invalid-feedback">Vui lòng nhập họ tên cán bộ</div>').insertAfter(
                            '#HoTenCB');
                    }
                    isValid = false;
                } else {
                    $('#HoTenCB').removeClass('is-invalid');
                }

                // Kiểm tra giới tính
                if ($('#GioiTinh').val() === '') {
                    $('#GioiTinh').addClass('is-invalid');
                    if (!$('#GioiTinh').next('.invalid-feedback').length) {
                        $('<div class="invalid-feedback">Vui lòng chọn giới tính</div>').insertAfter(
                            '#GioiTinh');
                    }
                    isValid = false;
                } else {
                    $('#GioiTinh').removeClass('is-invalid');
                }

                // Kiểm tra email
                let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if ($('#Email').val() === '') {
                    $('#Email').addClass('is-invalid');
                    if (!$('#Email').next('.invalid-feedback').length) {
                        $('<div class="invalid-feedback">Vui lòng nhập email</div>').insertAfter('#Email');
                    }
                    isValid = false;
                } else if (!emailRegex.test($('#Email').val())) {
                    $('#Email').addClass('is-invalid');
                    if (!$('#Email').next('.invalid-feedback').length) {
                        $('<div class="invalid-feedback">Email không đúng định dạng</div>').insertAfter(
                            '#Email');
                    }
                    isValid = false;
                } else {
                    $('#Email').removeClass('is-invalid');
                }

                // Kiểm tra số điện thoại
                let phoneRegex = /^[0-9]{10,11}$/;
                if ($('#Sdt').val() === '') {
                    $('#Sdt').addClass('is-invalid');
                    if (!$('#Sdt').next('.invalid-feedback').length) {
                        $('<div class="invalid-feedback">Vui lòng nhập số điện thoại</div>').insertAfter(
                            '#Sdt');
                    }
                    isValid = false;
                } else if (!phoneRegex.test($('#Sdt').val())) {
                    $('#Sdt').addClass('is-invalid');
                    if (!$('#Sdt').next('.invalid-feedback').length) {
                        $('<div class="invalid-feedback">Số điện thoại phải có 10-11 chữ số</div>')
                            .insertAfter('#Sdt');
                    }
                    isValid = false;
                } else {
                    $('#Sdt').removeClass('is-invalid');
                }

                if (!isValid) {
                    e.preventDefault();
                    // Hiển thị thông báo lỗi tổng quan
                    iziToast.error({
                        title: 'Lỗi',
                        message: 'Vui lòng điền đầy đủ thông tin bắt buộc',
                        position: 'topRight'
                    });

                    // Cuộn đến trường lỗi đầu tiên
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first').offset().top - 200
                    }, 500);
                }
            });

            // Xử lý hiển thị form thêm mới học vị
            $('#MaHV').change(function() {
                if ($(this).val() === 'new') {
                    $('.hv-new-fields').show();
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'newHocVi',
                        name: 'MaHV',
                        value: 'HV' + Math.floor(Math.random() * 1000)
                    }).appendTo('#MaHV').parent();
                } else {
                    $('.hv-new-fields').hide();
                    $('#newHocVi').remove();
                }
            });

            // Xử lý hiển thị form thêm mới chức vụ
            $('#TenChucVu').change(function() {
                if ($(this).val() === 'new') {
                    $('.cv-new-fields').show();
                    $('#TenChucVuNew').on('input', function() {
                        $('#TenChucVu').val($(this).val());
                    });
                } else {
                    $('.cv-new-fields').hide();
                }
            });

            // Xử lý hiển thị form thêm mới đơn vị
            $('#MaDV').change(function() {
                if ($(this).val() === 'new') {
                    $('.dv-new-fields').show();
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'newDonVi',
                        name: 'MaDV',
                        value: 'DV' + Math.floor(Math.random() * 1000)
                    }).appendTo('#MaDV').parent();
                } else {
                    $('.dv-new-fields').hide();
                    $('#newDonVi').remove();
                }
            });

            // Xử lý hiển thị form thêm mới bằng cấp
            $('#MaBang').change(function() {
                if ($(this).val() === 'new') {
                    $('.bc-new-fields').show();
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'newBangCap',
                        name: 'MaBang',
                        value: 'BC' + Math.floor(Math.random() * 1000)
                    }).appendTo('#MaBang').parent();
                } else {
                    $('.bc-new-fields').hide();
                    $('#newBangCap').remove();
                }
            });

            // Xử lý hiển thị form thêm mới khóa tập huấn
            $('#MaTapHuan').change(function() {
                if ($(this).val() === 'new') {
                    $('.th-new-fields').show();
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'newTapHuan',
                        name: 'MaTapHuan',
                        value: 'TH' + Math.floor(Math.random() * 1000)
                    }).appendTo('#MaTapHuan').parent();
                } else {
                    $('.th-new-fields').hide();
                    $('#newTapHuan').remove();
                }
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
            // Tạo file CSV từ dữ liệu mẫu
            let templateData = document.getElementById('excelTemplateData').innerText;
            let blob = new Blob([templateData], { type: 'text/xlsx;charset=utf-8;' });
            let url = URL.createObjectURL(blob);
            
            let link = document.createElement("a");
            link.setAttribute("href", url);
            link.setAttribute("download", "canbo_template.csv");
            link.style.visibility = 'hidden';
            
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Hiển thị thông báo hướng dẫn chi tiết
            iziToast.info({
                title: 'Hướng dẫn mở file CSV',
                message: 'Để mở file CSV trong Excel, thực hiện các bước sau:<br>1. Mở Excel và chọn "Data" > "From Text/CSV"<br>2. Chọn file vừa tải<br>3. Trong cửa sổ hiện ra, chọn "Delimiter: Comma"<br>4. Nhấn "Load"',
                position: 'topCenter',
                timeout: 15000,
                closeOnClick: false,
                progressBar: true
            });
        }
    </script>
@endsection
