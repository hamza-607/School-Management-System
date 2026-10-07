@extends('layouts/layoutMaster')

@section('title', 'تعديل عقوبة')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('page-script')
    {{-- السكربتات الأساسية للقالب --}}
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
            <span class="d-block fs-6 fw-medium text-muted">إدارة الطلاب</span>
            <a href="{{ route('students.index') }}" class="text-muted">القائمة / </a>
            <a href="{{ route('students.show', $theStudent->id) }}" class="text-muted">تفاصيل الطالب
                {{ $theStudent->name }}</a> /
            <a href="{{ route('penalties.index', $theStudent->id) }}" class="text-muted">العقوبات</a> /

            <span class="border-bottom border-2 border-primary"> تعديل تفاصيل عقوبة ال
                {{ $thepenalty->penalty_type }}</span>
        </h4>
    </div>




    <x-nav :student="$theStudent" />

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('penalties.update', [$thepenalty->id, $theStudent->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">نوع العقوبة<span class="text-danger">*</span></label>
                    <input class="form-control @error('penalty_type') is-invalid @enderror" type="text" name="penalty_type"
                        value="{{ $thepenalty->penalty_type }}" required>
                    @error('penalty_type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">سبب العقوبة<span class="text-danger">*</span></label>
                        <textarea class="form-control @error('reason') is-invalid @enderror" name="reason"
                            rows="3">{{ $thepenalty->reason }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">ملاحظات</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes"
                            rows="3">{{ $thepenalty->notes }}</textarea>
                        @error('notes')
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