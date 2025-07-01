$(document).ready(function () {
    // Hàm xóa các thông báo lỗi hiện tại
    function clearValidationErrors() {
        $('.validation-error').remove();
        $('.is-invalid').removeClass('is-invalid');
    }

    // Hàm hiển thị lỗi
    function showValidationError(element, message) {
        // Xóa lỗi cũ nếu có
        element.removeClass('is-invalid');
        element.next('.validation-error').remove();

        // Thêm class lỗi và thông báo
        element.addClass('is-invalid');
        $(`<div class="invalid-feedback validation-error text-danger">${message}</div>`).insertAfter(element);
    }

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

    $('#chuongTrinhForm').submit(function (e) {
        // Xóa các thông báo lỗi cũ
        clearValidationErrors();

        let isValid = true;
        let totalTiLePhanTram = 0;
        let errorMessages = [];

        // Validate hình thức đánh giá
        $('.danh-gia-row').each(function () {
            const tiLePhanTramInput = $(this).find('input[name$="[TiLePhanTram]"]');
            const tiLePhanTram = parseFloat(tiLePhanTramInput.val());
            
            if (isNaN(tiLePhanTram) || tiLePhanTram < 0 || tiLePhanTram > 100) {
                showValidationError(tiLePhanTramInput, 'Tỷ lệ phần trăm phải nằm trong khoảng 0-100%');
                errorMessages.push('Tỷ lệ phần trăm phải nằm trong khoảng 0-100%');
                isValid = false;
            }
            
            totalTiLePhanTram += tiLePhanTram;
        });

        // Kiểm tra tổng tỷ lệ phần trăm
        if (Math.abs(totalTiLePhanTram - 100) > 0.01) {
            $('.danh-gia-row input[name$="[TiLePhanTram]"]').each(function() {
                showValidationError($(this), 'Tổng tỷ lệ phần trăm phải bằng 100%');
            });
            errorMessages.push('Tổng tỷ lệ phần trăm phải bằng 100%');
            isValid = false;
        }

        // Validate tiêu chí xếp loại
        $('.xep-loai-row').each(function () {
            const diemTuInput = $(this).find('input[name$="[DiemTu]"]');
            const diemDenInput = $(this).find('input[name$="[DiemDen]"]');
            const diemTu = parseFloat(diemTuInput.val());
            const diemDen = parseFloat(diemDenInput.val());

            if (isNaN(diemTu) || isNaN(diemDen)) {
                if (isNaN(diemTu)) {
                    showValidationError(diemTuInput, 'Vui lòng nhập giá trị % tối thiểu');
                }
                if (isNaN(diemDen)) {
                    showValidationError(diemDenInput, 'Vui lòng nhập giá trị % tối đa');
                }
                errorMessages.push('Vui lòng nhập đầy đủ giá trị % tối thiểu và % tối đa');
                isValid = false;
            }

            if (diemTu > diemDen) {
                showValidationError(diemTuInput, 'Điểm tối thiểu không được lớn hơn điểm tối đa');
                showValidationError(diemDenInput, 'Điểm tối thiểu không được lớn hơn điểm tối đa');
                errorMessages.push('Điểm tối thiểu không được lớn hơn điểm tối đa');
                isValid = false;
            }

            if (diemTu < 0 || diemDen > 100) {
                if (diemTu < 0) {
                    showValidationError(diemTuInput, '% tối thiểu phải từ 0-100');
                }
                if (diemDen > 100) {
                    showValidationError(diemDenInput, '% tối đa phải từ 0-100');
                }
                errorMessages.push('% phải nằm trong khoảng 0-100');
                isValid = false;
            }
        });

        // Nếu có lỗi, ngăn submit và hiển thị thông báo
        if (!isValid) {
            e.preventDefault();
            
            // Hiển thị thông báo tổng quan
            swal({
                title: 'Lỗi Nhập Liệu',
                text: 'Vui lòng kiểm tra và điều chỉnh các trường có lỗi.',
                icon: 'error',
                buttons: {
                    confirm: {
                        text: 'Đóng',
                        visible: true
                    }
                }
            });

            // Cuộn đến phần đầu tiên có lỗi
            $('html, body').animate({
                scrollTop: $('.is-invalid').first().offset().top - 100
            }, 500);
        }
    });

    // Xóa lỗi khi người dùng thay đổi giá trị
    $(document).on('input', '.is-invalid', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.validation-error').remove();
    });
});