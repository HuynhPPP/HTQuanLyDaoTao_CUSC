@extends('layouts.new_app.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Quản Lý Học Vị</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="card">
                            <div class="card-header">
                                <h4>Danh Sách Học Vị</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('hocvi.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Thêm Học Vị Mới
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Mã Học Vị</th>
                                                <th>Tên Học Vị</th>
                                                <th>Ngành Học</th>
                                                <th>Chuyên Ngành</th>
                                                <th>Cơ Sở Đào Tạo</th>
                                                <th>Năm Cấp</th>
                                                <th>Hành Động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($hocVis as $hocVi)
                                                <tr>
                                                    <td>{{ $hocVi->MaHV }}</td>
                                                    <td>{{ $hocVi->TenHocVi }}</td>
                                                    <td>{{ $hocVi->NganhHoc }}</td>
                                                    <td>{{ $hocVi->ChuyenNganh }}</td>
                                                    <td>{{ $hocVi->CoSoDaoTao }}</td>
                                                    <td>{{ $hocVi->NamCap ? date('Y', strtotime($hocVi->NamCap)) : '' }}
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('hocvi.edit', $hocVi->MaHV) }}"
                                                                class="btn btn-warning btn-sm">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('hocvi.destroy', $hocVi->MaHV) }}"
                                                                method="POST" style="display:inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
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
                </div>
            </div>
        </section>
    </div>
@endsection
