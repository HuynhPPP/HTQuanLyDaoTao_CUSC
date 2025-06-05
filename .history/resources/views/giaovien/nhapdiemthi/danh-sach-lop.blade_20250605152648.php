@extends('layouts.new_app.app')

@section('content')
<div class="section-header">
    <h1>Danh sách lớp học để nhập điểm</h1>
</div>

<div class="section-body">
    <div class="card">
        <div class="card-body">
            @if($lopHocs->isEmpty())
                <div class="alert alert-info">
                    Bạn chưa được phân công giảng dạy lớp nào.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mã Lớp</th>
                                <th>Tên Lớp</th>
                                <th>Môn Học</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lopHocs as $lop)
                                <tr>
                                    <td>{{ $lop['maLop'] }}</td>
                                    <td>{{ $lop['tenLop'] }}</td>
                                    <td>{{ $lop['tenMH'] }}</td>
                                    <td>
                                        <a href="{{ route('giaovien.nhapdiemthi.nhap-diem', ['maLop' => $lop['maLop'], 'maMH' => $lop['maMH']]) }}" 
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