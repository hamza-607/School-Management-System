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
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">الطلاب / القائمة / تفاصيل الطالب {{ $theStudent->name }} / العقوبات / </span> تعديل عقوبة ال {{ $thepenalty->penalty_type }}
</h4>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('penalties.update', [$thepenalty->id ,$theStudent->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="col-md-6">
                <label class="form-label">نوع العقوبة<span class="text-danger">*</span></label>
                <input class="form-control @error('penalty_type') is-invalid @enderror" type="text" name="penalty_type" value="{{ $thepenalty->penalty_type }}" required>
                @error('penalty_type')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">سبب العقوبة<span class="text-danger">*</span></label>
                    <textarea class="form-control @error('reason') is-invalid @enderror" name="reason" rows="3">{{ $thepenalty->reason }}</textarea>
                    @error('reason')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">ملاحظات</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ $thepenalty->notes }}</textarea>
                    @error('notes')
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