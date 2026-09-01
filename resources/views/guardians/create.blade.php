@extends('layouts/layoutMaster')

@section('title', 'إضافة ولي أمر')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
<script src="{{ asset('assets/js/forms-pickers.js') }}"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">أولياء الامور/</span> إضافة ولي أمر جديد
</h4>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('guardians.store') }}" method="POST" enctype="multipart/form-data" id="studentForm">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">الاسم بالعربي <span class="text-danger">*</span></label>
                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">الاسم بالانكليزي</label>
                    <input class="form-control @error('e_name') is-invalid @enderror" type="text" name="e_name" value="{{ old('e_name') }}">
                    @error('e_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-6">
                    <label class="form-label">تاريخ الميلاد <span class="text-danger">*</span></label>
                    <input type="text"
                        name="date_of_birth"
                        id="flatpickr-date"
                        value="{{ old('date_of_birth') }}"
                        class="form-control  @error('date_of_birth') is-invalid @enderror"
                        placeholder="YYYY-MM-DD"
                        required>

                    @error('date_of_birth')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">الجنس<span class="text-danger">*</span></label>
                    <select class="selectpicker w-100 @error('gender') is-invalid @enderror"
                        data-style="btn-default"
                        name="gender">
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                    @error('gender')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">رقم الهاتف<span class="text-danger">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                    @error('phone')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">الإيميل</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                    @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">العنوان<span class="text-danger">*</span></label>
                    <input type="text" name="address" value="{{ old('address') }}" class="form-control" required>
                    @error('address')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary">حفظ</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">رجوع</a>
            </div>
        </form>
    </div>
</div>
@endsection