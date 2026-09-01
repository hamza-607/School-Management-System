@extends('layouts/layoutMaster')

@section('title', 'إضافة مادة')

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
    <span class="text-muted fw-light">المواد/</span> إضافة مادة جديدة
</h4>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('subjects.store') }}" method="POST" enctype="multipart/form-data" id="studentForm">
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
                    <label class="form-label">وصف</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
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