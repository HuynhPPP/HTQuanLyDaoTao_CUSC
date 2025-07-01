@extends('layouts.new_app.master')

@section('title', 'Chỉnh Sửa Chương Trình Đào Tạo')

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
            <h1>Chỉnh sửa chương trình đào tạo</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('chuongtrinh.index') }}">Danh sách chương trình đào
                        tạo</a></div>
                <div class="breadcrumb-item">Chỉnh sửa</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Cập nhật thông tin chương trình đào tạo</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('chuongtrinh.update', $chuongTrinh->MaChuongTrinh) }}" method="POST"
                        id="chuongTrinhForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mã chương trình <span class="text-danger">*</span></label>
                                    <input type="text" name="MaChuongTrinh" class="form-control"
                                        value="{{ $chuongTrinh->MaChuongTrinh }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tên chương trình <span class="text-danger">*</span></label>
                                    <input type="text" name="TenChuongTrinh" class="form-control"
                                        value="{{ old('TenChuongTrinh', $chuongTrinh->TenChuongTrinh) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phiên bản</label>
                                    <input type="text" name="PhienBan" class="form-control"
                                        value="{{ old('PhienBan', $chuongTrinh->PhienBan) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ngày triển khai</label>
                                    <input type="date" name="NgayTrienKhaiPB" class="form-control"
                                        value="{{ old('NgayTrienKhaiPB', $chuongTrinh->NgayTrienKhaiPB ? date('Y-m-d', strtotime($chuongTrinh->NgayTrienKhaiPB)) : '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="TenKhoaDaoTao" class="form-label">Khoá đào tạo <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('TenKhoaDaoTao') is-invalid @enderror"
                                        id="TenKhoaDaoTao" name="TenKhoaDaoTao">
                                        <option value="">-- Chọn khoá đào tạo --</option>
                                        @foreach ($khoadaotaos as $khoadaotao)
                                            <option value="{{ $khoadaotao->TenKhoaDaoTao }}"
                                                {{ old('TenKhoaDaoTao', $chuongTrinh->TenKhoaDaoTao) == $khoadaotao->TenKhoaDaoTao ? 'selected' : '' }}>
                                                {{ $khoadaotao->TenKhoaDaoTao }}</option>
                                        @endforeach
                                    </select>
                                    @error('TenKhoaDaoTao')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Hình thức đánh giá --}}
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-clipboard-list mr-2"></i>Hình thức đánh giá
                                    <small class="text-muted ml-2" data-toggle="tooltip"
                                        title="Xác định các phương thức và trọng số đánh giá">
                                        <i class="fas fa-info-circle"></i>
                                    </small>
                                </h5>
                                <button type="button" class="btn btn-primary btn-sm" id="themHinhThucDanhGia">
                                    <i class="fas fa-plus"></i> Thêm hình thức
                                </button>
                            </div>
                            <div class="card-body" id="danh-gia-wrapper">
                                @php
                                    $hinhThucDanhGia = $chuongTrinh->hinhThucDanhGia()->get();
                                @endphp
                                @forelse($hinhThucDanhGia as $index => $danhGia)
                                    <div class="row danh-gia-row mb-3 border-bottom pb-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="HinhThuc_{{ $index }}">Hình thức thi</label>
                                                <select name="danhgia[{{ $index }}][HinhThuc]"
                                                    id="HinhThuc_{{ $index }}" class="form-control" required>
                                                    <option value="Lý thuyết trắc nghiệm"
                                                        {{ $danhGia->HinhThuc == 'Lý thuyết trắc nghiệm' ? 'selected' : '' }}>
                                                        Lý thuyết trắc nghiệm</option>
                                                    <option value="Thực hành"
                                                        {{ $danhGia->HinhThuc == 'Thực hành' ? 'selected' : '' }}>Thực hành
                                                    </option>
                                                    <option value="Dự án"
                                                        {{ $danhGia->HinhThuc == 'Dự án' ? 'selected' : '' }}>Dự án
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="TiLePhanTram_{{ $index }}">Tỉ lệ (%)</label>
                                                <input type="number" name="danhgia[{{ $index }}][TiLePhanTram]"
                                                    id="TiLePhanTram_{{ $index }}" class="form-control"
                                                    placeholder="%" value="{{ $danhGia->TiLePhanTram }}" min="0"
                                                    max="100" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="SoBaiThi_{{ $index }}">Số bài thi</label>
                                                <input type="number" name="danhgia[{{ $index }}][SoBaiThi]"
                                                    id="SoBaiThi_{{ $index }}" class="form-control"
                                                    placeholder="Số bài thi" value="{{ $danhGia->SoBaiThi }}"
                                                    min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="DiemMoiBai_{{ $index }}">Điểm/bài</label>
                                                <input type="number" name="danhgia[{{ $index }}][DiemMoiBai]"
                                                    id="DiemMoiBai_{{ $index }}" class="form-control"
                                                    placeholder="Điểm/mỗi bài" value="{{ $danhGia->DiemMoiBai }}"
                                                    step="0.1" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="ThoiGian_{{ $index }}">Thời gian</label>
                                                <div class="input-group">
                                                    <input type="number" name="danhgia[{{ $index }}][ThoiGian]"
                                                        id="ThoiGian_{{ $index }}" class="form-control"
                                                        placeholder="Thời gian" value="{{ $danhGia->ThoiGian }}"
                                                        min="0">
                                                    <div class="input-group-append">
                                                        <select name="danhgia[{{ $index }}][DonViThoiGian]"
                                                            class="form-control">
                                                            <option value="phút"
                                                                {{ $danhGia->DonViThoiGian == 'phút' ? 'selected' : '' }}>
                                                                phút</option>
                                                            <option value="giờ"
                                                                {{ $danhGia->DonViThoiGian == 'giờ' ? 'selected' : '' }}>
                                                                giờ</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('chuongtrinh.xoa.hinhthucdanhgia', $danhGia->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <div class="col-md-1 d-flex align-items-center">
                                                <button type="submit"
                                                    class="btn btn-danger btn-sm delete-hinhthucdanhgia">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                @empty
                                    <div class="row danh-gia-row mb-3 border-bottom pb-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Hình thức thi</label>
                                                <select name="danhgia[0][HinhThuc]" class="form-control" required>
                                                    <option value="Lý thuyết trắc nghiệm">Lý thuyết trắc nghiệm</option>
                                                    <option value="Thực hành">Thực hành</option>
                                                    <option value="Dự án">Dự án</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Tỉ lệ (%)</label>
                                                <input type="number" name="danhgia[0][TiLePhanTram]"
                                                    class="form-control" placeholder="%" min="0" max="100"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Số bài thi</label>
                                                <input type="number" name="danhgia[0][SoBaiThi]" class="form-control"
                                                    placeholder="Số bài thi" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Điểm/bài</label>
                                                <input type="number" name="danhgia[0][DiemMoiBai]" class="form-control"
                                                    placeholder="Điểm/mỗi bài" step="0.1" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Thời gian</label>
                                                <div class="input-group">
                                                    <input type="number" name="danhgia[0][ThoiGian]"
                                                        class="form-control" placeholder="Thời gian" min="0">
                                                    <div class="input-group-append">
                                                        <select name="danhgia[0][DonViThoiGian]" class="form-control">
                                                            <option value="phút">phút</option>
                                                            <option value="giờ">giờ</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Tiêu chí xếp loại --}}
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-trophy mr-2"></i>Tiêu chí xếp loại
                                    <small class="text-muted ml-2" data-toggle="tooltip"
                                        title="Xác định ngưỡng điểm cho từng xếp loại">
                                        <i class="fas fa-info-circle"></i>
                                    </small>
                                </h5>
                                <button type="button" class="btn btn-primary btn-sm" id="themTieuChiXepLoai">
                                    <i class="fas fa-plus"></i> Thêm tiêu chí
                                </button>
                            </div>
                            <div class="card-body" id="xeploai-wrapper">
                                @php
                                    $tieuChiXepLoai = $chuongTrinh->tieuChiXepLoai()->get();
                                @endphp
                                @forelse($tieuChiXepLoai as $index => $xepLoai)
                                    <div class="row xep-loai-row mb-3 border-bottom pb-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="XepLoai_{{ $index }}">Xếp loại</label>
                                                <select name="xeploai[{{ $index }}][XepLoai]"
                                                    id="XepLoai_{{ $index }}" class="form-control" required>
                                                    <option value="Giỏi"
                                                        {{ $xepLoai->XepLoai == 'Giỏi' ? 'selected' : '' }}>Giỏi</option>
                                                    <option value="Khá"
                                                        {{ $xepLoai->XepLoai == 'Khá' ? 'selected' : '' }}>Khá</option>
                                                    <option value="Đạt"
                                                        {{ $xepLoai->XepLoai == 'Đạt' ? 'selected' : '' }}>Đạt</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="DiemTu_{{ $index }}">% tối thiểu</label>
                                                <input type="number" name="xeploai[{{ $index }}][DiemTu]"
                                                    id="DiemTu_{{ $index }}" class="form-control"
                                                    placeholder="% tối thiểu" value="{{ $xepLoai->DiemTu }}"
                                                    min="0" max="100" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="DiemDen_{{ $index }}">% tối đa</label>
                                                <input type="number" name="xeploai[{{ $index }}][DiemDen]"
                                                    id="DiemDen_{{ $index }}" class="form-control"
                                                    placeholder="% tối đa" value="{{ $xepLoai->DiemDen }}"
                                                    min="0" max="100" required>
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-row"
                                                onclick="$(this).closest('.xep-loai-row').remove()">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="row xep-loai-row mb-3 border-bottom pb-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Xếp loại</label>
                                                <select name="xeploai[0][XepLoai]" class="form-control" required>
                                                    <option value="Giỏi">Giỏi</option>
                                                    <option value="Khá">Khá</option>
                                                    <option value="Đạt">Đạt</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>% tối thiểu</label>
                                                <input type="number" name="xeploai[0][DiemTu]" class="form-control"
                                                    placeholder="% tối thiểu" min="0" max="100" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>% tối đa</label>
                                                <input type="number" name="xeploai[0][DiemDen]" class="form-control"
                                                    placeholder="% tối đa" min="0" max="100" required>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('chuongtrinh.index') }}" class="btn btn-secondary mr-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom-js')
    <script src="{{ asset('assets/js/add_hinhthuc_tieuchi.js') }}"></script>
    <script>
        $(document).on('click', '.remove-row', function(e) {
            e.preventDefault();

            const row = $(this).closest('.danh-gia-row, .xep-loai-row');

            Swal.fire({
                title: 'Bạn có chắc muốn xóa?',
                text: 'Hành động này sẽ không thể hoàn tác!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    row.remove();
                    Swal.fire('Đã xóa!', 'Dòng đã được xóa.', 'success');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Đã hủy', 'Dữ liệu vẫn được giữ nguyên.', 'info');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Cập nhật chỉ số index ban đầu dựa trên số lượng dòng hiện tại
            let danhGiaIndex = $('#danh-gia-wrapper .danh-gia-row').length;
            let xepLoaiIndex = $('#xeploai-wrapper .xep-loai-row').length;
        });
    </script>
@endsection
