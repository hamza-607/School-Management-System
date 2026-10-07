@extends('layouts/layoutMaster')

@section('title', 'تعديل موظف')

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
    <script src="{{ asset('assets/js/forms-selects.js') }}"></script>
    <script src="{{ asset('assets/js/forms-pickers.js') }}"></script>

    <script>
        $(document).ready(function () {

            // دالة التحكم بظهور الحقول بناءً على نوع العمل
            function toggleStaffFields() {
                let selectedValue = $('#staffTypeSelect').val();

                // 1. التعامل مع حقل "أخرى"
                if (selectedValue === 'other') {
                    $('#customStaffTypeContainer').slideDown();
                    $('#customStaffTypeInput').attr('required', true);
                } else {
                    $('#customStaffTypeContainer').slideUp();
                    $('#customStaffTypeInput').attr('required', false);
                }

                // 2. التعامل مع حقل "المادة" (يظهر فقط للمعلم)
                if (selectedValue === 'teacher') {
                    $('#subjectContainer').slideDown();
                    $('#subject_select').attr('required', true);
                } else {
                    $('#subjectContainer').slideUp();
                    $('#subject_select').attr('required', false);
                }
            }
            // تشغيل الدوال عند التغيير
            $('#staffTypeSelect').on('change', function () {
                toggleStaffFields();
            });

            $('#createAccountCheckbox').on('change', function () {
                toggleAccountFields();
            });

            // تشغيل الدوال عند تحميل الصفحة
            toggleStaffFields();
            toggleAccountFields();


            // سكربت إضافة مادة/صف جديد (المودال)
            $('#subject_select').on('change', function () {
                if ($(this).val() === 'add_new_subject') {
                    $(this).val(null).trigger('change');
                    var myModal = new bootstrap.Modal(document.getElementById('subjectModal'));
                    myModal.show();
                }
            });

            $('#confirmsubject').click(function () {
                let name = $('#subject_name_input').val();
                let e_name = $('#subject_e_name_input').val();
                let description = $('#subject_description').val();

                if (!name) {
                    alert('يرجى كتابة اسم المادة');
                    return;
                }
                let option = new Option(name, 'NEW', true, true);
                $('#subject_select').append(option).trigger('change');
                $('#new_subject_name').val(name);
                $('#new_subject_e_name').val(e_name);
                $('#new_subject_description').val(description);
                $('#subject_name_input').val('');
                bootstrap.Modal.getInstance(document.getElementById('subjectModal')).hide();
            });
        });
    </script>

    <script>
        $(document).ready(function () {


            // تشغيل الدوال الأصلية
            $('#staffTypeSelect').on('change', function () {
                toggleStaffFields();
            });

            toggleStaffFields();
            toggleAccountFields();

            // ... سكربت المودال (subject_select) ...
        });
    </script>
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
            <span class="d-block fs-6 fw-medium text-muted">إدارة الموظفين</span>
            <a href="{{ route('staff_members.index', ['from' => $from]) }}" class="text-muted">القائمة</a> /
            <span class="border-bottom border-2 border-primary">تعديل معلومات الموظف {{ $theStaff->name }}</span>
        </h4>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('staff_members.update', [$theStaff->id, 'from' => $from]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- الحقول الأساسية --}}
                    <div class="col-md-6">
                        <label class="form-label">الاسم بالعربي <span class="text-danger">*</span></label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                            value="{{ $theStaff->name }}" required>
                        @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الاسم بالانكليزي</label>
                        <input class="form-control @error('e_name') is-invalid @enderror" type="text" name="e_name"
                            value="{{ $theStaff->e_name }}">
                        @error('e_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">صورة الموظف <small class="text-muted">اتركه فارغاً للحفاظ على الصورة
                                الحالية</small></label>
                        <input type="file" class="form-control @error('img') is-invalid @enderror" name="img"
                            value="{{ $theStaff->picture }}">
                        @error('img')
                        <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">رقم الهاتف<span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                            value="{{ $theStaff->phone }}" required>
                        @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">إيميل<span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ $theStaff->email }}" required>
                        @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">تاريخ الميلاد <span class="text-danger">*</span></label>
                        <input type="text" name="date_of_birth" id="flatpickr-date" value="{{ $theStaff->date_of_birth }}"
                            class="form-control @error('date_of_birth') is-invalid @enderror" placeholder="YYYY-MM-DD"
                            required>
                        @error('date_of_birth')
                        <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الجنس <span class="text-danger">*</span></label>
                        <select class="selectpicker w-100 @error('gender') is-invalid @enderror" data-style="btn-default"
                            name="gender" required>
                            <option value="male" {{ $theStaff->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                            <option value="female" {{ $theStaff->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                        </select>
                        @error('gender')
                        <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">

                        <label class="form-label">نوع العمل <span class="text-danger">*</span></label>
                        <select id="staffTypeSelect" name="staff_type"
                            class="selectpicker w-100 @error('staff_type') is-invalid @enderror" data-style="btn-default"
                            required>
                            <option value="" disabled selected>اختر النوع...</option>
                            <option value="teacher" {{ $theStaff->staff_type == 'teacher' ? 'selected' : '' }}>معلم</option>
                            <option value="admin" {{ $theStaff->staff_type == 'admin' ? 'selected' : '' }}>إداري</option>
                            <option value="other" {{ $theStaff->staff_type != 'admin' && $theStaff->staff_type != 'teacher' ? 'selected' : '' }}>أخرى</option>
                        </select>
                        @error('staff_type')
                        <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6" id="customStaffTypeContainer" style="display: none;">
                        <label class="form-label">اكتب نوع العمل الجديد <span class="text-danger">*</span></label>
                        <input type="text" id="customStaffTypeInput" name="new_staff_type"
                            value="{{ $theStaff->staff_type }}"
                            class="form-control @error('new_staff_type') is-invalid @enderror"
                            placeholder="مثلاً: محاسب، مستخدم...">
                        @error('new_staff_type')
                        <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6" id="subjectContainer" style="display: none;">
                        <label class="form-label">المادة المسؤول عنها <span class="text-danger">*</span></label>
                        <select id="subject_select" name="subject"
                            class="select2 form-select @error('subject') is-invalid @enderror">
                            <option value="">اختر المادة</option>
                            <option value="add_new_subject" class="text-primary fw-bold">✚ إضافة مادة جديدة</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $theStaff->subject_id == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @error('subject')
                        <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">صورة عقد العمل<span class="text-danger">*</span> <small
                                class="text-muted">اتركه فارغاً للحفاظ على الملف الحالي</small></label>
                        <input type="file" class="form-control @error('contract_file') is-invalid @enderror"
                            name="contract_file" value="{{ $theStaff->contract->contract_file ?? '' }}">
                        @error('contract_file')
                        <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الراتب<span class="text-danger">*</span></label>
                        <input class="form-control @error('salary') is-invalid @enderror" type="number" step="any"
                            name="salary" value="{{ $theStaff->contract->salary ?? null }}" placeholder="0.0SP/%">
                        @error('salary')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>


                    @if ($from !== 'other')
                        @if ($theStaff->user)
                            <label class="form-check-label fw-bold p-4" style="border-top: 2px solid #dee2e6; border-bottom: 2px solid #dee2e6;" for="createAccountCheckbox">
                                ✓ لديه حساب بالفعل
                            </label>
                        @endif

                        @if (!$theStaff->user)
                            <div class="col-12 mt-3 p-4" style="border-top: 2px solid #dee2e6; border-bottom: 2px solid #dee2e6;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="createAccountCheckbox" name="create_account"
                                        {{ $theStaff->user_id ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="createAccountCheckbox">
                                        هل تريد إنشاء حساب لهذا الموظف؟
                                    </label>
                                </div>
                            </div>
                        @endif
                    @endif

                </div>

                <input type="hidden" name="new_subject_name" id="new_subject_name">
                <input type="hidden" name="new_subject_e_name" id="new_subject_e_name">
                <input type="hidden" name="new_subject_description" id="new_subject_description">


                <div class="mt-4 text-end">
                    <button type="submit" class="btn-staff-primary">حفظ</button>
                    <a href="{{ url()->previous() }}" class="btn-staff-outline">رجوع</a>
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