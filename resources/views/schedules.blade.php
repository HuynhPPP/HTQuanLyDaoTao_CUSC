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
                     <input type="text" class="form-control @error('TenTKB') is-invalid @enderror" id="TenTKB" name="TenTKB">
                     @error('TenTKB')
                     <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
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
                     <input class="form-control @error('Lop') is-invalid @enderror" id="Lop" name="Lop" list="DanhSachLopHoc">
                     <datalist id="DanhSachLopHoc">
                         @foreach($lophocs as $lophoc)
                             <option value="{{ $lophoc->MaLop }}">{{ $lophoc->TenLop }}</option>
                         @endforeach
                     </datalist>
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
    </div>

 @endsection
