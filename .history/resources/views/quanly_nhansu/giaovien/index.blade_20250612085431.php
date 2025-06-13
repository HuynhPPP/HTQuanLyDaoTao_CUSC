@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Danh sách giáo viên</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item">Danh sách giáo viên</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4></h4>
                            <div class="card-header-action">
                                <a href="{{ route('giaovien.ldap.account.list') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list-alt mr-1"></i>Danh sách tài khoản giáo viên
                                </a>
                                <a href="{{ route('giaovien.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Thêm giáo viên
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-cogs mr-1"></i>Thao tác khác
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('giaovien.dongbo.taikhoan.ldap') }}">
                                            <i class="fas fa-sync mr-1"></i>Tự tạo tài khoản CUSC
                                        </a>
                                        <a class="dropdown-item" href="{{ route('giaovien.ldap.kiem-tra-dong-bo') }}">
                                            <i class="fas fa-server mr-1"></i>Kiểm tra kết nối máy chủ
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Mã GV</th>
                                            <th>Họ và tên</th>
                                            <th>Email CUSC</th>
                                            <th>Vai trò giảng viên</th>
                                            <th>Chuyên ngành</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($giaoviens as $index => $gv)
                                            <tr>
                                                <td>{{ $gv->MaGV }}</td>
                                                <td>{{ $gv->HoTenGV }}</td>
                                                <td>{{ $gv->EmailCUSC ?? 'N/A' }}</td>
                                                <td>
                                                    <span
                                                        class="badge 
                                                        {{ $gv->LoaiGV == 'CoHuu' ? 'badge-primary' : 'badge-warning' }}">
                                                        {{ $gv->LoaiGV == 'CoHuu' ? 'Cơ hữu' : 'Mời giảng' }}
                                                    </span>
                                                </td>
                                                <td>{{ $gv->ChuyenNganh }}</td>
                                                <td>
                                                    <a href="{{ route('giaovien.show', $gv->MaGV) }}"
                                                        class="btn btn-info btn-sm" data-toggle="tooltip"
                                                        title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('giaovien.edit', $gv->MaGV) }}"
                                                        class="btn btn-warning btn-sm" data-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('giaovien.destroy', $gv->MaGV) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm delete-btn"
                                                            data-toggle="tooltip" title="Xóa">
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
    </div>
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
        // Xử lý xóa giáo viên
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);

            swal({
                title: 'Bạn có chắc chắn muốn xóa giáo viên này?',
                text: 'Dữ liệu đã xóa sẽ không thể khôi phục!',
                icon: 'warning',
                buttons: ['Hủy', 'Xóa'],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit(); // Xác nhận thì submit form
                } else {
                    swal('Thao tác đã bị hủy.');
                }
            });
        });
        // Xử lý loading khi tự tạo tài khoản
        $('a[href="{{ route('giaovien.dongbo.taikhoan.ldap') }}"]').click(function(e) {
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
    </script>
    <script>
        document.getElementById('btnKiemTraLDAP').addEventListener('click', function() {
            Swal.fire({
                title: 'Đang kiểm tra kết nối...',
                didOpen: () => {
                    Swal.showLoading();
                    fetch('{{ route('giaovien.ldap.kiem-tra-dong-bo') }}')
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    html: `<p>Số sinh viên chưa có tài khoản: <strong>${data.sinh_viens_chua_co_tai_khoan}</strong></p>`
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: data.error || 'Không xác định'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Không thể kiểm tra kết nối. Vui lòng thử lại sau.'
                            });
                            console.error('Lỗi kiểm tra LDAP:', error);
                        });
                },
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });
        });
    </script>
@endsection
