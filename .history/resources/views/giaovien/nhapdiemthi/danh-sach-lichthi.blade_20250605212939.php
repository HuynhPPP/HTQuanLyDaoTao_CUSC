@extends('layouts.new_app.master')

@section('main-content')
@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh sách lịch thi môn {{ $tenMH }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item">Danh sách lịch thi môn {{ $tenMH }}</div>
            </div>
        </div>

<div class="section-body">
    <div class="card">
        <div class="card-body">
            @if($lichThis->isEmpty())
                <div class="alert alert-info">
                    Chưa có lịch thi cho môn học này.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Ngày Thi</th>
                                <th>Giờ Thi</th>
                                <th>Phòng Thi</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lichThis as $lichThi)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($lichThi->NgayThi)->format('d/m/Y') }}</td>
                                    <td>{{ $lichThi->GioThi }}</td>
                                    <td>{{ $lichThi->PhongThi }}</td>
                                    <td>
                                        <a href="{{ route('giaovien.nhapdiemthi.nhap-diem', ['maLichThi' => $lichThi->MaLichThi]) }}" 
                                           class="btn btn-primary">
                                            Nhập Điểm
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('giaovien.nhapdiemthi.danh-sach-mon') }}" class="btn btn-secondary">Quay Lại</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json'
            },
            order: [[0, 'desc']]
        });
    });
</script>
@endsection 