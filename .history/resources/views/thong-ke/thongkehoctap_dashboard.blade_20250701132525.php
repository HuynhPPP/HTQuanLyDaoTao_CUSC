@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Thống kê kết quả học tập - Lớp {{ $lop->MaLop }}</h1>
        </div>

        <ul class="nav nav-tabs mb-4" id="stat-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-mh" data-toggle="tab" href="#monhoc" role="tab">Bảng điểm từng môn</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-dat" data-toggle="tab" href="#datmon" role="tab">Thống kê đạt/chưa đạt</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-tk" data-toggle="tab" href="#tongket" role="tab">Tổng kết học lực</a>
            </li>
        </ul>

        <div class="tab-content" id="tabs-content">
            <div class="tab-pane fade show active" id="monhoc" role="tabpanel">
                @include('thong-ke.partials.diem_monhoc')
            </div>
            <div class="tab-pane fade" id="datmon" role="tabpanel">
                @include('thong-ke.partials.datmon')
            </div>
            <div class="tab-pane fade" id="tongket" role="tabpanel">
                @include('thong-ke.partials.tongket_hocluc')
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
