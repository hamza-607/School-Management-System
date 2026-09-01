@extends('layouts/layoutMaster')

@section('title', 'إضافة شعب')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

{{-- إضافة قسم السكربتات لتضمين المكتبات اللازمة --}}
@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
<script src="{{ asset('assets/js/forms-pickers.js') }}"></script>

<script>
    $(document).ready(function() {
        // السكربت الخاص بفتح المودال عند اختيار "إضافة صف جديد"
        $('#grade_select').on('change', function() {
            if ($(this).val() === 'add_new_grade') {
                // إرجاع القيمة فارغة مؤقتاً حتى لا يظل الخيار "إضافة صف" مختاراً
                $(this).val(null).trigger('change');

                var myModal = new bootstrap.Modal(
                    document.getElementById('gradeModal')
                );
                myModal.show();
            }
        });

        // السكربت الخاص بتأكيد إضافة الصف الجديد داخل المودال
        $('#confirmGrade').click(function() {
            let name = $('#grade_name_input').val();

            if (!name) {
                alert('يرجى كتابة اسم الصف');
                return;
            }

            // إنشاء خيار جديد في السيلكت
            let option = new Option(name, 'NEW', true, true);
            $('#grade_select').append(option).trigger('change');

            // وضع الاسم في الحقل المخفي ليتم إرساله مع الفورم
            $('#new_grade_name').val(name);

            // تفريغ المدخل وإغلاق المودال
            $('#grade_name_input').val('');
            bootstrap.Modal.getInstance(
                document.getElementById('gradeModal')
            ).hide();
        });
    });
</script>
@endsection


@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">الشُعب/</span> تعديل معلومات الشعبة {{ $theSection->name }}
</h4>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('sections.update', $theSection->id) }}" method="POST" enctype="multipart/form-data" id="studentForm">
            @csrf
            @method('PUT')

            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">الأسم<span class="text-danger">*</span></label>
                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ $theSection->name }}" required>
                    @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">الصف <span class="text-danger">*</span></label>
                    <select id="grade_select"
                        name="grade"
                        class="select2 form-select @error('grade') is-invalid @enderror"
                        required>
                        <option value="">اختر الصف</option>
                        <option value="add_new_grade" class="text-primary fw-bold">✚ إضافة صف جديد</option>
                        @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}" {{ $theSection->grade->id == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                        @endforeach
                    </select>
                    @error('grade')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">السعة</label>
                    <input class="form-control @error('capacity') is-invalid @enderror" type="text" name="capacity" value="{{ $theSection->capacity }}">
                    @error('capacity')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- الحقل المخفي لاستقبال اسم الصف الجديد --}}
                <input type="hidden" name="new_grade_name" id="new_grade_name">

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">رجوع</a>
                </div>

                {{-- Modal Grade --}}
                <div class="modal fade" id="gradeModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header border-bottom">
                                <h5 class="modal-title">إضافة صف جديد</h5>
                            </div>
                            <div class="modal-body row g-3">
                                <div class="col-12">
                                    <label class="form-label">اسم الصف</label>
                                    <input type="text" id="grade_name_input" class="form-control" placeholder="أدخل اسم الصف">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="button" id="confirmGrade" class="btn btn-primary">تأكيد</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection