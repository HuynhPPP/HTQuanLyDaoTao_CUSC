@extends('layouts.new_app.master')

@section('main-content')
    <!-- Main Content -->
    {{-- <section class="section">
        <div class="section-header">
            <h1>Quản lý sinh viên</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                #
                                            </th>
                                            <th>Task Name</th>
                                            <th>Progress</th>
                                            <th>Members</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                1
                                            </td>
                                            <td>Create a mobile app</td>
                                            <td class="align-middle">
                                                <div class="progress" data-height="4" data-toggle="tooltip" title="100%">
                                                    <div class="progress-bar bg-success" data-width="100%"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <img alt="image" src="assets/img/avatar/avatar-5.png"
                                                    class="rounded-circle" width="35" data-toggle="tooltip"
                                                    title="Wildan Ahdian">
                                            </td>
                                            <td>2018-01-20</td>
                                            <td>
                                                <div class="badge badge-success">Completed</div>
                                            </td>
                                            <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                2
                                            </td>
                                            <td>Redesign homepage</td>
                                            <td class="align-middle">
                                                <div class="progress" data-height="4" data-toggle="tooltip" title="0%">
                                                    <div class="progress-bar" data-width="0"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <img alt="image" src="assets/img/avatar/avatar-1.png"
                                                    class="rounded-circle" width="35" data-toggle="tooltip"
                                                    title="Nur Alpiana">
                                                <img alt="image" src="assets/img/avatar/avatar-3.png"
                                                    class="rounded-circle" width="35" data-toggle="tooltip"
                                                    title="Hariono Yusup">
                                                <img alt="image" src="assets/img/avatar/avatar-4.png"
                                                    class="rounded-circle" width="35" data-toggle="tooltip"
                                                    title="Bagus Dwi Cahya">
                                            </td>
                                            <td>2018-04-10</td>
                                            <td>
                                                <div class="badge badge-info">Todo</div>
                                            </td>
                                            <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                3
                                            </td>
                                            <td>Backup database</td>
                                            <td class="align-middle">
                                                <div class="progress" data-height="4" data-toggle="tooltip" title="70%">
                                                    <div class="progress-bar bg-warning" data-width="70%"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <img alt="image" src="assets/img/avatar/avatar-1.png"
                                                    class="rounded-circle" width="35" data-toggle="tooltip"
                                                    title="Rizal Fakhri">
                                                <img alt="image" src="assets/img/avatar/avatar-2.png"
                                                    class="rounded-circle" width="35" data-toggle="tooltip"
                                                    title="Hasan Basri">
                                            </td>
                                            <td>2018-01-29</td>
                                            <td>
                                                <div class="badge badge-warning">In Progress</div>
                                            </td>
                                            <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                4
                                            </td>
                                            <td>Input data</td>
                                            <td class="align-middle">
                                                <div class="progress" data-height="4" data-toggle="tooltip" title="100%">
                                                    <div class="progress-bar bg-success" data-width="100%"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <img alt="image" src="assets/img/avatar/avatar-2.png"
                                                    class="rounded-circle" width="35" data-toggle="tooltip"
                                                    title="Rizal Fakhri">
                                                <img alt="image" src="assets/img/avatar/avatar-5.png"
                                                    class="rounded-circle" width="35" data-toggle="tooltip"
                                                    title="Isnap Kiswandi">
                                                <img alt="image" src="assets/img/avatar/avatar-4.png"
                                                    class="rounded-circle" width="35" data-toggle="tooltip"
                                                    title="Yudi Nawawi">
                                                <img alt="image" src="assets/img/avatar/avatar-1.png"
                                                    class="rounded-circle" width="35" data-toggle="tooltip"
                                                    title="Khaerul Anwar">
                                            </td>
                                            <td>2018-01-16</td>
                                            <td>
                                                <div class="badge badge-success">Completed</div>
                                            </td>
                                            <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Quản lý sinh viên</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-sinhvien">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Mã SV</th>
                                            <th>Họ Tên</th>
                                            <th>Ngày Sinh</th>
                                            <th>Giới Tính</th>
                                            <th>Số CCCD</th>
                                            <th>Email</th>
                                            <th>SĐT</th>
                                            <th>Địa Chỉ</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sinhViens as $index => $sv)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $sv->MaSV }}</td>
                                                <td>{{ $sv->HoTen }}</td>
                                                <td>{{ \Carbon\Carbon::parse($sv->NgaySinh)->format('d/m/Y') }}</td>
                                                <td>{{ $sv->GioiTinh == 1 ? 'Nam' : 'Nữ' }}</td>
                                                <td>{{ $sv->SoCCCD }}</td>
                                                <td>{{ $sv->Email }}</td>
                                                <td>{{ $sv->Sdt }}</td>
                                                <td>{{ $sv->DiaChi }}</td>
                                                <td>
                                                    <a href="{{ route('student.show', $sv->MaSV) }}"
                                                        class="btn btn-primary btn-sm">Chi tiết</a>
                                                    <a href=""
                                                        class="btn btn-warning btn-sm">Sửa</a>
                                                    <form action="" method="POST"
                                                        style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{-- Thêm phân trang nếu cần --}}
                                {{ $sinhviens->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
