@extends('layouts/blankLayout')

@section('title', 'كود التفعيل')


@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
<style>
    .password-mismatch {
        color: #ea5455;
        font-size: 0.85rem;
        margin-top: 5px;
        display: none;
    }

    .verification-code {
        direction: ltr;
    }

    .verification-code input {
        width: 3rem;
        height: 3.5rem;
        text-align: center;
        font-size: 1.25rem;
        font-weight: 600;
    }
</style>
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formEditPassword');
        const digits = Array.from(document.querySelectorAll('.verification-digit'));
        const code = document.getElementById('otp');

        const submitCode = function() {
            const value = digits.map((digit) => digit.value).join('');
            code.value = value;

            if (value.length === digits.length) {
                form.submit();
            }
        };

        digits.forEach((digit, index) => {
            digit.addEventListener('input', function() {
                digit.value = digit.value.replace(/\D/g, '').slice(-1);

                if (digit.value && index < digits.length - 1) {
                    digits[index + 1].focus();
                }

                submitCode();
            });

            digit.addEventListener('keydown', function(event) {
                if (event.key === 'Backspace' && !digit.value && index > 0) {
                    digits[index - 1].focus();
                }
            });

            digit.addEventListener('paste', function(event) {
                event.preventDefault();
                const pastedCode = event.clipboardData.getData('text').replace(/\D/g, '').slice(0, digits.length);

                pastedCode.split('').forEach((value, pastedIndex) => {
                    if (digits[index + pastedIndex]) {
                        digits[index + pastedIndex].value = value;
                    }
                });

                const nextIndex = Math.min(index + pastedCode.length, digits.length - 1);
                digits[nextIndex].focus();
                submitCode();
            });
        });

        digits[0].focus();
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

                    <h4 class="mb-2 text-center">كود التفعيل 🔐</h4>

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

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    {{-- قمنا بتغيير الـ ID من formAuthentication إلى formEditPassword --}}
                    <form id="formEditPassword" class="mb-3" action="{{ route('checkVarificationCode', $userID) }}" method="POST">
                        @csrf

                        <p class="text-center mb-4">أدخل رمز التفعيل المكون من 6 أرقام</p>

                        <div class="verification-code d-flex justify-content-center gap-2 mb-3" dir="ltr">
                            @for ($index = 0; $index < 6; $index++)
                                <input type="text"
                                class="form-control verification-digit"
                                inputmode="numeric"
                                maxlength="1"
                                pattern="[0-9]"
                                aria-label="رقم {{ $index + 1 }} من رمز التفعيل"
                                autocomplete="{{ $index === 0 ? 'one-time-code' : 'off' }}"
                                required>
                                @endfor
                        </div>

                        <input type="hidden" name="otp" id="otp">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection