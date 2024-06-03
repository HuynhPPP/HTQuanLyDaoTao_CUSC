@extends('layouts.app')

@section('content')

 <div class="container my-5">
        <div class="row">
            <h1 class="text-center my-5">Lập thời khóa biểu</h1>
            <div class="d-flex justify-content-center">
            <form class="w-50" method="POST" action="{{ route('schedules.submit') }}">
                    @csrf
                 <div class="mb-3">
                     <label for="TenTKB" class="form-label">Tên thời khóa biểu</label>
                     <input type="text" class="form-control" id="TenTKB">
                 </div>
     
                 <div class="mb-3">
                     <label for="KhoaDaoTao" class="form-label">Chọn khoá đào tạo</label>
                     <select id="KhoaDaoTao" class="form-select" aria-label="Default select example">
                         <option selected>----- Khóa Đào Tạo -----</option>
                     {{-- @foreach($khoaDaoTaos as $khoaDaoTao)
                         <option value="{{ $khoaDaoTao->id }}">{{ $khoaDaoTao->name }}</option>
                     @endforeach --}}
                     </select>
                 </div>
     
                 <div class="mb-3">
                     <label for="ChuongTrinhTruyenKhai" class="form-label">Chọn chương trình triển khai </label>
                     <select id="ChuongTrinhTruyenKhai" class="form-select" aria-label="Default select example">
                     {{-- @foreach($chuongTrinhs as $chuongTrinh)
                         <option value="{{ $chuongTrinh->id }}">{{ $chuongTrinh->name }}</option>
                     @endforeach --}}
                     </select>
                 </div>
     
                 <div class="mb-3">
                     <label for="Lop" class="form-label">Chọn lớp </label>
                     <select id="Lop" class="form-select" aria-label="Default select example">
                         {{-- @foreach($lops as $lop)
                             <option value="{{ $lop->id }}">{{ $lop->name }}</option>
                         @endforeach --}}
                     </select>
                 </div>
     
                 <div class="mb-3">
                     <label for="NgayBatDau" class="form-label">Chọn ngày bắt đầu học </label>
                     <input type="date" class="form-control" id="NgayBatDau">
                 </div>
     
                 <div class="mb-3">
                     <label for="PhongLT" class="form-label">Chọn phòng học lý thuyết </label>
                     <select id="PhongLT" class="form-select" aria-label="Default select example">
                         {{-- @foreach($phongLTs as $phongLT)
                             <option value="{{ $phongLT->id }}">{{ $phongLT->name }}</option>
                         @endforeach --}}
                     </select>
                 </div>
     
                 <div class="mb-3">
                     <label for="PhongTH" class="form-label">Chọn phòng học thực hành </label>
                     <select id="PhongTH" class="form-select" aria-label="Default select example">
                         {{-- @foreach($phongTHs as $phongTH)
                             <option value="{{ $phongTH->id }}">{{ $phongTH->name }}</option>
                         @endforeach --}}
                     </select>
                 </div>
                   
                   <div class="d-flex justify-content-center mt-5">
                     <button type="submit" class="btn btn-primary">Lập thời khóa biểu</button>
                   </div>
                </form>
            </div>
        </div>

{{-- @if(isset($TenTKB)) --}}
    <div class="row border border-3 rounded-3 mt-5 text-center">
        <div class="col my-5">
            <h5>TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ</h5>
            <h1>CANTHO UNIVERSITY SOFTWARE CENTER</h1>
            <p>Khu III, Đại học Cần Thơ – 01 Lý Tự Trọng, Tp. Cần Thơ – Tel: 0292.3731072 & Fax: 0292.3731071 – Email: cusc@ctu.edu.vn</p>
        </div>
        <div class="text-center">
            <h1>{{ $TenTKB ?? 'Tên thời khóa biểu'}}</h1>
        </div>
        <div class="d-flex justify-content-between mb-5">
            <div class="col-3 align-items-start">
                <p>Mã lớp:</p>
            </div>

            <div class="col-4 text-start">
                <p class="m-0">Bắt đầu học từ ngày:</p>
                <p class="m-0">Học Lý thuyết tại phòng:</p>
                <p class="m-0">Học Thực hành tại phòng:</p>
            </div>
        </div>
        <table class="">
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
                <tr>
                    <!-- Nội dung bảng -->
                </tr>
            </tbody>
        </table>
    </div>
    {{-- @endif --}}
    </div>

 @endsection
