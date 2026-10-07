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
            <span class="d-block fs-6 fw-medium text-muted">إدارة المواد</span>
            <a href="{{ route('subjects.index') }}" class="text-muted">القائمة</a> /
            <span class="border-bottom border-2 border-primary">تعديل مادة {{ $theSubject->name }}</span>
        </h4>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('subjects.update', $theSubject->id) }}" method="POST" enctype="multipart/form-data"
                id="studentForm">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">الاسم بالعربي <span class="text-danger">*</span></label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                            value="{{ $theSubject->name }}" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الاسم بالانكليزي</label>
                        <input class="form-control @error('e_name') is-invalid @enderror" type="text" name="e_name"
                            value="{{ $theSubject->e_name ?? '-' }}">
                        @error('e_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">وصف</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                            rows="3">{{ $theSubject->description ?? '-' }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">حالة المادة<span class="text-danger">*</span></label>
                        <select class="selectpicker w-100 @error('is_active') is-invalid @enderror" data-style="btn-default"
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
                    <button type="submit" class="btn-staff-primary">حفظ</button>
                    <a href="{{ url()->previous() }}" class="btn-staff-outline">رجوع</a>
                </div>
            </form>
        </div>
    </div>
@endsection