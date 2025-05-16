@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Quản Lý Công Việc Phụ Trách</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Danh Sách Công Việc Phụ Trách</h4>
                            <div class="card-header-action">
                                <a href="{{ route('phutrach.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Thêm Công Việc Mới
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Công Việc Phụ Trách</th>
                                            <th>Miêu Tả Chi Tiết</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($phuTrachs as $phuTrach)
                                            <tr>
                                                <td>{{ $phuTrach->CongViecPhuTrach }}</td>
                                                <td>{{ $phuTrach->MieuTaChiTiet }}</td>
                                                <td>
                                                    <a href="{{ route('phutrach.edit', $phuTrach->CongViecPhuTrach) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('phutrach.destroy', $phuTrach->CongViecPhuTrach) }}" method="POST"
                                                        style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm delete-phutrach">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach --}}
                                        @foreach ($phuTrachs as $phuTrach)
                                            @if ($phuTrach && $phuTrach->CongViecPhuTrach && $phuTrach->MieuTaChiTiet)
                                                <tr>
                                                    <td>{{ $phuTrach->CongViecPhuTrach }}</td>
                                                    <td>{{ $phuTrach->MieuTaChiTiet }}</td>
                                                    <td>
                                                        <a href="{{ route('phutrach.edit', $phuTrach->CongViecPhuTrach) }}"
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form
                                                            action="{{ route('phutrach.destroy', $phuTrach->CongViecPhuTrach) }}"
                                                            method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm delete-phutrach">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
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
            $('.delete-phutrach').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: 'Bạn có chắc chắn muốn xóa công việc phụ trách này?',
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
