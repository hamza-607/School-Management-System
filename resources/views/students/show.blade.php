@extends('layouts.layoutMaster')

@section('title', 'تفاصيل الطالب')
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
                html: `
                        <div>
                            هل أنت متأكد أنك تريد حذف هذا الطالب؟
                            <br>
                            <span class="text-danger">
                            هذه العملية سوف تؤدي إلى حذف جميع الملفات المرتبطة بهذا الطالب
                            </span>
                            <br>
                            <strong>هذه العملية لا يمكن التراجع عنها.</strong>
                        </div>
                    `,
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
            <span class="d-block fs-6 fw-medium text-muted">إدارة الطلاب</span>
            <a href="{{ route('students.index') }}" class="text-muted">القائمة</a> /
            <span class="border-bottom border-2 border-primary"> تفاصيل الطالب {{ $theStudent->name }}</span>
        </h4>
    </div>

    <x-nav :student="$theStudent" />

    <div class="container-fluid px-2 py-4" style="position:relative">

        <!-- Header -->
        <div class="card shadow-sm border-0 mb-4 header-card">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div style="width:160px; border-radius: 50px;  border: 2px solid #8b8577;">
                        <img style="border-radius: 50px; "
                            src="{{ $theStudent->picture ? url(Storage::url($theStudent->picture)) : asset('storage/studentPhoto/def.png') }}"
                            alt="logo" name="logo" width="160">
                    </div>
                    <div>
                        <h5 class="mb-1" style="font-size:xx-large;">{{ $theStudent->name }}</h5>

                        <div class="patient-details">
                            <div class="staff-detail-sub">
                                <i class="bi bi-person me-1"></i>
                                {{ $theStudent->e_name }}
                            </div>
                            <div class="staff-detail-sub">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $theStudent->address }}
                            </div><br>

                            <div class="mt-3 text-muted d-flex align-items-center flex-wrap" style="font-size: 1rem;">
                                <span class="staff-detail-sub">
                                    <i class="bi bi-telephone"></i>
                                    <span class="me-1">رقم الهاتف:</span>
                                </span>

                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $theStudent->phoneNumber) }}"
                                    target="_blank" class="text-decoration-none me-3">
                                    {{ $theStudent->phoneNumber }}
                                </a>

                                <div class="staff-detail-sub me-1">
                                    <i class="bi bi-record-circle"></i>
                                    <span class="me-1">الحالة :</span>
                                </div>
                                @if($theStudent->is_active == 1)
                                    <div class="staff-status-pill staff-status-active">
                                        <span class="dot"></span> مفعّل
                                    </div>
                                @else
                                    <div class="staff-status-pill staff-status-inactive">
                                        <span class="dot"></span>
                                        غير مفعّل
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
                    <button type="button" class="btn btn-label-danger px-4" onclick="confirmDelete({{ $theStudent->id }})">
                        حذف الطالب
                    </button>

                    <form id="delete-form-{{ $theStudent->id }}" action="{{ route('students.destroy', $theStudent->id) }}"
                        method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    <a href="{{ route('students.edit', $theStudent->id) }}" class="btn btn-primary px-4">
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
                <div class="card shadow-sm border-0 header-card3">
                    <div class="card-body">
                        <!-- عنوان القسم مع الأيقونة -->
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-info-circle fs-3 text-primary"></i>
                            <h5 class="mb-0">معلومات إضافية</h5>
                        </div>

                        <div class="row g-4">
                            <!-- 1. الصف -->
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-mortarboard fs-4 text-primary"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1">الصف:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            {{ $theStudent->grade->name }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. تاريخ التسجيل -->
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                                        style="width: 50px; height: 50px; min-width: 50px; background-color: #f1f3f5; color: #6c757d;">
                                        <i class="bi bi-calendar-check fs-4 text-primary"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1">تاريخ
                                            التسجيل:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            {{ $theStudent->created_at ? $theStudent->created_at->format('Y-m-d') : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. تاريخ الميلاد -->
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-calendar-event fs-4 text-primary"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1">تاريخ
                                            الميلاد:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            {{ $theStudent->date_of_birth }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. الشعبة -->
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-door-open fs-4 text-primary"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1">الشعبة:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            <a
                                                href="{{ $theStudent->section ? route('sections.show', $theStudent->section->id) : '#' }}">{{ $theStudent->section->name ?? '-' }}</a>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. الجنس -->
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-gender-ambiguous fs-4 text-primary"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1">الجنس:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            {{ $theStudent->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 header-card3 mt-4 mb-4">
                    <div class="card-body">

                        <div class="tab-btn d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-people fs-4 text-primary"></i>
                            <h5 class="mb-0 fw-bold">أولياء أمور الطالب</h5>
                        </div>

                        <div class="d-flex flex-nowrap overflow-x-auto gap-3 pb-3"
                            style="overflow-x: scroll; scrollbar-width: none;">
                            @if ($theStudent->student_parents->isNotEmpty())
                                @foreach ($theStudent->student_parents as $student_parent)
                                    <div class="parent-card border rounded-3 p-3 bg-light-subtle"
                                        style="min-width: 300px; flex: 0 0 auto;">
                                        <a href="{{ route('guardians.show', $student_parent->guardian->id) }}"
                                            class="d-flex align-items-center gap-3 mb-2" title="ولي الأمر">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle"
                                                style="width: 48px; height: 48px; min-width: 48px; background-color: #e6f0ef  !important; color: #006559 !important;">
                                                <i class="bi bi-person fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $student_parent->guardian->name }}</h6>
                                                <small class="text-muted">{{ $student_parent->relationship_to_student }}</small>
                                            </div>
                                        </a>
                                        <div class="mt-2 pt-2 border-top">
                                            <div>
                                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $student_parent->guardian->phone) }}"
                                                    target="_blank" class="me-3 small mb-1">
                                                    <i class="bi bi-whatsapp me-2 text-success"></i>
                                                    {{ $student_parent->guardian->phone }} </a>
                                            </div>
                                            <div>
                                                <a href="https://mail.google.com/mail/?view=cm&to={{ $student_parent->guardian->email }}"
                                                    target="_blank" class="text-decoration-none me-3 small">
                                                    <i
                                                        class="bi bi-envelope me-2 text-secondary"></i>{{ $student_parent->guardian->email ?? '-' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                    </div>
                </div>

                <x-files-table :model="$theStudent" mainTitle="ملفات ومرفقات الطالب"
                    secTitle="لا توجد ملفات مرفوعة."></x-files-table>

            </div>


            <div class="col-md-4">
                <!-- السجل الأكاديمي -->
                <div class="card shadow-sm border-0 header-card2">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">

                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-journal-text fs-3 text-primary"></i>
                                <h5 class="mb-0 fw-bold">السجل الأكاديمي</h5>
                            </div>

                            <button class="btn-staff-outline d-flex align-items-center px-3">
                                <i class="ti ti-download me-1"></i> تحميل
                            </button>
                        </div>

                        <div class="row g-2" id="enrollmentsList">
                            @foreach ($theStudent->student_enrollments as $index => $enrollment)
                                @php
                                    $result = match ($enrollment->result) {
                                        'passed' => ['label' => 'ناجح', 'class' => 'bg-success'],
                                        'failed' => ['label' => 'راسب', 'class' => 'bg-danger'],
                                        default => ['label' => 'قيد الدراسة', 'class' => 'bg-secondary'],
                                    };
                                @endphp
                                <div class="col-12 {{ $index >= 4 ? 'enrollment-extra d-none' : '' }}">
                                    <div
                                        class="d-flex align-items-center justify-content-between py-1 px-2 border rounded-3 bg-light-subtle">
                                        <div>
                                            <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">الصف:
                                                {{ $enrollment->grade->name }}
                                            </h6>
                                            <small class="text-muted" style="font-size: 0.75rem;">السنة الدراسية:
                                                {{ $enrollment->academic_year->name }}</small>
                                        </div>
                                        <span class="badge rounded-pill {{ $result['class'] }}">
                                            {{ $result['label'] }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @php
                            $enrollmentCount = $theStudent->student_enrollments->count();
                        @endphp

                        @if($enrollmentCount > 4)
                            <div class="text-center mt-2">
                                <button type="button" class="btn btn-sm btn-link text-decoration-none"
                                    id="toggleEnrollmentsBtn">
                                    عرض المزيد {{ '(' . ($enrollmentCount - 4) . ')' }}
                                </button>
                            </div>
                        @endif

                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const btn = document.getElementById('toggleEnrollmentsBtn');
                        const extras = document.querySelectorAll('.enrollment-extra');
                        const extraCount = extras.length;

                        btn.addEventListener('click', function () {
                            const isHidden = extras[0]?.classList.contains('d-none');
                            extras.forEach(el => el.classList.toggle('d-none'));
                            this.textContent = isHidden ? 'عرض أقل' : `عرض المزيد (${extraCount})`;
                        });
                    });
                </script>

                <!-- السجل المالي -->
                <div class="card shadow-sm border-0 header-card2 mt-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">

                            <div class="d-flex align-items-center gap-2">
                                <i class="ti ti-report-money fs-3 text-primary"></i>
                                <h5 class="mb-0 fw-bold">السجل المالي</h5>
                            </div>

                            <button class="btn-staff-outline d-flex align-items-center px-3">
                                <i class="ti ti-download me-1"></i> تحميل
                            </button>
                        </div>

                        <div class="row g-3">
                            <!-- منطقة المحتوى -->
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