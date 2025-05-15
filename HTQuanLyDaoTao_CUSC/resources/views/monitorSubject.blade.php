@extends('layouts.app')

@section('content')

 <div class="container mt-5">
        <h1>Danh sách Tập huấn</h1>
        <table class="table table-bordered">
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
                @foreach($taphuans as $taphuan)
                    <tr>
                        <td>{{ $taphuan->MaTapHuan }}</td>
                        <td>{{ $taphuan->TenKhoaTapHuan }}</td>
                        <td>{{ $taphuan->ThoiGianBatDau }}</td>
                        <td>{{ $taphuan->ThoiGianKetThuc }}</td>
                        <td>{{ $taphuan->DiaDiem }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

 @endsection
