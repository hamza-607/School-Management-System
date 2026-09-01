@extends('layouts/layoutMaster')

@section('title', 'تعديل صف')

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
    <span class="text-muted fw-light">الصفوف/</span> تعديل الصف {{ $theGreade->name }}
</h4>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('grades.update', $theGreade->id) }}" method="POST" enctype="multipart/form-data" id="studentForm">
            @csrf
            @method('PUT')

            <div class="row g-3 align-items-end"> <!-- أضفنا هذا الكلاس فقط للمحاذاة -->
                <div class="col-md-6">
                    <label class="form-label">اسم الصف<span class="text-danger">*</span></label>
                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ $theGreade->name }}" required>
                    @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 text-end">
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection