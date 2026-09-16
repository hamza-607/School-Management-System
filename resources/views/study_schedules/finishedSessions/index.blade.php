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
            let teacher = $('#teacherFilter').val();
            let grade = $('#gradeFilter').val();
            let section = $('#sectionFilter').val();

            let url = new URL(window.location.href);

            if (perPage) url.searchParams.set('per_page', perPage);
            else url.searchParams.delete('per_page');

            if (teacher) url.searchParams.set('teacher_id', teacher);
            else url.searchParams.delete('teacher_id');

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

        $('#sessionPerPage, #teacherFilter, #sectionFilter').on('change', function() {
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
    <span class="text-muted fw-light">سجل الحصص الدرسية / </span> القائمة
</h4>

<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">قائمة الحصص الدرسية</h5>
    </div>

    <div class="card-header border-bottom">
        <div>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">المدرس</label>
                    <select id="teacherFilter" name="teacher" class="selectpicker w-100"
                        data-style="btn-default" data-width="100%">
                        <option value="">كل المدرسين</option>
                        @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                        @endforeach
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
                    <th class="align-middle">المدرس</th>
                    <th class="align-middle">المادة</th>
                    <th class="align-middle">الصف</th>
                    <th class="align-middle">الشعبة</th>
                    <th class="text-center align-middle">وقت البدء الفعلي</th>
                    <th class="text-center align-middle">وقت الانتهاء الفعلي</th>
                    <th class="align-middle">حالة الحصة الدرسية</th>
                    <th class="text-center align-middle">نوع الحصة الدرسية</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse ($finishedSessions as $index => $finishedSession)
                <tr class="clickable-row" data-href="{{ route('finishedSessions.show', $finishedSession->id) }}" style="cursor:pointer;">
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

                    $status = $finishedSession->status === 'completed' ? 'مكتملة' : ($finishedSession->status === 'active' ? 'نشطة' : 'ملغية');
                    $class = $finishedSession->status === 'completed' ? 'bg-label-success text-success border border-success-subtle' : ($finishedSession->status === 'active' ? 'badge bg-label-warning text-warning p-1 me-2' : 'bg-label-danger text-danger border border-danger-subtle') ;

                    // dd($Dropdown);
                    @endphp

                    <td class="align-middle fw-bold text-dark">{{ $daysMap[$finishedSession->sectionSubjectTeacher->appointment->day] ?? $finishedSession->sectionSubjectTeacher->appointment->day }}</td>

                    <td class="align-middle">
                        <a href="{{ route('staff_members.show', $finishedSession->sectionSubjectTeacher->staff->id) }}" class="fw-bold">{{ $finishedSession->sectionSubjectTeacher->staff->name }}</a>
                    </td>

                    <td class="align-middle">
                        <a href="{{ route('subjects.show', $finishedSession->sectionSubjectTeacher->subject_id) }}">{{ $finishedSession->sectionSubjectTeacher->subject->name }}</a>
                    </td>

                    <td>
                        {{ $finishedSession->sectionSubjectTeacher->grade->name }}
                    </td>

                    <td class="align-middle">
                        <a href="{{ route('sections.show', $finishedSession->sectionSubjectTeacher->section->id) }}">{{ $finishedSession->sectionSubjectTeacher->section->name }}</a>
                    </td>

                    <td class="text-center align-middle">
                        <span class="badge bg-label-secondary text-dark">
                            {{ $finishedSession->actual_start_time ? \Carbon\Carbon::parse($finishedSession->actual_start_time)->format('h:i A') : '-'}}
                        </span>
                    </td>

                    <td class="text-center align-middle">
                        <span class="badge bg-label-secondary text-dark">
                            {{ $finishedSession->actual_end_time ? \Carbon\Carbon::parse($finishedSession->actual_end_time)->format('h:i A') : '-'}}
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
                        <span class="badge bg-label-secondary text-dark">{{ $finishedSession->sectionSubjectTeacher->type === 'regular' ? 'اساسية' : 'تعويضية' }}</span>
                    </td>

                    <td>
                        @if ($finishedSession->status !== 'active')
                        <div class="d-inline-block text-nowrap">
                            <form action="{{ route('finishedSessions.destroy', $finishedSession->id) }}" method="POST"
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
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4">لا يوجد حصص درسية لهذا المدرس</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-end px-3 pb-3 mt-3">
            {{ $finishedSessions->links() }}
        </div>
    </div>
</div>

@endsection