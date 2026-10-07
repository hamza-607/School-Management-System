@extends('layouts/layoutMaster')

@section('title', 'الحصص الدرسية')

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>

    <script>
        // تاريخ المذاكرة
        const quizDatePicker = flatpickr('#flatpickr-date', {
            dateFormat: 'Y-m-d',
            allowInput: true,
            static: true   // حتى يظهر التقويم فوق المودال
        });

        const addQuizModal = document.getElementById('addQuizModal');

        addQuizModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('quizSessionId').value = button.getAttribute('data-session-id');
        });

        addQuizModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('addQuizForm').reset();
            quizDatePicker.clear();
        });
    </script>

    <script>
        $(document).ready(function () {

            function updateFilters() {
                // جلب القيم من العناصر
                let perPage = $('#sessionPerPage').val();
                let day = $('#dayFilter').val();
                let status = $('#statusFilter').val(); // الجديد
                let subject = $('#subjectFilter').val(); // الجديد

                let url = new URL(window.location.href);

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
            $('#sessionPerPage, #dayFilter, #statusFilter, #subjectFilter').on('change', function () {
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

        :root {
            --sage: #006559;
            --sage-bg: #e6f1ef;
            --burgundy: #7a3540;
            --burgundy-bg: #f6ecec;
            --amber: #8a6530;
            --amber-bg: #f3ead6;
            --info: #2f5d8a;
            --info-bg: #e7eef6;
            --cream: #f5f8f7;
            --line: #dbe6e3;
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

        .theme-status-active .dot,
        .status-dot-active {
            background: var(--sage);
        }

        .theme-status-inactive {
            background: var(--burgundy-bg);
            color: var(--burgundy);
            border-color: #e6d4d4;
        }

        .theme-status-inactive .dot,
        .status-dot-canceled {
            background: var(--burgundy);
        }

        .theme-status-pending {
            background: var(--amber-bg);
            color: var(--amber);
            border-color: #e6dab8;
        }

        .theme-status-pending .dot {
            background: var(--amber);
        }

        .theme-status-completed {
            background: var(--info-bg);
            color: var(--info);
            border-color: #cfdcea;
        }

        .theme-status-completed .dot,
        .status-dot-completed {
            background: var(--info);
        }

        .theme-status-menu {
            border: 1px solid var(--line);
            border-radius: .35rem;
            padding: .4rem;
            box-shadow: 0 4px 14px rgba(0, 53, 46, .08);
        }

        .theme-status-menu .dropdown-item {
            border-radius: .3rem;
            font-size: .88rem;
            padding: .5rem .7rem;
        }

        .theme-status-menu .dropdown-item:hover {
            background: var(--cream);
        }

        .theme-status-menu .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-inline-end: .5rem;
        }

        .status-dot-scheduled {
            background: var(--amber);
        }

        .status-dot-completed {
            background: var(--info);
        }

        .staff-filter-bar {
            background: var(--cream);
            border-bottom: 1px solid var(--line);
            padding: 1.35rem 1.5rem;
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
            Pw
        }
    </style>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">البرامج الدراسية</span>
            <a href="{{ route('studySchedules.superIndex') }}" class="text-muted">القائمة</a> / 
            <span class="border-bottom border-2 border-primary"> برنامج الصف {{ $section->grade->name }} - الشعبة
                {{ $section->name }}</span>
        </h4>
    </div>

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">قائمة الحصص الدرسية </h5>
            <div class="d-flex">
                <a href="{{ route('studySchedules.create', $section->id) }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> إضافة حصة درسية جديدة
                </a>
            </div>
        </div>

        <div class="staff-filter-bar">
            <div>
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="fw-bold mb-1">حالة الحصة</label>
                        <select id="statusFilter" name="status" class="selectpicker w-100" data-style="btn-default"
                            data-width="100%">
                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>كل الحالات</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>مجدولة</option>
                            <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>ملغية</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشطة</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="fw-bold mb-1">المادة</label>
                        <select id="subjectFilter" name="subject" class="selectpicker w-100" data-style="btn-default"
                            data-width="100%">
                            <option value="" {{ request('subject') == '' ? 'selected' : '' }}>كل المواد</option>
                            @foreach ($sectionSubjectTeachers as $sectionSubjectTeacher)
                                <option value="{{ $sectionSubjectTeacher->subject->id }}" {{ request('subject') == $sectionSubjectTeacher->subject->id ? 'selected' : '' }}>
                                    {{ $sectionSubjectTeacher->subject->name }}
                                </option>
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

        @php
            $today = strtolower(\Carbon\Carbon::now()->format('l'));
            $selectedDay = request()->query('day', $today);

            $daysMap = [
                'sunday' => 'الأحد',
                'monday' => 'الاثنين',
                'tuesday' => 'الثلاثاء',
                'wednesday' => 'الأربعاء',
                'thursday' => 'الخميس',
                'friday' => 'الجمعة',
                'saturday' => 'السبت',
            ];
        @endphp

        <div class="card-datatable table-responsive">
            <div class="card-body pb-2">
                <div class="row row-cols-auto g-2">
                    @foreach ($daysMap as $dayKey => $dayLabel)
                        <div class="col">
                            <a href="{{ request()->fullUrlWithQuery(['day' => $dayKey]) }}" class="text-decoration-none">
                                <div class="card border {{ $selectedDay === $dayKey ? 'border-primary bg-label-primary' : '' }} px-3 py-2 text-center"
                                    style="min-width: 90px; border-radius: 0.5rem; transition: all .2s ease;">
                                    <span class="fw-bold {{ $selectedDay === $dayKey ? 'text-primary' : 'text-dark' }}">
                                        {{ $dayLabel }}
                                    </span>
                                    @if ($dayKey === $today)
                                        <small class="text-muted d-block" style="font-size: 10px;">اليوم</small>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <table class="table table-hover border-top">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المادة</th>
                        <th>المدرس</th>
                        <th class="text-center align-middle">التوقيت (من - إلى)</th>
                        <th>حالة الحصة الدرسية</th>
                        <th>نوع الحصة الدرسية</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($sectionSubjectTeachers as $index => $sectionSubjectTeacher)
                        <tr class="clickable-row"
                            data-href="{{ route('studySchedules.show', [$sectionSubjectTeacher->id, $section->id]) }}"
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

                                $status = $sectionSubjectTeacher->appointment->status === 'scheduled' ? 'مجدولة' : ($sectionSubjectTeacher->appointment->status === 'active' ? 'نشطة' : ($sectionSubjectTeacher->appointment->status === 'completed' ? 'مكتملة' : 'ملغية'));
                                $class = $sectionSubjectTeacher->appointment->status === 'scheduled' ? 'theme-status-pending' : ($sectionSubjectTeacher->appointment->status === 'active' ? 'theme-status-active' : ($sectionSubjectTeacher->appointment->status === 'completed' ? 'theme-status-completed' : 'theme-status-inactive'));

                                $statusOptions = [
                                    ['name' => 'مجدولة', 'class' => 'status-dot status-dot-scheduled'],
                                    ['name' => 'نشطة', 'class' => 'status-dot status-dot-active'],
                                    ['name' => 'مكتملة', 'class' => 'status-dot status-dot-completed'],
                                    ['name' => 'ملغية', 'class' => 'status-dot status-dot-canceled'],
                                ];
                                $Dropdown = array_values(array_filter($statusOptions, function ($option) use ($status) {
                                    return $option['name'] !== $status;
                                }));

                                $sessionType = $sectionSubjectTeacher->type === 'regular' ? 'اساسية' : ($sectionSubjectTeacher->type === 'final' ? 'امتحان نهائي' : ($sectionSubjectTeacher->type === 'quiz' ? 'اختبار' : 'تعويضية'));
                            @endphp

                            <td class="align-middle fw-bold text-dark">
                                {{ $daysMap[$sectionSubjectTeacher->appointment->day] ?? $sectionSubjectTeacher->appointment->day }}
                            </td>

                            <td>
                                <a href="{{ route('subjects.show', $sectionSubjectTeacher->subject->id) }}" class="fw-bold">
                                    {{ $sectionSubjectTeacher->subject->name }}
                                </a>
                            </td>

                            <td>
                                <a
                                    href="{{ route('staff_members.show', $sectionSubjectTeacher->staff->id) }}">{{ $sectionSubjectTeacher->staff->name }}</a>
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
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span class="theme-status-pill {{ $class }}"><span class="dot"></span>
                                            {{ $status }}
                                        </span>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end theme-status-menu">
                                        @foreach ($Dropdown as $option)
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('sessionStatus', [$sectionSubjectTeacher->id, 'sessionStatus' => $option['name']]) }}">
                                                    <span class="{{ $option['class'] }}"></span>
                                                    {{ $option['name'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-label-secondary text-dark">{{ $sessionType }}</span>

                                @if ($sessionType === 'اساسية' || $sessionType === 'تعويضية')
                                    <button type="button" class="btn btn-sm btn-outline-primary ms-1 add-quiz-btn"
                                        data-bs-toggle="modal" data-bs-target="#addQuizModal"
                                        data-session-id="{{ $sectionSubjectTeacher->id }}">
                                        <i class="ti ti-plus ti-xs me-1"></i> مذاكرة
                                    </button>
                                @endif
                            </td>
                            <td>
                                <div class="d-inline-block text-nowrap">
                                    <a href="{{ route('studySchedules.edit', [$sectionSubjectTeacher->id, $section->id]) }}"
                                        class="staff-action-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960"
                                            width="18px" fill="#ab8347">
                                            <path
                                                d="M216-216h51l375-375-51-51-375 375v51Zm-72 72v-153l498-498q11-11 23.84-16 12.83-5 27-5 14.16 0 27.16 5t24 16l51 51q11 11 16 24t5 26.54q0 14.45-5.02 27.54T795-642L297-144H144Zm600-549-51-51 51 51Zm-127.95 76.95L591-642l51 51-25.95-25.05Z" />
                                        </svg>
                                    </a>

                                    <form
                                        action="{{ route('studySchedules.destroy', [$sectionSubjectTeacher->id, $section->id]) }}"
                                        method="POST" style="display:inline-block">
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">لا يوجد حصص درسية لهذه الشعبة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== Modal: إضافة مذاكرة ===== --}}
    <div class="modal fade" id="addQuizModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('quizzes.store') }}" method="POST" id="addQuizForm" class="modal-content">
                @csrf

                <input type="hidden" name="sessionID" id="quizSessionId" value="">

                <div class="modal-header border-bottom">
                    <h5 class="modal-title">إضافة مذاكرة</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">عنوان المذاكرة</label>
                        <input type="text" name="title" class="form-control" placeholder="مثال: مذاكرة الفصل الأول"
                            required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">التاريخ</label>
                            <input type="text" name="date" id="flatpickr-date" value="{{ old('date') }}"
                                class="form-control" placeholder="YYYY-MM-DD" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">العلامة العظمى</label>
                            <input type="number" name="max_score" class="form-control" min="1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>

@endsection