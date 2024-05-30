@extends('layouts.app')

@section('content')
<div class="container"  style="height: 24rem">
  <div id="carouselIndicators" class="carousel slide w-75 p-5 mx-auto" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
      <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
      <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
      <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="5" aria-label="Slide 6"></button>
    </div>
    <div class="carousel-inner border rounded-3 shadow">
      <div class="carousel-item active" data-bs-interval="10000">
        <img src="{{ asset('storage/slider_CUSC/2024-01-22%20web%20aptech%20aptech.jpg') }}" class="d-block w-100" alt="2024-01-22 web aptech aptech">
      </div>
      <div class="carousel-item" data-bs-interval="10000">
        <img src="{{ asset('storage/slider_CUSC/2024-01-22%20web%20aptech%20arena.jpg') }}" class="d-block w-100" alt="2024-01-22 web aptech arena">
      </div>
      <div class="carousel-item" data-bs-interval="10000">
        <img src="{{ asset('storage/slider_CUSC/2024-03-13%20web%20aptech.jpg') }}" class="d-block w-100" alt="2024-03-13 web aptech">
      </div>
      <div class="carousel-item" data-bs-interval="10000">
        <img src="{{ asset('storage/slider_CUSC/2024-03-21%20web%20aptech.jpg') }}" class="d-block w-100" alt="2024-03-21 web aptech">
      </div>
      <div class="carousel-item" data-bs-interval="10000">
        <img src="{{ asset('storage/slider_CUSC/2024-05-07%20web%20aptech.jpg') }}" class="d-block w-100" alt="2024-05-07 web aptech">
      </div>
      <div class="carousel-item" data-bs-interval="10000">
        <img src="{{ asset('storage/slider_CUSC/2024-05-20%20web%20aptech.jpg') }}" class="d-block w-100" alt="2024-05-20 web aptech">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</div>
@endsection

