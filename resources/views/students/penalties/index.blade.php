@extends('layouts/layoutMaster')

@section('title', 'العقوبات الطلابية')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>

<style>
    /* Ensure SweetAlert overlays above everything and style popup */
    .swal2-container {
        z-index: 20000 !important;
    }

    .swal2-popup-custom {
        border-radius: 0.5rem;
    }

    .col-count {
        width: 150px !important;
        text-align: center;
    }

    .address-truncate {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<script>
    $(document).ready(function() {

        function updateFilters() {
            // جلب القيم من العناصر
            let perPage = $('#penaltyPerPage').val();
            let status = $('#statusFilter').val(); // الجديد
            let search = $('#penaltySearchInput').val(); // إضافة البحث لضمان عدم ضياعه

            let url = new URL(window.location.href);

            // تحديث البارامترات في الرابط
            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');

            if (perPage) url.searchParams.set('per_page', perPage);
            else url.searchParams.delete('per_page');

            if (status) url.searchParams.set('status', status); // الحالة
            else url.searchParams.delete('status');

            url.searchParams.delete('page');

            window.location.href = url.toString();
        }

        // تشغيل التحديث عند تغيير أي قائمة منسدلة
        $('#penaltyPerPage, #statusFilter').on('change', function() {
            updateFilters();
        });

        // تشغيل البحث مع تأخير بسيط (اختياري لو أردت تفعيل البحث)
        let timeout = null;
        $('#penaltySearchInput').on('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                updateFilters();
            }, 500);
        });

        // تأكيد الحذف (SweetAlert2)
        $(document).on('click', '.delete-record', function(e) {
            e.preventDefault();
            let $btn = $(this);
            Swal.fire({
                title: 'تأكيد الحذف',
                html: `<div>هل أنت متأكد أنك تريد حذف هذه العقوبة؟<br><strong>هذه العملية لا يمكن التراجع عنها.</strong></div>`,
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
                backdrop: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $btn.closest('form').submit();
                }
            });
        });

        $(document).on('click', '.clickable-row', function(e) {
            if ($(e.target).closest('a').length || $(e.target).closest('button').length || $(e.target).closest('form').length) {
                return;
            }
            window.location.href = $(this).data('href');
        });
    });
</script>

@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">الطلاب / القائمة / تفاصيل الطالب {{ $theStudent->name }} / </span> العقوبات

</h4>

<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">قائمة العقوبات</h5>
        <div class="d-flex">
            <a href="{{ route('penalties.create', $theStudent->id) }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> إضافة عقوبة جديدة
            </a>
        </div>
    </div>

    <div class="card-header border-bottom">
        <div>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">حالة العقوبة</label>
                    <select id="statusFilter" name="status" class="selectpicker w-100"
                        data-style="btn-default" data-width="100%">
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>كل الحالات</option>
                        <option value="applied" {{ request('status') == 'applied' ? 'selected' : '' }}>مطبقة</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>غير مطبقة</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>ملغية</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row mx-2 my-3">
        <div class="col-md-6">
            <div class="me-3">
                <label class="d-flex align-items-center selectpicker" data-style="btn-default">عرض
                    <select id="penaltyPerPage" class="selectpicker ms-2 me-2" data-style="btn-default" data-width="80px">
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
                    <input id="penaltySearchInput" type="search" class="form-control form-control-sm ms-2"
                        placeholder="بحث عن عقوبة..." value="{{ request('search') }}"> </label>
            </div>
        </div>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover border-top">
            <thead>
                <tr>
                    <th>#</th>
                    <th>نوع العقوبة</th>
                    <th>السبب</th>
                    <th>اصدرت من قبل</th>
                    <th>عدلت من قبل</th>
                    <th>ملاحظات</th>
                    <th>حالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse ($penalties as $index => $penalty)
                <tr class="clickable-row" data-href="{{ route('penalties.show',[ $penalty->id ,$theStudent->id]) }}" style="cursor:pointer;">
                    @php
                    $status = $penalty->status === 'applied' ? 'مطبقة' : ($penalty->status === 'pending' ? 'غير مطبقة' : 'ملغية');
                    $class = $penalty->status === 'pending' ? 'bg-label-warning text-warning border border-warning-subtle' : ($penalty->status === 'applied' ? 'bg-label-success text-success border border-success-subtle' : 'bg-label-danger text-danger border border-danger-subtle');

                    $isDropdownEnabled = $penalty->status === 'pending';
                    @endphp

                    <td>{{ $index + 1 }}</td>

                    <td class="align-middle fw-bold text-dark">{{ $penalty->penalty_type }}</td>

                    <td>{{ $penalty->reason }}</td>

                    <td><a href="{{ route('staff_members.show',[ $penalty->user->staff->id, 'from' => $penalty->user->staff->staff_type]) }}">{{ $penalty->user->name }}</a></td>
                    <td>
                        @if ($penalty->updated_by_user)
                        <a href="{{ route('staff_members.show',[ $penalty->updated_by_user->staff->id, 'from' => $penalty->updated_by_user->staff->staff_type]) }}">{{ $penalty->updated_by_user->name ?? '-'}}</a>
                        @else
                        -
                        @endif
                    </td>
                    <td class="address-truncate" title="{{ $penalty->notes ?? '-' }}">{{ $penalty->notes ?? '-'}}</td>

                    <td>
                        <div class="dropdown">

                            <button
                                type="button"
                                class="btn p-0 dropdown-toggle hide-arrow"
                                @if($isDropdownEnabled)
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                @else
                                disabled
                                style="cursor: default;"
                                @endif>
                                <span class="badge {{ $class }} px-3 py-2">
                                    {{ $status }}
                                </span>
                            </button>

                            @if($isDropdownEnabled)
                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('penaltyStatus',[$penalty->id, 'penaltyStatus' => 'مطبقة']) }}">
                                        <span class="badge bg-label-success text-success p-1 me-2">
                                            <i class="ti ti-circle-filled ti-xs"></i>
                                        </span>
                                        مطبقة
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('penaltyStatus',[$penalty->id, 'penaltyStatus' => 'ملغية']) }}">
                                        <span class="badge bg-label-danger text-danger p-1 me-2">
                                            <i class="ti ti-circle-filled ti-xs"></i>
                                        </span>
                                        ملغية
                                    </a>
                                </li>

                            </ul>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="d-inline-block text-nowrap">
                            <a href="{{ route('penalties.edit',[$penalty->id,$theStudent->id ]) }}"
                                class="btn btn-sm btn-icon action-btn-hover">
                                <i><svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                        width="20px" fill="#F2CDA2">
                                        <path
                                            d="M216-216h51l375-375-51-51-375 375v51Zm-72 72v-153l498-498q11-11 23.84-16 12.83-5 27-5 14.16 0 27.16 5t24 16l51 51q11 11 16 24t5 26.54q0 14.45-5.02 27.54T795-642L297-144H144Zm600-549-51-51 51 51Zm-127.95 76.95L591-642l51 51-25.95-25.05Z" />
                                    </svg>
                                </i>
                            </a>

                            <form action="{{ route('penalties.destroy', [$penalty->id, $theStudent->id]) }}" method="POST"
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
                    <td colspan="7" class="text-center py-4">لا يوجد عقوبات لهذا الطالب</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-end px-3 pb-3 mt-3">
            {{ $penalties->links() }}
        </div>
    </div>
</div>

@endsection