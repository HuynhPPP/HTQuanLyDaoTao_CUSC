@extends('layouts.new_app.master')

@section('page-title', 'Đánh Giá Kết Quả Học Tập')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Đánh Giá Kết Quả Học Tập</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item active">Đánh giá học tập</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Thông tin tổng quan -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Thông tin tổng quan</h4>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-graduation-cap fa-2x text-primary mb-2"></i>
                                        <h5>{{ count($dsChuongTrinh) }}</h5>
                                        <p class="text-muted mb-0">Chương trình đào tạo</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-users fa-2x text-success mb-2"></i>
                                        <h5>{{ count($dsLop) }}</h5>
                                        <p class="text-muted mb-0">Lớp học</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-chart-bar fa-2x text-info mb-2"></i>
                                        <h5>Đánh giá</h5>
                                        <p class="text-muted mb-0">Kết quả học tập</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-analytics fa-2x text-warning mb-2"></i>
                                        <h5>Phân tích</h5>
                                        <p class="text-muted mb-0">Xu hướng học tập</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Các chức năng đánh giá -->
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Đánh giá theo lớp</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Xem kết quả học tập và phân tích hiệu suất của sinh viên trong từng lớp học.</p>
                            <div class="d-grid">
                                <a href="#lop-hoc" class="btn btn-outline-primary" data-toggle="collapse">
                                    <i class="fas fa-list"></i> Chọn lớp học
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-star"></i> Sinh viên xuất sắc</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Danh sách sinh viên có thành tích học tập tốt nhất trong toàn trường.</p>
                            <div class="d-grid">
                                <a href="{{ route('ranking.top') }}" class="btn btn-outline-success">
                                    <i class="fas fa-chart-line"></i> Xem danh sách
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-balance-scale"></i> So sánh hiệu suất</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Phân tích và so sánh hiệu suất học tập giữa các lớp và chương trình.</p>
                            <div class="d-grid">
                                <a href="{{ route('ranking.so-sanh-lop') }}" class="btn btn-outline-info">
                                    <i class="fas fa-analytics"></i> So sánh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách lớp học -->
            <div class="row">
                <div class="col-12">
                    <div class="collapse" id="lop-hoc">
                        <div class="card">
                            <div class="card-header">
                                <h4>Danh sách lớp học</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($dsLop as $lop)
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <div class="card border-left-primary">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="card-title mb-1">{{ $lop->TenLop }}</h6>
                                                        <p class="card-text text-muted small mb-2">
                                                            Mã lớp: {{ $lop->MaLop }}
                                                        </p>
                                                        <p class="card-text text-muted small mb-0">
                                                            Chương trình: {{ $lop->loaidaotao->TenChuongTrinh ?? 'Chưa xác định' }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('ranking.lop', $lop->MaLop) }}" 
                                                           class="btn btn-primary btn-sm">
                                                            <i class="fas fa-chart-bar"></i> Xem
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách chương trình đào tạo -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Đánh giá theo chương trình đào tạo</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($dsChuongTrinh as $ct)
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="card border-left-info">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="card-title mb-1">{{ $ct->TenChuongTrinh }}</h6>
                                                    <p class="card-text text-muted small mb-0">
                                                        Mã chương trình: {{ $ct->MaChuongTrinh }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <a href="{{ route('ranking.chuong-trinh', $ct->MaChuongTrinh) }}" 
                                                       class="btn btn-info btn-sm">
                                                        <i class="fas fa-chart-pie"></i> Xem
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
