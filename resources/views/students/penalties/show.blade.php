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


    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'تأكيد الحذف',
                html: `
                        <div>
                            هل أنت متأكد أنك تريد حذف عقوبة هذا الطالب؟
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

        .theme-status-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .35rem .85rem;
            border-radius: 1rem;
            font-size: .82rem;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .theme-status-pill .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .theme-status-active {
            background: #e6f1ef;
            color: #006559;
            border-color: #cfe3df;
        }

        .theme-status-active .dot {
            background: #006559;
        }

        .theme-status-inactive {
            background: #f6ecec;
            color: #7a3540;
            border-color: #e6d4d4;
        }

        .theme-status-inactive .dot {
            background: #7a3540;
        }

        .theme-status-pending {
            background: #f3ead6;
            color: #8a6530;
            border-color: #e6dab8;
        }

        .theme-status-pending .dot {
            background: #8a6530;
        }
    </style>
    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">إدارة الطلاب</span>
            <a href="{{ route('students.index') }}" class="text-muted">القائمة / </a>
            <a href="{{ route('students.show', $theStudent->id) }}" class="text-muted">تفاصيل الطالب
                {{ $theStudent->name }} / </a>
            <a href="{{ route('penalties.index', $theStudent->id) }}" class="text-muted">العقوبات</a> /

            <span class="border-bottom border-2 border-primary"> تفاصيل عقوبة ال {{ $thepenalty->penalty_type }}</span>
        </h4>
    </div>

    <x-nav :student="$theStudent" />

    <div class="container-fluid px-2 py-4" style="position:relative">

        <!-- Header -->
        <div class="card shadow-sm border-0 mb-4 header-card">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                        style="width: 70px; height: 70px; min-width: 70px; background-color: #f1f3f5; color: #696cff;">
                        <i class="ti-xs ti ti-file-alert me-1 fs-1 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="mb-1" style="font-size:xx-large;"> نوع العقوبة : {{ $thepenalty->penalty_type }}</h5>

                        <div class="patient-details">
                            <div class="staff-detail-sub ">
                                <i class="bi bi-mortarboard me-1"></i>
                                السبب {{ $thepenalty->reason}}
                            </div>
                            <div class="staff-detail-sub ">
                                <i class="bi bi-person-badge me-1"></i>
                                أصدرت من قبل:
                                <a
                                    href="{{ route('staff_members.show', [$thepenalty->user->staff->id, 'from' => $thepenalty->user->staff->staff_type]) }}">{{ $thepenalty->user->name ?? '-'}}</a>
                            </div>
                            <div class="staff-detail-sub">
                                <i class="bi bi-person-badge me-1"></i>
                                عدلت من قبل:
                                @if ($thepenalty->updated_by_user)
                                    <a
                                        href="{{ route('staff_members.show', [$thepenalty->updated_by_user->staff->id, 'from' => $thepenalty->updated_by_user->staff->staff_type]) }}">{{ $thepenalty->updated_by_user->name ?? '-'}}</a>
                                @else
                                    -
                                @endif
                            </div><br>

                            <div class="staff-detail-sub  mt-3 text-muted d-flex align-items-center flex-wrap" style="font-size: 1rem;">
                                <i class="bi bi-record-circle me-2"></i>
                                <span class="me-1">الحالة :</span>
                                @if($thepenalty->status === 'applied')
                                    <span class="theme-status-pill theme-status-active">
                                        <span class="dot"></span>
                                        مطبقة
                                    </span>
                                @elseif($thepenalty->status === 'pending')
                                    <span class="theme-status-pill theme-status-pending">
                                        <span class="dot"></span>
                                        غير مطبقة
                                    </span>
                                @else
                                    <span class="theme-status-pill theme-status-inactive">
                                        <span class="dot"></span>
                                        ملغية
                                    </span>
                                @endif
                            </div>

                            <div class="staff-detail-sub mt-3 text-muted d-flex align-items-center flex-wrap" style="font-size: 1rem;">
                                <i class="bi bi-journal-text me-2"></i>
                                الملاحظات :
                                {{ $thepenalty->notes ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
                    <button type="button" class="btn btn-label-danger px-4" onclick="confirmDelete({{ $thepenalty->id }})">
                        حذف العقوبة
                    </button>

                    <form id="delete-form-{{ $thepenalty->id }}"
                        action="{{ route('penalties.destroy', [$thepenalty->id, $theStudent->id]) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    <a href="{{ route('penalties.edit', [$thepenalty->id, $theStudent->id]) }}"
                        class="btn btn-primary px-4">
                        تعديل
                    </a>

                    <a href="{{ url()->previous() }}" class="btn-staff-outline px-4">
                        رجوع
                    </a>
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