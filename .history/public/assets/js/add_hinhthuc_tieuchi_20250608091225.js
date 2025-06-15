$(document).ready(function () {
    // Khởi tạo ban đầu với dữ liệu mẫu
    function initDefaultData() {
        // Hình thức đánh giá
        const danhGiaData = [
            {
                HinhThuc: 'Lý thuyết trắc nghiệm',
                TiLePhanTram: 50,
                SoBaiThi: 6,
                DiemMoiBai: 20,
                ThoiGian: 40,
                DonViThoiGian: 'phút'
            },
            {
                HinhThuc: 'Thực hành',
                TiLePhanTram: 30,
                SoBaiThi: 5,
                DiemMoiBai: 20,
                ThoiGian: 60,
                DonViThoiGian: 'phút'
            },
            {
                HinhThuc: 'Dự án',
                TiLePhanTram: 20,
                SoBaiThi: 1,
                DiemMoiBai: 100,
                ThoiGian: 24,
                DonViThoiGian: 'giờ'
            }
        ];

        // Xếp loại
        const xepLoaiData = [
            {
                XepLoai: 'Đạt',
                DiemTu: 40,
                DiemDen: 60
            },
            {
                XepLoai: 'Khá',
                DiemTu: 60,
                DiemDen: 75
            },
            {
                XepLoai: 'Giỏi',
                DiemTu: 75,
                DiemDen: 100
            }
        ];

        // Điền dữ liệu hình thức đánh giá
        danhGiaData.forEach((item, index) => {
            if (index > 0) {
                $('#themHinhThucDanhGia').click();
            }

            const row = $('.danh-gia-row').eq(index);
            row.find('select[name$="[HinhThuc]"]').val(item.HinhThuc);
            row.find('input[name$="[TiLePhanTram]"]').val(item.TiLePhanTram);
            row.find('input[name$="[SoBaiThi]"]').val(item.SoBaiThi);
            row.find('input[name$="[DiemMoiBai]"]').val(item.DiemMoiBai);
            row.find('input[name$="[ThoiGian]"]').val(item.ThoiGian);
            row.find('select[name$="[DonViThoiGian]"]').val(item.DonViThoiGian);
        });

        // Điền dữ liệu xếp loại
        xepLoaiData.forEach((item, index) => {
            if (index > 0) {
                $('#themTieuChiXepLoai').click();
            }

            const row = $('.xep-loai-row').eq(index);
            row.find('select[name$="[XepLoai]"]').val(item.XepLoai);
            row.find('input[name$="[DiemTu]"]').val(item.DiemTu);
            row.find('input[name$="[DiemDen]"]').val(item.DiemDen);
        });
    }

    // Gọi hàm khởi tạo dữ liệu mẫu
    initDefaultData();

    let danhGiaIndex = 3;
    let xepLoaiIndex = 3;

    // Thêm hình thức đánh giá
    $('#themHinhThucDanhGia').click(function () {
        const newRow = `
            <div class="row danh-gia-row mb-3">
                <div class="col-md-3">
                    <select name="danhgia[${danhGiaIndex}][HinhThuc]" class="form-control">
                        <option value="Lý thuyết trắc nghiệm">Lý thuyết trắc nghiệm</option>
                        <option value="Thực hành">Thực hành</option>
                        <option value="Dự án">Dự án</option>
                        <option value="Khác">Khác</option>
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
                    <input type="number" step="0.1" name="xeploai[${xepLoaiIndex}][DiemTu]" class="form-control" placeholder="Điểm tối thiểu" min="0" max="100">
                </div>
                <div class="col-md-4">
                    <input type="number" step="0.1" name="xeploai[${xepLoaiIndex}][DiemDen]" class="form-control" placeholder="Điểm tối đa" min="0" max="100">
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
    $('#chuongTrinhForm').submit(function (e) {
        let isValid = true;
        let totalTiLePhanTram = 0;

        // Validate hình thức đánh giá
        $('.danh-gia-row').each(function () {
            const tiLePhanTram = parseFloat($(this).find('input[name$="[TiLePhanTram]"]').val()) || 0;
            totalTiLePhanTram += tiLePhanTram;

            if (tiLePhanTram < 0 || tiLePhanTram > 100) {
                alert('Tỷ lệ phần trăm phải nằm trong khoảng 0-100%');
                isValid = false;
                return false;
            }
        });

        // Kiểm tra tổng tỷ lệ phần trăm
        if (isValid && Math.abs(totalTiLePhanTram - 100) > 0.01) {
            alert('Tổng tỷ lệ phần trăm phải bằng 100%');
            isValid = false;
        }

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
                if (diemTu < 0 || diemDen > 100) {
                    alert('Điểm phải nằm trong khoảng 0-100');
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