$(document).ready(function () {
    let danhGiaIndex = $('#danh-gia-wrapper .danh-gia-row').length;
    let xepLoaiIndex = $('#xeploai-wrapper .xep-loai-row').length;

    // Thêm hình thức đánh giá
    $('#themHinhThucDanhGia').click(function () {
        const newRow = `
            <div class="row danh-gia-row mb-3 border-bottom pb-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Hình thức thi</label>
                        <select name="danhgia[${danhGiaIndex}][HinhThuc]" class="form-control" required>
                            <option value="Lý thuyết trắc nghiệm">Lý thuyết trắc nghiệm</option>
                            <option value="Thực hành">Thực hành</option>
                            <option value="Dự án">Dự án</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tỉ lệ (%)</label>
                        <input type="number" 
                            name="danhgia[${danhGiaIndex}][TiLePhanTram]" 
                            class="form-control" 
                            placeholder="%" 
                            min="0" 
                            max="100" 
                            required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Số bài thi</label>
                        <input type="number" 
                            name="danhgia[${danhGiaIndex}][SoBaiThi]" 
                            class="form-control" 
                            placeholder="Số bài thi" 
                            min="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Điểm/bài</label>
                        <input type="number" 
                            name="danhgia[${danhGiaIndex}][DiemMoiBai]" 
                            class="form-control" 
                            placeholder="Điểm/mỗi bài" 
                            step="0.1" 
                            min="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Thời gian</label>
                        <div class="input-group">
                            <input type="number" 
                                name="danhgia[${danhGiaIndex}][ThoiGian]" 
                                class="form-control" 
                                placeholder="Thời gian" 
                                min="0">
                            <div class="input-group-append">
                                <select name="danhgia[${danhGiaIndex}][DonViThoiGian]" class="form-control">
                                    <option value="phút">phút</option>
                                    <option value="giờ">giờ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        `;
        $('#danh-gia-wrapper').append(newRow);
        danhGiaIndex++;
    });

    // Thêm tiêu chí xếp loại
    $('#themTieuChiXepLoai').click(function () {
        const newRow = `
            <div class="row xep-loai-row mb-3 border-bottom pb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Xếp loại</label>
                        <select name="xeploai[${xepLoaiIndex}][XepLoai]" class="form-control" required>
                            <option value="Giỏi">Giỏi</option>
                            <option value="Khá">Khá</option>
                            <option value="Đạt">Đạt</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>% tối thiểu</label>
                        <input type="number" 
                            name="xeploai[${xepLoaiIndex}][DiemTu]" 
                            class="form-control" 
                            placeholder="% tối thiểu" 
                            min="0" 
                            max="100" 
                            required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>% tối đa</label>
                        <input type="number" 
                            name="xeploai[${xepLoaiIndex}][DiemDen]" 
                            class="form-control" 
                            placeholder="% tối đa" 
                            min="0" 
                            max="100" 
                            required>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        `;
        $('#xeploai-wrapper').append(newRow);
        xepLoaiIndex++;
    });

    // Xử lý sự kiện xóa dòng
    $(document).on('click', '.remove-row', function() {
        $(this).closest('.danh-gia-row, .xep-loai-row').remove();
    });

    // Validate form trước khi submit
    $('#chuongTrinhForm').submit(function (e) {
        let isValid = true;
        let totalTiLePhanTram = 0;

        // Validate hình thức đánh giá
        $('.danh-gia-row').each(function () {
            const tiLePhanTram = parseFloat($(this).find('input[name$="[TiLePhanTram]"]').val());
            
            if (isNaN(tiLePhanTram) || tiLePhanTram < 0 || tiLePhanTram > 100) {
                swal({
                    title: 'Lỗi',
                    text: 'Tỷ lệ phần trăm phải nằm trong khoảng 0-100%',
                    icon: 'error',
                    buttons: {
                        confirm: {
                            text: 'Đóng',
                            visible: true
                        }
                    }
                });
                isValid = false;
                return false;
            }
            
            totalTiLePhanTram += tiLePhanTram;
        });

        // Kiểm tra tổng tỷ lệ phần trăm
        if (isValid && totalTiLePhanTram !== 100) {
            swal({
                title: 'Lỗi',
                text: 'Tổng tỷ lệ phần trăm phải bằng 100%',
                icon: 'error',
                buttons: {
                    confirm: {
                        text: 'Đóng',
                        visible: true
                    }
                }
            });
            isValid = false;
        }

        // Validate tiêu chí xếp loại
        $('.xep-loai-row').each(function () {
            const diemTu = parseFloat($(this).find('input[name$="[DiemTu]"]').val());
            const diemDen = parseFloat($(this).find('input[name$="[DiemDen]"]').val());

            if (isNaN(diemTu) || isNaN(diemDen)) {
                swal({
                    title: 'Lỗi',
                    text: 'Vui lòng nhập đầy đủ giá trị % tối thiểu và % tối đa',
                    icon: 'error',
                    buttons: {
                        confirm: {
                            text: 'Đóng',
                            visible: true
                        }
                    }
                });
                isValid = false;
                return false;
            }

            if (diemTu > diemDen) {
                swal({
                    title: 'Lỗi',
                    text: 'Điểm tối thiểu không được lớn hơn điểm tối đa',
                    icon: 'error',
                    buttons: {
                        confirm: {
                            text: 'Đóng',
                            visible: true
                        }
                    }
                });
                isValid = false;
                return false;
            }

            if (diemTu < 0 || diemDen > 100) {
                swal({
                    title: 'Lỗi',
                    text: '% phải nằm trong khoảng 0-100',
                    icon: 'error',
                    buttons: {
                        confirm: {
                            text: 'Đóng',
                            visible: true
                        }
                    }
                });
                isValid = false;
                return false;
            }
        });

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Thêm nút chọn tất cả các hình thức đánh giá
    $('#chonTatCaHinhThucDanhGia').click(function() {
        $('#danh-gia-wrapper .danh-gia-row input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    });

    // Thêm nút chọn tất cả các tiêu chí xếp loại
    $('#chonTatCaXepLoai').click(function() {
        $('#xeploai-wrapper .xep-loai-row input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    });

    // Xoá nhiều hình thức đánh giá
    $('#xoaHinhThucDanhGia').click(function() {
        const selectedRows = $('#danh-gia-wrapper .danh-gia-row input[type="checkbox"]:checked');
        
        if (selectedRows.length === 0) {
            swal({
                title: 'Thông báo',
                text: 'Vui lòng chọn hình thức đánh giá để xoá',
                icon: 'warning',
                buttons: {
                    confirm: {
                        text: 'Đóng',
                        visible: true
                    }
                }
            });
            return;
        }

        swal({
            title: 'Xác nhận xoá',
            text: `Bạn có chắc chắn muốn xoá ${selectedRows.length} hình thức đánh giá?`,
            icon: 'warning',
            buttons: {
                cancel: 'Huỷ',
                confirm: 'Xoá'
            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                const hinhThucIds = [];
                selectedRows.each(function() {
                    const rowId = $(this).val();
                    hinhThucIds.push(rowId);
                    $(this).closest('.danh-gia-row').remove();
                });

                // Gọi AJAX để xoá trên server
                $.ajax({
                    url: '/quan-ly/hinh-thuc-danh-gia/xoa',
                    method: 'POST',
                    data: { 
                        ids: hinhThucIds,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        swal({
                            title: 'Thành công',
                            text: 'Đã xoá các hình thức đánh giá đã chọn',
                            icon: 'success',
                            buttons: {
                                confirm: {
                                    text: 'Đóng',
                                    visible: true
                                }
                            }
                        });
                    },
                    error: function(xhr) {
                        swal({
                            title: 'Lỗi',
                            text: 'Không thể xoá hình thức đánh giá',
                            icon: 'error',
                            buttons: {
                                confirm: {
                                    text: 'Đóng',
                                    visible: true
                                }
                            }
                        });
                    }
                });
            }
        });
    });

    // Xoá nhiều tiêu chí xếp loại
    $('#xoaTieuChiXepLoai').click(function() {
        const selectedRows = $('#xeploai-wrapper .xep-loai-row input[type="checkbox"]:checked');
        
        if (selectedRows.length === 0) {
            swal({
                title: 'Thông báo',
                text: 'Vui lòng chọn tiêu chí xếp loại để xoá',
                icon: 'warning',
                buttons: {
                    confirm: {
                        text: 'Đóng',
                        visible: true
                    }
                }
            });
            return;
        }

        swal({
            title: 'Xác nhận xoá',
            text: `Bạn có chắc chắn muốn xoá ${selectedRows.length} tiêu chí xếp loại?`,
            icon: 'warning',
            buttons: {
                cancel: 'Huỷ',
                confirm: 'Xoá'
            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                const xepLoaiIds = [];
                selectedRows.each(function() {
                    const rowId = $(this).val();
                    xepLoaiIds.push(rowId);
                    $(this).closest('.xep-loai-row').remove();
                });

                // Gọi AJAX để xoá trên server
                $.ajax({
                    url: '/quan-ly/tieu-chi-xep-loai/xoa',
                    method: 'POST',
                    data: { 
                        ids: xepLoaiIds,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        swal({
                            title: 'Thành công',
                            text: 'Đã xoá các tiêu chí xếp loại đã chọn',
                            icon: 'success',
                            buttons: {
                                confirm: {
                                    text: 'Đóng',
                                    visible: true
                                }
                            }
                        });
                    },
                    error: function(xhr) {
                        swal({
                            title: 'Lỗi',
                            text: 'Không thể xoá tiêu chí xếp loại',
                            icon: 'error',
                            buttons: {
                                confirm: {
                                    text: 'Đóng',
                                    visible: true
                                }
                            }
                        });
                    }
                });
            }
        });
    });
});