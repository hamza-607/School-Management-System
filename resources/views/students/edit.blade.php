@extends('layouts/layoutMaster')

@section('title', 'تعديل معلومات طالب')

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
    $(document).ready(function() {

        // الصف
        $('#grade_select').on('change', function() {

            if ($(this).val() === 'add_new_grade') {

                $(this).val(null).trigger('change');

                var myModal = new bootstrap.Modal(
                    document.getElementById('gradeModal')
                );

                myModal.show();
            }
        });

        $('#confirmGrade').click(function() {

            let name = $('#grade_name_input').val();

            if (!name) {
                alert('يرجى كتابة اسم الصف');
                return;
            }

            let option = new Option(name, 'NEW', true, true);

            $('#grade_select').append(option).trigger('change');

            $('#new_grade_name').val(name);

            $('#grade_name_input').val('');

            bootstrap.Modal.getInstance(
                document.getElementById('gradeModal')
            ).hide();
        });


        // الشعبة
        $('#section_select').on('change', function() {

            if ($(this).val() === 'add_new_section') {

                $(this).val(null).trigger('change');

                var myModal = new bootstrap.Modal(
                    document.getElementById('sectionModal')
                );

                myModal.show();
            }
        });

        $('#confirmSection').click(function() {

            let name = $('#section_name_input').val();
            let capacity = $('#section_capacity_input').val();

            if (!name) {
                alert('يرجى كتابة اسم الشعبة');
                return;
            }

            let option = new Option(name, 'NEW', true, true);

            $('#section_select').append(option).trigger('change');

            $('#new_section_name').val(name);
            $('#new_section_capacity').val(capacity);

            $('#section_name_input').val('');
            $('#section_capacity_input').val('');

            bootstrap.Modal.getInstance(
                document.getElementById('sectionModal')
            ).hide();
        });

        let allSections = $('#section_select option').clone();

        // فلترة الشعب حسب الصف
        $('#grade_select').on('change', function() {

            let gradeId = $(this).val();

            // تفريغ الشُّعب
            $('#section_select').html('');

            // أول خيار
            $('#section_select').append(
                '<option value="">اختر الشعبة</option>'
            );

            // إذا ما في صف مختار
            if (!gradeId || gradeId === 'add_new_grade') {

                $('#section_select')
                    .val('')
                    .trigger('change.select2');

                return;
            }

            // إضافة خيار إضافة شعبة
            $('#section_select').append(
                '<option value="add_new_section">✚ إضافة شعبة جديدة</option>'
            );

            // عرض الشعب التابعة للصف
            allSections.each(function() {

                let optionValue = $(this).val();
                let sectionGrade = $(this).data('grade');

                if (
                    optionValue !== '' &&
                    optionValue !== 'add_new_section' &&
                    sectionGrade == gradeId
                ) {
                    $('#section_select').append($(this).clone());
                }
            });

            // تحديث select2
            $('#section_select')
                .val('')
                .trigger('change.select2');
        });

        // عند تحميل الصفحة
        let currentGrade = $('#grade_select').val();
        let currentSection = "{{ $theStudent->section_id }}";

        // إذا في صف مختار مسبقاً (صف الطالب)
        if (currentGrade) {

            $('#grade_select').trigger('change');

            // إعادة تحديد شعبة الطالب الحالية
            setTimeout(() => {

                $('#section_select')
                    .val(currentSection)
                    .trigger('change.select2');

            }, 100);

        } else {

            // إذا ما في صف مختار
            $('#section_select').html(
                '<option value="">اختر الشعبة</option>'
            ).trigger('change.select2');
        }
    });
</script>

<script>
    $(function() {
        let parentIndex = 0;

        // دالة موحدة لإضافة كرت ولي أمر
        function addNewParentCard(data = null) {
            // إذا كان هناك ID فهذا يعني أنه ولي أمر موجود مسبقاً في قاعدة البيانات

            let content = `
        <div class="card border shadow-sm mb-3 parent-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ti ti-user me-2"></i>بيانات ولي الامر</h6>
                <button type="button" class="btn btn-sm btn-danger remove-parent"><i class="ti ti-trash"></i></button>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" name="new_parent[new][name]" value="${data ? data.name : ''}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الاسم بالإنكليزي</label>
                        <input type="text" name="new_parent[new][e_name]" value="${data ? (data.e_name || '') : ''}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">صلة القرابة</label>
                        <input type="text" name="new_parent[new][relationship_to_student]" value="${data ? (data.pivot_relationship || data.relationship_to_student || '') : ''}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                        <input type="text" name="new_parent[new][phone]" value="${data ? data.phone : ''}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="new_parent[new][email]" value="${data ? (data.email || '') : ''}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" name="new_parent[new][address]" value="${data ? data.address : ''}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">تاريخ الميلاد</label>
                        <input type="text" name="new_parent[new][date_of_birth]" value="${data ? (data.date_of_birth || '') : ''}" class="form-control flatpickr-parent" placeholder="YYYY-MM-DD">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الجنس</label>
                        <select class="selectpicker w-100" name="new_parent[new][gender]" data-style="btn-default">
                            <option value="male" ${data && data.gender == 'male' ? 'selected' : ''}>ذكر</option>
                            <option value="female" ${data && data.gender == 'female' ? 'selected' : ''}>أنثى</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>`;

            $('#parentsContainer').append(content);

            // تفعيل العناصر الجديدة
            let $newCard = $('#parentsContainer .parent-card').last();
            $newCard.find('.selectpicker').selectpicker();
            $newCard.find('.flatpickr-parent').flatpickr({
                dateFormat: "Y-m-d"
            });

        }

        // زر إضافة ولي أمر جديد يدوياً
        $('#parentAdd').on('click', function() {
            addNewParentCard();
        });

        // حذف الكرت
        $(document).on('click', '.remove-parent', function() {
            $(this).closest('.parent-card').remove();
        });
    });
</script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">الطلاب/</span> تعديل بيانات الطالب {{ $theStudent->name }}
</h4>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('students.update', $theStudent->id) }}" method="POST" enctype="multipart/form-data" id="studentForm">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">الاسم بالعربي <span class="text-danger">*</span></label>
                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ $theStudent->name }}" required>
                    @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">الاسم بالانكليزي</label>
                    <input class="form-control @error('e_name') is-invalid @enderror" type="text" name="e_name" value="{{ $theStudent->e_name ?? null }}">
                    @error('e_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">صورة الطالب</label>
                    <input type="file" class="form-control @error('img') is-invalid @enderror" type="text" name="img" value="{{ $theStudent->picture ?? null }}">
                    @error('img')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- الصف --}}
                <div class="col-md-6">
                    <label class="form-label">الصف <span class="text-danger">*</span></label>
                    <select id="grade_select"
                        name="grade"
                        class="select2 form-select @error('grade') is-invalid @enderror"
                        required>
                        <option value="">اختر الصف</option>
                        <option value="add_new_grade" class="text-primary fw-bold"><span style="color:#006559; font-size:32px;">✚</span> إضافة صف جديد</option>
                        @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}"
                            {{ $theStudent->grade_id == $grade->id ? 'selected' : '' }}>
                            {{ $grade->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('grade')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- الشعبة --}}
                <div class="col-md-6">
                    <label class="form-label">الشعبة</label>

                    <select id="section_select"
                        name="section"
                        class="select2 form-select @error('section') is-invalid @enderror">

                        <option value="">اختر الشعبة</option>

                        <option value="add_new_section" class="text-primary fw-bold">
                            ✚ إضافة شعبة جديدة
                        </option>

                        @foreach ($sections as $section)
                        <option
                            value="{{ $section->id }}"
                            data-grade="{{ $section->grade_id }}"
                            {{ $theStudent->section_id == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                        @endforeach

                    </select>

                    @error('section')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">تاريخ الميلاد <span class="text-danger">*</span></label>
                    <input type="text"
                        name="date_of_birth"
                        id="flatpickr-date"
                        value="{{ $theStudent->date_of_birth }}"
                        class="form-control  @error('date_of_birth') is-invalid @enderror"
                        placeholder="YYYY-MM-DD"
                        required>

                    @error('date_of_birth')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">الجنس<span class="text-danger">*</span></label>
                    <select class="selectpicker w-100 @error('gender') is-invalid @enderror"
                        data-style="btn-default"
                        name="gender">
                        <option value="male" {{ $theStudent->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ $theStudent->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                    @error('gender')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ $theStudent->phoneNumber ?? null }}" class="form-control">
                    @error('phone')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">العنوان<span class="text-danger">*</span></label>
                    <input type="text" name="address" value="{{ $theStudent->address }}" class="form-control" required>
                    @error('address')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">حالة الطالب<span class="text-danger">*</span></label>
                    <select class="selectpicker w-100 @error('is_active') is-invalid @enderror"
                        data-style="btn-default"
                        name="is_active">
                        <option value="1" {{ $theStudent->is_active == 1 ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ $theStudent->is_active == 0 ? 'selected' : '' }}>غير نشط</option>
                    </select>
                    @error('is_active')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="card border-0 bg-label-secondary mb-4">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-semibold">أولياء الأمور</h6>
                                <small class="text-muted">أضف أو عدل بيانات أولياء الأمور المرتبطين بالطالب</small>
                            </div>
                            <button type="button" class="btn btn-primary" id="parentAdd">
                                <i class="ti ti-plus me-1"></i> إضافة ولي أمر جديد
                            </button>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li class="invalid-feedback d-block"> <span class="text-danger">*</span>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
                @endif

                <div id="parentsContainer">
                    @foreach ($theStudent->student_parents as $index => $student_parent)
                    <div class="card border shadow-sm mb-3 parent-card">
                        <input type="hidden" name="old_parent_ids[]" value="{{ $student_parent->guardian->id }}">
                        <input type="hidden" name="new_parent[{{ $index }}][id]" value="{{ $student_parent->guardian->id }}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="ti ti-user me-2"></i>بيانات ولي الامر</h6>
                            <button type="button" class="btn btn-sm btn-danger remove-parent"><i class="ti ti-trash"></i></button>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">الاسم <span class="text-danger">*</span></label>
                                    <input type="text" name="new_parent[{{ $index }}][name]" value="{{ $student_parent->guardian->name }}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">الاسم بالإنكليزي</label>
                                    <input type="text" name="new_parent[{{ $index }}][e_name]" value="{{ $student_parent->guardian->e_name }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">صلة القرابة</label>
                                    <input type="text" name="new_parent[{{ $index }}][relationship_to_student]" value="{{ $student_parent->relationship_to_student }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                    <input type="text" name="new_parent[{{ $index }}][phone]" value="{{ $student_parent->guardian->phone }}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" name="new_parent[{{ $index }}][email]" value="{{ $student_parent->guardian->email }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">العنوان <span class="text-danger">*</span></label>
                                    <input type="text" name="new_parent[{{ $index }}][address]" value="{{ $student_parent->guardian->address }}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">تاريخ الميلاد</label>
                                    <input type="text" name="new_parent[{{ $index }}][date_of_birth]" value="{{ $student_parent->guardian->date_of_birth }}" class="form-control flatpickr-parent" placeholder="YYYY-MM-DD">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">الجنس</label>
                                    <select class="selectpicker w-100" name="new_parent[{{ $index }}][gender]" data-style="btn-default">
                                        <option value="male" {{ $student_parent->guardian->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="female" {{ $student_parent->guardian->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <input type="hidden" name="new_grade_name" id="new_grade_name">
            <input type="hidden" name="new_section_name" id="new_section_name">
            <input type="hidden" name="new_section_capacity" id="new_section_capacity">

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary">حفظ</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">رجوع</a>
            </div>

            {{-- Modal Grade --}}
            <div class="modal fade" id="gradeModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>إضافة صف جديد</h5>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-12">
                                <label class="form-label">اسم الصف</label>
                                <input type="text" id="grade_name_input" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="confirmGrade" class="btn btn-primary">تأكيد</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Section --}}
            <div class="modal fade" id="sectionModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>إضافة شعبة جديدة</h5>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-12">
                                <label class="form-label">اسم الشعبة</label>
                                <input type="text" id="section_name_input" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">السعة</label>
                                <input type="text" id="section_capacity_input" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="confirmSection" class="btn btn-primary">تأكيد</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection