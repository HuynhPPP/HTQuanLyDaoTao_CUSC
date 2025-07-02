@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Thống kê chấm báo cáo đồ án</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('bao-cao.upload.form') }}" class="btn btn-secondary">Quay lại upload</a>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header justify-content-between">
                <h4>Lịch báo cáo đã tổng hợp</h4>
                <a href="{{ route('bao-cao.export') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </a>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Lớp</th>
                            <th>Đề tài</th>
                            <th>GV Hướng dẫn</th>
                            <th>GV Phản biện</th>
                            <th>Ngày</th>
                            <th>Giờ</th>
                            <th>Địa điểm</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->class_id }}</td>
                                <td>{{ $report->report_name }}</td>
                                <td>{{ $report->instructor->HoTenGV ?? 'N/A' }}</td>
                                <td>{{ $report->reviewer->HoTenGV ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}</td>
                                <td>{{ $report->report_time_start }} - {{ $report->report_time_end }}</td>
                                <td>{{ $report->location }}</td>
                                <td>
                                    <a href="{{ route('bao-cao.edit', $report->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($reports->isEmpty())
                    <div class="text-center text-muted">Không có dữ liệu.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
