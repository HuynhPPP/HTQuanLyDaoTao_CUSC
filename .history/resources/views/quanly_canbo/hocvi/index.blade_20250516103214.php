@extends('layouts.new_app.app')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Quản lý Học Vị</h1>
        </div>

        <div class="section-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <a href="{{ route('hocvi.create') }}" class="btn btn-primary">Thêm Học Vị Mới</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Mã Học Vị</th>
                                    <th>Tên Học Vị</th>
                                    <th>Ngành Học</th>
                                    <th>Cơ Sở Đào Tạo</th>
                                    <th>Năm Cấp</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hocVis as $hocVi)
                                <tr>
                                    <td>{{ $hocVi->MaHV }}</td>
                                    <td>{{ $hocVi->TenHocVi }}</td>
                                    <td>{{ $hocVi->NganhHoc }}</td>
                                    <td>{{ $hocVi->CoSoDaoTao }}</td>
                                    <td>{{ $hocVi->NamCap ? date('Y', strtotime($hocVi->NamCap)) : '' }}</td>
                                    <td>
                                        <a href="{{ route('hocvi.edit', $hocVi->MaHV) }}" class="btn btn-warning">Sửa</a>
                                        <form action="{{ route('hocvi.destroy', $hocVi->MaHV) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $hocVis->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection