@extends('layouts.new_app.master')

@section(section: 'main-content')
    <div class="section">
        <div class="section-header">
            <h1>Chọn lớp và môn học</h1>
        </div>

        <form action="{{ route('bangdiem.xem') }}" method="GET">
            <div class="form-group">
                <label for="maLop">Chọn lớp:</label>
                <select name="maLop" id="maLop" class="form-control select2" required>
                    @foreach ($dsLop as $lop)
                        <option value="{{ $lop->MaLop }}">{{ $lop->MaLop }} - {{ $lop->TenLop }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3">
                <label for="tenMH">Chọn môn học:</label>
                <select name="tenMH" id="tenMH" class="form-control select2" required>
                    @foreach ($dsMon as $mon)
                        <option value="{{ $mon->TenMH }}">{{ $mon->TenMH }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button type="submit" class="btn btn-primary">Xem bảng điểm</button>
                
                <a href="{{ route('bangdiem.nhap-diem') }}" class="btn btn-success" id="btnNhapDiem">
                    <i class="fas fa-edit"></i> Nhập điểm
                </a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const maLopSelect = document.getElementById('maLop');
        const tenMHSelect = document.getElementById('tenMH');
        const btnNhapDiem = document.getElementById('btnNhapDiem');

        function updateNhapDiemLink() {
            const maLop = maLopSelect.value;
            const tenMH = tenMHSelect.value;
            
            btnNhapDiem.href = `/tochucthi/bangdiem/nhap-diem?maLop=${maLop}&tenMH=${tenMH}`;
        }

        maLopSelect.addEventListener('change', updateNhapDiemLink);
        tenMHSelect.addEventListener('change', updateNhapDiemLink);

        // Khởi tạo ban đầu
        updateNhapDiemLink();
    });
    </script>
@endsection
