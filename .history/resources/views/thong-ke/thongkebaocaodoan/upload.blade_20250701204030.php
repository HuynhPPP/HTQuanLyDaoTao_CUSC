@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Upload phân công chấm báo cáo đồ án</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('bao-cao.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="report_file">Chọn file Word (.doc/.docx):</label>
                        <input type="file" name="report_file" id="report_file"
                               class="form-control @error('report_file') is-invalid @enderror">
                    
                        @error('report_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload & Tổng hợp
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
