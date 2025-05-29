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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($danhSachSinhVien as $dssv)
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
                                                    min="0" max="20" step="0.1" required>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                    name="diemThi[{{ $loop->index }}][DiemThucHanh]" 
                                                    class="form-control diem-thuc-hanh-input" 
                                                    value="{{ $dssv->diemThi ? $dssv->diemThi['ThucHanh']['Diem'] : '' }}" 
                                                    min="0" max="20" step="0.1" required>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                    name="diemThi[{{ $loop->index }}][DiemDuAn]" 
                                                    class="form-control diem-du-an-input" 
                                                    value="{{ $dssv->diemThi ? $dssv->diemThi['DuAn']['Diem'] : '' }}" 
                                                    min="0" max="100" step="0.1" required>
                                            </td>
                                            <td class="diem-tong-ket">
                                                {{ $dssv->diemThi ? $dssv->diemThi['TongKet']['Diem'] : '' }}
                                            </td>
                                            <td class="trang-thai">
                                                {{ $dssv->diemThi ? $dssv->diemThi['TongKet']['TrangThai'] : '' }}
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
    document.getElementById('formNhapDiemLop').addEventListener('submit', function(e) {
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
                    const index = match[1];
                    const field = match[2];
                    
                    if (!acc[index]) acc[index] = {};
                    acc[index][field] = data[key];
                    
                    return acc;
                }, [])
        };

        fetch('/tochucthi/diemthi/luu-diem-lop', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(jsonData)
        })
        .then(response => response.json())
        .then(result => {
            const ketQuaDiv = document.getElementById('ketQuaNhapDiem');
            if (result.success) {
                ketQuaDiv.innerHTML = `
                    <div class="alert alert-success">
                        <h4>Kết Quả Nhập Điểm</h4>
                        <p>${result.message}</p>
                    </div>
                `;
                
                // Tự động tính điểm tổng kết và trạng thái
                document.querySelectorAll('tr').forEach(row => {
                    const diemLyThuyet = parseFloat(row.querySelector('.diem-ly-thuyet-input').value) || 0;
                    const diemThucHanh = parseFloat(row.querySelector('.diem-thuc-hanh-input').value) || 0;
                    const diemDuAn = parseFloat(row.querySelector('.diem-du-an-input').value) || 0;

                    const diemTongKet = (diemLyThuyet * 0.5) + (diemThucHanh * 0.3) + (diemDuAn * 0.2);
                    const trangThai = diemTongKet >= 5.0 ? 'DatChuan' : 'ChuaDatChuan';

                    row.querySelector('.diem-tong-ket').textContent = diemTongKet.toFixed(2);
                    row.querySelector('.trang-thai').textContent = trangThai;
                });
            } else {
                ketQuaDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <h4>Lỗi Nhập Điểm</h4>
                        <p>${result.message}</p>
                        ${result.errors ? `<ul>
                            ${result.errors.map(error => `<li>${error}</li>`).join('')}
                        </ul>` : ''}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            const ketQuaDiv = document.getElementById('ketQuaNhapDiem');
            ketQuaDiv.innerHTML = `
                <div class="alert alert-danger">
                    <h4>Lỗi Hệ Thống</h4>
                    <p>Đã có lỗi xảy ra khi lưu điểm. Vui lòng thử lại sau.</p>
                </div>
            `;
        });
    });
    </script>
@endsection

