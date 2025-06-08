@extends('layouts.new_app.master')

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Nhập Điểm Thi - {{ $lichThi->monHoc->TenMH }}</h4>
                        <div class="card-header-action">
                            <span class="badge badge-primary mr-2">
                                <i class="fas fa-calendar-alt"></i> 
                                Ngày Thi: {{ \Carbon\Carbon::parse($lichThi->NgayThi)->format('d/m/Y') }}
                            </span>
                            <span class="badge badge-info">
                                <i class="fas fa-door-open"></i> 
                                Phòng Thi: {{ $lichThi->PhongThi }}
                            </span>
                        </div>
                    </div>
                    
                    <form action="{{ route('giaovien.nhapdiemthi.luu-diem', ['maLichThi' => $lichThi->MaLichThi]) }}" 
                          method="POST" id="nhapDiemForm">
                        @csrf
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="diemTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>STT</th>
                                            <th>Mã SV</th>
                                            <th>Tên Sinh Viên</th>
                                            <th>Lần Thi</th>
                                            <th>Điểm Tổng</th>
                                            <th>Điểm Thực Hành</th>
                                            <th>Điểm Lý Thuyết</th>
                                            <th>Điểm Bài Tập</th>
                                            <th>Ghi Chú</th>
                                            <th>Trạng Thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sinhViens as $index => $sinhVien)
                                            @php
                                                $diemThi = $diemThis->get($sinhVien->MaSV) ?? null;
                                                $diemTongHop = $diemThi ? 
                                                    ($diemThi->DiemThucHanh * 0.3 + 
                                                     $diemThi->DiemLyThuyet * 0.5 + 
                                                     $diemThi->DiemBaiTap * 0.2) : null;
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $sinhVien->MaSV }}</td>
                                                <td>{{ $sinhVien->HoTenSV }}</td>
                                                <td>
                                                    <input type="number" 
                                                           name="diems[{{ $sinhVien->MaSV }}][LanThi]"
                                                           class="form-control form-control-sm lan-thi"
                                                           min="1" max="3" 
                                                           value="{{ $diemThi ? $diemThi->LanThi : 1 }}">
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           name="diems[{{ $sinhVien->MaSV }}][Diem]"
                                                           class="form-control form-control-sm diem-tong-hop"
                                                           min="0" max="10" step="0.1" 
                                                           value="{{ $diemTongHop ?? '' }}"
                                                           readonly>
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           name="diems[{{ $sinhVien->MaSV }}][DiemThucHanh]"
                                                           class="form-control form-control-sm diem-thanh-phan"
                                                           min="0" max="10" step="0.1" 
                                                           value="{{ $diemThi ? $diemThi->DiemThucHanh : '' }}">
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           name="diems[{{ $sinhVien->MaSV }}][DiemLyThuyet]"
                                                           class="form-control form-control-sm diem-thanh-phan"
                                                           min="0" max="10" step="0.1" 
                                                           value="{{ $diemThi ? $diemThi->DiemLyThuyet : '' }}">
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           name="diems[{{ $sinhVien->MaSV }}][DiemBaiTap]"
                                                           class="form-control form-control-sm diem-thanh-phan"
                                                           min="0" max="10" step="0.1" 
                                                           value="{{ $diemThi ? $diemThi->DiemBaiTap : '' }}">
                                                </td>
                                                <td>
                                                    <input type="text" 
                                                           name="diems[{{ $sinhVien->MaSV }}][GhiChu]"
                                                           class="form-control form-control-sm" 
                                                           value="{{ $diemThi ? $diemThi->GhiChu : '' }}">
                                                </td>
                                                <td>
                                                    <span class="badge badge-info status-label">Chưa nhập</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Lưu Điểm
                                    </button>
                                    <a href="{{ route('giaovien.nhapdiemthi.danh-sach-mon') }}" 
                                       class="btn btn-secondary ml-2">
                                        <i class="fas fa-arrow-left"></i> Quay Lại
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <span class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Điểm tổng hợp = (Thực Hành * 0.3) + (Lý Thuyết * 0.5) + (Bài Tập * 0.2)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            // Tính điểm tổng hợp tự động
            function tinhDiemTongHop() {
                $('.diem-tong-hop').each(function() {
                    let row = $(this).closest('tr');
                    let diemThucHanh = parseFloat(row.find('input[name$="[DiemThucHanh]"]').val()) || 0;
                    let diemLyThuyet = parseFloat(row.find('input[name$="[DiemLyThuyet]"]').val()) || 0;
                    let diemBaiTap = parseFloat(row.find('input[name$="[DiemBaiTap]"]').val()) || 0;

                    let diemTongHop = (diemThucHanh * 0.3 + diemLyThuyet * 0.5 + diemBaiTap * 0.2).toFixed(1);
                    $(this).val(diemTongHop);

                    // Cập nhật trạng thái
                    let statusLabel = row.find('.status-label');
                    if (diemThucHanh > 0 || diemLyThuyet > 0 || diemBaiTap > 0) {
                        statusLabel.removeClass('badge-info').addClass('badge-success').text('Đã nhập');
                    } else {
                        statusLabel.removeClass('badge-success').addClass('badge-info').text('Chưa nhập');
                    }
                });
            }

            // Validate và tính điểm khi nhập
            $('.diem-thanh-phan').on('input', function() {
                let value = parseFloat($(this).val());
                let min = parseFloat($(this).attr('min'));
                let max = parseFloat($(this).attr('max'));

                if (isNaN(value) || value < min || value > max) {
                    $(this).val('');
                }

                tinhDiemTongHop();
            });

            // Validate lần thi
            $('.lan-thi').on('input', function() {
                let value = parseInt($(this).val());
                let min = parseInt($(this).attr('min'));
                let max = parseInt($(this).attr('max'));

                if (isNaN(value) || value < min || value > max) {
                    $(this).val(1);
                }
            });

            // Khởi tạo ban đầu
            tinhDiemTongHop();
        });
    </script>
@endsection
