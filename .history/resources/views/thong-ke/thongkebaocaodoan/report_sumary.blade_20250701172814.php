@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Thống kê báo cáo đồ án</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('thong-ke.upload.form.doan') }}" class="btn btn-link">
                <i class="fas fa-upload"></i> Quay lại Upload
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header justify-content-between">
                <h4 class="card-title">Danh sách buổi báo cáo</h4>
                <a href="{{ route('thong-ke.reports.export.doan') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </a>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Lớp</th>
                            <th>Đồ án</th>
                            <th>GV Hướng dẫn</th>
                            <th>GV Phản biện</th>
                            <th>Ngày</th>
                            <th>Giờ</th>
                            <th>Địa điểm</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->class->name ?? 'N/A' }}</td>
                                <td>{{ $report->report_name }}</td>
                                <td>{{ $report->instructor->full_name ?? 'N/A' }}</td>
                                <td>{{ $report->reviewer->full_name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}</td>
                                <td>{{ $report->report_time_start }} – {{ $report->report_time_end }}</td>
                                <td>{{ $report->location }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($reports->isEmpty())
                    <div class="text-center text-muted mt-3">Chưa có dữ liệu báo cáo</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
