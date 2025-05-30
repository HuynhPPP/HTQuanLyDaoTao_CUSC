@extends('layouts.new_app.master')
@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Nhập điểm thi - Lớp: {{ $lop->TenLop }} - Môn: {{ $tenMH }}</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <h2>Nhập Điểm Chi Tiết</h2>
                        <form id="formNhapDiem" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mã Sinh Viên</label>
                                        <input type="text" name="MaSV" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Tên Môn Học</label>
                                        <input type="text" name="TenMH" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Mã Lớp</label>
                                        <input type="text" name="MaLop" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Lần Thi</label>
                                        <input type="number" name="LanThi" class="form-control" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Điểm Lý Thuyết (50%, 6 bài, 20đ/bài)</label>
                                        <input type="number" name="DiemLyThuyet" class="form-control" min="0" max="20" step="0.1" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Điểm Thực Hành (30%, 5 bài, 20đ/bài)</label>
                                        <input type="number" name="DiemThucHanh" class="form-control" min="0" max="20" step="0.1" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Điểm Dự Án (20%, 100đ)</label>
                                        <input type="number" name="DiemDuAn" class="form-control" min="0" max="100" step="0.1" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Ghi Chú (Tùy Chọn)</label>
                                        <textarea name="GhiChu" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Lưu Điểm</button>
                        </form>

                        <div id="ketQuaNhapDiem" class="mt-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('formNhapDiem').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        fetch('/tochucthi/diemthi/nhap-diem', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            const ketQuaDiv = document.getElementById('ketQuaNhapDiem');
            if (result.diemThi) {
                ketQuaDiv.innerHTML = `
                    <div class="alert alert-success">
                        <h4>Kết Quả Nhập Điểm</h4>
                        <p>Điểm Lý Thuyết: ${result.diemThi.LyThuyet.Diem}</p>
                        <p>Điểm Thực Hành: ${result.diemThi.ThucHanh.Diem}</p>
                        <p>Điểm Dự Án: ${result.diemThi.DuAn.Diem}</p>
                        <p>Điểm Tổng Kết: ${result.diemThi.TongKet.Diem}</p>
                        <p>Trạng Thái: ${result.diemThi.TongKet.TrangThai}</p>
                    </div>
                `;
            } else {
                ketQuaDiv.innerHTML = `
                    <div class="alert alert-danger">
                        ${result.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
        });
    });
    </script>
@endsection

