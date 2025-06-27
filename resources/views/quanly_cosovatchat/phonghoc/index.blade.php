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
                            <!-- Filter Form -->
                            <form action="{{ route('phonghoc.index') }}" method="GET" class="mb-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ngày</label>
                                            <input type="date" name="ngay" class="form-control"
                                                value="{{ request('ngay', $selectedDate->format('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Khung giờ</label>
                                            <select name="khung_gio" class="form-control">
                                                <option value="">Tất cả</option>
                                                @foreach ($khunggios as $khunggio)
                                                    <option value="{{ $khunggio->TenKhungGio }}"
                                                        {{ request('khung_gio') == $khunggio->TenKhungGio ? 'selected' : '' }}>
                                                        {{ $khunggio->TenKhungGio }} ({{ $khunggio->ThoiGian }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Trạng thái</label>
                                            <select name="trang_thai" class="form-control">
                                                <option value="">Tất cả</option>
                                                <option value="Trống"
                                                    {{ request('trang_thai') == 'Trống' ? 'selected' : '' }}>Trống</option>
                                                <option value="Đang sử dụng"
                                                    {{ request('trang_thai') == 'Đang sử dụng' ? 'selected' : '' }}>Đang sử
                                                    dụng</option>
                                                <option value="Bảo trì"
                                                    {{ request('trang_thai') == 'Bảo trì' ? 'selected' : '' }}>Bảo trì
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Lọc</button>
                                        <a href="{{ route('phonghoc.index') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Tên phòng</th>
                                            <th>Loại phòng</th>
                                            <th>Sức chứa</th>
                                            <th>Trạng thái tại {{ $selectedKhungGioTen ?? 'Hiện tại' }}</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($phonghocs as $phong)
                                            <tr>
                                                <td>{{ $phong->TenPhong }}</td>
                                                <td>{{ $phong->LoaiPhong }}</td>
                                                @if ($phong->SucChua)
                                                    <td>{{ $phong->SucChua }}</td>
                                                @else
                                                    <td>N/A</td>
                                                @endif
                                                @php
                                                    $status = $phong->trang_thai_dong;
                                                @endphp
                                                <td>
                                                    @if ($status === 'Đang sử dụng')
                                                        <span class="badge badge-danger">Đang sử dụng</span>
                                                        @if ($phong->ten_lop_dang_su_dung)
                                                            <br>Lớp: <b>{{ $phong->ten_lop_dang_su_dung }}</b>
                                                        @endif
                                                        @if ($phong->ten_mon_dang_su_dung)
                                                            <br>Môn: <b>{{ $phong->ten_mon_dang_su_dung }}</b>
                                                        @endif
                                                    @elseif ($status === 'Bảo trì')
                                                        <span class="badge badge-warning">Bảo trì</span>
                                                    @else
                                                        <span class="badge badge-success">Trống</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('phonghoc.edit', $phong->TenPhong) }}"
                                                        class="btn btn-warning btn-sm" title="Sửa"><i
                                                            class="fas fa-edit"></i></a>
                                                    <form action="{{ route('phonghoc.destroy', $phong->TenPhong) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm delete-phonghoc"
                                                            title="Xóa"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
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
