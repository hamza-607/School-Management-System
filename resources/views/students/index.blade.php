@extends('layouts/layoutMaster')

@section('title', 'الطلاب')

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
</style>

<script>
    $(document).ready(function() {

        // =========================================
        // البحث مع Refresh للصفحة
        // =========================================

        let timeout = null;

        $('#studentSearchInput').on('input', function() {

            clearTimeout(timeout);

            timeout = setTimeout(() => {

                let search = $(this).val();
                let perPage = $('#studentPerPage').val();

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

        $('#studentPerPage').on('change', function() {

            let perPage = $(this).val();
            let search = $('#studentSearchInput').val();

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

        $('#filtersCollapse').on('show.bs.collapse', function() {

            $('#filtersArrow')
                .removeClass('ti-chevron-down')
                .addClass('ti-chevron-up');

        });

        $('#filtersCollapse').on('hide.bs.collapse', function() {

            $('#filtersArrow')
                .removeClass('ti-chevron-up')
                .addClass('ti-chevron-down');

        });

        // =========================================
        // تطبيق الفلاتر
        // =========================================

        $('#applyFiltersBtn').on('click', function() {

            let search = $('#studentSearchInput').val();
            let perPage = $('#studentPerPage').val();

            let status = $('#statusFilter').val();
            let grade = $('#gradeFilter').val();
            let section = $('#sectionFilter').val();
            let gender = $('#genderFilter').val();
            let financial = $('#financialFilter').val();

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
            } else {
                url.searchParams.delete('per_page');
            }

            // status
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }

            // grade
            if (grade) {
                url.searchParams.set('grade_id', grade);
            } else {
                url.searchParams.delete('grade_id');
            }

            // section
            if (section) {
                url.searchParams.set('section_id', section);
            } else {
                url.searchParams.delete('section_id');
            }

            // gender
            if (gender) {
                url.searchParams.set('gender', gender);
            } else {
                url.searchParams.delete('gender');
            }

            // financial
            if (financial) {
                url.searchParams.set('financial_status', financial);
            } else {
                url.searchParams.delete('financial_status');
            }

            // reset pagination to first page
            url.searchParams.delete('page');

            // refresh
            window.location.href = url.toString();

        });

        // =========================================
        // إعادة تعيين الفلاتر
        // =========================================

        $('#resetFiltersBtn').on('click', function() {

            let url = new URL(window.location.href);

            url.searchParams.delete('search');
            url.searchParams.delete('status');
            url.searchParams.delete('grade_id');
            url.searchParams.delete('section_id');
            url.searchParams.delete('gender');
            url.searchParams.delete('financial_status');
            url.searchParams.delete('per_page');
            url.searchParams.delete('page');

            window.location.href = url.toString();

        });

        function resetSections() {
            let $select = $('#sectionFilter');
            $select.selectpicker('destroy');
            $select.html('<option value="">اختر صف أولاً</option>');
            $select.prop('disabled', true);
            $select.selectpicker();
        }

        const grades = @json($grades);
        const initialGradeId = "{{ request('grade_id') }}";
        const initialSectionId = "{{ request('section_id') }}";

        function fillSections(gradeId, selectedSectionId = '') {
            let grade = grades.find(g => String(g.id) === String(gradeId));
            let sections = Array.isArray(grade?.sections) ? grade.sections : [];
            let $select = $('#sectionFilter');

            $select.selectpicker('destroy');
            $select.empty();

            if (sections.length === 0) {
                $select.append('<option value="">لا توجد شعب لهذا الصف</option>');
                $select.prop('disabled', false);
            } else {
                $select.append('<option value="">كل الشعب</option>');
                sections.forEach(sec => {
                    let selected = String(sec.id) === String(selectedSectionId) ? 'selected' : '';
                    $select.append(`<option value="${sec.id}" ${selected}>${sec.name}</option>`);
                });
                $select.prop('disabled', false);
            }

            $select.selectpicker();
        }

        if (initialGradeId) {
            fillSections(initialGradeId, initialSectionId);
        } else {
            resetSections();
        }

        $('#gradeFilter').on('change', function() {
            let gradeId = $(this).val();

            if (!gradeId) {
                resetSections();
            } else {
                fillSections(gradeId);
            }
        });

        $(document).on('click', '.delete-record', function(e) {
            e.preventDefault();

            let $btn = $(this);

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
@php
$filtersOpen = request()->filled('status') || request()->filled('grade_id') || request()->filled('section_id') || request()->filled('gender') || request()->filled('financial_status');
@endphp
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">الطلاب /</span> القائمة
</h4>

<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">قائمة الطلاب</h5>
        <div class="d-flex">
            <div class="btn-group me-2">
                <button class="btn btn-label-secondary">
                    <i class="ti ti-download me-1"></i> طباعة
                </button>
            </div>

            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> إضافة طالب جديد
            </a>
        </div>
    </div>

    <div class="card-header border-bottom">

        <div class="d-flex justify-content-between align-items-center cursor-pointer" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="{{ $filtersOpen ? 'true' : 'false' }}">
            <h5 class="mb-0">قسم الفلاتر</h5>
            <i id="filtersArrow" class="ti {{ $filtersOpen ? 'ti-chevron-up' : 'ti-chevron-down' }} fs-4 transition text-primary"></i>
        </div>

        <div class="collapse mt-3 {{ $filtersOpen ? 'show' : '' }}" id="filtersCollapse">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">الحالة</label>
                    <select id="statusFilter" name="status" class="selectpicker w-100" data-style="btn-default" data-width="100%">
                        <option value="">الكل</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">الصف</label>
                    <select id="gradeFilter" name="grade_id" class="selectpicker w-100" data-style="btn-default" data-width="100%">
                        <option value="">كل الصفوف</option>
                        @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                            {{ $grade->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">الشعبة</label>
                    <select id="sectionFilter" name="section_id" class="selectpicker w-100" data-style="btn-default" data-width="100%" disabled>
                        <option value="">اختر صف أولاً</option>
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">الجنس</label>
                    <select id="genderFilter" name="gender" class="selectpicker w-100" data-style="btn-default" data-width="100%">
                        <option value="">الكل</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">الحالة المالية</label>
                    <select id="financialFilter" name="financial_status" class="selectpicker w-100" data-style="btn-default" data-width="100%">
                        <option value="">الكل</option>
                        <option value="paid" {{ request('financial_status') == 'paid' ? 'selected' : '' }}>مسدد</option>
                        <option value="unpaid" {{ request('financial_status') == 'unpaid' ? 'selected' : '' }}>غير مسدد</option>
                    </select>
                </div>

                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" id="resetFiltersBtn" class="btn btn-label-secondary">
                            <i class="ti ti-refresh me-1"></i> إعادة تعيين
                        </button>
                        <button type="button" id="applyFiltersBtn" class="btn btn-primary">
                            <i class="ti ti-filter me-1"></i> تطبيق الفلاتر
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-datatable table-responsive">
        <div class="row mx-2 my-3">
            <div class="col-md-6">
                <div class="me-3">
                    <label class="d-flex align-items-center selectpicker" data-style="btn-default">عرض
                        <select id="studentPerPage" class="selectpicker ms-2 me-2" data-style="btn-default" data-width="80px">
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
                        <input id="studentSearchInput" type="search" class="form-control form-control-sm ms-2"
                            placeholder="بحث عن طالب..." value="{{ request('search') }}"> </label>
                </div>
            </div>
        </div>

        <table class="table table-hover border-top">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>الصف</th>
                    <th>الشعبة</th>
                    <th>حالة الطالب</th>
                    <th>تاريخ التسجيل</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0" id="studentTableBody">
                @forelse ($students as $index => $student)
                <tr class="clickable-row" data-href="{{ route('students.show', $student->id) }}" style="cursor:pointer;">
                    <td>{{ $index + 1 }}</td>
                    <td class="truncate">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="d-flex flex-column">
                                <span class="text-body fw-bold text-truncate">{{ $student->name }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="truncate">{{ $student->grade->name ?? '-' }}</td>
                    <td class="truncate">{{ $student->section->name ?? '-' }}</td>
                    @php
                    $status = $student->is_active ? 'نشط' : 'غير نشط';
                    $class = $student->is_active ? 'bg-label-success text-success border border-success-subtle' : 'bg-label-danger text-danger border border-danger-subtle';
                    @endphp
                    <td>
                        <a href="{{ route('toggleStatus', $student->id) }}" class="badge {{ $class }} px-3 py-2">
                            {{ $status }}
                        </a>
                    </td>
                    <td>{{ $student->created_at ? $student->created_at->format('Y-m-d') : '-' }}</td>
                    <td>
                        <div class="d-inline-block text-nowrap">
                            <a href="{{ route('students.edit', $student->id) }}"
                                class="btn btn-sm btn-icon action-btn-hover">
                                <i><svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                        width="20px" fill="#F2CDA2">
                                        <path
                                            d="M216-216h51l375-375-51-51-375 375v51Zm-72 72v-153l498-498q11-11 23.84-16 12.83-5 27-5 14.16 0 27.16 5t24 16l51 51q11 11 16 24t5 26.54q0 14.45-5.02 27.54T795-642L297-144H144Zm600-549-51-51 51 51Zm-127.95 76.95L591-642l51 51-25.95-25.05Z" />
                                    </svg>
                                </i>
                            </a>

                            <form action="{{ route('students.destroy', $student->id) }}" method="POST"
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
                    <td colspan="6" class="text-center">لا يوجد طلاب للعرض</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-end px-3 pb-3 mt-3">
            {{ $students->links() }}
        </div>
    </div>
</div>

@endsection