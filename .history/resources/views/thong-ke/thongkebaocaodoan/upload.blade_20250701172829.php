@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Tải lên file phân công</h1>
    </div>

    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-md-6">

                {{-- Success Alert --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif

                {{-- Error Alert --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('thong-ke.thong-ke.upload.file.doan') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="report_file" class="form-label">Chọn file .doc/.docx</label>
                                <input type="file" name="report_file" id="report_file" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-upload"></i> Upload
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('thong-ke.reports.show.doan') }}" class="btn btn-link text-decoration-none">
                                <i class="fas fa-table"></i> Xem thống kê đã tổng hợp
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
