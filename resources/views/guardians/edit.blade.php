@extends('layouts/layoutMaster')

@section('title', 'تعديل ولي أمر')

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
    <style>
        .btn-staff-outline {
            border: 1px solid #dbe6e3;
            background: #f5f8f7;
            color: #3a5350;
            border-radius: .3rem;
            font-weight: 600;
            font-size: .9rem;
            padding: .55rem 1.4rem;
            transition: border-color .15s ease, color .15s ease;
        }

        .btn-staff-outline:hover {
            border-color: var(--primary);
            color: var(--ink);
        }

        .btn-staff-primary {
            background: #006559;
            border: 1px solid #006559;
            color: #fff;
            border-radius: .3rem;
            font-weight: 600;
            font-size: .9rem;
            padding: .44rem 1.4rem;
            transition: background .15s ease;
        }

        .btn-staff-primary:hover {
            background: #00473f;
            border-color: #00473f;
            color: #fff;
        }
    </style>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">إدارة أولياء الأمور</span>
            <a href="{{ route('guardians.index') }}" class="text-muted">القائمة</a> /
            <span class="border-bottom border-2 border-primary">تعديل تفاصيل ولي الأمر {{ $theGuardian->name }}</span>
        </h4>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('guardians.update', $theGuardian->id) }}" method="POST" enctype="multipart/form-data"
                id="studentForm">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">الاسم بالعربي <span class="text-danger">*</span></label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                            value="{{ $theGuardian->name }}" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الاسم بالانكليزي</label>
                        <input class="form-control @error('e_name') is-invalid @enderror" type="text" name="e_name"
                            value="{{ $theGuardian->e_name }}">
                        @error('e_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-6">
                        <label class="form-label">تاريخ الميلاد <span class="text-danger">*</span></label>
                        <input type="text" name="date_of_birth" id="flatpickr-date"
                            value="{{ $theGuardian->date_of_birth }}"
                            class="form-control  @error('date_of_birth') is-invalid @enderror" placeholder="YYYY-MM-DD"
                            required>

                        @error('date_of_birth')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الجنس<span class="text-danger">*</span></label>
                        <select class="selectpicker w-100 @error('gender') is-invalid @enderror" data-style="btn-default"
                            name="gender">
                            <option value="male" {{ $theGuardian->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                            <option value="female" {{ $theGuardian->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">رقم الهاتف<span class="text-danger">*</span></label>
                        <input type="text" name="phone" value="{{ $theGuardian->phone }}" class="form-control">
                        @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الإيميل</label>
                        <input type="email" name="email" value="{{ $theGuardian->email }}" class="form-control">
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">العنوان<span class="text-danger">*</span></label>
                        <input type="text" name="address" value="{{ $theGuardian->address }}" class="form-control" required>
                        @error('address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn-staff-primary">حفظ</button>
                    <a href="{{ url()->previous() }}" class="btn-staff-outline">رجوع</a>
                </div>
            </form>
        </div>
    </div>
@endsection