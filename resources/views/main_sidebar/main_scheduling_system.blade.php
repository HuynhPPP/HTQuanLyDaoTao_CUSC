@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Quản lý đào tạo</h1>
        </div>
        <div class="row">
            @foreach ($functions as $func)
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="{{ $func['link'] }}" style="text-decoration: none;">
                        <div class="card card-statistic-1">
                            <div class="card-icon {{ $func['color'] }}">
                                <i class="fa {{ $func['icon'] }}"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ $func['text'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endsection
