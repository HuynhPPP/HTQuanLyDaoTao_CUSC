@extends('layouts.new_app.master')
@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Quản lý phòng học</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item">Quản lý phòng học</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4></h4>
                            <a href="{{ route('phonghoc.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm mới phòng học
                            </a>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('phonghoc.index') }}" class="mb-3">
                                <label for="ngay"><b>Chọn ngày:</b></label>
                                <input type="date" id="ngay" name="ngay"
                                    value="{{ $selectedDate->format('Y-m-d') }}" onchange="this.form.submit()"
                                    style="margin-left: 8px; margin-right: 16px;">
                                <span><b>Đang xem ngày:</b> {{ $selectedDate->format('d/m/Y') }}</span>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Khung giờ</th>
                                            @foreach ($phonghocs as $phong)
                                                <th>{{ $phong->TenPhong }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($khunggios as $khunggio)
                                            <tr>
                                                <td><b>{{ $khunggio->TenKhungGio }}</b><br><small>{{ $khunggio->ThoiGian }}</small>
                                                </td>
                                                @foreach ($phonghocs as $phong)
                                                    @php $cell = $matrix[$khunggio->TenKhungGio][$phong->TenPhong] ?? ['status'=>'Trống','MaLop'=>null]; @endphp
                                                    <td class="text-center">
                                                        @if ($cell['status'] === 'Đang sử dụng')
                                                            {{-- <span class="badge badge-danger">Đang sử dụng</span><br> --}}
                                                            <span class="badge badge-primary">Lớp:
                                                                {{ $cell['MaLop'] }}</span>
                                                        @else
                                                            <span class="badge badge-success">Trống</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom-js')
    <script>
        $(document).ready(function() {
            $('.delete-phonghoc').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa phòng học này?',
                    text: 'Nếu phòng học đang được gán cho lớp, bạn cần xóa các gán phòng trước khi xóa phòng học này. Dữ liệu đã xóa sẽ không thể khôi phục!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                    dangerMode: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {
                        Swal.fire('Thao tác đã bị hủy.');
                    }
                });
            });
        });
    </script>
@endsection
