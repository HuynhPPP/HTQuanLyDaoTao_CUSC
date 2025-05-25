@extends('layouts.new_app.master')
@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Nhập điểm thi - Lớp: {{ $lop->TenLop }} - Môn: {{ $tenMH }}</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('bangdiem.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="maLop" value="{{ $maLop }}">
                        <input type="hidden" name="tenMH" value="{{ $tenMH }}">

                        <!-- Nhập điểm thủ công -->
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Mã SV</th>
                                        <th>Họ Tên</th>
                                        <th>Lần thi</th>
                                        <th>Điểm</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($danhSachSV as $sv)
                                        <tr>
                                            <td>{{ $sv->sinhVien->MaSV }}</td>
                                            <td>{{ $sv->HoTen }}</td>
                                            <td>
                                                <input type="number" name="lanThi[{{ $sv->MaSV }}]"
                                                    class="form-control" value="1" min="1">
                                            </td>
                                            <td>
                                                <input type="number" name="diem[{{ $sv->MaSV }}]" class="form-control"
                                                    step="0.1" min="0" max="10">
                                            </td>
                                            <td>
                                                <input type="text" name="ghiChu[{{ $sv->MaSV }}]"
                                                    class="form-control">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Import Excel -->
                        <div class="form-group mt-3">
                            <label>Import điểm từ Excel</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls">
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Lưu điểm</button>
                            <a href="{{ route('bangdiem.export', ['maLop' => $maLop, 'tenMH' => $tenMH]) }}"
                                class="btn btn-success">Xuất Excel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
