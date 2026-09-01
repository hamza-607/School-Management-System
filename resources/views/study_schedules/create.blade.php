@extends('layouts/layoutMaster')

@section('title', 'إضافة حصة درسية')

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
{{-- السكربتات الأساسية للقالب --}}
<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
<script src="{{ asset('assets/js/forms-pickers.js') }}"></script>

<script>
</script>
@endsection


@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">البرامج الدراسية / القائمة / برنامج الصف {{ $theSession->grade->name }} - الشعبة {{ $theSession->name }} / </span> إضافة حصة درسية جديدة
</h4>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('studySchedules.store', $theSession->id) }}" method="POST" id="studySessionForm">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">المادة<span class="text-danger">*</span></label>
                    <select class="selectpicker w-100 @error('subject') is-invalid @enderror"
                        data-style="btn-default"
                        name="subject">
                        <option value="">اختر المادة</option>
                        @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    @error('subject')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">المدرس<span class="text-danger">*</span></label>
                    <select class="selectpicker w-100 @error('staff') is-invalid @enderror"
                        data-style="btn-default"
                        name="staff">
                        <option value="">اختر مدرس</option>

                    </select>
                    @error('staff')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary">حفظ</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">رجوع</a>
            </div>

            {{-- Modal إضافة مادة جديدة --}}
            <div class="modal fade" id="subjectModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>إضافة مادة جديدة</h5>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-12">
                                <label class="form-label">الاسم بالعربي<span class="text-danger">*</span></label>
                                <input type="text" id="subject_name_input" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">الاسم بالانكليزي</label>
                                <input type="text" id="subject_e_name_input" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">الوصف</label>
                                <textarea id="subject_description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="confirmsubject" class="btn btn-primary">تأكيد</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
