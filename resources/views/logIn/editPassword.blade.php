@extends('layouts/layoutMaster')

@section('title', 'تغيير كلمة السر')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/swiper/swiper.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/cards-advance.css')}}">
<style>
    .password-mismatch {
        color: #ea5455;
        font-size: 0.85rem;
        margin-top: 5px;
        display: none;
    }
</style>
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/swiper/swiper.js')}}">
</script>
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection

@section('page-script')
{{-- اترك هذا السطر كما هو، لن نلحق به ضرراً --}}
<script src="{{asset('assets/js/pages-auth.js')}}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // قمنا بتغيير الـ ID هنا أيضاً ليطابق الـ ID الجديد في الفورم
        const form = document.getElementById('formEditPassword');
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('new_password_confirmation');

        const errorMsg = document.createElement('div');
        errorMsg.className = 'password-mismatch';
        errorMsg.innerHTML = 'Passwords do not match!';
        confirmPassword.closest('.mb-3').appendChild(errorMsg);

        function checkPasswords() {
            if (confirmPassword.value !== '' && newPassword.value !== confirmPassword.value) {
                errorMsg.style.display = 'block';
                confirmPassword.classList.add('is-invalid');
                return false;
            } else {
                errorMsg.style.display = 'none';
                confirmPassword.classList.remove('is-invalid');
                return true;
            }
        }

        confirmPassword.addEventListener('keyup', checkPasswords);
        newPassword.addEventListener('keyup', checkPasswords);

        form.addEventListener('submit', function(e) {
            if (!checkPasswords()) {
                e.preventDefault();
                confirmPassword.focus();
            }
        });
    });
</script>
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

                    {{-- قمنا بتغيير الـ ID من formAuthentication إلى formEditPassword --}}
                    <form id="formEditPassword" class="mb-3" action="{{ route('editPasswordStore') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="old_password">كلمة السر القديمة</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="old_password" class="form-control" name="old_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>

                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="new_password">كلمة السر الجديدة</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="new_password" class="form-control" name="new_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>

                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="new_password_confirmation">إعادة كلمة السر الجديدة</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="new_password_confirmation" class="form-control" name="new_password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">حفظ كلمة السر</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection