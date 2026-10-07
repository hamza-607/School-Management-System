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


            // تأكيد الحذف (SweetAlert2)
            $(document).on('click', '.delete-record', function (e) {
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
        :root {
            --ink: #00352e;
            --ink-soft: #3a5350;
            --primary: #006559;
            --gold: #ab8347;
            --cream: #f5f8f7;
            --line: #dbe6e3;
            --sage: #006559;
            --sage-bg: #e6f1ef;
            --burgundy: #7a3540;
            --burgundy-bg: #f6ecec;
            --info: #2f5d8a;
            --info-bg: #e7eef6;
        }

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

        .theme-status-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .35rem .85rem;
            border-radius: 1rem;
            font-size: .82rem;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .theme-status-pill .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .theme-status-active {
            background: var(--sage-bg);
            color: var(--sage);
            border-color: #cfe3df;
        }

        .theme-status-active .dot {
            background: var(--sage);
        }

        .theme-status-inactive {
            background: var(--burgundy-bg);
            color: var(--burgundy);
            border-color: #e6d4d4;
        }

        .theme-status-completed {
            background: var(--info-bg);
            color: var(--info);
            border-color: #cfdcea;
        }

        .theme-status-locked {
            cursor: default;
        }

        .staff-action-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .3rem;
            border: 1px solid var(--line);
            background: #fff;
            transition: border-color .15s ease, background .15s ease;
        }

        .staff-action-btn:hover {
            border-color: var(--primary);
            background: var(--cream);
        }

        .staff-filter-bar {
            background: var(--cream);
            border-bottom: 1px solid var(--line);
            padding: 1.35rem 1.5rem;
        }
    </style>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">سجل الحصص الدرسية </span>
            <span class="border-bottom border-2 border-primary">القائمة</span>
        </h4>
    </div>

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">قائمة الحصص الدرسية</h5>
        </div>

        <div class="staff-filter-bar">
            <div>
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="fw-bold mb-1">المدرس</label>
                        <select id="teacherFilter" name="teacher" class="selectpicker w-100" data-style="btn-default"
                            data-width="100%">
                            <option value="">كل المدرسين</option>
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
                        <tr class="clickable-row" data-href="{{ route('finishedSessions.show', $finishedSession->id) }}"
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

                                $statusInfo = [
                                    'active' => ['label' => 'نشطة', 'badgeClass' => 'theme-status-active'],
                                    'completed' => ['label' => 'مكتملة', 'badgeClass' => 'theme-status-completed'],
                                    'canceled' => ['label' => 'ملغية', 'badgeClass' => 'theme-status-inactive'],
                                ][$finishedSession->status] ?? ['label' => 'ملغية', 'badgeClass' => 'theme-status-inactive'];

                                // dd($Dropdown);
                                $sessionType = $finishedSession->session_type === 'regular' ? 'اساسية' : ($finishedSession->session_type === 'final' ? 'امتحان نهائي' : ($finishedSession->session_type === 'quiz' ? 'اختبار' : 'تعويضية'));

                            @endphp

                            <td class="align-middle fw-bold text-dark">
                                {{ $daysMap[$finishedSession->appointment->day] ?? $finishedSession->appointment->day }}
                            </td>

                            <td class="align-middle">
                                <a href="{{ route('staff_members.show', $finishedSession->staff->id) }}"
                                    class="fw-bold">{{ $finishedSession->staff->name }}</a>
                            </td>

                            <td class="align-middle">
                                <a
                                    href="{{ route('subjects.show', $finishedSession->subject_id) }}">{{ $finishedSession->subject->name }}</a>
                            </td>

                            <td>
                                {{ $finishedSession->grade->name }}
                            </td>

                            <td class="align-middle">
                                <a
                                    href="{{ route('sections.show', $finishedSession->section->id) }}">{{ $finishedSession->section->name }}</a>
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
                                @if ($finishedSession->status === 'active')
                                    <span class="theme-status-pill {{ $statusInfo['badgeClass'] }}"><span
                                            class="dot"></span>{{ $statusInfo['label'] }}</span>
                                @else
                                    <span class="theme-status-pill {{ $statusInfo['badgeClass'] }} theme-status-locked">
                                        <i class="ti ti-lock ti-xs"></i>{{ $statusInfo['label'] }}
                                    </span>
                                @endif
                            </td>

                            <td class="text-center align-middle">
                                <span class="badge bg-label-secondary text-dark">{{ $sessionType }}</span>
                            </td>

                            <td>
                                @if ($finishedSession->status !== 'active')
                                    <div class="d-inline-block text-nowrap">
                                        <form action="{{ route('finishedSessions.destroy', $finishedSession->id) }}" method="POST"
                                            style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="staff-action-btn delete-record action-btn-hover">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960"
                                                    width="18px" fill="#7a3540">
                                                    <path
                                                        d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z" />
                                                </svg>
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