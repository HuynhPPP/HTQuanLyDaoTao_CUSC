@extends('layouts.new_app.master')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Tra Cứu Điểm Thi</h1>
    </div>

    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Nhập Mã Sinh Viên</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tracuu.diem') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="MaSV">Mã Sinh Viên</label>
                                <input type="text" 
                                       class="form-control @error('MaSV') is-invalid @enderror" 
                                       id="MaSV" 
                                       name="MaSV" 
                                       value="{{ old('MaSV', $maSV) }}"
                                       placeholder="Nhập mã sinh viên"
                                       required>
                                @error('MaSV')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Tra Cứu Điểm
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 