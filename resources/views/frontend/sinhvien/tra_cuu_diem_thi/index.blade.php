@extends('layouts.new_app.master')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Tra cứu điểm thi</h1>
    </div>

    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Bảng điểm của tôi</h4>
                        <div class="card-header-form ml-auto">
                            <a href="{{ route('tracuu.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-sync-alt"></i> Làm mới
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0">Vui lòng truy cập mục này từ menu. Trang sẽ tự động hiển thị bảng điểm của bạn khi đã đăng nhập.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 