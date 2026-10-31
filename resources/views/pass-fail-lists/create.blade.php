@extends('layouts/layoutMaster')


@section('title', 'تحديد النتائج')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('page-script')
    <script>
        $(document).ready(function () {

            const semesters = @json($semesters);
            const total = $('.student-row').length;

            // ---------- العدّاد ----------
            function updateCounter() {
                const decided = $('.decision-select').filter(function () { return $(this).val() !== ''; }).length;
                $('#decidedCount').text(decided);
                $('#decidedBar').css('width', (decided / total * 100) + '%');
            }

            // ---------- لون القائمة حسب القرار ----------
            $(document).on('change', '.decision-select', function () {
                $(this)
                    .toggleClass('is-pass', $(this).val() === 'passed')
                    .toggleClass('is-fail', $(this).val() === 'failed');
                updateCounter();
            });

            // =========================================
            // البحث مع Refresh للصفحة (Server-side)
            // =========================================
            let timeout = null;

            $('#studentSearch').on('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    let search = $(this).val();
                    let perPage = $('#sessionPerPage').val();
                    let url = new URL(window.location.href);

                    if (search) {
                        url.searchParams.set('search', search);
                    } else {
                        url.searchParams.delete('search');
                    }

                    if (perPage) {
                        url.searchParams.set('per_page', perPage);
                    }

                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                }, 500);
            });

            // =========================================
            // تغيير عدد الصفوف
            // =========================================
            $('#sessionPerPage').on('change', function () {
                let perPage = $(this).val();
                let search = $('#studentSearch').val();
                let url = new URL(window.location.href);

                if (perPage) {
                    url.searchParams.set('per_page', perPage);
                } else {
                    url.searchParams.delete('per_page');
                }

                if (search) {
                    url.searchParams.set('search', search);
                }

                url.searchParams.delete('page');
                window.location.href = url.toString();
            });

            $('#saveBtn').on('click', function () {
                Swal.fire({
                    title: 'تأكيد حفظ النتائج',
                    html: `<div>
                        هل أنت متأكد أنك تريد حفظ هذه النتائج؟ 
                        <br>
                        <span class="text-danger">
                            هذه العملية لا يمكن التراجع عنها وسوف يتم قفل الشعبة بعد هذا الإجراء
                        </span>
                        <br>
                    </div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، احفظ',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true,
                    focusCancel: true,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn-swal-danger',
                        cancelButton: 'btn-swal-cancel',
                        popup: 'swal2-popup-custom'
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    backdrop: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('resultForm').submit();
                    }
                });
            });

            updateCounter();
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
            --gold-bg: #faf3e6;
        }

        .swal2-popup-custom {
            border-radius: .4rem;
            font-family: 'Cairo', sans-serif;
        }

        .swal2-container {
            z-index: 20000 !important;
        }

        .btn-swal-danger {
            background: var(--primary) !important;
            border: none !important;
            color: #fff !important;
            border-radius: .3rem !important;
            padding: .5rem 1.3rem !important;
            font-weight: 600 !important;
            margin-inline-start: .5rem;
        }

        .btn-swal-cancel {
            background: var(--cream) !important;
            border: 1px solid var(--line) !important;
            color: var(--ink-soft) !important;
            border-radius: .3rem !important;
            padding: .5rem 1.3rem !important;
            font-weight: 600 !important;
        }


        .btn-swal-primary {
            background: var(--primary) !important;
            border: none !important;
            color: #fff !important;
            border-radius: .3rem !important;
            padding: .5rem 1.3rem !important;
            font-weight: 600 !important;
            margin-inline-start: .5rem;
        }

        .btn-swal-cancel {
            background: var(--cream) !important;
            border: 1px solid var(--line) !important;
            color: var(--ink-soft) !important;
            border-radius: .3rem !important;
            padding: .5rem 1.3rem !important;
            font-weight: 600 !important;
        }

        .staff-filter-bar {
            background: var(--cream);
            border-bottom: 1px solid var(--line);
            padding: 1.35rem 1.5rem;
        }

        .btn-staff-outline {
            border: 1px solid var(--line);
            background: var(--cream);
            color: var(--ink-soft);
            border-radius: .3rem;
            font-weight: 600;
            font-size: .9rem;
            padding: .55rem 1.1rem;
            transition: border-color .15s ease, color .15s ease;
        }

        .btn-staff-outline:hover {
            border-color: var(--primary);
            color: var(--ink);
        }

        .btn-staff-outline:disabled {
            opacity: .55;
            pointer-events: none;
        }

        .staff-status-active {
            background: var(--sage-bg);
            color: var(--sage);
            border-color: #d3ddd5;
        }

        .staff-status-active .dot {
            background: var(--sage);
        }

        .staff-status-inactive {
            background: var(--burgundy-bg);
            color: var(--burgundy);
            border-color: #e6d4d4;
        }

        .staff-status-inactive .dot {
            background: var(--burgundy);
        }

        .pill-pending {
            background: var(--gold-bg);
            color: #8a6a33;
            border-color: #eadcc0;
        }

        .pill-pending .dot {
            background: var(--gold);
        }

        .staff-status-pill {
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
        }

        button.staff-status-pill {
            cursor: pointer;
            transition: filter .15s ease;
        }

        button.staff-status-pill:hover {
            filter: brightness(.96);
        }

        .mark-low {
            color: var(--burgundy);
            font-weight: 700;
        }

        .decision-select {
            min-width: 120px;
            font-weight: 600;
        }

        .decision-select.is-pass {
            background-color: var(--sage-bg);
            border-color: var(--sage);
            color: var(--sage);
        }

        .decision-select.is-fail {
            background-color: var(--burgundy-bg);
            border-color: var(--burgundy);
            color: var(--burgundy);
        }

        .decided-progress {
            height: 6px;
            background: var(--line);
            border-radius: 1rem;
            overflow: hidden;
        }

        .decided-progress>div {
            height: 100%;
            background: var(--primary);
            transition: width .25s ease;
        }
    </style>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">إدارة الترفع والرسوب</span>
            <a href="{{ route('passFailLists.index') }}" class="text-muted">القائمة</a> /
            <span class="border-bottom border-2 border-primary">تحديد النتائج</span>
        </h4>
    </div>

    <div class="card">
        <div
            class="card-header border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div>
                <h5 class="card-title mb-1">
                    {{ $section->grade->name }} - شعبة {{ $section->name }}
                    <span id="lockedBadge" class="staff-status-pill staff-status-inactive ms-2 d-none">
                        <span class="dot"></span> النتائج مقفلة
                    </span>
                </h5>
                <span class="text-muted">السنة الدراسية {{ $year->name }} &middot; {{ count($students) }} طالب</span>
            </div>
            <form action="{{ route('passFailLists.store', $section->id) }}" method="POST" class="d-flex gap-2" id="resultForm">
                @csrf

                <button type="button" id="saveBtn" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i> حفظ النتائج
                </button>
            </form>

        </div>

        <div class="staff-filter-bar">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4 w-100">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-bold">تم تحديد النتائج</span>
                        <span class="text-muted"><strong id="decidedCount">0</strong> من {{ count($students) }}</span>
                    </div>
                    <div class="decided-progress">
                        <div id="decidedBar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mx-2 my-3 justify-content-between align-items-center">
            <div class="col-auto">
                <label class="d-flex align-items-center mb-0">عرض
                    <select id="sessionPerPage" class="selectpicker ms-2 me-2" data-style="btn-default" data-width="80px">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select> مدخلات
                </label>
            </div>

            <div class="col-12 col-md-4">
                <input id="studentSearch" type="search" class="form-control" value="{{ request('search') }}"
                    placeholder="بحث عن طالب...">
            </div>
        </div>



        <div class="card-datatable table-responsive">
            <table class="table table-hover border-top mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الطالب</th>
                        @foreach ($semesters as $semester)
                            <th class="text-center">{{ $semester->name }}</th>
                        @endforeach
                        <th class="text-center">معدل السنة</th>
                        <th class="text-center">المواد الراسبة</th>
                        <th class="text-center">العقوبات</th>
                        <th class="text-center">النتيجة</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($students as $index => $student)

                        @php
                            $complete = !in_array(null, $student['marks'], true);

                            $failedCount = count($student['failes']);

                            $penaltiesCount = count($student['penalties']);
                            $pendingCount = collect($student['penalties'])->where('status', 'pending')->count();
                        @endphp
                        <tr class="student-row" data-name="{{ $student['name'] }}" data-failed="{{ $failedCount }}"
                            data-pending="{{ $pendingCount }}" data-incomplete="{{ $complete ? 0 : 1 }}">
                            <td>{{ $index + 1 }}</td>
                            <td class="truncate">
                                <span class="fw-bold">{{ $student['name'] }}</span>

                            </td>

                            @foreach ($student['marks'] as $mark)
                                <td class="text-center">
                                    @if ($mark)
                                        <div class="fw-bold" style="color:{{  $mark['percent'] < 50 ? '#7a3540' : '#006559' }}">
                                            {{ $mark['percent'] }}%
                                        </div>
                                        <small class="d-block text-muted" dir="ltr">{{ $mark['score'] }} / {{ $mark['max'] }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach


                            <td class="text-center fw-bold" style="color:{{$student['yearMark'] < 50 ? '#7a3540' : '#006559'}}">
                                {{ $student['yearMark'] !== null ? $student['yearMark'] : '-' }}
                            </td>

                            <td class="text-center">
                                @if (!$complete)
                                    <span class="staff-status-pill pill-pending"><span class="dot"></span> علامات ناقصة</span>
                                @elseif ($failedCount > 0)
                                    <button type="button" class="staff-status-pill staff-status-inactive" data-bs-toggle="modal"
                                        data-bs-target="#failedModal{{ $index }}">
                                        <span class="dot"></span> {{ $failedCount }}
                                        {{ $failedCount === 1 ? 'مادة' : 'مواد' }}
                                    </button>
                                @else
                                    <span class="staff-status-pill staff-status-active"><span class="dot"></span> لا يوجد</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($penaltiesCount > 0)
                                    <button type="button" class="staff-status-pill staff-status-inactive" data-bs-toggle="modal"
                                        data-bs-target="#penaltiesModal{{ $index }}">
                                        <span class="dot"></span> {{ $penaltiesCount }}
                                        {{ $penaltiesCount === 1 ? 'عقوبة' : 'عقوبات' }}
                                    </button>
                                @else
                                    <span class="staff-status-pill staff-status-active"><span class="dot"></span> لا يوجد</span>
                                @endif
                            </td>

                            <td class="text-end">
                                <select class="selectpicker decision-select w-100 @error('result') is-invalid @enderror"
                                    data-style="btn-default" name="students[{{ $index }}][result]" form="resultForm" required>
                                    <option value="">اختر</option>
                                    <option {{ $student['result'] === 'passed' ? 'selected' : '' }} value="passed">ناجح</option>
                                    <option {{ $student['result'] === 'failed' ? 'selected' : '' }} value="failed">راسب</option>
                                </select>
                            </td>

                           <input type="hidden" name="students[{{ $index }}][student_id]" form="resultForm" value="{{ $student['id'] }}">
                        </tr>
                    @endforeach

                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="{{ 6 + count($semesters) }}" class="text-center text-muted py-4">
                            لا يوجد طلاب مطابقون
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($students as $index => $student)

        @if (count($student['failes']) > 0)
            <div class="modal fade" id="failedModal{{ $index }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">المواد الراسبة - {{ $student['name'] }}</h5>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-hover border-top mb-0">
                                    <thead>
                                        <tr>
                                            <th>المادة</th>
                                            <th class="text-center">العلامة</th>
                                            <th class="text-center">النسبة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($student['failes'] as $fail)
                                            <tr>
                                                <td class="fw-bold">{{ $fail['name'] }}</td>
                                                <td class="text-center" dir="ltr" style="color: #7a3540">{{ $fail['mark']['score'] }} /
                                                    {{ $fail['mark']['max'] }}</td>
                                                <td class="text-center mark-low" style="color: #7a3540">{{ $fail['mark']['percent'] }}%
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-staff-outline" data-bs-dismiss="modal">إغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (count($student['penalties']) > 0)
            <div class="modal fade" id="penaltiesModal{{ $index }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">العقوبات - {{ $student['name'] }}</h5>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-hover border-top mb-0">
                                    <thead>
                                        <tr>
                                            <th>العقوبة</th>
                                            <th>التاريخ</th>
                                            <th>الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($student['penalties'] as $penalty)
                                            <tr>
                                                <td class="fw-bold">{{ $penalty['type'] }}</td>
                                                <td>{{ $penalty['date'] }}</td>
                                                <td>
                                                    @if ($penalty['status'] === 'applied')
                                                        <span class="staff-status-pill staff-status-active"><span class="dot"></span>
                                                            مطبقة</span>
                                                    @elseif ($penalty['status'] === 'pending')
                                                        <span class="staff-status-pill pill-pending"><span class="dot"></span> غير
                                                            مطبقة</span>
                                                    @else
                                                        <span class="staff-status-pill staff-status-inactive"><span class="dot"></span> ملغية</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-staff-outline" data-bs-dismiss="modal">إغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    @endforeach
@endsection