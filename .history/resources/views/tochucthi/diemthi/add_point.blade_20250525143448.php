@extends('layouts.new_app.master')
@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Nhập điểm thi - Lớp: {{ $lop->TenLop }} - Môn: {{ $tenMH }}</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('bangdiem.import') }}" method="POST" enctype="multipart/form-data" id="diemForm">
                        @csrf
                        <input type="hidden" name="maLop" value="{{ $maLop }}">
                        <input type="hidden" name="tenMH" value="{{ $tenMH }}">

                        <!-- Debug: Hiển thị dữ liệu để kiểm tra -->
                        @if (session('debug'))
                            <div class="alert alert-info">
                                <strong>Debug Info:</strong>
                                <pre>{{ print_r(session('debug'), true) }}</pre>
                            </div>
                        @endif

                        <!-- Nhập điểm thủ công -->
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Mã SV</th>
                                        <th>Họ Tên</th>
                                        <th>Lần thi</th>
                                        <th>Điểm</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($danhSachSV as $index => $sv)
                                        <tr>
                                            <td>{{ $sv->sinhVien->MaSV }}</td>
                                            <td>{{ $sv->sinhVien->HoTen }}</td>
                                            <td>
                                                <input type="number" name="lanThi[{{ $sv->sinhVien->MaSV }}]"
                                                    class="form-control lan-thi-input"
                                                    value="{{ $sv->diem ? $sv->diem->LanThi : 1 }}" min="1"
                                                    data-masv="{{ $sv->sinhVien->MaSV }}" required>
                                            </td>
                                            <td>
                                                <input type="number" name="diem[{{ $sv->sinhVien->MaSV }}]"
                                                    class="form-control diem-input" step="0.1" min="0"
                                                    max="10" value="{{ $sv->diem ? $sv->diem->Diem : '' }}"
                                                    data-masv="{{ $sv->sinhVien->MaSV }}" placeholder="Nhập điểm">
                                            </td>
                                            <td>
                                                <input type="text" name="ghiChu[{{ $sv->sinhVien->MaSV }}]"
                                                    class="form-control ghi-chu-input"
                                                    value="{{ $sv->diem ? $sv->diem->GhiChu : '' }}"
                                                    data-masv="{{ $sv->sinhVien->MaSV }}" placeholder="Ghi chú">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Import Excel -->
                        <div class="form-group mt-3">
                            <label>Import điểm từ Excel</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls">
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary" onclick="debugForm()">Lưu điểm</button>
                            <a href="{{ route('bangdiem.export', ['maLop' => $maLop, 'tenMH' => $tenMH]) }}"
                                class="btn btn-success">Xuất Excel</a>
                        </div>
                    </form>

                    <script>
                        function debugForm() {
                            // Debug: Log form data trước khi submit
                            const formData = new FormData(document.getElementById('diemForm'));
                            console.log('Form Data:');
                            for (let [key, value] of formData.entries()) {
                                console.log(key, value);
                            }

                            // Kiểm tra dữ liệu điểm
                            const diemInputs = document.querySelectorAll('.diem-input');
                            diemInputs.forEach(input => {
                                if (input.value) {
                                    console.log(`MaSV: ${input.dataset.masv}, Diem: ${input.value}`);
                                }
                            });
                        }

                        // Debug: Theo dõi thay đổi input
                        document.addEventListener('DOMContentLoaded', function() {
                            const inputs = document.querySelectorAll('input[data-masv]');
                            inputs.forEach(input => {
                                input.addEventListener('change', function() {
                                    console.log(
                                        `Changed - MaSV: ${this.dataset.masv}, Field: ${this.name}, Value: ${this.value}`
                                        );
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
