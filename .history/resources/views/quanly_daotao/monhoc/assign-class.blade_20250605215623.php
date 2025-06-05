@extends('layouts.new_app.master')

@section('title', 'Phân Công Giảng Viên Cho Lớp')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Phân Công Giảng Viên Cho Lớp - {{ $monhoc->TenMH }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('monhoc.index') }}">Môn Học</a></div>
            <div class="breadcrumb-item">Phân Công Lớp</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Thêm Phân Công Mới</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('monhoc.store-class-assignment', $monhoc->MaMH) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Giảng Viên</label>
                                <select name="MaGV" class="form-control" required>
                                    <option value="">Chọn Giảng Viên</option>
                                    @foreach($giaoviens as $gv)
                                        <option value="{{ $gv->MaGV }}">{{ $gv->HoTenGV }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lớp Học</label>
                                <select name="MaLop" class="form-control" required>
                                    <option value="">Chọn Lớp Học</option>
                                    @foreach($lops as $lop)
                                        <option value="{{ $lop->MaLop }}">{{ $lop->TenLop }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ngày Bắt Đầu</label>
                                <input type="date" name="NgayBatDau" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ngày Kết Thúc</label>
                                <input type="date" name="NgayKetThuc" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Ghi Chú</label>
                                <textarea name="GhiChu" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Lưu Phân Công</button>
                        <a href="{{ route('monhoc.index') }}" class="btn btn-secondary ml-2">Hủy</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Các Phân Công Hiện Tại</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Giảng Viên</th>
                                <th>Lớp Học</th>
                                <th>Ngày Bắt Đầu</th>
                                <th>Ngày Kết Thúc</th>
                                <th>Ghi Chú</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currentAssignments as $assignment)
                                <tr>
                                    <td>{{ $assignment->giaoVien->HoTenGV }}</td>
                                    <td>{{ $assignment->lop->TenLop }}</td>
                                    <td>{{ $assignment->NgayBatDau ?? 'Chưa xác định' }}</td>
                                    <td>{{ $assignment->NgayKetThuc ?? 'Chưa xác định' }}</td>
                                    <td>{{ $assignment->GhiChu ?? 'Không có' }}</td>
                                    <td>
                                        <a href="{{ route('monhoc.edit-class-assignment', ['MaMH' => $monhoc->MaMH, 'MaGV' => $assignment->MaGV, 'MaLop' => $assignment->MaLop]) }}" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm remove-assignment-btn" 
                                                data-maMH="{{ $monhoc->MaMH }}"
                                                data-maGV="{{ $assignment->MaGV }}"
                                                data-maLop="{{ $assignment->MaLop }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom-js')
<script>
    $(document).ready(function() {
        $('.remove-assignment-btn').click(function(e) {
            e.preventDefault();
            const maMH = $(this).data('mamh');
            const maGV = $(this).data('magv');
            const maLop = $(this).data('malop');

            swal({
                title: 'Bạn có chắc chắn muốn xóa phân công này?',
                text: 'Dữ liệu đã xóa sẽ không thể khôi phục!',
                icon: 'warning',
                buttons: ['Hủy', 'Xóa'],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    // Gọi route xóa phân công
                    $.ajax({
                        url: `/mon-hoc/${maMH}/xoa-phan-cong-lop/${maGV}/${maLop}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            swal('Đã xóa phân công thành công', {
                                icon: 'success',
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            swal('Lỗi', 'Không thể xóa phân công', 'error');
                        }
                    });
                } else {
                    swal('Thao tác đã bị hủy.');
                }
            });
        });
    });
</script>
@endsection 