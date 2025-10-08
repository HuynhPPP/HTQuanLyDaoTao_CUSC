@extends('layouts.new_app.master')

@section('page-title', 'Chi tiết Sinh viên có Nguy cơ')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>📋 Chi tiết Sinh viên có Nguy cơ</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('canh-bao.index') }}">Danh sách nguy cơ</a>
                </div>
                <div class="breadcrumb-item active">Chi tiết</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-user-graduate"></i> Thông tin sinh viên</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="font-weight-bold">Mã sinh viên:</td>
                                            <td>{{ $canhBao['MaSV'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Họ tên:</td>
                                            <td>{{ $canhBao['sinhVien']->HoTen ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Lớp học:</td>
                                            <td>{{ $canhBao['lopHoc']->TenLop ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Môn học:</td>
                                            <td>
                                                @if ($canhBao['LoaiCanhBao'] == 'tut_hang')
                                                    <span class="text-muted">Tổng thể (tất cả môn)</span>
                                                @else
                                                    {{ $canhBao['monHoc']->TenMH ?? 'N/A' }}
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="font-weight-bold">Loại nguy cơ:</td>
                                            <td>
                                                @switch($canhBao['LoaiCanhBao'])
                                                    @case('diem_thap')
                                                        <span class="badge badge-danger">Điểm thấp</span>
                                                    @break

                                                    @case('tut_hang')
                                                        <span class="badge badge-warning">Tụt hạng</span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-secondary">{{ $canhBao['LoaiCanhBao'] }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Mức độ:</td>
                                            <td>
                                                @if ($canhBao['MucDo'] == 'cao')
                                                    <span class="badge badge-danger">Cao</span>
                                                @elseif($canhBao['MucDo'] == 'trung_binh')
                                                    <span class="badge badge-warning">Trung bình</span>
                                                @else
                                                    <span class="badge badge-info">Thấp</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Ngày phát hiện:</td>
                                            <td>{{ \Carbon\Carbon::parse($canhBao['NgayTao'])->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5>Chi tiết nguy cơ:</h5>
                                    <div
                                        class=" alert-{{ $canhBao['MucDo'] == 'cao' ? 'danger' : ($canhBao['MucDo'] == 'trung_binh' ? 'warning' : 'info') }}">
                                        {{ $canhBao['NoiDung'] }}
                                    </div>
                                </div>
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
        // Kiểm tra nếu có thông báo lỗi từ server
        @if (session('error'))
            iziToast.error({
                title: 'Lỗi!',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        @endif

        @if (session('success'))
            iziToast.success({
                title: 'Thành công!',
                message: '{{ session('success') }}',
                position: 'topRight'
            });
        @endif
    </script>
@endsection
