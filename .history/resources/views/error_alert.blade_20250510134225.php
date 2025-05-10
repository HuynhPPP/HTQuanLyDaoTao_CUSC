@extends('layouts.app')

@section('content')

    @if (session('error'))
        <script src="{{ asset('assets/modules/izitoast/js/iziToast.min.js') }}"></script>
        <script>
            alert('{{ session('error') }}');
            window.location = '{{ session('redirectTo') }}';
        </script>
    @endif

 @endsection
