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
                    هل أنت متأكد أنك تريد حذف هذه المادة؟
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

    // دالة التبديل بين التابات
    function switchTab(el) {
        let tabs = document.querySelectorAll('.tab-btn')
        tabs.forEach(t => t.classList.remove('active'))
        el.classList.add('active')
    }
</script>

@endsection
@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">المواد /</span> تفاصيل المادة {{ $theSubject->name }}
</h4>

<x-nav :subject="$theSubject" />

<div class="container-fluid px-2 py-4" style="position:relative">
    <div class="card shadow-sm border-0 header-card">
        <div class="card-body">
            <div class="d-flex align-items-start gap-3 mb-3">
                <div>
                    <h5 class="mb-1" style="font-size:xx-large;">{{ $theSubject->name }}</h5>

                    <div class="patient-details">
                        <div class="mb-1 text-muted" style="font-size:large;">
                            {{ $theSubject->e_name }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 border-top border-bottom pt-3 pb-3">
                <button type="button" class="btn btn-label-danger px-4" onclick="confirmDelete({{ $theSubject->id }})">
                    حذف المادة
                </button>

                <form id="delete-form-{{ $theSubject->id }}" action="{{ route('subjects.destroy', $theSubject->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>

                <a href="{{ route('subjects.edit', $theSubject->id) }}"
                    class="btn btn-label-warning px-4">
                    تعديل
                </a>
                <a href="{{ url()->previous() }}" class="btn btn-label-secondary px-4">
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
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $teacher->phone ) }}" target="_blank" class="text-decoration-underline me-3 small mb-1">
                                <i class="bi bi-whatsapp me-2 text-success"></i>
                                {{ $teacher->phone }} </a>
                        </div>
                        <div>
                            <a href="https://mail.google.com/mail/?view=cm&to={{ $teacher->email }}"
                                target="_blank"
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

    <x-files-table :model="$theSubject" mainTitle="ملفات ومرفقات المادة" secTitle="لا توجد ملفات مرفوعة."></x-files-table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection