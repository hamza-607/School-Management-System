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
    <style>
        :root {
            --ink: #00352e;
            --ink-soft: #3a5350;
            --primary: #006559;
            --cream: #f5f8f7;
            --line: #dbe6e3;
        }

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
            border-color: #006559 color: #00352e;
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

        .study-program-card {
            border-radius: .5rem;
            border: 1px solid var(--line) !important;
            transition: all .25s ease;
        }

        .study-program-icon {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: .5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--cream);
            color: var(--primary);
        }

        .study-program-card h6 {
            color: var(--ink);
            font-weight: 700;
        }

        .study-program-badge {
            display: inline-flex;
            padding: .25rem .7rem;
            border-radius: 1rem;
            background: #e6f1ef;
            color: var(--primary);
            border: 1px solid #cfe3df;
            font-size: .8rem;
            font-weight: 600;
        }

        .study-program-card .card-footer {
            background: transparent;
            border-top: 1px solid var(--line) !important;
        }
    </style>
    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">إدارة الترفع والرسوب</span>
            <span class="border-bottom border-2 border-primary">القائمة</span>
        </h4>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <h5 class="mb-0">قائمة الترفع والرسوب</h5>
            {{-- <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addYearModal">
                <i class="ti ti-plus me-1"></i> سنة دراسية جديدة
            </button> --}}
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
                        </h2>

                        <div id="yearCollapse{{ $year->id }}"
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }} m-3"
                            data-bs-parent="#yearsAccordion">
                            <div class="accordion-body">

                                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                                    @forelse ($year->sections as $section)
                                        <div class="col">
                                            <div class="card h-100 study-program-card border">
                                                <div class="card-body d-flex align-items-center gap-3">
                                                    <div class="study-program-icon">
                                                        <i class="ri-book-2-line ri-24px"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $section->grade->name }}</h6>
                                                        <span class="study-program-badge">شعبة {{ $section->name }}</span>
                                                    </div>
                                                    <div class="text-muted">
                                                        <i
                                                            class="ti ti-users ti-xs me-1"></i>{{ $section->student_enrollments->count() }}
                                                        طالب
                                                    </div>
                                                </div>

                                                <div class="card-footer d-flex gap-2 py-2 px-3">
                                                    @if (!$section->results_locked)
                                                        <a href="{{ route('passFailLists.create', $section->id) }}"
                                                            class="btn btn-primary btn-sm py-2 flex-grow-1">
                                                            <i class="ti ti-edit ti-xs me-1"></i>تحديد النتائج
                                                        </a>
                                                    @else
                                                        <a href="{{ route('passFailLists.create', $section->id) }}"
                                                            class="btn-staff-outline flex-grow-1 text-center">
                                                            <i class="ti ti-eye ti-xs me-1"></i>عرض النتائج
                                                        </a>
                                                        <button type="button" class="btn-staff-outline">
                                                            <i class="ti ti-download me-1"></i> طباعة
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="text-center text-muted py-5">لا يوجد شعب مسندة لك حالياً</div>
                                        </div>
                                    @endforelse
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">لا يوجد سنوات دراسية بعد</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection