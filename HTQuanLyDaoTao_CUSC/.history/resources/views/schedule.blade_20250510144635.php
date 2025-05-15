@extends('layouts.new_app.master')

@section('main-content')
<section class="section" style="background: #f8fafc; min-height: 100vh;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow rounded-4 border-0 mb-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo_CTU.png') }}" alt="logo" width="80" class="mb-3">
                            <h5 class="fw-bold text-primary mb-1">TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ</h5>
                            <h1 class="fw-bold mb-2">CANTHO UNIVERSITY SOFTWARE CENTER</h1>
                            <p class="text-secondary mb-0">Khu III, Đại học Cần Thơ – 01 Lý Tự Trọng, Tp. Cần Thơ – Tel: 0292.3731072 & Fax: 0292.3731071 – Email: cusc@ctu.edu.vn</p>
                        </div>
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-success">{{ $schedule->TenTKB }}</h2>
                        </div>
                        <div class="row justify-content-between mb-4">
                            <div class="col-md-4 text-start">
                                <p class="mb-1"><strong>Mã lớp:</strong> {{ $schedule->MaLop }}</p>
                                <div class="small text-muted">
                                    <span>Ver: {{ $chuongtrinh->PhienBan }}</span> |
                                    <span>{{ \Carbon\Carbon::parse($chuongtrinh->NgayTrienKhaiPB)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 text-start">
                                <p class="mb-1">Bắt đầu học từ ngày: <strong>{{  \Carbon\Carbon::parse($schedule->NgayHoc)->format('d/m/Y')}}</strong></p>
                                <p class="mb-1">Học Lý thuyết tại phòng: <strong>{{ $phonglt->TenPhong ?? ' Chưa có ' }}</strong></p>
                                <p class="mb-1">Học Thực hành tại phòng: <strong>{{ $phongth->TenPhong ?? ' Chưa có ' }}</strong></p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover rounded shadow-sm bg-white">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Tuần</th>
                                        <th>Giờ học</th>
                                        <th>THỨ HAI</th>
                                        <th>THỨ BA</th>
                                        <th>THỨ TƯ</th>
                                        <th>THỨ NĂM</th>
                                        <th>THỨ SÁU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php // ... giữ nguyên code tạo $scheduleMatrix ... @endphp
                                    @foreach ($scheduleMatrix as $week => $days)
                                        @php $weekDates = collect($days)->pluck('date')->toArray(); @endphp
                                        <tr>
                                            <th>{{ implode(' - ', [$weekDates[0], end($weekDates)]) }}</th>
                                            <th class="text-wrap align-middle">{{ $week }}</th>
                                            <th class="text-wrap align-middle" style="width: 12rem;">{{ $dsmh->TenKhungGio ?? ''}}</th>
                                            @foreach ($days as $dayData)
                                                <td class="text-wrap align-middle" style="width: 12rem; {{ $dayData['style'] }}">
                                                    {{ $dayData['subject'] }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3 gap-2">
                            <form id="deleteScheduleForm" action="{{ route('deleteSchedule', ['TenTKB' => $schedule->TenTKB]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </button>
                            </form>
                            <a href="{{ route('exportExcel', $schedule->TenTKB) }}" class="btn btn-primary">
                                <i class="fas fa-file-excel"></i> Xuất
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Các nút tròn chức năng ở góc dưới trái giữ nguyên -->
    <div class="position-fixed bottom-0 start-0 m-3">
        <div class="mb-2">
            <button type="button" class="btn btn-primary btn-lg rounded-circle" id="addTimeSlotButton" data-bs-toggle="modal" data-bs-target="#timeSlotModal" title="Thêm khung giờ học">
                <i class="fas fa-clock"></i>
            </button>
        </div>
        <div class="mb-2">
            <button type="button" class="btn btn-primary btn-lg rounded-circle" id="addAbsenceButton" data-bs-toggle="modal" data-bs-target="#absenceModal" title="Thêm ngày nghỉ">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        <div class="mb-2">
            <button type="button" class="btn btn-primary btn-lg rounded-circle" id="addSelfStudy" data-bs-toggle="modal" data-bs-target="#SelfStudyModal" title="Thêm ngày tự học">
                <i class="fa-brands fa-leanpub"></i>
            </button>
        </div>
        <div class="mb-2">
            <button type="button" class="btn btn-primary btn-lg rounded-circle" id="editTKB" data-bs-toggle="modal" data-bs-target="#EditTKBModal" title="Chỉnh sửa thời gian khai giảng">
                <i class="fa-regular fa-calendar"></i>
            </button>
        </div>
    </div>
    <!-- Các modal và script giữ nguyên -->
    @includeIf('partials.schedule_modals')
    <script>
        function confirmDelete() {
            if (confirm('Bạn có chắc chắn muốn xóa thời khóa biểu này?')) {
                document.getElementById('deleteScheduleForm').submit();
            }
        }
    </script>
    @if (session('success'))
        <script>
            iziToast.success({
                title: 'Thành công',
                message: '{{ session('success') }}',
                position: 'topRight'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            iziToast.error({
                title: 'Lỗi',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        </script>
    @endif
</section>
@endsection
