@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
  <div class="section-header d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Tiến trình học tập của tôi</h1>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm rounded-4 border-0">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-user-graduate mr-2"></i>Thông tin sinh viên</h4>
            <span class="badge badge-primary">{{ $sinhVien->MaSV }}</span>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <table class="table table-borderless">
                  <tr><td><strong>Họ và tên:</strong></td><td>{{ $sinhVien->HoTen }}</td></tr>
                  <tr><td><strong>Email:</strong></td><td>{{ $sinhVien->Email }}</td></tr>
                </table>
              </div>
              <div class="col-md-6">
                <table class="table table-borderless">
                  <tr><td><strong>Ngày sinh:</strong></td><td>{{ optional($sinhVien->NgaySinh ? \Carbon\Carbon::parse($sinhVien->NgaySinh) : null)->format('d/m/Y') }}</td></tr>
                  <tr><td><strong>Số điện thoại:</strong></td><td>{{ $sinhVien->Sdt }}</td></tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 col-md-4 col-sm-6 col-12">
        <div class="card card-statistic-1 shadow-sm">
          <div class="card-icon bg-primary"><i class="fas fa-book"></i></div>
          <div class="card-wrap">
            <div class="card-header"><h4>Tổng môn học</h4></div>
            <div class="card-body">{{ $thongKe['tongMonHoc'] }}</div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-4 col-sm-6 col-12">
        <div class="card card-statistic-1 shadow-sm">
          <div class="card-icon bg-success"><i class="fas fa-check-circle"></i></div>
          <div class="card-wrap">
            <div class="card-header"><h4>Đã hoàn thành</h4></div>
            <div class="card-body">{{ $thongKe['monDaHoanThanh'] }}</div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-4 col-sm-6 col-12">
        <div class="card card-statistic-1 shadow-sm">
          <div class="card-icon bg-info"><i class="fas fa-clock"></i></div>
          <div class="card-wrap">
            <div class="card-header"><h4>Đang học</h4></div>
            <div class="card-body">{{ $thongKe['monDangHoc'] }}</div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-4 col-sm-6 col-12">
        <div class="card card-statistic-1 shadow-sm">
          <div class="card-icon bg-warning"><i class="fas fa-star"></i></div>
          <div class="card-wrap">
            <div class="card-header"><h4>Điểm TB (CTĐT)</h4></div>
            <div class="card-body">{{ number_format($thongKe['diemTrungBinh'], 2) }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm rounded-4 border-0">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-list-alt"></i> Chi tiết tiến trình học tập</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead class="thead-light">
                  <tr>
                    <th>Môn học</th>
                    <th>Lớp</th>
                    <th>Lý thuyết</th>
                    <th>Thực hành</th>
                    <th>Dự án</th>
                    <th>Tổng (CTĐT)</th>
                    <th>Trạng thái</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($tienTrinh as $item)
                  <tr>
                    <td><strong>{{ $item->monHoc->TenMH ?? 'N/A' }}</strong><br><small class="text-muted">{{ $item->MaMH }}</small></td>
                    <td>{{ $item->lopHoc->MaLop ?? 'N/A' }}</td>
                    <td>{{ number_format($item->DiemLyThuyet ?? 0, 2) }}</td>
                    <td>{{ number_format($item->DiemThucHanh ?? 0, 2) }}</td>
                    <td>{{ number_format($item->DiemDuAn ?? 0, 2) }}</td>
                    <td>{{ number_format($item->TongDiemTinhLai ?? ($item->DiemTong ?? 0), 2) }}</td>
                    <td>
                      @php
                        $mapColor = ['ChuaDangKy'=>'warning','DangKy'=>'secondary','DangHoc'=>'info','DaHoanThanh'=>'success','ChuaHoanThanh'=>'danger'];
                        $mapText = ['ChuaDangKy'=>'Chưa đăng ký','DangKy'=>'Đã đăng ký','DangHoc'=>'Đang học','DaHoanThanh'=>'Đã hoàn thành','ChuaHoanThanh'=>'Chưa hoàn thành'];
                      @endphp
                      <span class="badge badge-{{ $mapColor[$item->TrangThai] ?? 'secondary' }}">{{ $mapText[$item->TrangThai] ?? $item->TrangThai }}</span>
                    </td>
                  </tr>
                  @empty
                  <tr><td colspan="7" class="text-center text-muted">Chưa có dữ liệu</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection


