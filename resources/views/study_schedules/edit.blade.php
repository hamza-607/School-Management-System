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
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('page-script')
{{-- السكربتات الأساسية للقالب --}}
<script src="{{ asset('assets/js/forms-extras.js') }}"></script>
<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
<script src="{{ asset('assets/js/forms-pickers.js') }}"></script>

<script>
    $(document).ready(function() {
        // المواد + المدرسين من الكونترولر
        const subjectsData = @json($subjects);
        // معرف المدرس الحالي المخزن في قاعدة البيانات لهذه الجلسة
        const currentTeacherId = "{{ $theSession->teacher_id }}";

        // عند اختيار مادة
        $('#subject_select').on('change', function() {
            let subjectId = $(this).val();

            if (subjectId === 'add_new_subject') {
                $(this).val(null).trigger('change');
                var myModal = new bootstrap.Modal(document.getElementById('subjectModal'));
                myModal.show();
                return;
            }

            // 1. تفريغ القائمة
            $('#staff_select').html('<option value="">اختر المدرس</option>');

            // 2. جلب المدرسين المرتبطين
            let selectedSubject = subjectsData.find(s => s.id == subjectId);

            if (selectedSubject && selectedSubject.teachers.length > 0) {
                selectedSubject.teachers.forEach(teacher => {
                    // التحقق برمجياً: إذا كان الـ ID يطابق المدرس المسجل، نضع الخاصية selected
                    let isSelected = (teacher.id == currentTeacherId) ? 'selected' : '';
                    $('#staff_select').append(`<option value="${teacher.id}" ${isSelected}>${teacher.name}</option>`);
                });
            }

            // 3. دائماً أضف خيار "إضافة مدرس" في نهاية القائمة
            $('#staff_select').append('<option value="add_new_teacher" class="text-primary fw-bold">✚ إضافة مدرس جديد</option>');

            // 4. إذا كنت قد أضفت مدرساً "جديداً" للتو (NEW)
            let hiddenName = $('#hidden_staff_name').val();
            if (hiddenName) {
                $('#staff_select').append(new Option(hiddenName, 'NEW', true, true));
            }

            $('#staff_select').trigger('change.select2');
        });

        // --- تشغيل التغيير تلقائياً عند تحميل الصفحة لتعبئة قائمة المدرسين واختيار المدرس الصحيح ---
        if ($('#subject_select').val()) {
            $('#subject_select').trigger('change');
        }

        // عند تأكيد إضافة مادة جديدة
        $('#confirmsubject').click(function() {
            let name = $('#subject_name_input').val().trim();
            let eName = $('#subject_e_name_input').val().trim();
            let desc = $('#subject_description').val().trim();

            if (!name) {
                alert('يرجى كتابة اسم المادة');
                return;
            }

            $('#hidden_subject_name').val(name);
            $('#hidden_subject_e_name').val(eName);
            $('#hidden_subject_description').val(desc);

            let option = new Option(name, 'NEW', true, true);
            $('#subject_select').append(option).trigger('change');

            $('#subject_name_input').val('');
            $('#subject_e_name_input').val('');
            $('#subject_description').val('');

            bootstrap.Modal.getInstance(document.getElementById('subjectModal')).hide();
        });
    });

    // عند اختيار المدرس
    $('#staff_select').on('change', function() {
        if ($(this).val() === 'add_new_teacher') {
            $(this).val(null).trigger('change');
            var myModal = new bootstrap.Modal(document.getElementById('teacherModal'));
            myModal.show();
        }
    });

    // تأكيد إضافة المدرس
    $('#confirmTeacher').click(function() {
        let name = $('#teacher_name_input').val().trim();
        let e_name = $('#teacher_e_name_input').val().trim();
        let phone = $('#teacher_phone_input').val().trim();
        let img = $('#teacher_img_input').val().trim();
        let email = $('#teacher_email_input').val().trim();
        let birth = $('#flatpickr-date').val().trim();
        let gender = $('#teacher_gender_select').val();
        let salary = $('#teacher_salary_input').val().trim();
        let createAccount = $('#createAccountCheckbox').is(':checked') ? 'on' : 'off';
        let password = $('#passwordInput').val().trim();
        let passwordConfirm = $('#confirmPasswordInput').val();
        let contractFile = $('#contract_file_input')[0].files[0];

        if (!name || !phone || !email || !birth || !salary || !contractFile) {
            alert('يرجى ملء كافة الحقول المطلوبة للمدرس');
            return;
        }

        $('#hidden_staff_name').val(name);
        $('#hidden_staff_e_name').val(e_name);
        $('#hidden_staff_phone').val(phone);
        $('#hidden_staff_email').val(email);
        $('#hidden_staff_birth').val(birth);
        $('#hidden_staff_gender').val(gender);
        $('#hidden_staff_salary').val(salary);
        $('#hidden_staff_create_account').val(createAccount);
        $('#hidden_staff_password').val(password);
        $('#hidden_staff_password_confirmation').val(passwordConfirm);

        let option = new Option(name, 'NEW', true, true);
        $('#staff_select').append(option).trigger('change');

        bootstrap.Modal.getInstance(document.getElementById('teacherModal')).hide();
    });

    $(document).ready(function() {
        function toggleAccountFields() {
            if ($('#createAccountCheckbox').is(':checked')) {
                $('#passwordFieldsContainer').slideDown();
                $('#passwordInput').prop('required', true);
                $('#confirmPasswordInput').prop('required', true);
            } else {
                $('#passwordFieldsContainer').slideUp();
                $('#passwordInput').prop('required', false);
                $('#confirmPasswordInput').prop('required', false);
            }
        }

        $('#createAccountCheckbox').on('change', function() {
            toggleAccountFields();
        });

        toggleAccountFields();

        $('#passwordInput, #confirmPasswordInput').on('keyup', function() {
            if (!$('#createAccountCheckbox').is(':checked')) return;
            let password = $('#passwordInput').val();
            let confirmPassword = $('#confirmPasswordInput').val();

            if (password !== confirmPassword && confirmPassword !== '') {
                $('#confirmPasswordInput').addClass('is-invalid');
                $('#passwordError').show();
            } else {
                $('#confirmPasswordInput').removeClass('is-invalid');
                $('#passwordError').hide();
            }
        });

        $('form').on('submit', function(e) {
            if (!$('#createAccountCheckbox').is(':checked')) return;
            let password = $('#passwordInput').val();
            let confirmPassword = $('#confirmPasswordInput').val();

            if (password !== confirmPassword) {
                e.preventDefault();
                $('#confirmPasswordInput').addClass('is-invalid').focus();
                $('#passwordError').show();
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.time-mask').forEach(function(el) {
            new Cleave(el, {
                time: true,
                timePattern: ['h', 'm', 's']
            });
        });
    });
</script>
@endsection


@section('content')

<style>
    /* تنحيف شريط السكرول */
    ::-webkit-scrollbar {
        width: 6px;
        /* عرض الشريط */
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        /* خلفية المسار */
    }

    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        /* لون المقبض */
        border-radius: 10px;
        /* تنعيم الشكل */
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
        /* لون عند المرور */
    }
</style>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible" role="alert">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">البرامج الدراسية / القائمة / برنامج الصف {{ $theSection->grade->name }} - الشعبة {{ $theSection->name }} / </span> تعديل معلومات حصة  {{ $theSession->subject->name }} 
</h4>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('studySchedules.update',[$theSession->id, $theSection->id]) }}" method="POST" id="studySessionForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">المادة<span class="text-danger">*</span></label>
                    <select id="subject_select"
                        name="subject"
                        class="select2 form-select @error('subject') is-invalid @enderror">
                        <option value="">اختر المادة</option>
                        <option value="add_new_subject" class="text-primary fw-bold">✚ إضافة مادة جديدة</option>

                        @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ $theSession->subject_id == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                        @endforeach
                    </select>

                    @error('subject')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-6">
                    <label class="form-label">المدرس<span class="text-danger">*</span></label>
                    <select id="staff_select"
                        name="staff"
                        class="select2 form-select @error('staff') is-invalid @enderror">
                        <option value="">اختر المادة أولاً</option>
                        <option value="add_new_teacher" class="text-primary fw-bold">✚ إضافة مدرس جديد</option>
                    </select>
                    @error('staff')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-6">
                    <label class="form-label">اليوم<span class="text-danger">*</span></label>
                    <select name="day" class="select2 form-select @error('day') is-invalid @enderror" required>
                        <option value="">اختر اليوم</option>
                        <option value="sunday" {{ $theSession->appointment->day === 'sunday' ? 'selected' : ''   }}>الأحد</option>
                        <option value="monday" {{ $theSession->appointment->day === 'monday' ? 'selected' : '' }}>الاثنين</option>
                        <option value="tuesday" {{ $theSession->appointment->day === 'tuesday' ? 'selected' : '' }}>الثلاثاء</option>
                        <option value="wednesday" {{ $theSession->appointment->day === 'wednesday' ? 'selected' : '' }}>الأربعاء</option>
                        <option value="thursday" {{ $theSession->appointment->day === 'thursday' ? 'selected' : '' }}>الخميس</option>
                        <option value="friday" {{ $theSession->appointment->day === 'friday' ? 'selected' : '' }}>الجمعة</option>
                        <option value="saturday" {{ $theSession->appointment->day === 'saturday' ? 'selected' : '' }}>السبت</option>
                    </select>
                    @error('day')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">وقت البدء<span class="text-danger">*</span></label>
                    <input type="text" name="start_time" class="form-control time-mask" placeholder="23:59:59" required value="{{ $theSession->appointment->start_time }}">
                    @error('staff')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">وقت الانتهاء<span class="text-danger">*</span></label>
                    <input type="text" name="end_time" class="form-control time-mask" placeholder="23:59:59" required value="{{ $theSession->appointment->end_time }}">
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
                        <div class="modal-header border-bottom">
                            <h5>إضافة مادة جديدة</h5>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-12">
                                <label class="form-label">الاسم بالعربي<span class="text-danger">*</span></label>
                                <input type="text" id="subject_name_input" name="new_subject_name" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">الاسم بالانكليزي</label>
                                <input type="text" id="subject_e_name_input" name="new_subject_e_name" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">الوصف</label>
                                <textarea id="subject_description" name="new_subject_description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="confirmsubject" class="btn btn-primary">تأكيد</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal إضافة مدرس جديد --}}
            <div class="modal fade" id="teacherModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header border-bottom">
                            <h5>إضافة مدرس جديد</h5>
                        </div>
                        <div class="modal-body row g-3 overflow-auto" style="max-height: 75vh;">
                            <div class="col-md-12">
                                <label class="form-label">الاسم بالعربي <span class="text-danger">*</span></label>
                                <input class="form-control" id="teacher_name_input" type="text">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">الاسم بالانكليزي</label>
                                <input class="form-control" id="teacher_e_name_input" type="text">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">صورة الموظف</label>
                                <input type="file" id="teacher_img_input" name="new_staff_img" class="form-control">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">رقم الهاتف<span class="text-danger">*</span></label>
                                <input type="text" id="teacher_phone_input" class="form-control">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">إيميل<span class="text-danger">*</span></label>
                                <input type="email" id="teacher_email_input" class="form-control">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">تاريخ الميلاد <span class="text-danger">*</span></label>
                                <input type="text" id="flatpickr-date" value="{{ old('new_staff_date_of_birth') }}" class="form-control" placeholder="YYYY-MM-DD">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">الجنس <span class="text-danger">*</span></label>
                                <select class="selectpicker w-100" data-style="btn-default" id="teacher_gender_select">
                                    <option value="male" {{ old('new_staff_gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                    <option value="female" {{ old('new_staff_gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">صورة عقد العمل<span class="text-danger">*</span></label>
                                <input type="file" id="contract_file_input" name="new_staff_contract_file" class="form-control">
                            </div>

                            <div class="col-12">
                                <label class="form-label">الراتب<span class="text-danger">*</span></label>
                                <input class="form-control" id="teacher_salary_input" type="number" step="any" placeholder="0.0SP/%">
                            </div>

                            <div class="col-12 mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="createAccountCheckbox" value="off">
                                    <label class="form-check-label fw-bold" for="createAccountCheckbox">
                                        هل تريد إنشاء حساب لهذا الموظف؟
                                    </label>
                                </div>
                            </div>

                            <div class="col-12" id="passwordFieldsContainer" style="display: none;">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">كلمة السر <span class="text-danger">*</span></label>
                                        <input type="password" id="passwordInput" class="form-control" placeholder="············">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">إعادة كلمة السر <span class="text-danger">*</span></label>
                                        <input type="password" id="confirmPasswordInput" class="form-control" placeholder="············">
                                        <div id="passwordError" class="invalid-feedback">كلمتا السر غير متطابقتين!</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="confirmTeacher" class="btn btn-primary">تأكيد</button>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" id="hidden_staff_name" name="new_staff_name">
            <input type="hidden" id="hidden_staff_e_name" name="new_staff_e_name">
            <input type="hidden" id="hidden_staff_phone" name="new_staff_phone">
            <input type="hidden" id="hidden_staff_email" name="new_staff_email">
            <input type="hidden" id="hidden_staff_birth" name="new_staff_date_of_birth">
            <input type="hidden" id="hidden_staff_gender" name="new_staff_gender">
            <input type="hidden" id="hidden_staff_salary" name="new_staff_salary">
            <input type="hidden" id="hidden_staff_create_account" name="new_staff_create_account">
            <input type="hidden" id="hidden_staff_password" name="new_staff_password">
            <input type="hidden" id="hidden_staff_password_confirmation" name="new_staff_password_confirmation">

            <input type="hidden" id="hidden_subject_name" name="new_subject_name">
            <input type="hidden" id="hidden_subject_e_name" name="new_subject_e_name">
            <input type="hidden" id="hidden_subject_description" name="new_subject_description">

        </form>
    </div>
</div>
@endsection