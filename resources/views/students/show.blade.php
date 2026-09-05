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
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">الطلاب / القائمة /</span> تفاصيل الطالب {{ $theStudent->name }}
</h4>

<x-nav :student="$theStudent" />

<div class="container-fluid px-2 py-4" style="position:relative">

    <!-- Header -->
    <div class="card shadow-sm border-0 mb-4 header-card">
        <div class="card-body">
            <div class="d-flex align-items-start gap-3 mb-3">
                <div style="width:160px; border-radius: 50px;  border: 2px solid #000;">
                    <img style="border-radius: 50px; " src="{{ $theStudent->picture ? url(Storage::url($theStudent->picture)) : asset('storage/studentPhoto/def.png') }}" alt="logo" name="logo" width="160">
                </div>
                <div>
                    <h5 class="mb-1" style="font-size:xx-large;">{{ $theStudent->name }}</h5>

                    <div class="patient-details">
                        <div class="mb-1 text-muted" style="font-size:large;">
                            <i class="bi bi-person me-1"></i>
                            {{ $theStudent->e_name }}
                        </div>
                        <div class="text-muted" style="font-size:medium;">
                            <i class="bi bi-geo-alt me-1"></i>
                            {{ $theStudent->address }}
                        </div><br>

                        <div class="mt-3 text-muted d-flex align-items-center flex-wrap" style="font-size: 1rem;">
                            <i class="bi bi-telephone me-2"></i>
                            <span class="me-1">رقم الهاتف:</span>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $theStudent->phoneNumber) }}" target="_blank" class="text-decoration-none me-3">
                                {{ $theStudent->phoneNumber }}
                            </a>
                            <span class="me-1">الحالة :</span>
                            @if($theStudent->is_active == 1)
                            <div class="status-indicator status-active">
                                <span class="status-dot"></span>
                                <span class="status-text">مفعل</span>
                            </div>
                            @else
                            <div class="status-indicator status-inactive">
                                <span class="status-dot"></span>
                                <span class="status-text">غير مفعل</span>
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

                <form id="delete-form-{{ $theStudent->id }}" action="{{ route('students.destroy', $theStudent->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>

                <a href="{{ route('students.edit', $theStudent->id) }}" class="btn btn-label-warning px-4">
                    تعديل
                </a>

                <a href="{{ url()->previous() }}" class="btn btn-label-secondary px-4">
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
                        <h5 class="mb-0 fw-bold">معلومات إضافية</h5>
                    </div>

                    <div class="row g-4">
                        <!-- 1. الصف -->
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width: 50px; height: 50px; min-width: 50px; background-color: #f1f3f5; color: #6c757d;">
                                    <i class="bi bi-mortarboard fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">الصف:</span>
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
                                    <i class="bi bi-calendar-check fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">تاريخ التسجيل:</span>
                                    <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                        {{ $theStudent->created_at ? $theStudent->created_at->format('Y-m-d') : '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- 3. تاريخ الميلاد -->
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width: 50px; height: 50px; min-width: 50px; background-color: #f1f3f5; color: #6c757d;">
                                    <i class="bi bi-calendar-event fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">تاريخ الميلاد:</span>
                                    <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                        {{ $theStudent->date_of_birth }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- 4. الشعبة -->
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width: 50px; height: 50px; min-width: 50px; background-color: #f1f3f5; color: #6c757d;">
                                    <i class="bi bi-door-open fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">الشعبة:</span>
                                    <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                        {{ $theStudent->section->name ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- 5. الجنس -->
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width: 50px; height: 50px; min-width: 50px; background-color: #f1f3f5; color: #6c757d;">
                                    <i class="bi bi-gender-ambiguous fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">الجنس:</span>
                                    <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                        {{ $theStudent->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 header-card3 mt-4">
                <div class="card-body">

                    <div class="tab-btn d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-people fs-4 text-primary"></i>
                        <h5 class="mb-0 fw-bold">أولياء أمور الطالب</h5>
                    </div>

                    <div class="d-flex flex-nowrap overflow-x-auto gap-3 pb-3" style="overflow-x: scroll; scrollbar-width: none;">
                        @if ($theStudent->student_parents->isNotEmpty())
                        @foreach ($theStudent->student_parents as $student_parent)
                        <div class="parent-card border rounded-3 p-3 bg-light-subtle" style="min-width: 300px; flex: 0 0 auto;">
                            <a href="{{ route('guardians.show', $student_parent->guardian->id) }}" class="d-flex align-items-center gap-3 mb-2" title="ولي الأمر">
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
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $student_parent->guardian->phone ) }}" target="_blank" class="me-3 small mb-1">
                                        <i class="bi bi-whatsapp me-2 text-success"></i>
                                        {{ $student_parent->guardian->phone }} </a>
                                </div>
                                <div>
                                    <a href="https://mail.google.com/mail/?view=cm&to={{ $student_parent->guardian->email }}"
                                        target="_blank"
                                        class="text-decoration-none me-3 small">
                                        <i class="bi bi-envelope me-2 text-secondary"></i>{{ $student_parent->guardian->email ?? '-' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>

                </div>
            </div>

            <x-files-table :model="$theStudent" mainTitle="ملفات ومرفقات الطالب" secTitle="لا توجد ملفات مرفوعة."></x-files-table>

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

                        <button class="btn btn-sm btn-outline-secondary d-flex align-items-center px-3">
                            <i class="ti ti-download me-1"></i>
                            <span>تحميل</span>
                        </button>
                    </div>

                    <div class="row g-3">
                        <!-- منطقة المحتوى -->
                    </div>
                </div>
            </div>

            <!-- السجل المالي -->
            <div class="card shadow-sm border-0 header-card2 mt-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">

                        <div class="d-flex align-items-center gap-2">
                            <i class="ti ti-report-money fs-3 text-primary"></i>
                            <h5 class="mb-0 fw-bold">السجل المالي</h5>
                        </div>

                        <button class="btn btn-sm btn-outline-secondary d-flex align-items-center px-3">
                            <i class="ti ti-download me-1"></i>
                            <span>تحميل</span>
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