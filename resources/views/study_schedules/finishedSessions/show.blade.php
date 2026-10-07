@extends('layouts.layoutMaster')

@section('title', 'تفاصيل الحصة الدرسية')
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
    <style>
        .swal2-container {
            z-index: 20000 !important;
        }

        .swal2-popup-custom {
            border-radius: 0.5rem;
        }
    </style>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'تأكيد الحذف',
                html: `
                        <div>
                            هل أنت متأكد أنك تريد حذف هذا الطالب؟
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
                    confirmButton: 'btn btn-danger ms-2',
                    cancelButton: 'btn btn-secondary',
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
            --gold: #ab8347;
            --cream: #f5f8f7;
            --line: #dbe6e3;
        }

        .swal2-popup-custom {
            border-radius: .4rem;
            font-family: 'Cairo', sans-serif;
        }

        .btn-swal-danger {
            background: #7a3540 !important;
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

        .staff-info-icon {
            width: 75px;
            height: 75px;
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

        .staff-detail-sub {
            color: #8b8577;
            font-size: 1rem;
        }

        .staff-detail-sub i {
            color: var(--gold);
            margin-inline-end: .35rem;
        }

        .btn-staff-outline {
            border: 1px solid var(--line);
            background: var(--cream);
            color: var(--ink-soft);
            border-radius: .3rem;
            font-weight: 600;
            font-size: .9rem;
            padding: .55rem 1.1rem;
            transition: border-color .15s ease, color .15s ease;
        }

        .btn-staff-outline:hover {
            border-color: var(--primary);
            color: var(--ink);
        }
    </style>

   <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">سجل الحصص الدرسية</span>
            <a href="{{ route('finishedSessions.index') }}" class="text-muted">القائمة / </a>
            <span class="border-bottom border-2 border-primary">عرض سجل حصة {{ $theSession->subject->name }}</span>
        </h4>
    </div>

    <div class="container-fluid px-2 py-4" style="position:relative">

        <!-- Header -->
        <div class="card shadow-sm border-0 mb-4 header-card">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="staff-info-icon">
                        <i class="bi bi-journal-bookmark fs-2 text-primary"></i>
                    </div>
                    <div>
                        @php
                            $daysMap = [
                                'sunday' => 'الأحد',
                                'monday' => 'الاثنين',
                                'tuesday' => 'الثلاثاء',
                                'wednesday' => 'الأربعاء',
                                'thursday' => 'الخميس',
                                'friday' => 'الجمعة',
                                'saturday' => 'السبت'
                            ];

                            $sessionType = $theSession->session_type === 'regular' ? 'اساسية' : ($theSession->session_type === 'final' ? 'امتحان نهائي' : ($theSession->session_type === 'quiz' ? 'اختبار' : 'تعويضية'));
                        @endphp
                        <h5 class="mb-1" style="font-size:xx-large;">{{ $theSession->subject->name }}
                            <span class="text-center align-middle fs-6">
                                <span class="badge bg-label-secondary text-dark">{{ $sessionType }}</span>
                            </span>
                        </h5>

                        <div class="patient-details">
                            <div class="staff-detail-sub">
                                <i class="bi bi-mortarboard me-1"></i>
                                الصف {{ $theSession->grade->name }}
                                -
                                شعبة <a
                                    href="{{ $theSession->section ? route('sections.show', $theSession->section->id) : '#' }}">{{ $theSession->section->name ?? 'غير محددة'}}</a>
                            </div>
                            <div class="staff-detail-sub">
                                <i class="bi bi-calendar-week me-2"></i>
                                <span class="me-1">يوم الحصة:</span>
                                <span
                                    class="badge bg-label-secondary text-dark me-3">{{ $daysMap[$theSession->appointment->day] ?? $theSession->appointment->day }}
                                    - {{ $theSession->created_at->format('d/m/Y') }}</span>
                            </div><br>
                            <div class="staff-detail-sub">
                                <i class="bi bi-clock-history me-2"></i>
                                <span class="me-1">التوقيت:</span>
                                <span class="me-3">
                                    <span class="badge bg-label-secondary text-dark">
                                        {{ $theSession->appointment->start_time ? \Carbon\Carbon::parse($theSession->appointment->start_time)->format('h:i A') : '-' }}
                                    </span>
                                    <span class="mx-1">-</span>
                                    <span class="badge bg-label-secondary text-dark">
                                        {{ $theSession->appointment->start_time ? \Carbon\Carbon::parse($theSession->appointment->end_time)->format('h:i A') : '-' }}
                                    </span>
                                </span>
                            </div>
                            <div class="staff-detail-sub">
                                <i class="bi bi-clock-history me-2"></i>
                                <span class="me-1">التوقيت الفعلي:</span>
                                <span class="me-3">
                                    <span class="badge bg-label-secondary text-dark">
                                        {{ $theSession->actual_start_time ? \Carbon\Carbon::parse($theSession->actual_start_time)->format('h:i A') : '-' }}
                                    </span>
                                    <span class="mx-1">-</span>
                                    <span class="badge bg-label-secondary text-dark">
                                        {{ $theSession->actual_end_time ? \Carbon\Carbon::parse($theSession->actual_end_time)->format('h:i A') : '-' }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
                    <button type="button" class="btn btn-label-danger px-4" onclick="confirmDelete({{ $theSession->id }})">
                        حذف الحصة
                    </button>

                    <form id="delete-form-{{ $theSession->id }}"
                        action="{{ route('finishedSessions.destroy', $theSession->id)  }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    <a href="{{ url()->previous() }}" class="btn-staff-outline">
                        رجوع
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card shadow-sm border-0 header-card2">

                    <div class="card-body">

                        <div class="tab-btn d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-person-video3 fs-4 text-primary"></i>
                            <h5 class="mb-0 fw-bold">الاستاذ المسؤول عن الحصة</h5>
                        </div>

                        <div class="pb-3">
                            <div class="parent-card border rounded-3 p-3 bg-light-subtle"
                                style="max-width: 860px; flex: 0 0 auto;">
                                <a href="{{ route('staff_members.show', [$theSession->staff->id, 'from' => $theSession->staff->staff_type]) }}"
                                    class="d-flex align-items-center gap-3 mb-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                                        style="width: 48px; height: 48px; min-width: 48px; background-color: #e6f0ef  !important; color: #006559 !important;">
                                        <i class="bi bi-person fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $theSession->staff->name }}</h6>
                                        <small class="text-muted">أستاذ مادة {{ $theSession->subject->name }}</small>
                                    </div>
                                </a>
                                <div class="mt-2 pt-2 border-top">
                                    <div>
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $theSession->staff->phone) }}"
                                            target="_blank" class="me-3 small mb-1">
                                            <i class="bi bi-whatsapp me-2 text-success"></i>
                                            {{ $theSession->staff->phone }} </a>
                                    </div>
                                    <div>
                                        <a href="https://mail.google.com/mail/?view=cm&to={{ $theSession->staff->email }}"
                                            target="_blank" class="text-decoration-none me-3 small">
                                            <i
                                                class="bi bi-envelope me-2 text-secondary"></i>{{ $theSession->staff->email ?? '-' }}
                                        </a>
                                    </div>
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