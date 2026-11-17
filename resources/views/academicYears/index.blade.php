@extends('layouts/layoutMaster')

@section('title', 'السنوات الدراسية')

@section('vendor-style')
    <!-- استدعاء ملفات الـ CSS الخاصة بـ Flatpickr -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endsection

@section('vendor-script')
    <!-- استدعاء ملفات الـ JS الخاصة بـ Flatpickr -->
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
@endsection

@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // تفعيل الـ Flatpickr لحقلي تاريخ السنة الدراسية الجديدة
            flatpickr('#academic-start-date', {
                dateFormat: 'Y-m-d',
                allowInput: true
            });

            flatpickr('#academic-end-date', {
                dateFormat: 'Y-m-d',
                allowInput: true
            });

            // تفعيل الـ Flatpickr لحقلي تاريخ الفصل الدراسي
            flatpickr('#semester-start-date', {
                dateFormat: 'Y-m-d',
                allowInput: true
            });

            flatpickr('#semester-end-date', {
                dateFormat: 'Y-m-d',
                allowInput: true
            });

            // تمرير academic_year_id الصحيح لمودال "إضافة فصل"
            document.querySelectorAll('.add-semester-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.getElementById('semesterYearId').value = btn.dataset.yearId;
                });
            });
        });
    </script>

    <style>
        .button_2 {
            color: #006559 !important;
            border-color: #006559 !important;
        }

        .button_2:hover {
            color: #fff !important;
            background-color: #006559 !important;
            border-color: #006559 !important;
        }

        .bg-show {
            color: #006559 !important;
            border-color: #006559 !important;
            background-color: #e6f1ef !important;
        }

        .btn-staff-outline {
            border: 1px solid #dbe6e3;
            background: #f5f8f7;
            color: #3a5350;
            border-radius: .3rem;
            font-weight: 600;
            font-size: .9rem;
            padding: .55rem 1.4rem;
            transition: border-color .15s ease, color .15s ease;
        }

        .btn-staff-outline:hover {
            border-color: #006559;
            color: #00352e;
        }

        .btn-staff-primary {
            background: #006559;
            border: 1px solid #006559;
            color: #fff;
            border-radius: .3rem;
            font-weight: 600;
            font-size: .9rem;
            padding: .44rem 1.4rem;
            transition: background .15s ease;
        }

        .btn-staff-primary:hover {
            background: #00473f;
            border-color: #00473f;
            color: #fff;
        }

        .static-danger-alert {
            background-color: #e6d4d4;
            border: 1px solid #e6d4d4;
            border-inline-start: 4px solid #7a3540;
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            box-shadow: 0 2px 6px rgba(217, 119, 6, 0.08);
        }

        .static-danger-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 0.5rem;
            background-color: #f6ecec;
            color: #7a3540;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .static-danger-title {
            color: #7a3540;
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .static-danger-text {
            color: #7a3540;
            font-size: 0.875rem;
            line-height: 1.65;
            margin-bottom: 0;
        }

        .static-warning-alert {
            background-color: #fdf3e3;
            border: 1px solid #f5dcb0;
            border-inline-start: 4px solid #b26a00;
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            box-shadow: 0 2px 6px rgba(217, 119, 6, 0.08);
        }

        .static-warning-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 0.5rem;
            background-color: #fbeccc;
            color: #b26a00;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .static-warning-title {
            color: #b26a00;
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .static-warning-text {
            color: #8a5300;
            font-size: 0.875rem;
            line-height: 1.65;
            margin-bottom: 0;
        }
    </style>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">إدارة السنوات الدراسية</span>
            <span class="border-bottom border-2 border-primary">القائمة</span>
        </h4>
    </div>

    <div class="static-danger-alert d-flex align-items-start gap-3 mb-4">
        <div class="static-danger-icon">
            <i class="ti ti-alert-triangle fs-4"></i>
        </div>
        <div>
            <h6 class="static-danger-title">تنبيه هام قبل إجراء أي تعديل</h6>
            <p class="static-danger-text">
                أي تعديل في هذه الصفحة سيؤثر على جميع العمليات داخل المشروع عند جميع المستخدمين، لذا يرجى توخي الحذر وإخبار
                جميع المستخدمين لتسجيل الخروج من حساباتهم عند إجراء أي تغييرات.
            </p>
        </div>
    </div>

    @if (App\Models\System::where('key', 'system_locked')->first()->value)
        <div class="static-warning-alert d-flex align-items-start gap-3 mb-4">
            <div class="static-warning-icon">
                <i class="ti ti-alert-triangle fs-4"></i>
            </div>
            <div>
                <h6 class="static-warning-title">تنبيه</h6>
                <p class="static-warning-text">
                    حسابات المعلمين متوقفة كلياً, <br>
                    حسابات الإداريين متوقفة عن تسجيل الدخول والخروج .
                </p>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <h5 class="mb-0">قائمة السنوات الدراسية</h5>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                @if ($yearCanDo)
                    @if (App\Models\System::where('key', 'system_locked')->first()->value)
                        <a href="{{ route('systemSettings') }}" class="btn btn-primary">
                            <i class="ti ti-power me-1"></i> تفعيل النظام
                        </a>
                    @else
                        <a href="{{ route('systemSettings') }}" class="btn btn-label-danger">
                            <i class="ti ti-power me-1"></i> إيقاف النظام
                        </a>
                    @endif

                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addYearModal">
                        <i class="ti ti-plus me-1"></i>إضافة سنة دراسية جديدة
                    </button>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="accordion" id="yearsAccordion">
                @forelse ($academicYears as $year)

                    <div class="accordion-item">
                        <h2 class="accordion-header d-flex align-items-center justify-content-between">
                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#yearCollapse{{ $year->id }}"
                                style="background-color: #e6f1ef !important; color: #00352e !important;">
                                <span class="fw-bold">{{ $year->name }}</span>
                                <span class="text-muted ms-2">
                                    {{ $year->start_date }} &larr; {{ $year->end_date }}
                                </span>
                                @if ($year->is_current)
                                    <span class="badge bg-show ms-2">السنة الحالية</span>
                                @endif
                            </button>

                            @if (!$year->is_current && $yearCanDo)
                                <form action="{{ route('academicYears.setCurrent', $year->id) }}" method="POST" class="px-3">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit" class="btn btn-sm button_2">
                                        <i class="ti ti-star me-1"></i> تفعيل
                                    </button>
                                </form>
                            @endif
                        </h2>

                        <div id="yearCollapse{{ $year->id }}"
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                            data-bs-parent="#yearsAccordion">
                            <div class="accordion-body">

                                @if ($yearCanDo)
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <button type="button" class="btn btn-sm button_2 mt-1 add-semester-btn"
                                            data-bs-toggle="modal" data-bs-target="#addSemesterModal"
                                            data-year-id="{{ $year->id }}">
                                            <i class="ti ti-plus me-1"></i> إضافة فصل جديد
                                        </button>
                                    </div>
                                @endif

                                <ul class="list-group">
                                    @forelse ($year->semesters as $semester)
                                        <li
                                            class="list-group-item d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                                            <div>
                                                <span class="fw-semibold">{{ $semester->name }}</span>
                                                <span class="text-muted ms-2">
                                                    {{ $semester->start_date }} &larr; {{ $semester->end_date }}
                                                </span>
                                                @if ($semester->is_current)
                                                    <span class="badge bg-show ms-2">الفصل الحالي</span>
                                                @endif
                                            </div>

                                            @if (!$semester->is_current && $yearCanDo)
                                                <form action="{{ route('semesters.setCurrent', $semester->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <button type="submit" class="btn btn-sm btn btn-sm button_2">
                                                        تفعيل
                                                    </button>
                                                </form>
                                            @endif
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted text-center">لا يوجد فصول لهذه السنة بعد</li>
                                    @endforelse
                                </ul>

                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">لا يوجد سنوات دراسية بعد</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== Modal: إضافة سنة دراسية ===== --}}
    <div class="modal fade" id="addYearModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('academicYears.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">إضافة سنة دراسية جديدة</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">اسم السنة</label>
                        <input type="text" name="name" class="form-control" placeholder="مثال: 2025/2026" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">تاريخ البداية</label>
                            <input type="text" name="start_date" id="academic-start-date" class="form-control"
                                placeholder="YYYY-MM-DD" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">تاريخ النهاية</label>
                            <input type="text" name="end_date" id="academic-end-date" class="form-control"
                                placeholder="YYYY-MM-DD" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-staff-outline" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn-staff-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Modal: إضافة فصل ===== --}}
    <div class="modal fade" id="addSemesterModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('semesters.store') }} " method="POST" class="modal-content">
                @csrf

                <input type="hidden" name="academic_year_id" id="semesterYearId" value="">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">إضافة فصل دراسي</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">اسم الفصل</label>
                        <input type="text" name="name" class="form-control" placeholder="مثال: الفصل الأول" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">تاريخ البداية</label>
                            <input type="text" name="start_date" id="semester-start-date" class="form-control"
                                placeholder="YYYY-MM-DD" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">تاريخ النهاية</label>
                            <input type="text" name="end_date" id="semester-end-date" class="form-control"
                                placeholder="YYYY-MM-DD" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-staff-outline" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn-staff-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // نمرر academic_year_id الصحيح لمودال "إضافة فصل" حسب أي سنة ضغط المستخدم زرها
            document.querySelectorAll('.add-semester-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.getElementById('semesterYearId').value = btn.dataset.yearId;
                });
            });
        });
    </script>
@endsection