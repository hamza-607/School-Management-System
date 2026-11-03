@extends('layouts/layoutMaster')

@section('title', 'حضور الشعبة')


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

    <script>
        document.querySelectorAll('.session-row').forEach(function (row) {
            row.addEventListener('click', function (e) {
                // إذا النقرة على رابط أو داخله، اتركه يشتغل عادي
                if (e.target.closest('a')) return;

                const target = document.querySelector(row.dataset.target);
                if (!target) return;

                bootstrap.Collapse.getOrCreateInstance(target).toggle();
            });
        });

        document.querySelectorAll('.collapse-row').forEach(function (panel) {
            const row = document.querySelector('.session-row[data-target="#' + panel.id + '"]');
            const icon = row.querySelector('.collapse-icon');

            panel.addEventListener('show.bs.collapse', () => icon.style.transform = 'rotate(180deg)');
            panel.addEventListener('hide.bs.collapse', () => icon.style.transform = 'rotate(0deg)');
        });

        document.addEventListener('DOMContentLoaded', function () {
            const subjectFilter = document.getElementById('subjectsFilter');

            if (subjectFilter) {
                $(subjectFilter).on('change', function () {
                    const url = new URL(window.location.href);

                    if (this.value) {
                        url.searchParams.set('subject_id', this.value);
                    } else {
                        url.searchParams.delete('subject_id');
                    }

                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                });
            }
        });
    </script>
@endsection

@section('content')
    <style>
        :root {
            --ink: #00352e;
            --ink-soft: #3a5350;
            --primary: #006559;
            --primary-dark: #00473f;
            --gold: #ab8347;
            --gold-soft: #e9d9b8;
            --cream: #f5f8f7;
            --line: #dbe6e3;
            --sage: #006559;
            --sage-bg: #e6f1ef;
            --burgundy: #7a3540;
            --burgundy-bg: #f6ecec;
            --amber: #8a6530;
            --amber-bg: #f3ead6;
        }

        .btn-staff-outline {
            border: 1px solid var(--line);
            background: var(--cream);
            color: var(--ink-soft);
            border-radius: .3rem;
            font-weight: 600;
            font-size: .9rem;
            padding: .55rem 1.4rem;
            transition: border-color .15s ease, color .15s ease;
        }

        .btn-staff-outline:hover {
            border-color: var(--primary);
            color: var(--ink);
        }

        .staff-filter-bar {
            background: var(--cream);
            border-bottom: 1px solid var(--line);
            padding: 1.35rem 1.5rem;
        }

        /* تأثير انسيابي سلس عند فتح وإغلاق الصف */
        .collapse-row {
            transition: all 0.3s ease-in-out;
        }

        /* تنسيق مريح للعين لصندوق التفاصيل المنسدل */
        .attendance-details-box {
            background: #f8fafc;
            border-right: 3px solid var(--primary);
            border-radius: 0.5rem;
            padding: 1rem;
            margin: 0.5rem 1rem;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .col-auto-fit {
            width: 1%;
            white-space: nowrap;
        }

        .clickable-link {
            position: relative;
            z-index: 2;
        }

        /* تدوير السهم عند فتح القائمة المنسدلة */
        [data-bs-toggle="collapse"][aria-expanded="true"] .collapse-icon {
            transform: rotate(180deg);
        }

        .collapse-icon {
            transition: transform 0.2s ease-in-out;
        }

        .attendance-filter .bootstrap-select>.dropdown-toggle {
            padding-top: .75rem;
            padding-bottom: .75rem;
            font-size: .95rem;
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

        .theme-status-active {
            background: var(--sage-bg);
            color: var(--sage);
            border-color: #cfe3df;
        }

        .theme-status-inactive {
            background: var(--burgundy-bg);
            color: var(--burgundy);
            border-color: #e6d4d4;
        }

        .theme-status-pending {
            background: var(--amber-bg);
            color: var(--amber);
            border-color: #e6dab8;
        }
    </style>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">إدارة الشُعب</span>
            <a href="{{ route('sections.index') }}" class="text-muted">القائمة / </a>
            <a href="{{ route('sections.show', $theSection->id) }}" class="text-muted">تفاصيل الشعبة
                {{ $theSection->name }}</a> /
            <span class="border-bottom border-2 border-primary">سجل الحضور</span>
        </h4>
    </div>

    <x-nav :section="$theSection" />

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-6 d-block mb-1">عدد الطلاب</span>
                            <h3 class="mb-0 fw-bold text-dark">{{ $theSection->student_enrollments()->count() }}
                            </h3>
                        </div>
                        <div class="avatar avatar-md rounded bg-label-primary d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px;">
                            <i class="ti ti-users fs-3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-6 d-block mb-1">الجلسات المنتهية</span>
                            <h3 class="mb-0 fw-bold text-dark">{{ $finished_sessions->count() }}</h3>
                        </div>
                        <div class="avatar avatar-md rounded bg-label-primary d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px;">
                            <i class="ti ti-checks fs-3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-6 d-block mb-1">متوسط حضور الشعبة</span>
                            <h3 class="mb-0 fw-bold text-dark">{{ $avgAttendance }}%</h3>
                        </div>
                        <div class="avatar avatar-md rounded bg-label-primary d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px;">
                            <i class="ti ti-user-check fs-3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">

        <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="card-title mb-0">سجل الحضور</h5>

            <div class="d-flex align-items-stretch gap-2 attendance-filter" style="width: 300px; max-width: 100%;">
                <div class="flex-grow-1" style="min-width: 0;">
                    <select id="subjectsFilter" class="selectpicker w-100" data-style="btn-default" data-width="100%">
                        <option value="">كل المواد</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="button" class="btn-staff-outline d-flex align-items-center flex-shrink-0 text-nowrap">
                    <i class="ti ti-download me-1"></i> تحميل
                </button>
            </div>
        </div>

        <div class="card-datatable table-responsive">
            <table class="table table-hover border-top">
                <thead class="table-light">
                    <tr>
                        <th style="width: 20%;">التاريخ</th>
                        <th style="width: 18%;">اليوم</th>
                        <th style="width: 10%;" class="col-auto-fit">المادة</th>
                        <th style="width: 16%;" class="col-auto-fit">المعلم</th>
                        <th style="width: 12%;">حاضر</th>
                        <th style="width: 12%;">غائب</th>
                        <th style="width: 12%;">بعذر</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody style="cursor: pointer;">
                    @forelse ($finished_sessions as $session)
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
                        <tr class="session-row" data-target="#detail{{ $session->id }}">
                            <td>
                                <span>{{ $session->created_at->format('Y-m-d') }}</span>
                            </td>
                            <td>
                                {{ $session->appointment ? $daysMap[$session->appointment->day] : '-' }}
                            </td>
                            <td class="col-auto-fit">
                                <a href="{{ route('subjects.show', $session->subject->id) }}" class="fw-medium clickable-link">
                                    {{ $session->subject->name }}
                                </a>
                            </td>
                            <td class="col-auto-fit">
                                <a href="{{ route('staff_members.show', $session->staff->id) }}"
                                    class="fw-medium clickable-link">
                                    {{ $session->staff->name }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-semibold theme-status-pill theme-status-active">
                                    {{ $countOfPresent ?? 0 }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold theme-status-pill theme-status-inactive">
                                    {{ $countOfAbsent ?? 0 }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold theme-status-pill theme-status-pending">
                                    {{ $countOfExcused ?? 0 }}
                                </div>
                            </td>
                            <td>
                                <i class="ti ti-chevron-down collapse-icon text-muted"></i>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="8" class="p-0 border-0">
                                <div class="collapse collapse-row" id="detail{{ $session->id }}">
                                    <div class="attendance-details-box">
                                        <div class="fw-bold text-muted mb-2" style="font-size: 0.8rem;">
                                            <i class="ti ti-users me-1"></i> حالة الطلاب في هذه الجلسة:
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($theSection->student_enrollments as $i => $enrollment)
                                                @php
                                                    $attendance = $enrollment->student->student_attendances()->where('finished_session_id', $session->id)->first();
                                                    $status = $attendance ? $attendance->status : 'غير محدد';

                                                    $statusMap = [
                                                        'present' => [
                                                            'حاضر',
                                                            '#006559',
                                                            '#e6f1ef',
                                                        ],
                                                        'absent' => [
                                                            'غائب',
                                                            '#7a3540',
                                                            '#f6ecec',
                                                        ],
                                                        'excused' => [
                                                            'بعذر',
                                                            '#8a6530',
                                                            '#f3ead6',
                                                        ],
                                                        'غير محدد' => [
                                                            'غير محدد',
                                                            '#a8aaae',
                                                            '#f2f2f3',
                                                        ],
                                                    ];
                                                @endphp
                                                <div class="parent-card border rounded-3 p-1 bg-light-subtle"
                                                    style="min-width: 100px; flex: 0 0 auto;">
                                                    <a href="{{ route('students.show', $enrollment->student->id) }}"
                                                        class="d-flex align-items-center gap-3 m-2">
                                                        <div>
                                                            <h6 class="mb-0 fw-bold">{{ $enrollment->student->name }}</h6>
                                                        </div>
                                                    </a>
                                                    <div class="mt-2 pt-2 border-top">
                                                        <span class="badge w-100 text-center"
                                                            style="font-size: 0.7rem; color: {{ $statusMap[$status][1] }}; background-color: {{ $statusMap[$status][2] }};">
                                                            {{ $statusMap[$status][0] ?? 0 }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">لا توجد سجلات ضمن هذه الفلاتر</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-end px-3 pb-3 mt-3">
            </div>
        </div>
    </div>



@endsection