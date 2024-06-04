@extends('layouts.app')

@section('content')

 <div class="container my-5">
    <div class="row border border-3 rounded-3 mt-5 text-center">
        @if ($tkb->isEmpty())
        <p>Không tìm thấy thời khóa biểu với tên đã cung cấp.</p>
    @else
        @foreach ($tkb as $schedule)
        <div class="col my-5">
            <h5>TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ</h5>
            <h1>CANTHO UNIVERSITY SOFTWARE CENTER</h1>
            <p>Khu III, Đại học Cần Thơ – 01 Lý Tự Trọng, Tp. Cần Thơ – Tel: 0292.3731072 & Fax: 0292.3731071 – Email: cusc@ctu.edu.vn</p>
        </div>
        <div class="text-center">
            <h1>{{ $schedule->TenTKB }}</h1>
        </div>
        <div class="d-flex justify-content-between mb-5">
            <div class="col-3 align-items-start">
                <p>Mã lớp: {{ $schedule->MaLop }}</p>
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
                @for ($i = 1; $i <= $schedule->TuanHoc; $i++)
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
    <div class="text-center mt-3">
        <form id="deleteScheduleForm" action="{{ route('deleteSchedule', ['TenTKB' => $tkb->first()->TenTKB]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hủy bỏ</button>
            <button class="btn btn-primary">Xuất</button>
        </form>
    </div>
    @endforeach
@endif
</div>

<script>
    function confirmDelete() {
        if (confirm('Bạn có chắc chắn muốn xóa thời khóa biểu này?')) {
            document.getElementById('deleteScheduleForm').submit();
        }
    }
</script>

 @endsection
