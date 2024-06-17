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
                    @error ('Lop')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="HocKi" class="form-label">Học Kỳ</label>
                    <select id="HocKi" class="form-select @error('HocKi') is-invalid @enderror" name="HocKi">
                        <option value="">----- Mã Học Kỳ -----</option>
                    </select>
                    @error ('HocKi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="NgayHoc" class="form-label">Ngày bắt đầu học</label>
                    <input type="date" class="form-control @error('NgayHoc') is-invalid @enderror" id="NgayHoc" name="NgayHoc">
                    <div id="NgayHoc" class="invalid-feedback">
                        Không thể chọn ngày bắt đầu là thứ 7 hoặc chủ nhật.
                    </div>
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
        var chuongTrinhSelect = document.getElementById('ChuongTrinhTrienKhai');
        var lopSelect = document.getElementById('Lop');
        var HocKiSelect = document.getElementById('HocKi');

        if (KhoaDaoTao && KhoaDaoTao !== "") {
            fetch(`/getChuongTrinh/${KhoaDaoTao}`)
                .then(response => response.json())
                .then(data => {
                    chuongTrinhSelect.innerHTML = '<option value="">----- Chương Trình Triển Khai -----</option>';
                    data.forEach(chuongTrinh => {
                        chuongTrinhSelect.innerHTML += `<option value="${chuongTrinh.MaChuongTrinh}">${chuongTrinh.MaChuongTrinh} ${chuongTrinh.TenChuongTrinh}</option>`;
                    });
                    chuongTrinhSelect.disabled = false;
                });
        } else {
            chuongTrinhSelect.innerHTML = '<option value="">----- Chương Trình Triển Khai -----</option>';
            chuongTrinhSelect.disabled = true;
        }

        lopSelect.innerHTML = '<option value="">----- Mã Lớp Học -----</option>';
        lopSelect.disabled = true;

        HocKiSelect.innerHTML = '<option value="">----- Mã Học Kỳ -----</option>';
        HocKiSelect.disabled = true;
    });

    document.getElementById('ChuongTrinhTrienKhai').addEventListener('change', function() {
        var chuongTrinh = this.value;
        var lopSelect = document.getElementById('Lop');
        var HocKiSelect = document.getElementById('HocKi');

        if (chuongTrinh && chuongTrinh !== "") {
            fetch(`/getLop/${chuongTrinh}`)
                .then(response => response.json())
                .then(data => {
                    lopSelect.innerHTML = '<option value="">----- Mã Lớp Học -----</option>';
                    data.forEach(lop => {
                        lopSelect.innerHTML += `<option value="${lop.MaLop}">${lop.MaLop} ${lop.TenLop}</option>`;
                    });
                    lopSelect.disabled = false;
                });

            fetch(`/getHK/${chuongTrinh}`)
                .then(response => response.json())
                .then(data => {
                    HocKiSelect.innerHTML = '<option value="">----- Mã Học Kỳ -----</option>';
                    data.forEach(HocKi => {
                        HocKiSelect.innerHTML += `<option value="${HocKi.MaHK}">${HocKi.MaHK} ${HocKi.TenHK}</option>`;
                    });
                    HocKiSelect.disabled = false;
                });
        } else {
            lopSelect.innerHTML = '<option value="">----- Mã Lớp Học -----</option>';
            lopSelect.disabled = true;

            HocKiSelect.innerHTML = '<option value="">----- Mã Học Kỳ -----</option>';
            HocKiSelect.disabled = true;
        }
    });

    document.getElementById('ChuongTrinhTrienKhai').disabled = true;
    document.getElementById('Lop').disabled = true;
    document.getElementById('HocKi').disabled = true;
const ngayHoc = document.getElementById('NgayHoc');
//const errorElement = document.querySelector('.error-message'); // Assuming the error message element has a class 'error-message'

ngayHoc.addEventListener('change', function() {
  const ngay = new Date(this.value);
  const thu = ngay.getDay();

  if (thu === 0 || thu === 6) {
    // Hiển thị thông báo lỗi
    ngayHoc.classList.add('is-invalid');
    //errorElement.textContent = 'Vui lòng chọn ngày trong tuần (Thứ Hai - Thứ Sáu)';

    // Reset form về trạng thái chưa chọn
    this.value = ''; // Xóa giá trị trong input
  } else {
    // Xóa thông báo lỗi và class invalid
    ngayHoc.classList.remove('is-invalid');
    //errorElement.textContent = '';
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
