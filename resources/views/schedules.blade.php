@extends('layouts.app')

@section('content')

 <div class="container my-5">
        <div class="row">
            <h1 class="text-center my-5">Lập thời khóa biểu</h1>
            <div class="d-flex justify-content-center">
            <form class="w-50" method="POST" action="{{ route('saveSchedule') }}">
                    @csrf
                 <div class="mb-3">
                     <label for="TenTKB" class="form-label">Tên thời khóa biểu</label>
                     <input type="text" class="form-control" id="TenTKB" name="TenTKB">
                 </div>
     
                 {{-- <div class="mb-3">
                     <label for="LoaiDaoTao" class="form-label">Loại đào tạo</label>
                     <select id="LoaiDaoTao" class="form-select" aria-label="Chọn khóa đào tạo">
                         <option selected>----- Loại Đào Tạo -----</option>
                     @foreach($loaidaotaos as $loaidaotao)
                         <option value="{{ $loaidaotao->TenKhoaDaoTao }}">{{ $loaidaotao->TenKhoaDaoTao }}</option>
                     @endforeach
                     </select>
                 </div> --}}
     
                 {{-- <div class="mb-3">
                     <label for="ChuongTrinhTruyenKhai" class="form-label">Chương trình triển khai </label>
                     <input class="form-control" id="ChuongTrinhTruyenKhai" list="DanhSachChuongTrinh">
                     <datalist id="DanhSachChuongTrinh">
                     @foreach($chuongtrinhs as $chuongtrinh)
                         <option value="{{ $chuongtrinh->MaChuongTrinh}}">{{ $chuongtrinh->TenChuongTrinh }}</option>
                     @endforeach
                     </datalist>
                     </select>
                 </div> --}}
     
                 <div class="mb-3">
                     <label for="Lop" class="form-label">Mã lớp học </label>
                     <input class="form-control" id="Lop" name="Lop" list="DanhSachLopHoc">
                     <datalist id="DanhSachLopHoc">
                         @foreach($lophocs as $lophoc)
                             <option value="{{ $lophoc->MaLop }}">{{ $lophoc->TenLop }}</option>
                         @endforeach
                     </datalist>
                     </select>
                 </div>
     
                 {{-- <div class="mb-3">
                     <label for="NgayBatDau" class="form-label">Ngày bắt đầu học </label>
                     <input type="date" class="form-control" id="NgayBatDau">
                 </div> --}}
     
                 {{-- <div class="mb-3">
                     <label for="PhongLT" class="form-label">Phòng học lý thuyết </label>
                     <select id="PhongLT" class="form-select" aria-label="Chọn phòng học lý thuyết">
                        <option selected>----- Phòng Học Lý Thuyết -----</option>
                         @foreach($phongLTs as $phongLT)
                             <option value="{{ $phongLT->TenPhong }}">{{ $phongLT->TenPhong }}</option>
                         @endforeach
                     </select>
                 </div>
     
                 <div class="mb-3">
                     <label for="PhongTH" class="form-label">Phòng học thực hành </label>
                     <select id="PhongTH" class="form-select" aria-label="Chọn phòng học thực hành">
                        <option selected>----- Phòng Học Thực Hành -----</option>
                         @foreach($phongTHs as $phongTH)
                             <option value="{{ $phongTH->TenPhong }}">{{ $phongTH->TenPhong }}</option>
                         @endforeach
                     </select>
                 </div> --}}
                   
                   <div class="d-flex justify-content-center mt-5">
                     <button type="submit" class="btn btn-primary">Lập thời khóa biểu</button>
                   </div>
                </form>
            </div>
        </div>

    <div class="row border border-3 rounded-3 mt-5 text-center">
        <div class="col my-5">
            <h5>TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ</h5>
            <h1>CANTHO UNIVERSITY SOFTWARE CENTER</h1>
            <p>Khu III, Đại học Cần Thơ – 01 Lý Tự Trọng, Tp. Cần Thơ – Tel: 0292.3731072 & Fax: 0292.3731071 – Email: cusc@ctu.edu.vn</p>
        </div>
        <div class="text-center">
            <h1>{{ $tkb->TenTKB ?? 'Tên TKB' }}</h1>
        </div>
        <div class="d-flex justify-content-between mb-5">
            <div class="col-3 align-items-start">
                <p>Mã lớp: {{ $tkb->MaLop ?? 'Mã Lớp' }}</p>
            </div>

            <div class="col-4 text-start">
                <p class="m-0">Bắt đầu học từ ngày:</p>
                <p class="m-0">Học Lý thuyết tại phòng:</p>
                <p class="m-0">Học Thực hành tại phòng:</p>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <td>Ngày</td>
                    <td>Tuần</td>
                    <td>Giờ học</td>
                    <td>THỨ HAI</td>
                    <td>THỨ BA</td>
                    <td>THỨ TƯ</td>
                    <td>THỨ NĂM</td>
                    <td>THỨ SÁU</td>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= $tkb->TuanHoc; $i++)
                <tr>
                    <th rowspan="2">Hàng {{ $i }}</th>
                    <th rowspan="2">{{ $i }}</th>
                    <th>7:00-9:00</th>
                    <th>Hàng {{ $i }}</th>
                    <th>Hàng {{ $i }}</th>
                    <th>Hàng {{ $i }}</th>
                    <th>Hàng {{ $i }}</th>
                    <th>Hàng {{ $i }}</th>
                </tr>
                <tr>
                    <th>13:00-15:00</th>
                    <th>Hàng {{ $i }}</th>
                    <th>Hàng {{ $i }}</th>
                    <th>Hàng {{ $i }}</th>
                    <th>Hàng {{ $i }}</th>
                    <th>Hàng {{ $i }}</th>
                </tr>
            @endfor
            </tbody>
        </table>
    </div>
    {{-- @endif --}}
    </div>

 @endsection
