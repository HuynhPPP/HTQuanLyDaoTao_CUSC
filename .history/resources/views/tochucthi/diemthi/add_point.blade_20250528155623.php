@extends('layouts.new_app.master')
@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Nhập điểm thi - Lớp: {{ $lop->TenLop }} - Môn: {{ $tenMH }}</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form id="formNhapDiemLop" method="POST">
                        @csrf
                        <input type="hidden" name="maLop" value="{{ $lop->MaLop }}">
                        <input type="hidden" name="tenMH" value="{{ $tenMH }}">
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Mã SV</th>
                                        <th>Họ Tên</th>
                                        <th>Lần Thi</th>
                                        <th>Điểm Lý Thuyết (50%, 6 bài, 20đ/bài)</th>
                                        <th>Điểm Thực Hành (30%, 5 bài, 20đ/bài)</th>
                                        <th>Điểm Dự Án (20%, 100đ)</th>
                                        <th>Điểm Tổng Kết</th>
                                        <th>Trạng Thái</th>
                                        <th>Chỉnh Sửa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($danhSachSV as $dssv)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="diemThi[{{ $loop->index }}][MaSV]" 
                                                    value="{{ $dssv->sinhVien->MaSV }}">
                                                {{ $dssv->sinhVien->MaSV }}
                                            </td>
                                            <td>{{ $dssv->sinhVien->HoTen }}</td>
                                            <td>
                                                <input type="number" 
                                                    name="diemThi[{{ $loop->index }}][LanThi]" 
                                                    class="form-control lan-thi-input" 
                                                    value="{{ $dssv->diemThi ? $dssv->diemThi['TongKet']['LanThi'] : 1 }}" 
                                                    min="1" required>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                    name="diemThi[{{ $loop->index }}][DiemLyThuyet]" 
                                                    class="form-control diem-ly-thuyet-input" 
                                                    value="{{ $dssv->diemThi ? $dssv->diemThi['LyThuyet']['Diem'] : '' }}" 
                                                    min="0" max="100" step="0.1" required>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                    name="diemThi[{{ $loop->index }}][DiemThucHanh]" 
                                                    class="form-control diem-thuc-hanh-input" 
                                                    value="{{ $dssv->diemThi ? $dssv->diemThi['ThucHanh']['Diem'] : '' }}" 
                                                    min="0" max="100" step="0.1" required>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                    name="diemThi[{{ $loop->index }}][DiemDuAn]" 
                                                    class="form-control diem-du-an-input" 
                                                    value="{{ $dssv->diemThi ? $dssv->diemThi['DuAn']['Diem'] : '' }}" 
                                                    min="0" max="100" step="0.1" required>
                                            </td>
                                            <td class="diem-tong-ket" data-toggle="tooltip" 
                                                title="Điểm Lý Thuyết (50%): {{ $dssv->diemThi ? $dssv->diemThi['LyThuyet']['Diem'] : '' }} 
Điểm Thực Hành (30%): {{ $dssv->diemThi ? $dssv->diemThi['ThucHanh']['Diem'] : '' }} 
Điểm Dự Án (20%): {{ $dssv->diemThi ? $dssv->diemThi['DuAn']['Diem'] : '' }}">
                                                {{ $dssv->diemThi ? $dssv->diemThi['TongKet']['Diem'] : '' }}
                                            </td>
                                            <td class="trang-thai" data-toggle="tooltip" 
                                                title="Trạng thái đạt chuẩn dựa trên điểm tổng kết">
                                                {{ $dssv->diemThi ? $dssv->diemThi['TongKet']['TrangThai'] : '' }}
                                            </td>
                                            <td class="chinh-sua-cell">
                                                @if($dssv->diemThi)
                                                    <button type="button" class="btn btn-warning btn-sm" onclick="chinhSuaDiem('{{ $dssv->sinhVien->MaSV }}')">
                                                        <i class="fas fa-edit"></i> Chỉnh sửa
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Lưu Điểm Toàn Bộ Lớp</button>
                            <a href="{{ route('bangdiem.export', ['maLop' => $lop->MaLop, 'tenMH' => $tenMH]) }}" 
                               class="btn btn-success ml-2">Xuất Excel</a>
                        </div>
                    </form>

                    <div id="ketQuaNhapDiem" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Kích hoạt tooltip
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Thêm validation trước khi gửi
    function validateDiem(input) {
        const value = parseFloat(input.value);
        if (isNaN(value) || value < 0 || value > 100) {
            input.classList.add('is-invalid');
            return false;
        }
        input.classList.remove('is-invalid');
        return true;
    }

    // Thêm sự kiện validation cho các input điểm
    document.querySelectorAll('.diem-ly-thuyet-input, .diem-thuc-hanh-input, .diem-du-an-input').forEach(input => {
        input.addEventListener('input', function() {
            validateDiem(this);
        });
    });

    document.getElementById('formNhapDiemLop').addEventListener('submit', function(e) {
        // Kiểm tra validation trước khi submit
        const diemInputs = document.querySelectorAll('.diem-ly-thuyet-input, .diem-thuc-hanh-input, .diem-du-an-input');
        const isValid = Array.from(diemInputs).every(validateDiem);

        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng kiểm tra lại các điểm nhập (0-100)');
            return;
        }

        // Phần code submit cũ...
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        // Chuyển đổi dữ liệu để phù hợp với định dạng JSON
        const jsonData = {
            maLop: data.maLop,
            tenMH: data.tenMH,
            diemThi: Object.keys(data)
                .filter(key => key.startsWith('diemThi['))
                .reduce((acc, key) => {
                    const match = key.match(/diemThi\[(\d+)\]\[(\w+)\]/);
                    if (!match) return acc;
                    
                    const index = match[1];
                    const field = match[2];
                    
                    if (!acc[index]) acc[index] = {};
                    acc[index][field] = data[key];
                    
                    return acc;
                }, [])
        };

        // Kiểm tra dữ liệu trước khi gửi
        if (!jsonData.diemThi || jsonData.diemThi.length === 0) {
            alert('Không có dữ liệu điểm để lưu.');
            return;
        }

        // Thêm hiệu ứng loading
        const submitButton = e.target.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang lưu...';
        submitButton.disabled = true;

        fetch('/tochucthi/bangdiem/luu-diem-lop', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? 
                    document.querySelector('meta[name="csrf-token"]').getAttribute('content') : ''
            },
            body: JSON.stringify(jsonData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Lỗi kết nối máy chủ');
            }
            return response.json();
        })
        .then(result => {
            const ketQuaDiv = document.getElementById('ketQuaNhapDiem');
            if (result.success) {
                ketQuaDiv.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading">Kết Quả Nhập Điểm</h4>
                        <p>${result.message}</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                
                // Tự động tính điểm tổng kết và trạng thái
                document.querySelectorAll('tr').forEach(row => {
                    const diemLyThuyetInput = row.querySelector('.diem-ly-thuyet-input');
                    const diemThucHanhInput = row.querySelector('.diem-thuc-hanh-input');
                    const diemDuAnInput = row.querySelector('.diem-du-an-input');
                    const diemTongKetCell = row.querySelector('.diem-tong-ket');
                    const trangThaiCell = row.querySelector('.trang-thai');

                    if (diemLyThuyetInput && diemThucHanhInput && diemDuAnInput && 
                        diemTongKetCell && trangThaiCell) {
                        const diemLyThuyet = parseFloat(diemLyThuyetInput.value) || 0;
                        const diemThucHanh = parseFloat(diemThucHanhInput.value) || 0;
                        const diemDuAn = parseFloat(diemDuAnInput.value) || 0;

                        const diemTongKet = (diemLyThuyet * 0.5) + (diemThucHanh * 0.3) + (diemDuAn * 0.2);
                        const trangThai = diemTongKet >= 50.0 ? 'DatChuan' : 'ChuaDatChuan';

                        diemTongKetCell.textContent = diemTongKet.toFixed(2);
                        diemTongKetCell.setAttribute('data-original-title', 
                            `Điểm Lý Thuyết (50%): ${diemLyThuyet.toFixed(2)} 
Điểm Thực Hành (30%): ${diemThucHanh.toFixed(2)} 
Điểm Dự Án (20%): ${diemDuAn.toFixed(2)}`);
                        
                        trangThaiCell.textContent = trangThai;

                        // Thêm nút chỉnh sửa cho từng sinh viên
                        const maSV = row.querySelector('input[name^="diemThi["][name$="[MaSV]"]').value;
                        const chinhSuaCell = row.querySelector('.chinh-sua-cell');
                        if (chinhSuaCell) {
                            chinhSuaCell.innerHTML = `
                                <button type="button" class="btn btn-warning btn-sm" onclick="chinhSuaDiem('${maSV}')">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </button>
                            `;
                        }
                    }
                });
            } else {
                ketQuaDiv.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading">Lỗi Nhập Điểm</h4>
                        <p>${result.message}</p>
                        ${result.errors ? `<ul>
                            ${result.errors.map(error => `<li>${error}</li>`).join('')}
                        </ul>` : ''}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
            }

            // Khôi phục trạng thái nút submit
            submitButton.innerHTML = 'Lưu Điểm Toàn Bộ Lớp';
            submitButton.disabled = false;
        })
        .catch(error => {
            console.error('Lỗi:', error);
            const ketQuaDiv = document.getElementById('ketQuaNhapDiem');
            ketQuaDiv.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading">Lỗi Hệ Thống</h4>
                    <p>Đã có lỗi xảy ra khi lưu điểm. Vui lòng thử lại sau.</p>
                    <p>Chi tiết lỗi: ${error.message}</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;

            // Khôi phục trạng thái nút submit
            submitButton.innerHTML = 'Lưu Điểm Toàn Bộ Lớp';
            submitButton.disabled = false;
        });
    });

    // Hàm chỉnh sửa điểm cho từng sinh viên
    function chinhSuaDiem(maSV) {
        const row = document.querySelector(`input[value="${maSV}"]`).closest('tr');
        const diemLyThuyetInput = row.querySelector('.diem-ly-thuyet-input');
        const diemThucHanhInput = row.querySelector('.diem-thuc-hanh-input');
        const diemDuAnInput = row.querySelector('.diem-du-an-input');
        const lanThiInput = row.querySelector('.lan-thi-input');
        const diemTongKetCell = row.querySelector('.diem-tong-ket');
        const trangThaiCell = row.querySelector('.trang-thai');
        const chinhSuaCell = row.querySelector('.chinh-sua-cell');

        // Lưu giá trị ban đầu để có thể hoàn tác
        const oldValues = {
            diemLyThuyet: diemLyThuyetInput.value,
            diemThucHanh: diemThucHanhInput.value,
            diemDuAn: diemDuAnInput.value,
            lanThi: lanThiInput.value,
            diemTongKet: diemTongKetCell.textContent,
            trangThai: trangThaiCell.textContent
        };

        // Tạo form chỉnh sửa
        chinhSuaCell.innerHTML = `
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success btn-sm" onclick="luuChinhSua('${maSV}')">
                    <i class="fas fa-save"></i> Lưu
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="huyChinhSua('${maSV}')">
                    <i class="fas fa-times"></i> Hủy
                </button>
            </div>
        `;

        // Cho phép chỉnh sửa các input
        diemLyThuyetInput.removeAttribute('readonly');
        diemThucHanhInput.removeAttribute('readonly');
        diemDuAnInput.removeAttribute('readonly');
        lanThiInput.removeAttribute('readonly');

        // Lưu thông tin để hoàn tác
        row.dataset.oldValues = JSON.stringify(oldValues);
    }

    // Hàm lưu chỉnh sửa
    function luuChinhSua(maSV) {
        const row = document.querySelector(`input[value="${maSV}"]`).closest('tr');
        const diemLyThuyetInput = row.querySelector('.diem-ly-thuyet-input');
        const diemThucHanhInput = row.querySelector('.diem-thuc-hanh-input');
        const diemDuAnInput = row.querySelector('.diem-du-an-input');
        const lanThiInput = row.querySelector('.lan-thi-input');
        const diemTongKetCell = row.querySelector('.diem-tong-ket');
        const trangThaiCell = row.querySelector('.trang-thai');
        const chinhSuaCell = row.querySelector('.chinh-sua-cell');

        // Tính toán điểm tổng kết
        const diemLyThuyet = parseFloat(diemLyThuyetInput.value) || 0;
        const diemThucHanh = parseFloat(diemThucHanhInput.value) || 0;
        const diemDuAn = parseFloat(diemDuAnInput.value) || 0;

        const diemTongKet = (diemLyThuyet * 0.5) + (diemThucHanh * 0.3) + (diemDuAn * 0.2);
        const trangThai = diemTongKet >= 50.0 ? 'DatChuan' : 'ChuaDatChuan';

        // Chuẩn bị dữ liệu để gửi
        const jsonData = {
            maLop: document.querySelector('input[name="maLop"]').value,
            tenMH: document.querySelector('input[name="tenMH"]').value,
            diemThi: [{
                MaSV: maSV,
                DiemLyThuyet: diemLyThuyet,
                DiemThucHanh: diemThucHanh,
                DiemDuAn: diemDuAn,
                LanThi: parseInt(lanThiInput.value)
            }]
        };

        // Gửi yêu cầu cập nhật
        fetch('/tochucthi/bangdiem/luu-diem-lop', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(jsonData)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Cập nhật giao diện
                diemTongKetCell.textContent = diemTongKet.toFixed(2);
                trangThaiCell.textContent = trangThai;

                // Khóa input và hiển thị nút chỉnh sửa
                diemLyThuyetInput.setAttribute('readonly', true);
                diemThucHanhInput.setAttribute('readonly', true);
                diemDuAnInput.setAttribute('readonly', true);
                lanThiInput.setAttribute('readonly', true);

                chinhSuaCell.innerHTML = `
                    <button type="button" class="btn btn-warning btn-sm" onclick="chinhSuaDiem('${maSV}')">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </button>
                `;

                // Hiển thị thông báo thành công
                const ketQuaDiv = document.getElementById('ketQuaNhapDiem');
                ketQuaDiv.innerHTML = `
                    <div class="alert alert-success">
                        <h4>Cập Nhật Điểm</h4>
                        <p>Đã cập nhật điểm cho sinh viên ${maSV} thành công.</p>
                    </div>
                `;
            } else {
                throw new Error(result.message);
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            const ketQuaDiv = document.getElementById('ketQuaNhapDiem');
            ketQuaDiv.innerHTML = `
                <div class="alert alert-danger">
                    <h4>Lỗi Cập Nhật Điểm</h4>
                    <p>Không thể cập nhật điểm. ${error.message}</p>
                </div>
            `;
        });
    }

    // Hàm hủy chỉnh sửa
    function huyChinhSua(maSV) {
        const row = document.querySelector(`input[value="${maSV}"]`).closest('tr');
        const diemLyThuyetInput = row.querySelector('.diem-ly-thuyet-input');
        const diemThucHanhInput = row.querySelector('.diem-thuc-hanh-input');
        const diemDuAnInput = row.querySelector('.diem-du-an-input');
        const lanThiInput = row.querySelector('.lan-thi-input');
        const diemTongKetCell = row.querySelector('.diem-tong-ket');
        const trangThaiCell = row.querySelector('.trang-thai');
        const chinhSuaCell = row.querySelector('.chinh-sua-cell');

        // Khôi phục giá trị ban đầu
        const oldValues = JSON.parse(row.dataset.oldValues);
        diemLyThuyetInput.value = oldValues.diemLyThuyet;
        diemThucHanhInput.value = oldValues.diemThucHanh;
        diemDuAnInput.value = oldValues.diemDuAn;
        lanThiInput.value = oldValues.lanThi;
        diemTongKetCell.textContent = oldValues.diemTongKet;
        trangThaiCell.textContent = oldValues.trangThai;

        // Khóa input và hiển thị nút chỉnh sửa
        diemLyThuyetInput.setAttribute('readonly', true);
        diemThucHanhInput.setAttribute('readonly', true);
        diemDuAnInput.setAttribute('readonly', true);
        lanThiInput.setAttribute('readonly', true);

        chinhSuaCell.innerHTML = `
            <button type="button" class="btn btn-warning btn-sm" onclick="chinhSuaDiem('${maSV}')">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </button>
        `;
    }
    </script>
@endsection

