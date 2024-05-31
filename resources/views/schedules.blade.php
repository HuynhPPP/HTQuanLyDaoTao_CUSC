@extends('layouts.app')

@section('content')

 <div class="container mt-5">
        <h1>Danh sách Tập huấn</h1>
        <table class="table table-bordered">
            {{--<thead>
                <tr>
                    <th>Mã bằng </th>
                    <th>Tên bằng cấp</th>
                    <th>Chuyên môn</th>
                    <th>Thời gian cấp bằng</th>
                    <th>Đơn vị cấp</th>
                    <th>Số hiệu</th>
                    <th>Số vào sổ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bangcapcanbo as $BangCapCanBo)
                    <tr>
                        <td>{{ $BangCapCanBo->MaBang }}</td>
                        <td>{{ $BangCapCanBo->TenBang }}</td>
                        <td>{{ $BangCapCanBo->ChuyenMon }}</td>
                        <td>{{ $BangCapCanBo->ThoiGianCap }}</td>
                        <td class="text-wrap">{{ $BangCapCanBo->DonViCap }}</td>
                        <td>{{ $BangCapCanBo->SoHieu }}</td>
                        <td>{{ $BangCapCanBo->SoVaoSo }}</td>
                    </tr>
                @endforeach
            </tbody>
            --}}
            {{--
            <thead>
                <tr>
                    <th>Mã Tập Huấn</th>
                    <th>Tên Khóa Tập Huấn</th>
                    <th>Thời Gian Bắt Đầu</th>
                    <th>Thời Gian Kết Thúc</th>
                    <th>Địa Điểm</th>
                </tr>
            </thead>
            <tbody>
                @foreach($taphuans as $TapHuan)
                    <tr>
                        <td>{{ $TapHuan->MaTapHuan }}</td>
                        <td>{{ $TapHuan->TenKhoaTapHuan }}</td>
                        <td>{{ $TapHuan->ThoiGianBatDau }}</td>
                        <td>{{ $TapHuan->ThoiGianKetThuc }}</td>
                        <td>{{ $TapHuan->DiaDiem }}</td>
                    </tr>
                @endforeach

            </tbody>
            --}}
            {{--<thead>
                <tr>
                    <th>Tên chức vụ</th>
                    <th>Số chức vụ</th>
                    <th>Kinh nghiệm làm việc</th>
                    <th>Thời gian bắt đầu công tác</th>
                    <th>Thời Gian Kết Thúc công tác</th>
                    <th>Hiện tại</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chucvu as $ChucVu)
                    <tr>
                        <td>{{ $ChucVu->TenChucVu }}</td>
                        <td>{{ $ChucVu->SoChucVu }}</td>
                        <td>{{ $ChucVu->KinhNghiemLamViec }}</td>
                        <td>{{ $ChucVu->ThoiGianBDCongTac }}</td>
                        <td>{{ $ChucVu->ThoiGianKTCongTac }}</td>
                        <td>{{ $ChucVu->DangCongTac }}</td>
                    </tr>
                @endforeach

            </tbody>
            --}}
            {{--
            <thead>
                <tr>
                    <th>Tên học vị</th>
                    <th>Số lượng học vị</th>
                    <th>Thời điểm nhận chứng nhận</th>
                    <th>Tên cơ quan cấp</th>
                    <th>Địa Điểm</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hocvi as $HocVi)
                    <tr>
                        <td>{{ $HocVi->TenHocVi }}</td>
                        <td>{{ $HocVi->SoLuongHocVi }}</td>
                        <td>{{ $HocVi->ThoiDiemNhanChungNhan }}</td>
                        <td>{{ $HocVi->TenCoQuanCap }}</td>
                        <td>{{ $HocVi->DiaDiem }}</td>
                    </tr>
                @endforeach

            </tbody>
             --}}

            <thead>
                <tr>
                    <th>Lĩnh vực phụ trách</th>
                    <th>Miêu tả chi tiết</th>
                    <th>Số công việc phụ trách</th>

                </tr>
            </thead>
            <tbody>
                @foreach($phutrach as $PhuTrach)
                    <tr>
                        <td>{{ $PhuTrach->LinhVucPhuTrach }}</td>
                        <td>{{ $PhuTrach->MieuTaChiTiet }}</td>
                        <td>{{ $PhuTrach->SoLuongCVPhuTrach }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

 @endsection
