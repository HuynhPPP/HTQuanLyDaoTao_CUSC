@extends('layouts.app')

@section('content')
<div class="container text-center my-5 p-5">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-2 g-lg-5 justify-content-center align-items-center">
        @foreach ($functions as $function)
            <div class="col d-flex justify-content-center align-items-center">
                <a href="{{ $function['link'] }}" class="button__link d-flex flex-column justify-content-center align-items-center text-decoration-none" style="height: 10rem; width: 100%;">
                    <i class="fa-solid {{ $function['icon'] }} fs-1 mb-3"></i>
                    {{ $function['text'] }}
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
