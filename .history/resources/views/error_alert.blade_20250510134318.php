@extends('layouts.app')

@section('content')

    @if (session('error'))
        <script src="{{ asset('assets/modules/izitoast/js/iziToast.min.js') }}"></script>
        <script>
            iziToast.error({
                title: 'Lỗi',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        </script>
    @endif

@endsection
