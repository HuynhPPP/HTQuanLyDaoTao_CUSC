@extends('layouts.app')

@section('main-content')

    @if (session('error'))
        
        <script>
            iziToast.error({
                title: 'Lỗi',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        </script>
    @endif

@endsection
