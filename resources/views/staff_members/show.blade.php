@extends('layouts.layoutMaster')

@section('title', 'تفاصيل الموظف')
@section('vendor-style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
    <!-- استدعاء مكتبة أيقونات بوتستراب -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <!-- إضافة مكتبة SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'تأكيد الحذف',
                html: `<div>
                                                                                                هل أنت متأكد أنك تريد حذف هذا الموظف؟ 
                                                                                                <br>
                                                                                                <span class="text-danger">
                                                                                                هذه العملية سوف تؤدي إلى حذف جميع الملفات المرتبطة بهذا الموظف
                                                                                                </span>
                                                                                                <br>
                                                                                                <strong>هذه العملية لا يمكن التراجع عنها.</strong>
                                                                                                </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء',
                reverseButtons: true,
                focusCancel: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn-swal-danger',
                    cancelButton: 'btn-swal-cancel',
                    popup: 'swal2-popup-custom'
                },
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                backdrop: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // تنفيذ إرسال النموذج (Form) عند التأكيد
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // دالة التبديل بين التابات (التي كانت موجودة لديك سابقاً)
        function switchTab(el) {
            let tabs = document.querySelectorAll('.tab-btn')
            tabs.forEach(t => t.classList.remove('active'))
            el.classList.add('active')
        }
    </script>
@endsection

@section('content')
    <style>
        :root {
            --ink: #00352e;
            --ink-soft: #3a5350;
            --primary: #006559;
            --primary-dark: #00473f;
            --gold: #ab8347;
            --gold-soft: #e9d9b8;
            --cream: #f5f8f7;
            --line: #dbe6e3;
            --sage: #006559;
            --sage-bg: #e6f1ef;
            --burgundy: #7a3540;
            --burgundy-bg: #f6ecec;
        }

        .swal2-popup-custom {
            border-radius: .4rem;
            font-family: 'Cairo', sans-serif;
        }

        .swal2-container {
            z-index: 20000 !important;
        }

        .btn-swal-danger {
            background: var(--burgundy) !important;
            border: none !important;
            color: #fff !important;
            border-radius: .3rem !important;
            padding: .5rem 1.3rem !important;
            font-weight: 600 !important;
            margin-inline-start: .5rem;
        }

        .btn-swal-cancel {
            background: var(--cream) !important;
            border: 1px solid var(--line) !important;
            color: var(--ink-soft) !important;
            border-radius: .3rem !important;
            padding: .5rem 1.3rem !important;
            font-weight: 600 !important;
        }

        .btn-staff-outline {
            border: 1px solid var(--line);
            background: var(--cream);
            color: var(--ink-soft);
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

        .staff-detail-sub {
            color: #8b8577;
            font-size: 1rem;
        }

        .staff-detail-sub i {
            color: var(--gold);
            margin-inline-end: .35rem;
        }

        .staff-status-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .35rem .85rem;
            border-radius: 1rem;
            font-size: .82rem;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .staff-status-pill .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .staff-status-active {
            background: var(--sage-bg);
            color: var(--sage);
            border-color: #cfe3df;
        }

        .staff-status-active .dot {
            background: var(--sage);
        }

        .staff-status-inactive {
            background: var(--burgundy-bg);
            color: var(--burgundy);
            border-color: #e6d4d4;
        }

        .staff-status-inactive .dot {
            background: var(--burgundy);
        }

        .staff-info-icon {
            width: 50px;
            height: 50px;
            min-width: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--cream);
            border: 1px solid var(--line);
            color: var(--primary);
            font-size: 1.15rem;
        }

        .staff-info-icon.lg {
            width: 56px;
            height: 56px;
            min-width: 56px;
            font-size: 1.3rem;
        }
    </style>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">إدارة الموظفين</span>
            <a href="{{ route('staff_members.index', ['from' => $from]) }}" class="text-muted">القائمة</a> /
            <span class="border-bottom border-2 border-primary">تفاصيل الموظف {{ $theStaff->name }}</span>
        </h4>
    </div>

    <x-nav :staff="$theStaff" />

    <div class="container-fluid px-2 py-4" style="position:relative">

        <!-- Header -->
        <div class="card shadow-sm border-0 mb-4 header-card">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div style="width:160px; border-radius: 50px;  border: 2px solid #8b8577;">
                        <img style="border-radius: 50px; "
                            src="{{ $theStaff->picture ? url(Storage::url($theStaff->picture)) : asset('storage/studentPhoto/def.png') }}"
                            alt="logo" name="logo" width="160">
                    </div>
                    <div>
                        <h5 class="mb-1" style="font-size:xx-large;">{{ $theStaff->name }}</h5>

                        <div class="patient-details">
                            <div class="staff-detail-sub ">
                                <i class="bi bi-person me-1"></i>
                                {{ $theStaff->e_name }}
                            </div>

                            <div class="mt-3 text-muted d-flex align-items-center flex-wrap" style="font-size: 1rem;">
                                <div class="staff-detail-sub ">
                                    <i class="bi bi-telephone me-1"></i>
                                    <span class="me-1">رقم الهاتف:</span>
                                </div>
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $theStaff->phone) }}" target="_blank"
                                    class="text-decoration-none me-2">
                                    {{ $theStaff->phone }}
                                </a>

                                <div class="staff-detail-sub me-1">
                                    <i class="bi bi-record-circle"></i>
                                    <span class="me-1">الحالة :</span>
                                </div>
                                @if($theStaff->is_active == 1)
                                    <div class="staff-status-active staff-status-pill">
                                        ✓ مفعل
                                    </div>
                                @else
                                    <div class="staff-status-inactive staff-status-pill">
                                        ✕ غير مفعل
                                    </div>
                                @endif

                                @if ($from !== 'other')
                                    <div class="staff-detail-sub me-1 ms-2">
                                        <i class="bi bi-person-check-fill"></i>
                                        <span class="me-1">حساب المستخدم:</span>
                                    </div>
                                    @if($theStaff->user) {{-- بفرض وجود علاقة user في موديل Staff --}}
                                        <div class="staff-status-active staff-status-pill">
                                            ✓ لديه حساب بالفعل
                                        </div>
                                    @else
                                        <div class="staff-status-inactive staff-status-pill">
                                            ✕ ليس لديه حساب بعد
                                        </div>
                                    @endif
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
                    <button type="button" class="btn btn-label-danger px-4" onclick="confirmDelete({{ $theStaff->id }})">
                        حذف الموظف
                    </button>

                    <form id="delete-form-{{ $theStaff->id }}"
                        action="{{ route('staff_members.destroy', [$theStaff->id, 'from' => $from]) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    <a href="{{ route('staff_members.edit', [$theStaff->id, 'from' => $from]) }}"
                        class="btn btn-primary px-4">
                        تعديل
                    </a>

                    <a href="{{ url()->previous() }}" class="btn-staff-outline">
                        رجوع
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-md-8">
                <!-- معلومات اضافية -->
                <div class="card shadow-sm border-0 header-card3 mb-4">
                    <div class="card-body">
                        <!-- عنوان القسم مع الأيقونة -->
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-info-circle fs-3 text-primary"></i>
                            <h5 class="mb-0 fw-bold">معلومات إضافية</h5>
                        </div>

                        @php
                            $job = $theStaff->staff_type === 'teacher' ? 'مدرس' : ($theStaff->staff_type === 'admin' ? 'إداري' : $theStaff->type);
                        @endphp
                        <div class="row g-4">
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-mortarboard text-primary fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1">نوع العمل:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            {{ $job }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. تاريخ التسجيل -->
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-calendar-check text-primary  fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1">تاريخ
                                            التسجيل:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            {{ $theStaff->created_at ? $theStaff->created_at->format('Y-m-d') : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-calendar-event text-primary  fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1">تاريخ
                                            الميلاد:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            {{ $theStaff->date_of_birth }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if ($from === 'teacher')
                                <div class="col-6 col-md-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="staff-info-icon">
                                            <i class="bi bi-door-open text-primary  fs-4"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold mb-1">المادة:</span>
                                            <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                                <a href="{{ route('subjects.show', $theStaff->subject->id) }}">
                                                    {{ $theStaff->subject->name ?? '-' }}</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-gender-ambiguous text-primary fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1">الجنس:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            {{ $theStaff->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1">العقد:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            <a href="{{ url(Storage::url($theStaff->contract->contract_file)) }}"
                                                download="{{ $theStaff->contract->contract_file }}">اضغط هنا</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--
                <div class="card shadow-sm border-0 header-card3 mt-4">
                    <div class="card-body">

                        <div class="tab-btn d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-people fs-4"></i>
                            <h5 class="mb-0 fw-bold">الشُعب المسؤول عنهم</h5>
                        </div>

                        <div class="d-flex flex-nowrap overflow-x-auto gap-3 pb-3"
                            style="overflow-x: scroll; scrollbar-width: none;">
                            @foreach ($theGuardian->student_parents as $student_parent)
                            <div class="parent-card border rounded-3 p-3 bg-light-subtle"
                                style="min-width: 300px; flex: 0 0 auto;">
                                <a href="{{ route('students.show', $student_parent->student->id) }}"
                                    class="d-flex align-items-center gap-3 mb-2">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $student_parent->student->name }}</h6>
                                        <small class="text-muted">{{ $student_parent->relationship_to_student }}</small>
                                    </div>
                                </a>
                                <div class="mt-2 pt-2 border-top">
                                    <div>
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $student_parent->student->phoneNumber ) }}"
                                            target="_blank" class="me-3 small mb-1">
                                            <i class="bi bi-whatsapp me-2 text-success"></i>
                                            {{ $student_parent->student->phoneNumber }} </a>
                                    </div>
                                    <div>
                                        <a href="https://mail.google.com/mail/?view=cm&to={{ $student_parent->student->email }}"
                                            target="_blank" class="text-decoration-none me-3 small">
                                            <i class="bi bi-envelope me-2 text-secondary"></i>{{
                                            $student_parent->student->email ?? '-' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>--}}

                <x-files-table :model="$theStaff" mainTitle="ملفات ومرفقات الموظف"
                    secTitle="لا توجد ملفات مرفوعة."></x-files-table>

            </div>


            <div class="col-md-4">
                <div class="card shadow-sm border-0 header-card2">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-telephone-fill fs-3 text-primary"></i>
                            <h5 class="mb-0 fw-bold">معلومات التواصل</h5>
                        </div>

                        <div class="row g-3">

                            <div class="col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-whatsapp text-primary fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1">رقم الهاتف:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $theStaff->phone) }}"
                                                target="_blank" class="text-decoration-none me-3">
                                                {{ $theStaff->phone }}
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-envelope text-primary fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1">الايميل:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            <a href="https://mail.google.com/mail/?view=cm&to={{ $theStaff->email ?? '-' }}"
                                                target="_blank" class="text-decoration-none me-3 small">
                                                {{ $theStaff->email ?? '-' }}
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <script>
            function switchTab(el) {

                let tabs = document.querySelectorAll('.tab-btn')

                tabs.forEach(t => t.classList.remove('active'))

                el.classList.add('active')

            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection