@extends('layouts.app')

@section('content')

<div class="container my-5">
    <div class="row">
        <h1 class="text-center my-5">Lập thời khóa biểu</h1>
        <div class="d-flex justify-content-center">
            <form class="w-50" method="POST" action="{{ route('saveSchedule') }}" onsubmit="return validateForm()">
                @csrf
                <div class="mb-3">
                    <label for="TenTKB" class="form-label">Tên thời khóa biểu</label>
                    <input type="text" class="form-control @error('TenTKB') is-invalid @enderror" id="TenTKB" name="TenTKB">
                    @error('TenTKB')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="LoaiDaoTao" class="form-label">Loại đào tạo</label>
                    <select id="LoaiDaoTao" class="form-select @error('LoaiDaoTao') is-invalid @enderror" name="LoaiDaoTao">
                        <option value="">----- Loại Đào Tạo -----</option>
                        @foreach($khoadaotaos as $khoadaotao)
                            <option value="{{ $khoadaotao->TenKhoaDaoTao }}">{{ $khoadaotao->TenKhoaDaoTao }}</option>
                        @endforeach
                    </select>
                    @error('LoaiDaoTao')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="ChuongTrinhTruyenKhai" class="form-label">Chương trình triển khai</label>
                    <select id="ChuongTrinhTruyenKhai" class="form-select @error('ChuongTrinhTruyenKhai') is-invalid @enderror" name="ChuongTrinhTruyenKhai">
                        <option value="">----- Chương Trình Triển Khai -----</option>
                    </select>
                    @error('ChuongTrinhTruyenKhai')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="Lop" class="form-label">Mã lớp học</label>
                    <select id="Lop" class="form-select @error('Lop') is-invalid @enderror" name="Lop">
                        <option value="">----- Mã Lớp Học -----</option>
                    </select>
                    @if ($errors->has('Lop'))
                        @foreach ($errors->get('Lop') as $message)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @endforeach
                    @endif
                </div>

                <div class="mb-3">
                    <label for="TuanHoc" class="form-label">Tuần học</label>
                    <input type="number" class="form-control @error('TuanHoc') is-invalid @enderror" id="TuanHoc" name="TuanHoc">
                    @error('TuanHoc')
                     <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                </div>

                <div class="d-flex justify-content-center mt-5">
                    <button type="submit" class="btn btn-primary">Lập thời khóa biểu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('LoaiDaoTao').addEventListener('change', function() {
        var loaiDaoTao = this.value;
        if (loaiDaoTao) {
            fetch(`/getChuongTrinh/${loaiDaoTao}`)
                .then(response => response.json())
                .then(data => {
                    var chuongTrinhSelect = document.getElementById('ChuongTrinhTruyenKhai');
                    chuongTrinhSelect.innerHTML = '<option value="">----- Chương Trình Triển Khai -----</option>';
                    data.forEach(chuongTrinh => {
                        chuongTrinhSelect.innerHTML += `<option value="${chuongTrinh.MaChuongTrinh}">${chuongTrinh.TenChuongTrinh}</option>`;
                    });
                });
        }
    });

    document.getElementById('ChuongTrinhTruyenKhai').addEventListener('change', function() {
        var chuongTrinh = this.value;
        if (chuongTrinh) {
            fetch(`/getLop/${chuongTrinh}`)
                .then(response => response.json())
                .then(data => {
                    var lopSelect = document.getElementById('Lop');
                    lopSelect.innerHTML = '<option value="">----- Mã Lớp Học -----</option>';
                    data.forEach(lop => {
                        lopSelect.innerHTML += `<option value="${lop.MaLop}">${lop.TenLop}</option>`;
                    });
                });
        }
    });

    // function validateForm() {
    //     var loaiDaoTao = document.getElementById('LoaiDaoTao').value;
    //     var chuongTrinh = document.getElementById('ChuongTrinhTruyenKhai').value;
    //     var lop = document.getElementById('Lop').value;

    //     if (loaiDaoTao === "" || chuongTrinh === "" || lop === "") {
    //         alert('Vui lòng chọn đầy đủ các trường thông tin.');
    //         return false;
    //     }
    //     return true;
    // }
</script>

@endsection
