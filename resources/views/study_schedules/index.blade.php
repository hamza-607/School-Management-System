@extends('layouts/layoutMaster')

@section('title', 'الحصص الدرسية')

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
            let perPage = $('#sessionPerPage').val();
            let day = $('#dayFilter').val();
            let status = $('#statusFilter').val(); // الجديد
            let subject = $('#subjectFilter').val(); // الجديد
            let search = $('#sessionSearchInput').val(); // إضافة البحث لضمان عدم ضياعه

            let url = new URL(window.location.href);

            // تحديث البارامترات في الرابط
            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');

            if (perPage) url.searchParams.set('per_page', perPage);
            else url.searchParams.delete('per_page');

            if (day) url.searchParams.set('day', day);
            else url.searchParams.delete('day');

            if (status) url.searchParams.set('status', status); // الحالة
            else url.searchParams.delete('status');

            if (subject) url.searchParams.set('subject', subject); // المادة
            else url.searchParams.delete('subject');

            // العودة للصفحة رقم 1 عند أي تغيير
            url.searchParams.delete('page');

            window.location.href = url.toString();
        }

        // تشغيل التحديث عند تغيير أي قائمة منسدلة
        $('#sessionPerPage, #dayFilter, #statusFilter, #subjectFilter').on('change', function() {
            updateFilters();
        });

        // تشغيل البحث مع تأخير بسيط (اختياري لو أردت تفعيل البحث)
        let timeout = null;
        $('#sessionSearchInput').on('input', function() {
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
    <span class="text-muted fw-light">البرامج الدراسية / القائمة / </span> برنامج الصف {{ $section->grade->name }} - الشعبة {{ $section->name }}
</h4>

<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">قائمة الحصص الدرسية </h5>
        <div class="d-flex">
            <a href="{{ route('studySchedules.create', $section->id) }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> إضافة حصة درسية جديدة
            </a>
        </div>
    </div>

    <div class="card-header border-bottom">
        <div>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">اليوم</label>
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
                    <label class="fw-bold mb-1">حالة الحصة</label>
                    <select id="statusFilter" name="status" class="selectpicker w-100"
                        data-style="btn-default" data-width="100%">
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>كل الأيام</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>مجدولة</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>ملغية</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشطة</option>
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">المادة</label>
                    <select id="subjectFilter" name="subject" class="selectpicker w-100"
                        data-style="btn-default" data-width="100%">
                        <option value="" {{ request('subject') == '' ? 'selected' : '' }}>كل المواد</option>
                        @foreach ($sectionSubjectTeachers as $sectionSubjectTeacher)
                        <option value="{{ $sectionSubjectTeacher->subject->id }}" {{ request('subject') == $sectionSubjectTeacher->subject->id  ? 'selected' : '' }}>{{ $sectionSubjectTeacher->subject->name }}</option>
                        @endforeach
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
                    <th>#</th>
                    <th>المادة</th>
                    <th>المدرس</th>
                    <th class="text-center align-middle">التوقيت (من - إلى)</th>
                    <th>حالة الحصة الدرسية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse ($sectionSubjectTeachers as $index => $sectionSubjectTeacher)
                <tr class="clickable-row" data-href="{{ route('studySchedules.show',[$sectionSubjectTeacher->id, $section->id]) }}" style="cursor:pointer;">
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

                    $status = $sectionSubjectTeacher->appointment->status === 'scheduled' ? 'مجدولة' : ($sectionSubjectTeacher->appointment->status === 'active' ? 'نشطة' : 'ملغية');
                    $class = $sectionSubjectTeacher->appointment->status === 'scheduled' ? 'bg-label-warning text-warning border border-warning-subtle' : ($sectionSubjectTeacher->appointment->status === 'active' ? 'bg-label-success text-success border border-success-subtle' : 'bg-label-danger text-danger border border-danger-subtle');

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

                    <td class="align-middle fw-bold text-dark">{{ $daysMap[$sectionSubjectTeacher->appointment->day] ?? $sectionSubjectTeacher->appointment->day }}</td>

                    <td>
                        <a href="{{ route('subjects.show', $sectionSubjectTeacher->subject->id) }}" class="fw-bold">
                            {{ $sectionSubjectTeacher->subject->name }}
                        </a>
                    </td>

                    <td>
                        <a href="{{ route('staff_members.show', $sectionSubjectTeacher->staff->id) }}">{{ $sectionSubjectTeacher->staff->name }}</a>
                    </td>

                    <td class="text-center align-middle">
                        <span class="badge bg-label-secondary text-dark">
                            {{ \Carbon\Carbon::parse($sectionSubjectTeacher->appointment->start_time)->format('h:i A') }}
                        </span>
                        <span class="mx-1">-</span>
                        <span class="badge bg-label-secondary text-dark">
                            {{ \Carbon\Carbon::parse($sectionSubjectTeacher->appointment->end_time)->format('h:i A') }}
                        </span>
                    </td>

                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="badge {{ $class }} px-3 py-2">
                                    {{ $status }}
                                </span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('sessionStatus',[$sectionSubjectTeacher->id, 'sessionStatus' => $Dropdown[0]['name']]) }}">
                                        <span class="{{ $Dropdown[0]['class'] }}"><i class="ti ti-circle-filled ti-xs"></i></span>
                                        {{ $Dropdown[0]['name'] }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('sessionStatus',[$sectionSubjectTeacher->id, 'sessionStatus' => $Dropdown[1]['name']]) }}">
                                        <span class="{{ $Dropdown[1]['class'] }}"><i class="ti ti-circle-filled ti-xs"></i></span>
                                        {{ $Dropdown[1]['name'] }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td>
                        <div class="d-inline-block text-nowrap">
                            <a href="{{ route('studySchedules.edit',[$sectionSubjectTeacher->id,$section->id ]) }}"
                                class="btn btn-sm btn-icon action-btn-hover">
                                <i><svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                        width="20px" fill="#F2CDA2">
                                        <path
                                            d="M216-216h51l375-375-51-51-375 375v51Zm-72 72v-153l498-498q11-11 23.84-16 12.83-5 27-5 14.16 0 27.16 5t24 16l51 51q11 11 16 24t5 26.54q0 14.45-5.02 27.54T795-642L297-144H144Zm600-549-51-51 51 51Zm-127.95 76.95L591-642l51 51-25.95-25.05Z" />
                                    </svg>
                                </i>
                            </a>

                            <form action="{{ route('studySchedules.destroy', [$sectionSubjectTeacher->id, $section->id]) }}" method="POST"
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
                    <td colspan="6" class="text-center py-4">لا يوجد حصص درسية لهذا المدرس</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-end px-3 pb-3 mt-3">
            {{ $sectionSubjectTeachers->links() }}
        </div>
    </div>
</div>

@endsection