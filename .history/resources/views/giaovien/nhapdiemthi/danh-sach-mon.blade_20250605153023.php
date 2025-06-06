@extends('layouts.new_app.master')

@section('main-content')
<div class="section-header">
    <h1>Danh sách môn học để nhập điểm</h1>
</div>

<div class="section-body">
    <div class="card">
        <div class="card-body">
            @if($monHocs->isEmpty())
                <div class="alert alert-info">
                    Bạn chưa được phân công giảng dạy môn học nào.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mã Môn Học</th>
                                <th>Tên Môn Học</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monHocs as $monHoc)
                                <tr>
                                    <td>{{ $monHoc->MaMH }}</td>
                                    <td>{{ $monHoc->TenMH }}</td>
                                    <td>
                                        <a href="{{ route('giaovien.nhapdiemthi.danh-sach-lichthi', ['tenMH' => $monHoc->TenMH]) }}" 
                                           class="btn btn-primary">
                                            Chọn Lịch Thi
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
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
            }
        });
    });
</script>
@endsection 