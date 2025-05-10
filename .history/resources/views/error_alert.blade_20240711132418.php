@extends('layouts.app')

@section('content')

    @if (session('error'))
        <script>
            alert('{{ session('error') }}');
            window.location = '{{ session('redirectTo') }}';
        </script>
    @endif

 @endsection
