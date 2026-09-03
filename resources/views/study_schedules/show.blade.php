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
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">البرامج الدراسية / القائمة / برنامج الصف {{ $theSection->grade->name }} - الشعبة {{ $theSection->name }} / </span> عرض معلومات حصة {{ $theSession->subject->name }}
</h4>

{{-- <x-nav :student="$theSession" /> --}}

<div class="container-fluid px-2 py-4" style="position:relative">

    <!-- Header -->
    <div class="card shadow-sm border-0 mb-4 header-card">
        <div class="card-body">
            <div class="d-flex align-items-start gap-3 mb-3">
                <div class="d-flex align-items-center justify-content-center rounded-circle"
                    style="width: 70px; height: 70px; min-width: 70px; background-color: #f1f3f5; color: #696cff;">
                    <i class="bi bi-journal-bookmark fs-2 text-primary"></i>
                </div>
                <div>
                    <h5 class="mb-1" style="font-size:xx-large;">{{ $theSession->subject->name }}</h5>

                    <div class="patient-details">
                        <div class="mb-1 text-muted" style="font-size:large;">
                            <i class="bi bi-mortarboard me-1"></i>
                            الصف {{ $theSection->grade->name }} - شعبة {{ $theSection->name }}
                        </div>
                        <div class="text-muted" style="font-size:medium;">
                            <i class="bi bi-person-badge me-1"></i>
                            الاستاذ: {{ $theSession->staff->name }}
                        </div><br>
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
                        @endphp
                        <div class="mt-3 text-muted d-flex align-items-center flex-wrap" style="font-size: 1rem;">
                            <i class="bi bi-calendar-week me-2"></i>
                            <span class="me-1">يوم الحصة:</span>
                            <span class="badge bg-label-secondary text-dark me-3">{{ $daysMap[$theSession->appointment->day] ?? $theSession->appointment->day }}</span>

                            <i class="bi bi-clock-history me-2"></i>
                            <span class="me-1">التوقيت:</span>
                            <span class="me-3">
                                <span class="badge bg-label-secondary text-dark">
                                    {{ \Carbon\Carbon::parse($theSession->appointment->start_time)->format('h:i A') }}
                                </span>
                                <span class="mx-1">-</span>
                                <span class="badge bg-label-secondary text-dark">
                                    {{ \Carbon\Carbon::parse($theSession->appointment->end_time)->format('h:i A') }}
                                </span>
                            </span>

                            <i class="bi bi-record-circle me-2"></i>
                            <span class="me-1">الحالة :</span>
                            @if($theSession->appointment->status === 'active')
                            {{-- حالة نشطة --}}
                            <div class="status-indicator status-active">
                                <span class="status-dot"></span>
                                <span class="badge bg-label-success text-success p-1 me-2">نشطة</span>
                            </div>
                            @elseif($theSession->appointment->status === 'scheduled')
                            {{-- حالة مجدولة --}}
                            <div class="status-indicator status-scheduled" style="color: #f39c12;">
                                <span class="status-dot" style="background-color: #f39c12;"></span>
                                <span class="badge bg-label-warning text-warning p-1 me-2">مجدولة</span>
                            </div>
                            @else
                            <div class="status-indicator status-inactive">
                                <span class="status-dot"></span>
                                <span class="badge bg-label-danger text-danger p-1 me-2">ملغية</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
                <button type="button" class="btn btn-label-danger px-4" onclick="confirmDelete({{ $theSession->id }})">
                    حذف الحصة
                </button>

                <form id="delete-form-{{ $theSession->id }}" action="{{ route('studySchedules.destroy', [$theSession->id, $theSection->id]) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>

                <a href="{{ route('studySchedules.edit', [$theSession->id, $theSection->id]) }}" class="btn btn-label-warning px-4">
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
            <div class="card shadow-sm border-0 header-card3">
                <x-files-table :model="$theSession" mainTitle="ملفات ومرفقات الحصة الدرسة" secTitle="لا توجد ملفات مرفوعة."></x-files-table>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card shadow-sm border-0 header-card2">

                <div class="card-body">

                    <div class="tab-btn d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-person-video3 fs-4 text-primary"></i>
                        <h5 class="mb-0 fw-bold">الاستاذ المسؤول عن الحصة</h5>
                    </div>

                    <div class="pb-3">
                        <div class="parent-card border rounded-3 p-3 bg-light-subtle" style="max-width: 860px; flex: 0 0 auto;">
                            <a href="{{ route('staff_members.show',$theSession->staff->id) }}" class="d-flex align-items-center gap-3 mb-2">
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
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $theSession->staff->phone ) }}" target="_blank" class="me-3 small mb-1">
                                        <i class="bi bi-whatsapp me-2 text-success"></i>
                                        {{ $theSession->staff->phone }} </a>
                                </div>
                                <div>
                                    <a href="https://mail.google.com/mail/?view=cm&to={{ $theSession->staff->email }}"
                                        target="_blank"
                                        class="text-decoration-none me-3 small">
                                        <i class="bi bi-envelope me-2 text-secondary"></i>{{ $theSession->staff->email ?? '-' }}
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