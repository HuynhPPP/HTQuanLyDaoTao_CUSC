@extends('layouts.new_app.master')

@section('main-content')
<section class="section">
    <div class="section-header">
      <h1>Lịch học theo tuần</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
        <div class="breadcrumb-item">Lịch học theo tuần</div>
    </div>
    </div>

    <div class="section-body">
      <h2 class="section-title">Calendar</h2>
      <p class="section-lead">
        We use 'Full Calendar' made by @fullcalendar. You can check the full documentation <a href="https://fullcalendar.io/">here</a>.
      </p>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Calendar</h4>
            </div>
            <div class="card-body">
              <div class="fc-overflow">                            
                <div id="myEvent"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('custom-js')

@endsection
