@extends('layouts/blankLayout')

@section('title', 'تسجيل الدخول')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
@endsection

@section('content')

<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
      <!-- Login -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-4 mt-2 flex-column">

            <img src="{{ Storage::url('schoolLogo/logo.png') }}"
              alt="Logo"
              width="200"
              class="mb-3">

            <span class="app-brand-text demo text-body fw-bold ms-1">
              مدرسة أُفق النموذجية
            </span>

          </div>

          @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          @if (session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
          @endif

          <h4 class="mb-1 pt-2">مرحباَ بعودتك</h4>
          <p class="mb-4">الرجاء تسجيل الدخول باستخدام بريدك الإلكتروني وكلمة السر</p>

          <form id="formAuthentication" class="mb-3" action="{{ route('storeLogIn') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label for="email" class="form-label">الإيميل</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="أدخل الإيميل الخاص بك" autofocus>
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">كلمة السر</label>
                <a href="">
                  <small>نسيت كلمة السر؟</small>
                </a>
              </div>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
            </div>
            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember-me">
                <label class="form-check-label" for="remember-me">
                  Remember Me
                </label>
              </div>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection