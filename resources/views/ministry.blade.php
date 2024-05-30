@extends('layouts.app')

@section('content')
<div class="container text-center my-5 p-5">
    <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-5">
      <div class="col">
        <button class="button__link">
            <i class="fa-solid fa-scale-balanced fs-1 mb-3"></i>
            Qui định, quyết định
        </button>
      </div>
      <div class="col">
        <button class="button__link">
            <i class="fa-regular fa-file-word fs-1 mb-3"></i>
            Biểu mẩu và quy trình
        </button>
      </div>
      <div class="col">
        <button class="button__link">
            <i class="fa-regular fa-file fs-1 mb-3"></i>
            Hướng dẫn
        </button>
      </div>
      <div class="col">
        <a href="{{ route('schedules') }}" class="button__link">
            <i class="fa-solid fa-calendar-days fs-1 mb-3"></i>
            Thời khóa biểu
        </a>
      </div>
      <div class="col">
        <button class="button__link">
            <i class="fa-solid fa-calendar-days fs-1 mb-3"></i>
            Lịch thi tháng
        </button>
      </div>
      <div class="col">
        <button class="button__link">
            <i class="fa-solid fa-calendar-days fs-1 mb-3"></i>
            Lịch thi các lớp
        </button>
      </div>
      <div class="col">
        <button class="button__link">
            <i class="fa-solid fa-magnifying-glass fs-1 mb-3"></i>
            Tra cứu điểm thi
        </button>
      </div>
      <div class="col">
        <button class="button__link">
            <i class="fa-solid fa-pen-to-square fs-1 mb-3"></i>
            Cập nhật thông tin
        </button>
      </div>
      <div class="col">
        <button class="button__link">
            <i class="fa-solid fa-table-list fs-1 mb-3"></i>
            CUSC Point
        </button>
      </div>
      <div class="col">
        <button class="button__link">
            <i class="fa-solid fa-table-list fs-1 mb-3"></i>
            Phiếu đánh giá cuối môn học
        </button>
      </div>
      <div class="col">
        <button class="button__link">
            <i class="fa-solid fa-table-list fs-1 mb-3"></i>
            Phiếu đánh giá dịch vụ
        </button>
      </div>
      <div class="col">
        <button class="button__link">
            <i class="fa-solid fa-scroll fs-1 mb-3"></i>
            Thông báo tuyển dụng của CUSC
        </button>
      </div>
    </div>
  </div>

 @endsection
