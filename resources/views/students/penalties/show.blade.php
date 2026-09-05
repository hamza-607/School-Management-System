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
    <span class="text-muted fw-light">الطلاب / القائمة / تفاصيل الطالب {{ $theStudent->name }} / العقوبات / </span> عرض معلومات عقوبة ال {{ $thepenalty->penalty_type }}
</h4>

{{-- <x-nav :student="$theSession" /> --}}

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
                        <div class="mb-1 text-muted" style="font-size:large;">
                            <i class="bi bi-mortarboard me-1"></i>
                            السبب {{ $thepenalty->reason}}
                        </div>
                        <div class="text-muted" style="font-size:medium;">
                            <i class="bi bi-person-badge me-1"></i>
                            أصدرت من قبل:
                            <a href="{{ route('staff_members.show',[ $thepenalty->user->staff->id, 'from' => $thepenalty->user->staff->staff_type]) }}">{{ $thepenalty->user->name ?? '-'}}</a>
                        </div>
                        <div class="text-muted" style="font-size:medium;">
                            <i class="bi bi-person-badge me-1"></i>
                            عدلت من قبل:
                            @if ($thepenalty->updated_by_user)
                            <a href="{{ route('staff_members.show',[ $thepenalty->updated_by_user->staff->id, 'from' => $thepenalty->updated_by_user->staff->staff_type]) }}">{{ $thepenalty->updated_by_user->name ?? '-'}}</a>
                            @else
                            -
                            @endif
                        </div><br>

                        <div class="mt-3 text-muted d-flex align-items-center flex-wrap" style="font-size: 1rem;">
                            <i class="bi bi-record-circle me-2"></i>
                            <span class="me-1">الحالة :</span>
                            @if($thepenalty->status === 'applied')
                            {{-- حالة نشطة --}}
                            <div class="status-indicator status-active">
                                <span class="status-dot"></span>
                                <span class="badge bg-label-success text-success p-1 me-2">مطبقة</span>
                            </div>
                            @elseif($thepenalty->status === 'pending')
                            {{-- حالة مجدولة --}}
                            <div class="status-indicator status-scheduled" style="color: #f39c12;">
                                <span class="status-dot" style="background-color: #f39c12;"></span>
                                <span class="badge bg-label-warning text-warning p-1 me-2">غير مطبقة</span>
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
                <button type="button" class="btn btn-label-danger px-4" onclick="confirmDelete({{ $thepenalty->id }})">
                    حذف العقوبة
                </button>

                <form id="delete-form-{{ $thepenalty->id }}" action="{{ route('penalties.destroy', [$thepenalty->id, $theStudent->id]) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>

                <a href="{{ route('penalties.edit', [$thepenalty->id, $theStudent->id]) }}" class="btn btn-label-warning px-4">
                    تعديل
                </a>

                <a href="{{ url()->previous() }}" class="btn btn-label-secondary px-4">
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