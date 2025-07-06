@extends('layouts.new_app.master')
@section('title', 'Phân công cán bộ coi thi')

@section('main-content')
    <div class="section">
        <div class="section-header d-flex justify-content-between">

            <h1>Phân công cán bộ môn học - {{ $lichThi->monHoc->TenMH }}
                ({{ $lichThi->NgayThi ? date('d-m-Y', strtotime($lichThi->NgayThi)) : '' }})</h1>
            <a href="{{ route('phancong.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Thêm phân công mới</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('phancong.store', $lichThi->MaLichThi) }}">
                                @csrf
                                <div class="form-group">
                                    <label for="MaCB">Chọn cán bộ</label>
                                    <select name="MaGV[]" class="form-control select2" multiple="">
                                        @foreach ($availableCanBos as $cb)
                                            <option value="{{ $cb->MaGV }}">
                                                {{ $cb->HoTenGV }} 
                                                ({{ $cb->type === 'Giảng viên' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('MaCB')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="VaiTro">Vai trò</label>
                                    <select name="VaiTro" class="form-control select2">
                                        <option value="Cán bộ coi thi">Cán bộ coi thi</option>
                                    </select>
                                    @error('VaiTro')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Phân công</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Danh sách đã phân công</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Họ tên</th>
                                            <th>Vai trò</th>
                                            <th>Ngày thi</th>
                                            <th>Thời gian</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($phanCongList as $index => $pc)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                        {{ $pc->giaoVien->HoTenGV }}
                                                </td>
                                                <td>{{ $pc->VaiTro }}</td>
                                                <td>{{ $pc->lichThi->NgayThi }}</td>
                                                <td>{{ $pc->lichThi->KhungGio }}</td>
                                                <td>
                                                    <form
                                                        action="{{ route('phancong.destroy', [$lichThi->MaLichThi, $pc->MaPhanCong]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm huy-canbo">Hủy</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Chưa có phân công</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-js')
    <script>
        $(document).ready(function() {
            $('.huy-canbo').click(function(e) {
                e.preventDefault(); // Ngăn submit mặc định
                const form = $(this).closest('form'); // Tìm form cha gần nhất

                Swal.fire({
                    title: 'Bạn có chắc chắn muốn huỷ phân công cán bộ này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                    dangerMode: true
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit(); // Xác nhận thì submit form
                    } else {
                        swal('Thao tác đã bị hủy.');
                    }
                });
            });
        });
    </script>
@endsection
