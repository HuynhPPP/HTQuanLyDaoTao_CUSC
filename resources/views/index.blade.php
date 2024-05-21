@extends('layouts.app')
    
@section('content')
<h1>Login</h1>
<form action={{ route('ldap') }} method="POST">
    @csrf
    <input type="text" name="username"> <br>
    <input type="password" name="password"> <br>
    {!! captcha_img() !!}
    <input type="text" name="captcha">
    <input type="submit" value="Login">
</form>

@if ($errors->any())
    <div style="color: red;">
        <h1>
            Sai captcha
        </h1>
    </div>
@endif
@endsection


