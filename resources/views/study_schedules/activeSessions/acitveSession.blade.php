@extends('layouts/layoutMaster')

@section('title', 'الحصص الدراسية النشطة')

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

        const grades = @json($grades);
        const initialGradeId = "{{ request('grade_id') }}";
        const initialSectionId = "{{ request('section_id') }}";

        function resetSections() {
            let $select = $('#sectionFilter');
            $select.selectpicker('destroy');
            $select.html('<option value="">اختر صف أولاً</option>');
            $select.prop('disabled', true);
            $select.selectpicker();
        }

        function fillSections(gradeId, selectedSectionId = '') {
            let grade = grades.find(g => String(g.id) === String(gradeId));
            let sections = Array.isArray(grade?.sections) ? grade.sections : [];
            let $select = $('#sectionFilter');

            $select.selectpicker('destroy');
            $select.empty();

            if (sections.length === 0) {
                $select.append('<option value="">لا توجد شعب لهذا الصف</option>');
            } else {
                $select.append('<option value="">كل الشعب</option>');
                sections.forEach(section => {
                    let selected = String(section.id) === String(selectedSectionId) ? 'selected' : '';
                    $select.append(`<option value="${section.id}" ${selected}>${section.name}</option>`);
                });
            }

            $select.prop('disabled', false);
            $select.selectpicker();
        }

        function updateFilters() {
            let perPage = $('#sessionPerPage').val();
            let day = $('#dayFilter').val();
            let grade = $('#gradeFilter').val();
            let section = $('#sectionFilter').val();

            let url = new URL(window.location.href);

            if (perPage) url.searchParams.set('per_page', perPage);
            else url.searchParams.delete('per_page');

            if (day) url.searchParams.set('day', day);
            else url.searchParams.delete('day');

            if (grade) url.searchParams.set('grade_id', grade);
            else url.searchParams.delete('grade_id');

            if (section) url.searchParams.set('section_id', section);
            else url.searchParams.delete('section_id');

            // العودة للصفحة رقم 1 عند أي تغيير
            url.searchParams.delete('page');

            window.location.href = url.toString();
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

            updateFilters();
        });

        $('#sessionPerPage, #dayFilter, #sectionFilter').on('change', function() {
            updateFilters();
        });



        // تأكيد الحذف (SweetAlert2)
        $(document).on('click', '.delete-record', function(e) {
            e.preventDefault();
            let $btn = $(this);
            Swal.fire({
                title: 'تأكيد الحذف',
                html: `<div>هل أنت متأكد أنك تريد حذف هذه الحصة الدرسية؟<br><strong>هذه العملية لا يمكن التراجع عنها.</strong></div>`,
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
    <span class="text-muted fw-light">الجلسات الفعالة / </span> القائمة
</h4>

<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">قائمة الحصص الدرسية </h5>
    </div>

    <div class="card-header border-bottom">
        <div>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">المدرس</label>
                    <select id="dayFilter" name="day" class="selectpicker w-100"
                        data-style="btn-default" data-width="100%">
                        <option value="" {{ request('day') == '' ? 'selected' : '' }}>كل الأيام</option>
                        <option value="sunday" {{ request('day') == 'sunday' ? 'selected' : '' }}>الأحد</option>
                        <option value="monday" {{ request('day') == 'monday' ? 'selected' : '' }}>الاثنين</option>
                        <option value="tuesday" {{ request('day') == 'tuesday' ? 'selected' : '' }}>الثلاثاء</option>
                        <option value="wednesday" {{ request('day') == 'wednesday' ? 'selected' : '' }}>الأربعاء</option>
                        <option value="thursday" {{ request('day') == 'thursday' ? 'selected' : '' }}>الخميس</option>
                        <option value="friday" {{ request('day') == 'friday' ? 'selected' : '' }}>الجمعة</option>
                        <option value="saturday" {{ request('day') == 'saturday' ? 'selected' : '' }}> السبت</option>
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">الصف</label>
                    <select id="gradeFilter" name="grade" class="selectpicker w-100"
                        data-style="btn-default" data-width="100%">
                        <option value="">كل الصفوف</option>
                        @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">الشعبة</label>
                    <select id="sectionFilter" name="section_id" class="selectpicker w-100"
                        data-style="btn-default" data-width="100%">
                        <option value="">اختر صف أولاً</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row mx-2 my-3">
        <div class="col-md-6">
            <div class="me-3">
                <label class="d-flex align-items-center selectpicker" data-style="btn-default">عرض
                    <select id="sessionPerPage" class="selectpicker ms-2 me-2" data-style="btn-default" data-width="80px">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select> مدخلات
                </label>
            </div>
        </div>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover border-top">
            <thead>
                <tr>
                    <th class="align-middle">#</th>
                    <th class="align-middle">الصف</th>
                    <th class="align-middle">الشعبة</th>
                    <th class="text-center align-middle">التوقيت (من - إلى)</th>
                    <th class="align-middle">حالة الحصة الدرسية</th>
                    <th class="text-center align-middle">نوع الحصة الدرسية</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse ($activeSessions as $index => $activeSession)
                <tr class="clickable-row" data-href="{{ route('controlSession',[$activeSession->id, $activeSession->section_id]) }}" style="cursor:pointer;">
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

                    $status = $activeSession->appointment->status === 'scheduled' ? 'مجدولة' : ($activeSession->appointment->status === 'active' ? 'نشطة' : 'ملغية');
                    $class = $activeSession->appointment->status === 'scheduled' ? 'bg-label-warning text-warning border border-warning-subtle' : ($activeSession->appointment->status === 'active' ? 'bg-label-success text-success border border-success-subtle' : 'bg-label-danger text-danger border border-danger-subtle');

                    $Dropdown =
                    $status === 'مجدولة' ?
                    [
                    [
                    'name' => 'نشطة',
                    'class' => 'badge bg-label-success text-success p-1 me-2'
                    ],
                    [
                    'name'=>'ملغية',
                    'class' => 'badge bg-label-danger text-danger p-1 me-2'
                    ]
                    ]
                    : ($status === 'نشطة' ?
                    [
                    [
                    'name'=>'مجدولة',
                    'class' => 'badge bg-label-warning text-warning p-1 me-2'
                    ],
                    [
                    'name'=>'ملغية',
                    'class' => 'badge bg-label-danger text-danger p-1 me-2'
                    ]
                    ]
                    : [
                    [
                    'name'=>'نشطة',
                    'class' => 'badge bg-label-success text-success p-1 me-2'
                    ],
                    [
                    'name'=>'مجدولة',
                    'class' => 'badge bg-label-warning text-warning p-1 me-2'
                    ]
                    ]);

                    // dd($Dropdown);
                    @endphp

                    <td class="align-middle fw-bold text-dark">{{ $daysMap[$activeSession->appointment->day] ?? $activeSession->appointment->day }}</td>

                    <td class="align-middle">
                        <span class="fw-bold">
                            {{ $activeSession->grade->name }}
                        </span>
                    </td>

                    <td class="align-middle">
                        <a href="{{ route('sections.show', $activeSession->section->id) }}">{{ $activeSession->section->name }}</a>
                    </td>

                    <td class="text-center align-middle">
                        <span class="badge bg-label-secondary text-dark">
                            {{ \Carbon\Carbon::parse($activeSession->appointment->start_time)->format('h:i A') }}
                        </span>
                        <span class="mx-1">-</span>
                        <span class="badge bg-label-secondary text-dark">
                            {{ \Carbon\Carbon::parse($activeSession->appointment->end_time)->format('h:i A') }}
                        </span>
                    </td>

                    <td class="align-middle">
                        <div class="btn p-0 dropdown-toggle hide-arrow">
                            <span class="badge {{ $class }} px-3 py-2">
                                {{ $status }}
                            </span>
                        </div>
                    </td>

                    <td class="text-center align-middle">
                        <span class="badge bg-label-secondary text-dark">{{ $activeSession->type === 'regular' ? 'اساسية' : 'تعويضية' }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">لا يوجد حصص درسية لهذا المدرس</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-end px-3 pb-3 mt-3">
            {{ $activeSessions->links() }}
        </div>
    </div>
</div>

@endsection