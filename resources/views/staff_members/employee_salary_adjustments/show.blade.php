@extends('layouts/layoutMaster')

@section('title', 'عرض تعديلات الراتب')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('vendor-script')
    {{-- أضفنا السكربتات الخاصة بالـ selectpicker هنا --}}
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
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
            border-color: #006559;
            color: #00352e;
        }
    </style>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">إدارة الموظفين</span>
            <a href="{{ route('staff_members.index', ['from' => $from]) }}" class="text-muted">القائمة / </a>
            <a href="{{ route('staff_members.show', [$staff->id, 'from' => $from]) }}" class="text-muted">تفاصيل الموظف
                {{ $staff->name }} / </a>
            <a href="{{ route('employee_salary_adjustments.index', [$staff->id, 'from' => $from]) }}"
                class="text-muted">التعديلات على الراتب </a> /
            <span class="border-bottom border-2 border-primary">تفاصيل التعديل</span>
        </h4>
    </div>

    <x-nav :staff="$staff" />

    <div class="card mb-4">
        <div class="card-body">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">نوع التعديل</label>
                    <div class="form-control" style="border:1px #ab8347 solid">
                        {{ $employeeSalaryAdjustment->type === 'deduction' ? 'خصومات' : 'علاوات' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">نوع القيمة</label>
                    <div class="form-control" style="border:1px #ab8347 solid">
                        {{ $employeeSalaryAdjustment->amount_type === 'fixed' ? 'قيمة معلومة SP' : 'نسبة %' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">السبب</label>
                    <div class="form-control" style="border:1px #ab8347 solid">{{ $employeeSalaryAdjustment->reason }}</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">القيمة</label>
                    <div class="form-control" style="border:1px #ab8347 solid">{{ $employeeSalaryAdjustment->amount }}</div>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ url()->previous() }}" class="btn-staff-outline">رجوع</a>
            </div>
        </div>
    </div>
@endsection