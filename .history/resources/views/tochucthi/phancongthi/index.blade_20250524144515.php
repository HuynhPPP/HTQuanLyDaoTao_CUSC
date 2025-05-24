@extends('layouts.new_app.master')
@section('title', 'Danh sách phân công thi')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Danh sách phân công thi</h1>
    </div>
    <div class="section-body">
        <a href="{{ route('phancong.create') }}" class="btn btn-primary mb-3">Phân công mới</a>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lịch thi</th>
                        <th>Cán bộ</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($phanCongs as $index => $pc)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pc->lichThi->monHoc->TenMH ?? 'N/A' }} - {{ $pc->lichThi->NgayThi }}</td>
                        <td>{{ $pc->canBo->HoTen ?? 'N/A' }}</td>
                        <td>{{ $pc->VaiTro }}</td>
                        <td>{{ $pc->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
