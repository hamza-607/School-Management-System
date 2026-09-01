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
                    هل أنت متأكد أنك تريد حذف هذا الموظف؟
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
    <span class="text-muted fw-light">الموظفين /</span> تفاصيل الموظف {{ $theStaff->name }}
</h4>

<x-nav :staff="$theStaff" />

<div class="container-fluid px-2 py-4" style="position:relative">

    <!-- Header -->
    <div class="card shadow-sm border-0 mb-4 header-card">
        <div class="card-body">
            <div class="d-flex align-items-start gap-3 mb-3">
                <div style="width:160px; border-radius: 50px;  border: 2px solid #000;">
                    <img style="border-radius: 50px; " src="{{ $theStaff->picture ? url(Storage::url($theStaff->picture)) : asset('storage/studentPhoto/def.png') }}" alt="logo" name="logo" width="160">
                </div>
                <div>
                    <h5 class="mb-1" style="font-size:xx-large;">{{ $theStaff->name }}</h5>

                    <div class="patient-details">
                        <div class="mb-1 text-muted" style="font-size:large;">
                            <i class="bi bi-person me-1"></i>
                            {{ $theStaff->e_name }}
                        </div>

                        <div class="mt-3 text-muted d-flex align-items-center flex-wrap" style="font-size: 1rem;">
                            <i class="bi bi-telephone me-2"></i>
                            <span class="me-1">رقم الهاتف:</span>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $theStaff->phone) }}" target="_blank" class="text-decoration-none me-3">
                                {{ $theStaff->phone }}
                            </a>

                            <span class="me-1">الحالة :</span>
                            @if($theStaff->is_active == 1)
                            <div class="text-success d-flex align-items-center fw-bold me-3">
                                ✓ مفعل
                            </div>
                            @else
                            <div class="text-danger d-flex align-items-center fw-bold me-3">
                                ✕ غير مفعل
                            </div>
                            @endif

                            @if ($from !== 'other')
                            <span class="me-1">حساب المستخدم:</span>
                            @if($theStaff->user) {{-- بفرض وجود علاقة user في موديل Staff --}}
                            <div class="text-success d-flex align-items-center fw-bold">
                                ✓ لديه حساب بالفعل
                            </div>
                            @else
                            <div class="text-danger d-flex align-items-center fw-bold me-3">
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

                <form id="delete-form-{{ $theStaff->id }}" action="{{ route('staff_members.destroy', $theStaff->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>

                <a href="{{ route('staff_members.edit', $theStaff->id) }}" class="btn btn-label-warning px-4">
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

                    @php
                    $job = $theStaff->staff_type === 'teacher' ? 'مدرس' : ($theStaff->staff_type === 'admin' ? 'إداري' : $theStaff->type);
                    @endphp
                    <div class="row g-4">
                        <!-- 1. الصف -->
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width: 50px; height: 50px; min-width: 50px; background-color: #f1f3f5; color: #6c757d;">
                                    <i class="bi bi-mortarboard fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">نوع العمل:</span>
                                    <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                        {{ $job }}
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
                                        {{ $theStaff->created_at ? $theStaff->created_at->format('Y-m-d') : '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width: 50px; height: 50px; min-width: 50px; background-color: #f1f3f5; color: #6c757d;">
                                    <i class="bi bi-calendar-event fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">تاريخ الميلاد:</span>
                                    <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                        {{ $theStaff->date_of_birth }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if ($from === 'teacher')
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width: 50px; height: 50px; min-width: 50px; background-color: #f1f3f5; color: #6c757d;">
                                    <i class="bi bi-door-open fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">المادة:</span>
                                    <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                        <a href="{{ route('subjects.show', $theStaff->subject->id) }}"> {{ $theStaff->subject->name ?? '-' }}</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width: 50px; height: 50px; min-width: 50px; background-color: #f1f3f5; color: #6c757d;">
                                    <i class="bi bi-gender-ambiguous fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">الجنس:</span>
                                    <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                        {{ $theStaff->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                    style="width: 50px; height: 50px; min-width: 50px; background-color: #f1f3f5; color: #6c757d;">
                                    <i class="bi bi-file-earmark-text fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">العقد:</span>
                                    <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                        <a href="{{ url(Storage::url($theStaff->contract->contract_file)) }}" download="{{ $theStaff->contract->contract_file }}">اضغط هنا</a>
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

                    <div class="d-flex flex-nowrap overflow-x-auto gap-3 pb-3" style="overflow-x: scroll; scrollbar-width: none;">
                        @foreach ($theGuardian->student_parents as $student_parent)
                        <div class="parent-card border rounded-3 p-3 bg-light-subtle" style="min-width: 300px; flex: 0 0 auto;">
                            <a href="{{ route('students.show', $student_parent->student->id) }}" class="d-flex align-items-center gap-3 mb-2">
            <div>
                <h6 class="mb-0 fw-bold">{{ $student_parent->student->name }}</h6>
                <small class="text-muted">{{ $student_parent->relationship_to_student }}</small>
            </div>
            </a>
            <div class="mt-2 pt-2 border-top">
                <div>
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $student_parent->student->phoneNumber ) }}" target="_blank" class="me-3 small mb-1">
                        <i class="bi bi-whatsapp me-2 text-success"></i>
                        {{ $student_parent->student->phoneNumber }} </a>
                </div>
                <div>
                    <a href="https://mail.google.com/mail/?view=cm&to={{ $student_parent->student->email }}"
                        target="_blank"
                        class="text-decoration-none me-3 small">
                        <i class="bi bi-envelope me-2 text-secondary"></i>{{ $student_parent->student->email ?? '-' }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
</div>--}}

<x-files-table :model="$theStaff" mainTitle="ملفات ومرفقات الموظف" secTitle="لا توجد ملفات مرفوعة."></x-files-table>

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
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                            style="width: 56px; height: 56px; min-width: 56px; background-color: #f1f3f5; color: #6c757d;">
                            <i class="bi bi-whatsapp me-2 text-secondary"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">رقم الهاتف:</span>
                            <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $theStaff->phone) }}" target="_blank" class="text-decoration-none me-3">
                                    {{ $theStaff->phone }}
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                            style="width: 56px; height: 56px; min-width: 56px; background-color: #f1f3f5; color: #6c757d;">
                            <i class="bi bi-envelope me-2"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">الايميل:</span>
                            <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                <a href="https://mail.google.com/mail/?view=cm&to={{ $theStaff->email ?? '-' }}"
                                    target="_blank"
                                    class="text-decoration-none me-3 small">
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