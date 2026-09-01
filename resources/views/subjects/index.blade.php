@extends('layouts/layoutMaster')

@section('title', 'المواد')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>

<style>
    .swal2-container {
        z-index: 20000 !important;
    }

    .swal2-popup-custom {
        border-radius: 0.5rem;
    }
</style>

<script>
    $(document).ready(function() {

        // =========================================
        // البحث مع Refresh للصفحة
        // =========================================

        let timeout = null;

        $('#subjectSearchInput').on('input', function() {

            clearTimeout(timeout);

            timeout = setTimeout(() => {

                let search = $(this).val();
                let perPage = $('#subjectPerPage').val();

                let url = new URL(window.location.href);

                // search
                if (search) {
                    url.searchParams.set('search', search);
                } else {
                    url.searchParams.delete('search');
                }

                // per_page
                if (perPage) {
                    url.searchParams.set('per_page', perPage);
                }

                // reset pagination to first page
                url.searchParams.delete('page');

                // refresh
                window.location.href = url.toString();

            }, 500);

        });

        // =========================================
        // تغيير عدد الصفوف
        // =========================================

        $('#subjectPerPage').on('change', function() {

            let perPage = $(this).val();
            let search = $('#subjectSearchInput').val();

            let url = new URL(window.location.href);

            // per_page
            if (perPage) {
                url.searchParams.set('per_page', perPage);
            } else {
                url.searchParams.delete('per_page');
            }

            // search
            if (search) {
                url.searchParams.set('search', search);
            }

            // reset pagination to first page
            url.searchParams.delete('page');

            // refresh
            window.location.href = url.toString();

        });

        $(document).on('click', '.delete-record', function(e) {
            e.preventDefault();

            let $btn = $(this);

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
                    $btn.closest('form').submit();
                }
            });
        });

        $(document).on('click', '.clickable-row', function(e) {

            if (
                $(e.target).closest('a').length ||
                $(e.target).closest('button').length ||
                $(e.target).closest('form').length
            ) {
                return;
            }

            window.location.href = $(this).data('href');
        });
    });
</script>
@endsection

@section('content')

<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">المواد /</span> القائمة
</h4>

<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">قائمة المواد</h5>
        <div class="d-flex">
            {{-- <div class="btn-group me-2">
                <button class="btn btn-label-secondary">
                    <i class="ti ti-download me-1"></i> طباعة
                </button>
            </div>
--}}
            <a href="{{ route('subjects.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> إضافة مادة جديدة
            </a>
        </div>
    </div>

    <div class="card-datatable table-responsive">
        <div class="row mx-2 my-3">
            <div class="col-md-6">
                <div class="me-3">
                    <label class="d-flex align-items-center selectpicker" data-style="btn-default">عرض
                        <select id="subjectPerPage" class="selectpicker ms-2 me-2" data-style="btn-default" data-width="80px">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select> مدخلات
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-md-end justify-content-start">
                    <label class="d-flex align-items-center">بحث:
                        <input id="subjectSearchInput" type="search" class="form-control form-control-sm ms-2"
                            placeholder="بحث عن مادة..." value="{{ request('search') }}"> </label>
                </div>
            </div>
        </div>

        <table class="table table-hover border-top">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>الوصف</th>
                    <th>حالة المادة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0" id="studentTableBody">
                @forelse ($subjects as $index => $subject)
                <tr class="clickable-row" data-href="{{ route('subjects.show', $subject->id) }}" style="cursor:pointer;">
                    <td>{{ $index + 1 }}</td>
                    <td class="truncate">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="d-flex flex-column">
                                <span class="text-body fw-bold text-truncate">{{ $subject->name }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="truncate">{{ $subject->description ?? '-' }}</td>
                    @php
                    $status = $subject->is_active ? 'نشط' : 'غير نشط';
                    $class = $subject->is_active ? 'bg-label-success text-success border border-success-subtle' : 'bg-label-danger text-danger border border-danger-subtle';
                    @endphp
                    <td>
                        <a href="{{ route('subjectToggleStatus', $subject->id) }}" class="badge {{ $class }} px-3 py-2">
                            {{ $status }}
                        </a>
                    </td>
                    <td>
                        <div class="d-inline-block text-nowrap">
                            <a href="{{ route('subjects.edit', $subject->id) }}"
                                class="btn btn-sm btn-icon action-btn-hover">
                                <i><svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                        width="20px" fill="#F2CDA2">
                                        <path
                                            d="M216-216h51l375-375-51-51-375 375v51Zm-72 72v-153l498-498q11-11 23.84-16 12.83-5 27-5 14.16 0 27.16 5t24 16l51 51q11 11 16 24t5 26.54q0 14.45-5.02 27.54T795-642L297-144H144Zm600-549-51-51 51 51Zm-127.95 76.95L591-642l51 51-25.95-25.05Z" />
                                    </svg>
                                </i>
                            </a>

                            <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST"
                                style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon delete-record action-btn-hover">
                                    <i>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                            width="20px" fill="#BB271A">
                                            <path
                                                d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z" />
                                        </svg>
                                    </i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">لا يوجد مواد للعرض</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-end px-3 pb-3 mt-3">
            {{ $subjects->links() }}
        </div>
    </div>
</div>

@endsection