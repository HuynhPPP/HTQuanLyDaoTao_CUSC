@extends('layouts.app')

@section('content')

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
