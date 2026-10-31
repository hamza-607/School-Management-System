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

    <script>
        $(document).ready(function () {

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

            $('#gradeFilter').on('change', function () {
                let gradeId = $(this).val();

                if (!gradeId) {
                    resetSections();
                } else {
                    fillSections(gradeId);
                }

                updateFilters();
            });

            $('#sessionPerPage, #teacherFilter, #sectionFilter').on('change', function () {
                updateFilters();
            });


            $(document).on('click', '.clickable-row', function (e) {
                if ($(e.target).closest('a').length || $(e.target).closest('button').length || $(e.target).closest('form').length) {
                    return;
                }
                window.location.href = $(this).data('href');
            });
        });
    </script>

@endsection

@section('content')

    <style>
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

        .staff-status-pill {
            background: #e6f1ef;
            color: #006559;
            border-color: #d3ddd5;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .35rem .85rem;
            border-radius: 1rem;
            font-size: .8rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid transparent;
        }

        .staff-status-pill .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #006559;

        }

        .staff-filter-bar {
            background: #f5f8f7;
            border-bottom: 1px solid #dbe6e3;
            padding: 1.35rem 1.5rem;
        }

        .theme-badge-neutral {
            display: inline-flex;
            align-items: center;
            padding: .3rem .75rem;
            border-radius: .3rem;
            background: #f5f8f7;
            border: 1px solid #dbe6e3;
            color: #3a5350;
            font-size: .82rem;
            font-weight: 600;
        }
    </style>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">الجلسات الفعالة </span>
            <span class="border-bottom border-2 border-primary">القائمة</span>
        </h4>
    </div>

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">قائمة الحصص الدرسية </h5>
        </div>

        <div class="staff-filter-bar">
            <div>
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="fw-bold mb-1">المدرس</label>
                        <select id="teacherFilter" name="teacher_id" class="selectpicker w-100" data-style="btn-default"
                            data-width="100%">
                            <option value="" {{ request('teacher_id') == '' ? 'selected' : '' }}>كل المدرسين</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="fw-bold mb-1">الصف</label>
                        <select id="gradeFilter" name="grade" class="selectpicker w-100" data-style="btn-default"
                            data-width="100%">
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
                        <select id="sectionFilter" name="section_id" class="selectpicker w-100" data-style="btn-default"
                            data-width="100%">
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
                        <select id="sessionPerPage" class="selectpicker ms-2 me-2" data-style="btn-default"
                            data-width="80px">
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
                        <tr class="clickable-row"
                            data-href="{{ route('controlSession', [$activeSession->id, $activeSession->section_id]) }}"
                            style="cursor:pointer;">
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

                            <td class="align-middle fw-bold text-dark">
                                {{ $daysMap[$activeSession->appointment->day] ?? $activeSession->appointment->day }}
                            </td>

                            <td class="align-middle">
                                <span class="fw-bold">
                                    {{ $activeSession->grade->name }}
                                </span>
                            </td>

                            <td class="align-middle">
                                <a
                                    href="{{ route('sections.show', $activeSession->section->id) }}">{{ $activeSession->section->name }}</a>
                            </td>

                            <td class="text-center align-middle">
                                <span class="theme-badge-neutral">
                                    {{ \Carbon\Carbon::parse($activeSession->appointment->start_time)->format('h:i A') }}
                                </span>
                                <span class="mx-1">-</span>
                                <span class="theme-badge-neutral">
                                    {{ \Carbon\Carbon::parse($activeSession->appointment->end_time)->format('h:i A') }}
                                </span>
                            </td>

                            <td class="align-middle">
                                <div class="btn p-0 dropdown-toggle hide-arrow">
                                    <span class="badge staff-status-pill px-3 py-2">
                                        <span class="dot"></span>
                                        {{ $activeSession->appointment->status === 'active' ? 'نشطة' : null}}
                                    </span>
                                </div>
                            </td>

                            <td class="text-center align-middle">
                                <span
                                    class="badge bg-label-secondary text-dark">{{ $activeSession->type === 'regular' ? 'اساسية' : 'تعويضية' }}</span>
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