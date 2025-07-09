@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh sách sinh viên</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item">Danh sách sinh viên</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="dropdown">
                                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cogs mr-1"></i>Thao tác khác
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ route('dongbo.taikhoan.ldap') }}">
                                        <i class="fas fa-sync mr-1"></i>Tự tạo tài khoản CUSC
                                    </a>
                                </div>
                            </div>
                            <h4></h4>
                            <div class="card-header-action">
                                <div class="" role="group" aria-label="Thao tác">
                                    
                                    <a href="{{ route('ldap.account.list') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-list-alt mr-1"></i>Danh sách tài khoản sinh viên
                                    </a>
                                    <a href="{{ route('student.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i>Thêm sinh viên
                                    </a>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="table-1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Mã SV</th>
                                            <th class="text-nowrap">Họ Tên</th>
                                            <th class="text-nowrap">Ngày Sinh</th>
                                            <th class="text-nowrap">Giới Tính</th>
                                            <th class="text-nowrap">Số CCCD</th>
                                            <th class="text-nowrap">Email CUSC</th>
                                            <th class="text-nowrap">SĐT</th>
                                            <th class="text-nowrap">Địa Chỉ</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sinhViens as $index => $sv)
                                            <tr>
                                                <td class="text-nowrap">{{ $sv->MaSV }}</td>
                                                <td class="text-nowrap">{{ $sv->HoTen }}</td>
                                                <td class="text-nowrap">
                                                    {{ \Carbon\Carbon::parse($sv->NgaySinh)->format('d/m/Y') }}</td>
                                                <td class="text-nowrap">{{ $sv->GioiTinh == 1 ? 'Nam' : 'Nữ' }}</td>
                                                <td class="text-nowrap">{{ $sv->SoCCCD }}</td>
                                                <td class="text-nowrap">{{ $sv->EmailCUSC }}</td>
                                                <td class="text-nowrap">{{ $sv->Sdt }}</td>
                                                <td class="text-truncate" style="max-width: 200px;">{{ $sv->DiaChi }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('student.show', $sv->MaSV) }}"
                                                        class="btn btn-info btn-sm" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    {{-- <a href="{{ route('student.edit_all', $sv->MaSV) }}"
                                                        class="btn btn-warning btn-sm" data-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a> --}}
                                                    <form action="{{ route('student.destroy', $sv->MaSV) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm delete-student"
                                                            title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
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
    <!-- Modal Loading -->
    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="sr-only">Đang tải...</span>
                    </div>
                    <h5 class="mt-3">Đang thực hiện tự động tạo tài khoản CUSC</h5>
                    <p class="text-muted">Vui lòng chờ trong giây lát...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-student', function(e) {
                e.preventDefault(); // Ngăn submit mặc định
                const form = $(this).closest('form'); // Tìm form cha gần nhất

                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa sinh viên này?',
                    text: 'Dữ liệu đã xóa sẽ không thể khôi phục!',
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

            // Xử lý loading khi tự tạo tài khoản
            $('a[href="{{ route('dongbo.taikhoan.ldap') }}"]').click(function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                // Hiển thị modal loading
                $('#loadingModal').modal('show');

                // Chuyển hướng trực tiếp để xử lý đồng bộ
                window.location.href = url;
            });

            // Kiểm tra và hiển thị thông báo từ session
            @if (session('success'))
                swal({
                    title: 'Thành công',
                    text: '{{ session('success') }}',
                    icon: 'success'
                });
            @endif

            @if (session('error'))
                swal({
                    title: 'Lỗi',
                    text: '{{ session('error') }}',
                    icon: 'error'
                });
            @endif

            // Hiển thị chi tiết lỗi nếu có
            @if (session('error_details'))
                function showErrorDetails() {
                    const errorDetails = @json(session('error_details'));
                    let errorMessage = "Chi tiết các lỗi:\n\n";

                    errorDetails.forEach((error, index) => {
                        errorMessage += `${index + 1}. Mã SV: ${error.ma_sv} - ${error.ho_ten}\n`;
                        errorMessage += `   Lỗi: ${error.error_message}\n\n`;
                    });

                    swal({
                        title: 'Chi tiết lỗi đồng bộ',
                        text: errorMessage,
                        icon: 'warning',
                        buttons: {
                            confirm: {
                                text: 'Đóng',
                                visible: true
                            }
                        }
                    });
                }

                // Thêm nút xem chi tiết lỗi
                if (errorDetails.length > 0) {
                    swal({
                        title: 'Đồng bộ hoàn tất',
                        text: 'Có một số lỗi trong quá trình đồng bộ. Bạn có muốn xem chi tiết?',
                        icon: 'warning',
                        buttons: {
                            cancel: 'Hủy',
                            confirm: {
                                text: 'Xem chi tiết',
                                visible: true
                            }
                        }
                    }).then((value) => {
                        if (value) {
                            showErrorDetails();
                        }
                    });
                }
            @endif
        });
    </script>
@endsection
