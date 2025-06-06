@extends('layouts.new_app.app')

@section('content')
<div class="section-header">
    <h1>Nhập Điểm Sinh Viên</h1>
</div>

<div class="section-body">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4>Lớp: {{ $maLop }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('giaovien.nhapdiemthi.luu-diem', ['maLop' => $maLop, 'maMH' => $maMH]) }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mã Sinh Viên</th>
                                <th>Tên Sinh Viên</th>
                                <th>Điểm Chuyên Cần (10%)</th>
                                <th>Điểm Giữa Kỳ (30%)</th>
                                <th>Điểm Cuối Kỳ (60%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sinhViens as $sinhVien)
                                @php
                                    $diemThi = $diemThis->get($sinhVien->MaSV) ?? null;
                                @endphp
                                <tr>
                                    <td>{{ $sinhVien->MaSV }}</td>
                                    <td>{{ $sinhVien->HoTenSV }}</td>
                                    <td>
                                        <input type="number" 
                                               name="diems[{{ $sinhVien->MaSV }}][DiemCC]" 
                                               class="form-control" 
                                               min="0" 
                                               max="10" 
                                               step="0.1"
                                               value="{{ $diemThi ? $diemThi->DiemCC : '' }}">
                                    </td>
                                    <td>
                                        <input type="number" 
                                               name="diems[{{ $sinhVien->MaSV }}][DiemGK]" 
                                               class="form-control" 
                                               min="0" 
                                               max="10" 
                                               step="0.1"
                                               value="{{ $diemThi ? $diemThi->DiemGK : '' }}">
                                    </td>
                                    <td>
                                        <input type="number" 
                                               name="diems[{{ $sinhVien->MaSV }}][DiemCK]" 
                                               class="form-control" 
                                               min="0" 
                                               max="10" 
                                               step="0.1"
                                               value="{{ $diemThi ? $diemThi->DiemCK : '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Lưu Điểm</button>
                    <a href="{{ route('giaovien.nhapdiemthi.danh-sach-lop') }}" class="btn btn-secondary ml-2">Quay Lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Validate điểm nhập vào
        $('input[type="number"]').on('input', function() {
            let value = parseFloat($(this).val());
            if (isNaN(value) || value < 0 || value > 10) {
                $(this).val('');
            }
        });
    });
</script>
@endsection 