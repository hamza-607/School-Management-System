@extends('layouts/blankLayout')

@section('title', 'نسيت كلمة السر')

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
            <div class="card">
                <div class="card-body">
                    <div class="app-brand justify-content-center mb-4 mt-2 flex-column">
                        <img src="{{ Storage::url('schoolLogo/logo.png') }}" alt="Logo" width="250" class="mb-3">
                        <span class="app-brand-text demo text-body fw-bold ms-1">مدرسة أفق النموذجية</span>
                    </div>

                    <h4 class="mb-2 text-center">تغيير كلمة السر 🔐</h4>

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
                        <ul class="mb-0">
                            <li>{{ session('success') }}</li>
                        </ul>
                    </div>
                    @endif

                    <form id="formEditPassword" action="{{ route('sendVarificationCode') }}" method="POST">
                        @csrf

                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="email">البريد الإلكتروني</label>
                            <div class="input-group input-group-merge">
                                <input type="email" id="email" class="form-control" name="email" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">إرسال رمز التحقق</button>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('login') }}">الرجوع لتسجيل الدخول</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection