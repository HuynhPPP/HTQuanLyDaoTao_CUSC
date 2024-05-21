@extends('layouts.app')
    
@section('content')
<div class="card mx-auto my-5" style="width: 25rem;">
  <h1 class="text-center pt-5 pb-4">Đăng nhập</h1>
<form  action={{ route('ldap') }} method="POST" class="px-5 pb-5">
    @csrf
    <div class="mb-3">
      <label for="InputEmail1" class="form-label">Tài khoản</label>
      <input type="text" class="form-control" id="InputEmail1" name="username" aria-describedby="emailHelp">
    </div>
    <div class="mb-4">
      <label for="InputPassword1" class="form-label">Mật khẩu</label>
      <input type="password" class="form-control" name="password" id="InputPassword1">
    </div>
    <div class="mb-3">
      <div class="text-center mb-3 ">{!! captcha_img() !!}</div>
      <input type="text" class="form-control" name="captcha">
    </div>
    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="Check1">
      <label class="form-check-label" for="Check1">Nhớ tôi!</label>
    </div>
    <div class="d-grid">
      <button type="submit" class="btn btn-primary">Đăng nhập</button>
    </div>  
  </form>
  
</div>
@if ($errors->any())
    <div style="color: red;">
        <h1>
            Sai captcha
        </h1>
    </div>
@endif
@endsection


