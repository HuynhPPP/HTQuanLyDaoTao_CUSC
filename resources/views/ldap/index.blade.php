@extends('layouts.app')

@section('content')
    @if (isset($message))
        <div style="color: green;">{{ $message }}</div>
    @endif
@endsection

