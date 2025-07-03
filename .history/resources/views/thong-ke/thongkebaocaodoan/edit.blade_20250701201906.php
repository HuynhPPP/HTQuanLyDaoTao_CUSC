@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Sửa lịch báo cáo</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('bao-cao.update', $report->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Lớp</label>
                        <input type="text" name="class_id" class="form-control" value="{{ $report->class_id }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Đề tài</label>
                        <input type="text" name="report_name" class="form-control" value="{{ $report->report_name }}">
                    </div>

                    <div class="form-group">
                        <label>GV Hướng dẫn</label>
                        <select name="instructor_id" class="form-control">
                            @foreach($giaoViens as $gv)
                                <option value="{{ $gv->MaGV }}" {{ $report->instructor_id == $gv->MaGV ? 'selected' : '' }}>
                                    {{ $gv->HoTenGV }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>GV Phản biện</label>
                        <select name="reviewer_id" class="form-control">
                            @foreach($giaoViens as $gv)
                                <option value="{{ $gv->MaGV }}" {{ $report->reviewer_id == $gv->MaGV ? 'selected' : '' }}>
                                    {{ $gv->HoTenGV }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Ngày báo cáo</label>
                        <input type="date" name="report_date" class="form-control" value="{{ $report->report_date }}">
                    </div>

                    <div class="form-group">
                        <label>Giờ bắt đầu</label>
                        <input type="time" name="report_time_start" class="form-control" value="{{ $report->report_time_start }}">
                    </div>

                    <div class="form-group">
                        <label>Giờ kết thúc</label>
                        <input type="time" name="report_time_end" class="form-control" value="{{ $report->report_time_end }}">
                    </div>

                    <div class="form-group">
                        <label>Địa điểm</label>
                        <input type="text" name="location" class="form-control" value="{{ $report->location }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('bao-cao.index') }}" class="btn btn-secondary ml-2">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
