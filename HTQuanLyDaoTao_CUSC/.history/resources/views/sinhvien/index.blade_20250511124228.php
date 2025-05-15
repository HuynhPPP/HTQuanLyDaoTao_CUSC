@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        {{-- <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Quản lý sinh viên</h5>
                        <a href="{{ route('student.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm sinh viên mới
                        </a>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã SV</th>
                                        <th>Họ tên</th>
                                        <th>Ngày sinh</th>
                                        <th>Giới tính</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sinhViens as $sv)
                                        <tr>
                                            <td>{{ $sv->MaSV }}</td>
                                            <td>{{ $sv->HoTen }}</td>
                                            <td>{{ \Carbon\Carbon::parse($sv->NgaySinh)->format('d/m/Y') }}</td>
                                            <td>{{ $sv->GioiTinh == 1 ? 'Nam' : 'Nữ' }}</td>
                                            <td>{{ $sv->Email }}</td>
                                            <td>{{ $sv->Sdt }}</td>
                                            <td>{{ $sv->hoSo->TrangThai ?? 'Chưa có' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('student.show', $sv->MaSV) }}"
                                                        class="btn btn-info btn-sm" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="" class="btn btn-warning btn-sm" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="" method="POST" class="d-inline"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
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

                        <div class="d-flex justify-content-center mt-4">
                            {{ $sinhViens->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Basic DataTables</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <div id="table-1_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer"><div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_length" id="table-1_length"><label>Show <select name="table-1_length" aria-controls="table-1" class="form-control form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></div></div><div class="col-sm-12 col-md-6"><div id="table-1_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="table-1"></label></div></div></div><div class="row"><div class="col-sm-12"><table class="table table-striped dataTable no-footer" id="table-1" role="grid" aria-describedby="table-1_info">
                      <thead>                                 
                        <tr role="row"><th class="text-center sorting" tabindex="0" aria-controls="table-1" rowspan="1" colspan="1" aria-label="
                            #
                          : activate to sort column ascending" style="width: 61.7969px;">
                            #
                          </th><th class="sorting_desc" tabindex="0" aria-controls="table-1" rowspan="1" colspan="1" aria-label="Task Name: activate to sort column ascending" aria-sort="descending" style="width: 256.5px;">Task Name</th><th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Progress" style="width: 136.484px;">Progress</th><th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Members" style="width: 338.75px;">Members</th><th class="sorting" tabindex="0" aria-controls="table-1" rowspan="1" colspan="1" aria-label="Due Date: activate to sort column ascending" style="width: 162.797px;">Due Date</th><th class="sorting" tabindex="0" aria-controls="table-1" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 192.75px;">Status</th><th class="sorting" tabindex="0" aria-controls="table-1" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending" style="width: 137.922px;">Action</th></tr>
                      </thead>
                      <tbody>                                 
                        
                        
                        
                        
                      <tr role="row" class="odd">
                          <td class="">
                            2
                          </td>
                          <td class="sorting_1">Redesign homepage</td>
                          <td class="align-middle">
                            <div class="progress" data-height="4" data-toggle="tooltip" title="" data-original-title="0%" style="height: 4px;">
                              <div class="progress-bar" data-width="0" style="width: 0px;"></div>
                            </div>
                          </td>
                          <td>
                            <img alt="image" src="assets/img/avatar/avatar-1.png" class="rounded-circle" width="35" data-toggle="tooltip" title="" data-original-title="Nur Alpiana">
                            <img alt="image" src="assets/img/avatar/avatar-3.png" class="rounded-circle" width="35" data-toggle="tooltip" title="" data-original-title="Hariono Yusup">
                            <img alt="image" src="assets/img/avatar/avatar-4.png" class="rounded-circle" width="35" data-toggle="tooltip" title="" data-original-title="Bagus Dwi Cahya">
                          </td>
                          <td>2018-04-10</td>
                          <td><div class="badge badge-info">Todo</div></td>
                          <td><a href="#" class="btn btn-secondary">Detail</a></td>
                        </tr><tr role="row" class="even">
                          <td class="">
                            4
                          </td>
                          <td class="sorting_1">Input data</td>
                          <td class="align-middle">
                            <div class="progress" data-height="4" data-toggle="tooltip" title="" data-original-title="100%" style="height: 4px;">
                              <div class="progress-bar bg-success" data-width="100%" style="width: 100%;"></div>
                            </div>
                          </td>
                          <td>
                            <img alt="image" src="assets/img/avatar/avatar-2.png" class="rounded-circle" width="35" data-toggle="tooltip" title="" data-original-title="Rizal Fakhri">
                            <img alt="image" src="assets/img/avatar/avatar-5.png" class="rounded-circle" width="35" data-toggle="tooltip" title="" data-original-title="Isnap Kiswandi">
                            <img alt="image" src="assets/img/avatar/avatar-4.png" class="rounded-circle" width="35" data-toggle="tooltip" title="" data-original-title="Yudi Nawawi">
                            <img alt="image" src="assets/img/avatar/avatar-1.png" class="rounded-circle" width="35" data-toggle="tooltip" title="" data-original-title="Khaerul Anwar">
                          </td>
                          <td>2018-01-16</td>
                          <td><div class="badge badge-success">Completed</div></td>
                          <td><a href="#" class="btn btn-secondary">Detail</a></td>
                        </tr><tr role="row" class="odd">
                          <td class="">
                            1
                          </td>
                          <td class="sorting_1">Create a mobile app</td>
                          <td class="align-middle">
                            <div class="progress" data-height="4" data-toggle="tooltip" title="" data-original-title="100%" style="height: 4px;">
                              <div class="progress-bar bg-success" data-width="100%" style="width: 100%;"></div>
                            </div>
                          </td>
                          <td>
                            <img alt="image" src="assets/img/avatar/avatar-5.png" class="rounded-circle" width="35" data-toggle="tooltip" title="" data-original-title="Wildan Ahdian">
                          </td>
                          <td>2018-01-20</td>
                          <td><div class="badge badge-success">Completed</div></td>
                          <td><a href="#" class="btn btn-secondary">Detail</a></td>
                        </tr><tr role="row" class="even">
                          <td class="">
                            3
                          </td>
                          <td class="sorting_1">Backup database</td>
                          <td class="align-middle">
                            <div class="progress" data-height="4" data-toggle="tooltip" title="" data-original-title="70%" style="height: 4px;">
                              <div class="progress-bar bg-warning" data-width="70%" style="width: 70%;"></div>
                            </div>
                          </td>
                          <td>
                            <img alt="image" src="assets/img/avatar/avatar-1.png" class="rounded-circle" width="35" data-toggle="tooltip" title="" data-original-title="Rizal Fakhri">
                            <img alt="image" src="assets/img/avatar/avatar-2.png" class="rounded-circle" width="35" data-toggle="tooltip" title="" data-original-title="Hasan Basri">
                          </td>
                          <td>2018-01-29</td>
                          <td><div class="badge badge-warning">In Progress</div></td>
                          <td><a href="#" class="btn btn-secondary">Detail</a></td>
                        </tr></tbody>
                    </table></div></div><div class="row"><div class="col-sm-12 col-md-5"><div class="dataTables_info" id="table-1_info" role="status" aria-live="polite">Showing 1 to 4 of 4 entries</div></div><div class="col-sm-12 col-md-7"><div class="dataTables_paginate paging_simple_numbers" id="table-1_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="table-1_previous"><a href="#" aria-controls="table-1" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="table-1" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item next disabled" id="table-1_next"><a href="#" aria-controls="table-1" data-dt-idx="2" tabindex="0" class="page-link">Next</a></li></ul></div></div></div></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
@endsection
