$(document).ready(function () {
    let danhGiaIndex = 1;
    let xepLoaiIndex = 1;

    // Thêm hình thức đánh giá
    $('#themHinhThucDanhGia').click(function () {
        const newRow = `
            <div class="row danh-gia-row mb-3">
                <div class="col-md-3">
                    <select name="danhgia[${danhGiaIndex}][HinhThuc]" class="form-control">
                        <option value="Lý thuyết trắc nghiệm">Lý thuyết trắc nghiệm</option>
                        <option value="Thực hành">Thực hành</option>
                        <option value="Dự án">Dự án</option>
                    </select>
                </div>
                <div class="col-md-1">
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
                <div class="col-md-2">
                    <select name="danhgia[${danhGiaIndex}][DonViThoiGian]" class="form-control">
                        <option value="phút">phút</option>
                        <option value="giờ">giờ</option>
                    </select>
                </div>
                <div class="col-md-auto mt-3">
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
    $('#themTieuChiXepLoai').click(function () {
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
                    <input type="number" step="10" name="xeploai[${xepLoaiIndex}][DiemTu]" class="form-control" placeholder="% tối thiểu" min="0" max="100">
                </div>
                <div class="col-md-4">
                    <input type="number" step="10" name="xeploai[${xepLoaiIndex}][DiemDen]" class="form-control" placeholder="% tối đa" min="0" max="100">
                </div>
                <div class="col-md-auto mt-3">
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
    $('#chuongTrinhForm').submit(function (e) {
        let isValid = true;

        // Validate hình thức đánh giá
        $('.danh-gia-row').each(function () {
            const tiLePhanTram = $(this).find('input[name$="[TiLePhanTram]"]').val();
            if (tiLePhanTram && (tiLePhanTram < 0 || tiLePhanTram > 100)) {
                alert('Tỷ lệ phần trăm phải nằm trong khoảng 0-100%');
                isValid = false;
                return false;
            }
        });

        // Validate tiêu chí xếp loại
        $('.xep-loai-row').each(function () {
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