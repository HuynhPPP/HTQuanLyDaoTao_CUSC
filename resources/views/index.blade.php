@extends('layouts.app')

@section('content')
<div class="card mx-auto my-5" style="width: 25rem;">
  <h1 class="text-center pt-5 pb-4">Đăng nhập</h1>
<form action={{ route('ldap') }} method="POST" class="px-5 pb-5">
    @csrf
    <div class="mb-3">
      <label for="InputUsername" class="form-label">Tài khoản</label>
      <input type="text" class="form-control" id="InputUsername" name="username">
      <div class="invalid-feedback"></div>
    </div>
    <div class="mb-4">
      <label for="InputPassword" class="form-label">Mật khẩu</label>
      <input type="password" class="form-control" name="password" id="InputPassword">
      <div class="invalid-feedback"></div>
    </div>
    <div class="mb-3">
      <div class="d-flex justify-content-center mb-3" style="height: 3rem">{!! captcha_img() !!}</div>
      <input type="text" class="form-control" name="captcha" id="InputCaptcha">
    </div>
    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="Check1">
      <label class="form-check-label" for="Check1">Nhớ tôi!</label>
    </div>
    <div class="d-grid">
      <button id="submit-button" type="submit" class="btn btn-primary">Đăng nhập</button>
      <div id="error-message" class="text-danger"></div>
    </div>
  </form>

</div>

@if ($errors->any())
  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class=" toast-body d-flex justify-content-between bg-danger">
          <p5 class="text-white">Sai captcha</p5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
@endif

<script src="{{ asset('js/toast.js') }}"></script>
<script src="{{ asset('js/validation.js') }}"></script>

@endsection

