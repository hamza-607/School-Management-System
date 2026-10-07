@extends('layouts.layoutMaster')

@section('title', 'تفاصيل المادة')
@section('vendor-style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
    <!-- استدعاء مكتبة أيقونات بوتستراب -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection


@section('page-script')
    {{-- هذا القسم هو المسؤول عن حل مشكلة التحكم بالـ Nav --}}


    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'تأكيد الحذف',
                html: `
                        <div>
                            هل أنت متأكد أنك تريد حذف هذه المادة؟
                            <br>
                            <span class="text-danger">
                             هذه العملية سوف تؤدي إلى حذف جميع الملفات المرتبطة بهذه المادة
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

        // دالة التبديل بين التابات
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
            --sage: #006559;
            --sage-bg: #e6f1ef;
            --burgundy: #7a3540;
            --burgundy-bg: #f6ecec;
            --amber: #8a6530;
            --amber-bg: #f3ead6;
            --info: #2f5d8a;
            --info-bg: #e7eef6;
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

        .staff-detail-sub {
            color: #8b8577;
            font-size: 1rem;
        }

        .staff-detail-sub i {
            color: var(--gold);
            margin-inline-end: .35rem;
        }
    </style>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">إدارة المواد</span>
            <a href="{{ route('subjects.index') }}" class="text-muted">القائمة</a> /
            <span class="border-bottom border-2 border-primary">تفاصيل المادة {{ $theSubject->name }}</span>
        </h4>
    </div>

    <x-nav :subject="$theSubject" />

    <div class="container-fluid px-2 py-4" style="position:relative">
        <div class="card shadow-sm border-0 header-card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="staff-info-icon">
                        <i class="bi bi-journal-bookmark fs-2 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="mb-1" style="font-size:xx-large;">{{ $theSubject->name }}</h5>

                        <div class="patient-details">
                            <div class="staff-detail-sub">
                                {{ $theSubject->e_name }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 border-top border-bottom pt-3 pb-3">
                    <button type="button" class="btn btn-label-danger px-4" onclick="confirmDelete({{ $theSubject->id }})">
                        حذف المادة
                    </button>

                    <form id="delete-form-{{ $theSubject->id }}" action="{{ route('subjects.destroy', $theSubject->id) }}"
                        method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    <a href="{{ route('subjects.edit', $theSubject->id) }}" class="btn btn-primary px-4">
                        تعديل
                    </a>
                    <a href="{{ url()->previous() }}" class="btn-staff-outline">
                        رجوع
                    </a>
                </div>

                <div class="tab-btn d-flex align-items-center gap-2 mb-3 mt-3">
                    <i class="bi bi-people fs-4 text-primary"></i>
                    <h5 class="mb-0 fw-bold">المعلمين</h5>
                </div>

                <div class="d-flex flex-nowrap overflow-x-auto gap-3" style="overflow-x: scroll; scrollbar-width: none;">
                    @forelse ($theSubject->teachers as $teacher)
                        <div class="parent-card border rounded-3 p-3 bg-light-subtle" style="min-width: 300px; flex: 0 0 auto;">
                            <a href="" class="d-flex align-items-center gap-3 mb-2">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width: 48px; height: 48px; min-width: 48px; background-color: #e6f0ef  !important; color: #006559 !important;">
                                    <i class="bi bi-person fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $teacher->name }}</h6>
                                </div>
                            </a>
                            <div class="mt-2 pt-2 border-top">
                                <div>
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $teacher->phone) }}" target="_blank"
                                        class="text-decoration-underline me-3 small mb-1">
                                        <i class="bi bi-whatsapp me-2 text-success"></i>
                                        {{ $teacher->phone }} </a>
                                </div>
                                <div>
                                    <a href="https://mail.google.com/mail/?view=cm&to={{ $teacher->email }}" target="_blank"
                                        class="text-decoration-none me-3 small">
                                        <i class="bi bi-envelope me-2 text-secondary"></i>{{ $teacher->email ?? '-' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">لا يوجد معلمين لهذه المادة.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <x-files-table :model="$theSubject" mainTitle="ملفات ومرفقات المادة"
            secTitle="لا توجد ملفات مرفوعة."></x-files-table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection