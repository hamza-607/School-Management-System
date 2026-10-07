@extends('layouts.layoutMaster')

@section('title', 'تفاصيل ولي الأمر')
@section('vendor-style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
    <!-- استدعاء مكتبة أيقونات بوتستراب -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection

@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'تأكيد الحذف',
                html: `
                                        <div>
                                            هل أنت متأكد أنك تريد حذف ولي الأمر هذا؟
                                            <br>
                                            <span class="text-danger">
                             هذه العملية سوف تؤدي إلى حذف جميع الملفات المرتبطة ب ولي الأمر هذا / لا يمكن حذف ولي الأمر إذا كان مرتبطًا بأي طالب.
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

        .staff-info-value {
            color: var(--ink-soft);
            font-size: .9rem;
        }

        .staff-info-value a {
            color: var(--primary);
            text-decoration: none;
            border-bottom: 1px dashed var(--gold-soft);
        }

        .staff-info-value a:hover {
            color: var(--primary-dark);
            border-color: var(--primary);
        }
    </style>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">إدارة أولياء الأمور</span>
            <a href="{{ route('guardians.index') }}" class="text-muted">القائمة</a> /
            <span class="border-bottom border-2 border-primary">تفاصيل ولي الأمر {{ $theGuardian->name }}</span>
        </h4>
    </div>


    <x-nav :guardian="$theGuardian" />

    <div class="container-fluid px-2 py-4" style="position:relative">

        <div class="card shadow-sm border-0 mb-4 header-card">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                    {{-- <div style="width:160px; border-radius: 50px;  border: 2px solid #000;">
                        <img style="border-radius: 50px; "
                            src="{{ $theGuardian->picture ? url(Storage::url($theGuardian->picture)) : asset('storage/studentPhoto/def.png') }}"
                            alt="logo" name="logo" width="160">
                    </div> --}}
                    <div>
                        <h5 class="mb-1" style="font-size:xx-large;">{{ $theGuardian->name }}</h5>

                        <div class="patient-details">
                            <div class="staff-detail-sub">
                                <i class="bi bi-person me-1"></i>
                                {{ $theGuardian->e_name }}
                            </div>
                            <div class="staff-detail-sub">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $theGuardian->address }}
                            </div><br>

                            <div class="mt-3 text-muted d-flex align-items-center flex-wrap" style="font-size: 1rem;">
                                <div class="staff-detail-sub">
                                    <i class="bi bi-telephone "></i>
                                    <span class="me-1">رقم الهاتف:</span>
                                </div>
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $theGuardian->phone) }}" target="_blank"
                                    class="text-decoration-none me-3">
                                    {{ $theGuardian->phone }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 border-top border-bottom pt-3 pb-3">
                    <button type="button" class="btn btn-label-danger px-4" onclick="confirmDelete({{ $theGuardian->id }})">
                        حذف المادة
                    </button>

                    <form id="delete-form-{{ $theGuardian->id }}"
                        action="{{ route('guardians.destroy', $theGuardian->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    <a href="{{ route('guardians.edit', $theGuardian->id) }}" class="btn btn-primary px-4">
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
                        <h5 class="mb-3">معلومات إضافية</h5>

                        <div class="row g-3">

                            <!-- 3. تاريخ الميلاد -->
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-calendar-event fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1" style="font-size: 0.95rem;">تاريخ
                                            الميلاد:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            {{ $theGuardian->date_of_birth ?? 'غير مسجل' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. الجنس -->
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-gender-ambiguous fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1" style="font-size: 0.95rem;">الجنس:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            {{ $theGuardian->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. عدد الطلاب -->
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon">
                                        <i class="bi bi-people fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1" style="font-size: 0.95rem;">عدد الطلاب المسؤول
                                            عنهم:</span>
                                        <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                            {{ $theGuardian->student_parents->count() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 header-card3 mt-4 mb-4">
                    <div class="card-body">

                        <!-- العنوان -->
                        <div class="tab-btn d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-people fs-4"></i>
                            <h5 class="mb-0 fw-bold">الطلاب المسؤول عنهم</h5>
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
                                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $student_parent->student->phoneNumber) }}"
                                                target="_blank" class="me-3 small mb-1">
                                                <i class="bi bi-whatsapp me-2 text-success"></i>
                                                {{ $student_parent->student->phoneNumber }} </a>
                                        </div>
                                        {{-- <div>
                                            <a href="https://mail.google.com/mail/?view=cm&to={{ $student_parent->student->email }}"
                                                target="_blank" class="text-decoration-none me-3 small">
                                                <i class="bi bi-envelope me-2 text-secondary"></i>{{
                                                $student_parent->student->email ?? '-' }}
                                            </a>
                                        </div> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

                <x-files-table :model="$theGuardian" mainTitle="ملفات ومرفقات ولي الأمر"
                    secTitle="لا توجد ملفات مرفوعة."></x-files-table>

            </div>


            <div class="col-md-4">
                <div class="card shadow-sm border-0 header-card2">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-telephone-fill fs-4 text-primary"></i>
                            <h5 class="mb-0 fw-bold">معلومات التواصل</h5>
                        </div>

                        <div class="row g-3">

                            <div class="col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon lg">
                                        <i class="bi bi-whatsapp fs-4 text-primary"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1" style="font-size: 0.95rem;">رقم الهاتف:</span>
                                        <span class="small">
                                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $theGuardian->phone) }}"
                                                target="_blank" class="text-decoration-none me-3">
                                                {{ $theGuardian->phone }}
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-info-icon lg">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold mb-1" style="font-size: 0.95rem;">الايميل:</span>
                                        <span class="small">
                                            <a href="https://mail.google.com/mail/?view=cm&to={{ $theGuardian->email ?? '-' }}"
                                                target="_blank" class="text-decoration-none me-3 small">
                                                {{ $theGuardian->email ?? '-' }}
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection