@extends('layouts.new_app.master')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Lập thời khóa biểu</h1>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow rounded-3 mb-5">
                <div class="card-body">
                    <form method="POST" action="{{ route('saveSchedule') }}" onsubmit="return validateForm()">
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
                        <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2">Lập thời khóa biểu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-center fw-bold text-secondary mb-4 mt-5">Các thời khóa biểu đã lập</h2>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="list-group">
                @forelse ($tkbs as $tkb)
                    <a href="{{ route('schedule',  $tkb->TenTKB) }}" class="list-group-item list-group-item-action fs-5 py-3">
                        {{ $tkb->TenTKB }}
                    </a>
                @empty
                    <div class="text-center text-muted py-4">Chưa có thời khóa biểu nào.</div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@if (session('success'))
    <script>
        iziToast.success({
            title: 'Thành công',
            message: '{{ session('success') }}',
            position: 'topRight'
        });
    </script>
@endif
@if (session('error'))
    <script>
        iziToast.error({
            title: 'Lỗi',
            message: '{{ session('error') }}',
            position: 'topRight'
        });
    </script>
@endif
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
</script>
@endsection
