@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Phân công giảng dạy môn học {{ $monhoc->TenMH }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('monhoc.index') }}">Danh sách môn học</a></div>
                <div class="breadcrumb-item">Phân công giảng dạy</div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Chi tiết phân công giảng dạy</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('monhoc.store-teacher', $monhoc->MaMH) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="MaGV">Giảng viên</label>
                                        <select name="MaGV" id="MaGV"
                                            class="form-control @error('MaGV') is-invalid @enderror select2" multiple=""">
                                            <option value="">-- Chọn giảng viên --</option>
                                            @foreach ($giaoviens as $gv)
                                                @if (!in_array($gv->MaGV, $existingTeachers))
                                                    <option value="{{ $gv->MaGV }}">
                                                        {{ $gv->MaGV }} - {{ $gv->HoTenGV }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('MaGV')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Lớp Học</label>
                                        <select name="MaLop" class="form-control @error('MaLop') is-invalid @enderror select2">
                                            <option value="">Chọn lớp học</option>
                                            @foreach ($lops as $lop)
                                                <option value="{{ $lop->MaLop }}">
                                                    {{ $lop->MaLop }} - {{ $lop->TenLop }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('MaLop')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="NgayBatDau">Ngày bắt đầu giảng dạy</label>
                                        <input type="date" class="form-control @error('NgayBatDau') is-invalid @enderror"
                                            id="NgayBatDau" name="NgayBatDau" value="{{ old('NgayBatDau') }}">
                                        @error('NgayBatDau')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="NgayKetThuc">Ngày kết thúc giảng dạy</label>
                                        <input type="date"
                                            class="form-control @error('NgayKetThuc') is-invalid @enderror" id="NgayKetThuc"
                                            name="NgayKetThuc" value="{{ old('NgayKetThuc') }}">
                                        @error('NgayKetThuc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="GhiChu">Ghi chú</label>
                                <textarea class="form-control" id="GhiChu" name="GhiChu" rows="3"
                                    placeholder="Nhập thông tin bổ sung về phân công giảng dạy">{{ old('GhiChu') }}</textarea>
                            </div>

                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle mr-2"></i>
                                Lưu ý: Một giảng viên có thể được phân công giảng dạy nhiều lớp cho cùng một môn học
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('monhoc.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Lưu phân công giảng dạy
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($monhoc->giangViens->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Danh sách giảng viên đang giảng dạy môn học {{ $monhoc->TenMH }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Giảng viên</th>
                                            <th>Lớp học</th>
                                            <th>Ngày bắt đầu</th>
                                            <th>Ngày kết thúc</th>
                                            <th>Ghi chú</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($monhoc->giangViens as $giangVien)
                                            <tr>
                                                <td>{{ $giangVien->HoTenGV }}</td>
                                                <td>{{ $giangVien->MaLop ?? 'Chưa phân công' }}</td>
                                                <td>{{ $giangVien->NgayBatDau ?? 'Chưa xác định' }}</td>
                                                <td>{{ $giangVien->NgayKetThuc ?? 'Chưa xác định' }}</td>
                                                <td>{{ $giangVien->GhiChu ?? 'Không có' }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('monhoc.edit-teacher', ['MaMH' => $monhoc->MaMH, 'maGV' => $giangVien->MaGV]) }}"
                                                            class="btn btn-warning" data-toggle="tooltip" title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form
                                                            action="{{ route('monhoc.remove-teacher', ['MaMH' => $monhoc->MaMH, 'maGV' => $giangVien->MaGV]) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger delete-monhoc"
                                                                data-toggle="tooltip" title="Xóa phân công">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('custom-js')
    <script>
        $(document).ready(function() {
            $('.delete-monhoc').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: 'Bạn có chắc chắn muốn phân công giảng dạy môn học này?',
                    text: 'Dữ liệu đã xóa sẽ không thể khôi phục!',
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
