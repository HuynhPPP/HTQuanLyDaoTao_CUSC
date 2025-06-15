@extends('layouts.new_app.master')

@section('title', 'Thêm Mới Chương Trình Đào Tạo')

@section('css')
<style>
    .remove-row {
        cursor: pointer;
        color: red;
        margin-left: 10px;
    }
</style>
@endsection

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Thêm mới chương trình đào tạo</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('chuongtrinh.index') }}">Chương trình đào tạo</a></div>
                <div class="breadcrumb-item">Thêm mới</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Nhập thông tin chương trình</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('chuongtrinh.store') }}" method="POST" id="chuongTrinhForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mã chương trình <span class="text-danger">*</span></label>
                                    <input type="text" name="MaChuongTrinh"
                                        class="form-control @error('MaChuongTrinh') is-invalid @enderror"
                                        value="{{ old('MaChuongTrinh') }}">
                                    @error('MaChuongTrinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tên chương trình đào tạo <span class="text-danger">*</span></label>
                                    <input type="text" name="TenChuongTrinh" class="form-control"
                                        value="{{ old('TenChuongTrinh') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phiên bản</label>
                                    <input type="text" name="PhienBan" class="form-control"
                                        value="{{ old('PhienBan') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ngày triển khai</label>
                                    <input type="date" name="NgayTrienKhaiPB" class="form-control"
                                        value="{{ old('NgayTrienKhaiPB') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="TenKhoaDaoTao" class="form-label">Khoá đào tạo <span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('TenKhoaDaoTao') is-invalid @enderror" id="TenKhoaDaoTao"
                                    name="TenKhoaDaoTao">
                                    <option value="">-- Chọn khoá đào tạo --</option>
                                    @foreach ($khoadaotaos as $khoadaotao)
                                        <option value="{{ $khoadaotao->TenKhoaDaoTao }}"
                                            {{ old('TenKhoaDaoTao') == $khoadaotao->TenKhoaDaoTao ? 'selected' : '' }}>
                                            {{ $khoadaotao->TenKhoaDaoTao }}</option>
                                    @endforeach
                                </select>
                                @error('TenKhoaDaoTao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Thêm hình thức đánh giá --}}
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mt-4">Hình thức đánh giá</h5>
                            <button type="button" class="btn btn-success btn-sm" id="themHinhThucDanhGia">
                                <i class="fas fa-plus"></i> Thêm hình thức
                            </button>
                        </div>
                        <div id="danh-gia-wrapper">
                            <div class="row danh-gia-row mb-3">
                                <div class="col-md-3">
                                    <select name="danhgia[0][HinhThuc]" class="form-control">
                                        <option value="Lý thuyết trắc nghiệm">Lý thuyết trắc nghiệm</option>
                                        <option value="Thực hành">Thực hành</option>
                                        <option value="Dự án">Dự án</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="danhgia[0][TiLePhanTram]" class="form-control" placeholder="%" min="0" max="100">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="danhgia[0][SoBaiThi]" class="form-control" placeholder="Số bài thi" min="0">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="danhgia[0][DiemMoiBai]" class="form-control" placeholder="Điểm/mỗi bài" step="0.1" min="0">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="danhgia[0][ThoiGian]" class="form-control" placeholder="Thời gian" min="0">
                                </div>
                                <div class="col-md-1">
                                    <select name="danhgia[0][DonViThoiGian]" class="form-control">
                                        <option value="phút">phút</option>
                                        <option value="giờ">giờ</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Thêm tiêu chí xếp loại --}}
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mt-4">Tiêu chí xếp loại</h5>
                            <button type="button" class="btn btn-success btn-sm" id="themTieuChiXepLoai">
                                <i class="fas fa-plus"></i> Thêm tiêu chí
                            </button>
                        </div>
                        <div id="xeploai-wrapper">
                            <div class="row xep-loai-row mb-3">
                                <div class="col-md-4">
                                    <select name="xeploai[0][XepLoai]" class="form-control">
                                        <option value="Giỏi">Giỏi</option>
                                        <option value="Khá">Khá</option>
                                        <option value="Đạt">Đạt</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" step="0.1" name="xeploai[0][DiemTu]" class="form-control" placeholder="Điểm tối thiểu" min="0" max="10">
                                </div>
                                <div class="col-md-4">
                                    <input type="number" step="0.1" name="xeploai[0][DiemDen]" class="form-control" placeholder="Điểm tối đa" min="0" max="10">
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer text-right">
                            <a href="{{ route('chuongtrinh.index') }}" class="btn btn-secondary mr-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Lưu chương trình</button>
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
        let danhGiaIndex = 1;
        let xepLoaiIndex = 1;

        // Thêm hình thức đánh giá
        $('#themHinhThucDanhGia').click(function() {
            const newRow = `
                <div class="row danh-gia-row mb-3">
                    <div class="col-md-3">
                        <select name="danhgia[${danhGiaIndex}][HinhThuc]" class="form-control">
                            <option value="Lý thuyết trắc nghiệm">Lý thuyết trắc nghiệm</option>
                            <option value="Thực hành">Thực hành</option>
                            <option value="Dự án">Dự án</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="danhgia[${danhGiaIndex}][TiLePhanTram]" class="form-control" placeholder="%" min="0" max="100">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="danhgia[${danhGiaIndex}][SoBaiThi]" class="form-control" placeholder="Số bài thi" min="0">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="danhgia[${danhGiaIndex}][DiemMoiBai]" class="form-control" placeholder="Điểm/mỗi bài" step="0.1" min="0">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="danhgia[${danhGiaIndex}][ThoiGian]" class="form-control" placeholder="Thời gian" min="0">
                    </div>
                    <div class="col-md-1">
                        <select name="danhgia[${danhGiaIndex}][DonViThoiGian]" class="form-control">
                            <option value="phút">phút</option>
                            <option value="giờ">giờ</option>
                        </select>
                    </div>
                    <div class="col-md-auto">
                        <span class="remove-row" onclick="$(this).closest('.danh-gia-row').remove()">
                            <i class="fas fa-trash-alt"></i>
                        </span>
                    </div>
                </div>
            `;
            $('#danh-gia-wrapper').append(newRow);
            danhGiaIndex++;
        });

        // Thêm tiêu chí xếp loại
        $('#themTieuChiXepLoai').click(function() {
            const newRow = `
                <div class="row xep-loai-row mb-3">
                    <div class="col-md-4">
                        <select name="xeploai[${xepLoaiIndex}][XepLoai]" class="form-control">
                            <option value="Giỏi">Giỏi</option>
                            <option value="Khá">Khá</option>
                            <option value="Đạt">Đạt</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" step="0.1" name="xeploai[${xepLoaiIndex}][DiemTu]" class="form-control" placeholder="Điểm tối thiểu" min="0" max="10">
                    </div>
                    <div class="col-md-4">
                        <input type="number" step="0.1" name="xeploai[${xepLoaiIndex}][DiemDen]" class="form-control" placeholder="Điểm tối đa" min="0" max="10">
                    </div>
                    <div class="col-md-auto">
                        <span class="remove-row" onclick="$(this).closest('.xep-loai-row').remove()">
                            <i class="fas fa-trash-alt"></i>
                        </span>
                    </div>
                </div>
            `;
            $('#xeploai-wrapper').append(newRow);
            xepLoaiIndex++;
        });

        // Validate form trước khi submit
        $('#chuongTrinhForm').submit(function(e) {
            let isValid = true;

            // Validate hình thức đánh giá
            $('.danh-gia-row').each(function() {
                const tiLePhanTram = $(this).find('input[name$="[TiLePhanTram]"]').val();
                if (tiLePhanTram && (tiLePhanTram < 0 || tiLePhanTram > 100)) {
                    alert('Tỷ lệ phần trăm phải nằm trong khoảng 0-100%');
                    isValid = false;
                    return false;
                }
            });

            // Validate tiêu chí xếp loại
            $('.xep-loai-row').each(function() {
                const diemTu = parseFloat($(this).find('input[name$="[DiemTu]"]').val());
                const diemDen = parseFloat($(this).find('input[name$="[DiemDen]"]').val());
                
                if (diemTu !== undefined && diemDen !== undefined) {
                    if (diemTu > diemDen) {
                        alert('Điểm tối thiểu không được lớn hơn điểm tối đa');
                        isValid = false;
                        return false;
                    }
                    if (diemTu < 0 || diemDen > 10) {
                        alert('Điểm phải nằm trong khoảng 0-10');
                        isValid = false;
                        return false;
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
