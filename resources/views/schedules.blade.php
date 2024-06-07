@extends('layouts.app')

@section('content')

<div class="container my-5">
    <div class="row">
        <h1 class="text-center my-5">Lập thời khóa biểu</h1>
        <div class="d-flex justify-content-center">
            <form class="w-50" method="POST" action="{{ route('saveSchedule') }}" onsubmit="return validateForm()">
                @csrf
                <div class="mb-3">
                    <label for="KhoaDaoTao" class="form-label">Khóa đào tạo</label>
                    <select id="KhoaDaoTao" class="form-select @error('KhoaDaoTao') is-invalid @enderror" name="KhoaDaoTao">
                        <option value="">----- Khóa Đào Tạo -----</option>
                        @foreach($khoadaotaos as $khoadaotao)
                            <option value="{{ $khoadaotao->TenKhoaDaoTao }}">{{ $khoadaotao->TenKhoaDaoTao }}</option>
                        @endforeach
                    </select>
                    @error('KhoaDaoTao')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="ChuongTrinhTrienKhai" class="form-label">Chương trình triển khai</label>
                    <select id="ChuongTrinhTrienKhai" class="form-select @error('ChuongTrinhTrienKhai') is-invalid @enderror" name="ChuongTrinhTrienKhai">
                        <option value="">----- Chương Trình Triển Khai -----</option>
                    </select>
                    @error('ChuongTrinhTrienKhai')
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
                    <label for="HocKi" class="form-label">Học Kỳ</label>
                    <select id="HocKi" class="form-select @error('HocKi') is-invalid @enderror" name="HocKi">
                        <option value="">Học kỳ</option>
                        @foreach($hockis as $hocki)
                            <option value="{{ $hocki->MaHK }}">{{ $hocki->MaHK }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('HocKi'))
                        @foreach ($errors->get('HocKi') as $message)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @endforeach
                    @endif
                </div>



                <div class="mb-3">
                    <label for="NgayHoc" class="form-label">Ngày bắt đầu học</label>
                    <input type="date" class="form-control @error('NgayHoc') is-invalid @enderror" id="NgayHoc" name="NgayHoc">
                    @error('NgayHoc')
                     <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                </div>

                <div class="mb-3">
                    <label for="TenKG" class="form-label">Khung gio hoc</label>
                    <select id="TenKG" class="form-select @error('TenKG') is-invalid @enderror" name="TenKG">
                        <option value="">----- Khung gio hoc -----</option>
                         @foreach($khunggios as $khunggio)
                            <option value="{{ $khunggio->TenKhungGio }}">{{ $khunggio->TenKhungGio }}</option>
                        @endforeach
                    </select>
                    @error('TenKG')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label for="PhongLT" class="form-label">Phòng lý thuyết</label>
                    <select id="PhongLT" class="form-select @error('PhongLT') is-invalid @enderror" name="PhongLT">
                        <option value="">Phòng lý thuyết</option>
                        @foreach($phongLTs as $phonglt)
                            <option value="{{ $phonglt->TenPhong }}">{{ $phonglt->TenPhong }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('PhongLT'))
                        @foreach ($errors->get('PhongLT') as $message)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @endforeach
                    @endif
                </div>

                <div class="mb-3">
                    <label for="PhongTH" class="form-label">Phòng thực hành</label>
                    <select id="PhongTH" class="form-select @error('PhongTH') is-invalid @enderror" name="PhongTH">
                        <option value="">Phòng thực hành</option>
                        @foreach($phongTHs as $phongTH)
                            <option value="{{ $phongTH->TenPhong }}">{{ $phongTH->TenPhong }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('PhongTH'))
                        @foreach ($errors->get('PhongTH') as $message)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @endforeach
                    @endif
                </div>
                <div class="d-flex justify-content-center mt-5">
                    <button type="submit" class="btn btn-primary">Lập thời khóa biểu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row text-center"">
            <h1>Các thời khóa biểu đã lập</h1>
            @foreach ($tkbs as $tkb)
              <a href="{{ route('schedule',  $tkb->TenTKB) }}" class="link link-primary fs-5">{{ $tkb->TenTKB }}</a>
            @endforeach
    </div>
</div>

<script>
    document.getElementById('KhoaDaoTao').addEventListener('change', function() {
        var KhoaDaoTao = this.value;
        if (KhoaDaoTao) {
            fetch(`/getChuongTrinh/${KhoaDaoTao}`)
                .then(response => response.json())
                .then(data => {
                    var chuongTrinhSelect = document.getElementById('ChuongTrinhTrienKhai');
                    chuongTrinhSelect.innerHTML = '<option value="">----- Chương Trình Triển Khai -----</option>';
                    data.forEach(chuongTrinh => {
                        chuongTrinhSelect.innerHTML += `<option value="${chuongTrinh.MaChuongTrinh}">${chuongTrinh.MaChuongTrinh} ${chuongTrinh.TenChuongTrinh}</option>`;
                    });
                });
        }
    });

    document.getElementById('ChuongTrinhTrienKhai').addEventListener('change', function() {
        var chuongTrinh = this.value;
        if (chuongTrinh) {
            fetch(`/getLop/${chuongTrinh}`)
                .then(response => response.json())
                .then(data => {
                    var lopSelect = document.getElementById('Lop');
                    lopSelect.innerHTML = '<option value="">----- Mã Lớp Học -----</option>';
                    data.forEach(lop => {
                        lopSelect.innerHTML += `<option value="${lop.MaLop}">${lop.MaLop} ${lop.TenLop}</option>`;
                    });
                });
        }
    });

    // function validateForm() {
    //     var KhoaDaoTao = document.getElementById('KhoaDaoTao').value;
    //     var chuongTrinh = document.getElementById('ChuongTrinhTrienKhai').value;
    //     var lop = document.getElementById('Lop').value;

    //     if (KhoaDaoTao === "" || chuongTrinh === "" || lop === "") {
    //         alert('Vui lòng chọn đầy đủ các trường thông tin.');
    //         return false;
    //     }
    //     return true;
    // }
</script>

@endsection
