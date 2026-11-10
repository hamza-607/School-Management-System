@extends('layouts/layoutMaster')

@section('title', 'سجل حضور الطالب')

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function bindFilter(selectId, paramName) {
                const el = document.getElementById(selectId);
                if (!el) return;

                $(el).on('change', function () {
                    const url = new URL(window.location.href);

                    if (this.value) {
                        url.searchParams.set(paramName, this.value);
                    } else {
                        url.searchParams.delete(paramName);
                    }

                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                });
            }

            bindFilter('subjectsFilter', 'subject_id');
            bindFilter('statusFilter', 'status');
            bindFilter('monthFilter', 'month');
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
    </style>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">إدارة الطلاب</span>
            <a href="{{ route('students.index') }}" class="text-muted">القائمة / </a>
            <a href="{{ route('students.show', $theStudent->id) }}" class="text-muted">تفاصيل الطالب
                {{ $theStudent->name }}</a> /
            <span class="border-bottom border-2 border-primary">سجل الحضور</span>
        </h4>
    </div>

    <x-nav :student="$theStudent" />

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted">عدد الجلسات</span>
                    <h3 class="mb-0 mt-1">{{ $countOfAttendances }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted">حاضر</span>
                    <h3 class="mb-0 mt-1" style="color: #006559;">{{ $countOfPresent }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted">غائب</span>
                    <h3 class="mb-0 mt-1" style="color: #7a3540;">{{ $countOfAbsent }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted">بعذر</span>
                    <h3 class="mb-0 mt-1" style="color: #8a6530;">{{ $countOfExcused }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <h6 class="mb-0 fw-bold">نسبة الحضور في {{ $yearName }}</h6>
                <span class="fw-bold">{{ $rateOfAttendances }}%</span>
            </div>
            <div class="progress" style="height: 10px">
                <div class="progress-bar"
                    style="background-color: {{ $rateOfAttendances >= 75 ? '#006559' : '#7a3540' }}; width: {{ $rateOfAttendances }}%">
                </div>
            </div>
            <small class="text-muted d-block mt-2">الغياب بعذر لا يُحتسب غياباً.</small>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="card-title mb-0">سجل الحضور</h5>

            <div class="d-flex align-items-stretch gap-2 attendance-filter" style="width: 500px; max-width: 100%;">
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

                <div class="flex-grow-1" style="min-width: 0;">
                    <select id="statusFilter" class="selectpicker w-100" data-style="btn-default" data-width="100%">
                        <option value="">كل الحالات</option>
                        <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>حاضر</option>
                        <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>غائب</option>
                        <option value="excused" {{ request('status') === 'excused' ? 'selected' : '' }}>بعذر</option>
                    </select>
                </div>


                @php
                    $monthsMap = [
                        1 => 'كانون الثاني',
                        2 => 'شباط',
                        3 => 'آذار',
                        4 => 'نيسان',
                        5 => 'أيار',
                        6 => 'حزيران',
                        7 => 'تموز',
                        8 => 'آب',
                        9 => 'أيلول',
                        10 => 'تشرين الأول',
                        11 => 'تشرين الثاني',
                        12 => 'كانون الأول',
                    ];
                @endphp

                <div class="flex-grow-1" style="min-width: 0;">
                    <select id="monthFilter" class="selectpicker w-100" data-style="btn-default" data-width="100%"
                        data-dropup-auto="false" data-size="6">
                        <option value="">كل الشهور</option>
                        @foreach ($monthsMap as $num => $name)
                            <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <a href="{{ route('students.attendanceRecords.pdf', [$theStudent->id, 'subject_id' => request('subject_id'), 'status' => request('status'), 'month' => request('month')]) }}"
                    target="_blank" rel="noopener" class="btn-staff-outline">
                    <i class="ti ti-printer me-1"></i> طباعة
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0" id="recordsTable">
                <thead class="table-light">
                    <tr>
                        <th>التاريخ</th>
                        <th>اليوم</th>
                        <th>المادة</th>
                        <th>المعلم</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $attendance)
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
                        <tr>
                            <td>{{ $attendance->finishedSession->created_at->format('Y-m-d') }}</td>
                            <td>{{ $daysMap[$attendance->finishedSession->appointment->day] }}</td>
                            <td>
                                <a href="{{ route('subjects.show', $attendance->finishedSession->subject_id) }}">
                                    {{ $attendance->finishedSession->subject->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('staff_members.show', $attendance->finishedSession->teacher_id) }}">
                                    {{ $attendance->finishedSession->staff->name }}
                                </a>
                            </td>
                            <td><span class="badge"
                                    style="color: {{ $statusMap[$attendance['status']][1] }}; background-color: {{ $statusMap[$attendance['status']][2] }};">{{ $statusMap[$attendance['status']][0] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">لا توجد نتائج ضمن هذه الفلاتر</td>
                        </tr>
                    @endforelse
                    <tr id="emptyRow" style="display: none">
                        <td colspan="5" class="text-center text-muted py-4">لا توجد نتائج مطابقة</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection