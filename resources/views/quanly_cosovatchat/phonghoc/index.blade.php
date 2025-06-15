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
                                                    $status = '🟢 Trống';
                                                    $maLop = null;
                                                    $tenMH = null;

                                                    if ($phong->TrangThai == 'Bảo trì') {
                                                        $status = '🟡 Bảo trì';
                                                    } else {
                                                        $found = false;
                                                        foreach ($phong->danhsachphong as $dsphong) {
                                                            // Check if the current danhsachphong record matches the selected date and time slot
                                                            if (
                                                                Carbon::parse($dsphong->NgaySuDung)->format('Y-m-d') ==
                                                                    $selectedDate->format('Y-m-d') &&
                                                                ($selectedKhungGioMaCa === null ||
                                                                    $dsphong->Ca == $selectedKhungGioMaCa)
                                                            ) {
                                                                $status = '🔴 Đang sử dụng';
                                                                $maLop = $dsphong->MaLop;

                                                                // Access eager-loaded data
                                                                if (
                                                                    $dsphong->lopHoc &&
                                                                    $dsphong->lopHoc->tkb &&
                                                                    $dsphong->lopHoc->tkb->hocki &&
                                                                    $dsphong->lopHoc->tkb->hocki->danhsachmonhoc->isNotEmpty()
                                                                ) {
                                                                    // Assuming there's only one danhsachmonhoc per hocki for simplicity in this context
                                                                    $danhsachmonhoc = $dsphong->lopHoc->tkb->hocki->danhsachmonhoc->first();
                                                                    if ($danhsachmonhoc && $danhsachmonhoc->monhoc) {
                                                                        $tenMH = $danhsachmonhoc->monhoc->TenMH;
                                                                    }
                                                                }

                                                                $found = true;
                                                                break; // Found matching record, no need to check further danhsachphong records for this room
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <td>
                                                    {!! $status !!}
                                                    @if ($status === '🔴 Đang sử dụng')
                                                        <br />
                                                        @if ($tenMH)
                                                            Môn: {{ $tenMH }}<br />
                                                        @endif
                                                        @if ($maLop)
                                                            Lớp: {{ $maLop }}
                                                        @endif
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

                swal({
                    title: 'Bạn có chắc chắn muốn xóa phòng học này?',
                    text: 'Nếu phòng học đang được gán cho lớp, bạn cần xóa các gán phòng trước khi xóa phòng học này. Dữ liệu đã xóa sẽ không thể khôi phục!',
                    icon: 'warning',
                    buttons: ['Hủy', 'Xóa'],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    } else {
                        swal('Thao tác đã bị hủy.');
                    }
                });
            });
        });
    </script>
@endsection
