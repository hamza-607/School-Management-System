@extends('layouts/layoutMaster')

@section('title', 'تعديل المادة')

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

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">المواد/</span> تعديل المادة {{ $theSubject->name }}
</h4>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('subjects.update', $theSubject->id) }}" method="POST" enctype="multipart/form-data" id="studentForm">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">الاسم بالعربي <span class="text-danger">*</span></label>
                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ $theSubject->name }}" required>
                    @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">الاسم بالانكليزي</label>
                    <input class="form-control @error('e_name') is-invalid @enderror" type="text" name="e_name" value="{{ $theSubject->e_name ?? '-' }}">
                    @error('e_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">وصف</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ $theSubject->description ?? '-' }}</textarea>
                    @error('description')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">حالة المادة<span class="text-danger">*</span></label>
                    <select class="selectpicker w-100 @error('is_active') is-invalid @enderror"
                        data-style="btn-default"
                        name="is_active">
                        <option value="1" {{ $theSubject->is_active == 1 ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ $theSubject->is_active == 0 ? 'selected' : '' }}>غير نشط</option>
                    </select>
                    @error('is_active')
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