@extends('layouts/layoutMaster')

@section('title', 'أولياء الأمور')

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

        // =========================================
        // دالة موحدة لتحديث الرابط بناءً على جميع الفلاتر
        // =========================================
        function updateFilters() {
            let search = $('#guardiansearchInput').val();
            let perPage = $('#guardianPerPage').val();
            let gender = $('#genderFilter').val();
            let relationship = $('#relationship_to_studentFilter').val();

            let url = new URL(window.location.href);

            // البحث
            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');

            // عدد الصفوف
            if (perPage) url.searchParams.set('per_page', perPage);
            else url.searchParams.delete('per_page');

            // الجنس
            if (gender) url.searchParams.set('gender', gender);
            else url.searchParams.delete('gender');

            // علاقة القرابة
            if (relationship) url.searchParams.set('relationship', relationship);
            else url.searchParams.delete('relationship');

            // إعادة الترقيم للصفحة الأولى عند أي تغيير في الفلترة
            url.searchParams.delete('page');

            window.location.href = url.toString();
        }

        // البحث مع تأخير (Timeout)
        let timeout = null;
        $('#guardiansearchInput').on('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                updateFilters();
            }, 500);
        });

        // التحديث عند تغيير أي من القوائم المنسدلة
        $('#guardianPerPage, #genderFilter, #relationship_to_studentFilter').on('change', function() {
            updateFilters();
        });

        // ================================
        // تأكيد حذف الطالب باستخدام SweetAlert2
        // ================================

        // $(document).on('click', '.delete-record', function(e) {
        //     e.preventDefault();
        //     let $btn = $(this);

        //     Swal.fire({
        //         title: 'تأكيد الحذف',
        //         text: 'هل أنت متأكد أنك تريد حذف ولي الأمر هذا؟ <span class="text-danger"> ( إذا كان هذا هو ولي الأمر الوحيد للطالب سوف يتم حذف جميع الطلاب المرتطبين به )</span> هذه العملية لا يمكن التراجع عنها.',
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonText: 'نعم، احذف',
        //         cancelButtonText: 'إلغاء',
        //         buttonsStyling: false,
        //         customClass: {
        //             confirmButton: 'btn btn-primary ms-2 mx-1',
        //             cancelButton: 'btn btn-label-secondary',
        //             popup: 'swal2-popup-custom'
        //         },
        //         allowOutsideClick: false,
        //         allowEscapeKey: false,
        //         allowEnterKey: false,
        //         backdrop: true
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $btn.closest('form').submit();
        //         }
        //     });
        // });

        $(document).on('click', '.delete-record', function(e) {
            e.preventDefault();

            let $btn = $(this);

            Swal.fire({
                title: 'تأكيد الحذف',
                html: `
            <div>
                هل أنت متأكد أنك تريد حذف ولي الأمر هذا؟
                <br>
                <span class="text-danger">
                    (إذا كان هذا هو ولي الأمر الوحيد للطالب، سيتم حذف جميع الطلاب المرتبطين به)
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
    <span class="text-muted fw-light">أولياء الأمور /</span> القائمة
</h4>

<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">قائمة أولياء الأمور</h5>
        <div class="d-flex">
            <div class="btn-group me-2">
                <button class="btn btn-label-secondary">
                    <i class="ti ti-download me-1"></i> طباعة
                </button>
            </div>

            <a href="{{ route('guardians.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> إضافة ولي أمر جديد
            </a>
        </div>
    </div>


    <div class="card-header border-bottom">
        <div>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">الجنس</label>
                    <select id="genderFilter" name="gender" class="selectpicker w-100" data-style="btn-default" data-width="100%">
                        <option value="">الكل</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">علاقة ولي الأمر بالطالب</label>
                    <select id="relationship_to_studentFilter" name="relationship" class="selectpicker w-100" data-style="btn-default" data-width="100%">
                        <option value="">الكل</option>
                        @foreach ($relationships as $g)
                        <option value="{{ $g }}" {{ request('relationship') == $g ? 'selected' : '' }}>
                            {{ $g }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card-datatable table-responsive">
        <div class="row mx-2 my-3">
            <div class="col-md-6">
                <div class="me-3">
                    <label class="d-flex align-items-center">عرض
                        <select id="guardianPerPage" class="selectpicker ms-2 me-2" data-style="btn-default" data-width="80px">
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
                        <input id="guardiansearchInput" type="search" class="form-control form-control-sm ms-2"
                            placeholder="بحث عن ولي أمر..." value="{{ request('search') }}"> </label>
                </div>
            </div>
        </div>

        <table class="table table-hover border-top">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>رقم الهاتف</th>
                    <th>الايميل</th>
                    <th class="col-count">عدد الطلاب المسؤول عنهم</th>
                    <th>العنوان</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0" id="guardianTableBody">
                @forelse ($guardians as $index => $guardian)
                <tr class="clickable-row" data-href="{{ route('guardians.show', $guardian->id) }}" style="cursor:pointer;">
                    <td>{{ $index + 1 }}</td>
                    <td class="truncate">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="d-flex flex-column">
                                <span class="text-body fw-bold text-truncate">{{ $guardian->name }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="truncate">
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $guardian->phone) }}" target="_blank" class="text-decoration-none me-3">
                            {{ $guardian->phone }}
                        </a>
                    </td>
                    <td class="truncate">
                        <a href="https://mail.google.com/mail/?view=cm&to={{ $guardian->email }}"
                            target="_blank"
                            class="text-decoration-none me-3 small">
                            <i class="bi bi-envelope me-2 text-secondary"></i>{{ $guardian->email ?? '-' }}
                        </a>
                    </td>
                    <td class="count-column text-center">
                        <span class="badge bg-label-secondary">{{ $guardian->student_parents->count() }}</span>
                    </td>
                    <td>
                        <div class="address-truncate" title="{{ $guardian->address }}">
                            {{ $guardian->address }}
                        </div>
                    </td>
                    <td>
                        <div class="d-inline-block text-nowrap">
                            <a href="{{ route('students.create', ['fromGuardianID' => $guardian->id]) }}"
                                class="btn btn-sm btn-icon action-btn-hover" title="إضافة طالب">
                                <i>
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#4BABC6">
                                        <path d="M720-400v-120H600v-80h120v-120h80v120h120v80H800v120h-80Zm-360-80q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T106-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q31 15 48.5 43.5T682-272v112H40Z" />
                                    </svg>
                                </i>
                            </a>

                            <a href="{{ route('guardians.edit', $guardian->id) }}"
                                class="btn btn-sm btn-icon action-btn-hover">
                                <i><svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                        width="20px" fill="#F2CDA2">
                                        <path
                                            d="M216-216h51l375-375-51-51-375 375v51Zm-72 72v-153l498-498q11-11 23.84-16 12.83-5 27-5 14.16 0 27.16 5t24 16l51 51q11 11 16 24t5 26.54q0 14.45-5.02 27.54T795-642L297-144H144Zm600-549-51-51 51 51Zm-127.95 76.95L591-642l51 51-25.95-25.05Z" />
                                    </svg>
                                </i>
                            </a>

                            <form action="{{ route('guardians.destroy', $guardian->id) }}" method="POST"
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
                    <td colspan="7" class="text-center">لا يوجد أولياء أمور للعرض</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-end px-3 pb-3 mt-3">
            {{ $guardians->links() }}
        </div>
    </div>
</div>

@endsection