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
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">التعديلات/</span> عرض التعديل على راتب الموظف {{ $staff->name }}
</h4>

<x-nav :staff="$staff" />

<div class="card mb-4">
    <div class="card-body">

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">نوع التعديل</label>
                <div class="form-control">{{ $employeeSalaryAdjustment->type === 'deduction' ? 'خصومات' : 'علاوات' }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">نوع القيمة</label>
                <div class="form-control">{{ $employeeSalaryAdjustment->amount_type === 'fixed' ? 'قيمة معلومة SP' : 'نسبة %' }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">السبب</label>
                <div class="form-control">{{ $employeeSalaryAdjustment->reason }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">القيمة</label>
                <div class="form-control">{{ $employeeSalaryAdjustment->amount }}</div>
            </div>
        </div>

        <div class="mt-4 text-end">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">رجوع</a>
        </div>
    </div>
</div>
@endsection