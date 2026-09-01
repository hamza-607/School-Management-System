@extends('layouts/layoutMaster')

@section('title', 'إضافة تعديل')

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
    <span class="text-muted fw-light">التعديلات/</span> إضافة تعديل جديد على راتب الموظف {{ $staff->name }}
</h4>

<x-nav :staff="$staff" />

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('employee_salary_adjustments.store', [$staff->id,'from' => $from]) }}" method="POST">
            @csrf

            <div class="row g-3">
                {{-- نوع العقد أصبح Select --}}
                <div class="col-md-6">
                    <label class="form-label">نوع التعديل<span class="text-danger">*</span></label>
                    <select class="selectpicker w-100 @error('type') is-invalid @enderror"
                        data-style="btn-default"
                        name="type"
                        required>
                        <option value="" disabled selected>اختر نوع التعديل</option>
                        <option value="deduction" {{ old('type') == 'deduction' ? 'selected' : '' }}>خصومات</option>
                        <option value="allowance" {{ old('type') == 'allowance' ? 'selected' : '' }}>علاوات</option>
                    </select>
                    @error('type')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">نوع القيمة<span class="text-danger">*</span></label>
                    <select class="selectpicker w-100 @error('amount_type') is-invalid @enderror"
                        data-style="btn-default"
                        name="amount_type"
                        required>
                        <option value="" disabled selected>اختر نوع القيمة</option>
                        <option value="fixed" {{ old('amount_type') == 'fixed' ? 'selected' : '' }}>قيمة معلومة SP</option>
                        <option value="percentage" {{ old('amount_type') == 'percentage' ? 'selected' : '' }}>نسبة %</option>
                    </select>
                    @error('amount_type')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">السبب</label>
                    <textarea class="form-control @error('reason') is-invalid @enderror" name="reason" rows="3">{{ old('reason') }}</textarea>
                    @error('reason')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">القيمة<span class="text-danger">*</span></label>
                    <input class="form-control @error('amount') is-invalid @enderror" type="number" step="any" name="amount" value="{{ old('amount') }}" placeholder="0.0SP/%">
                    @error('amount')
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