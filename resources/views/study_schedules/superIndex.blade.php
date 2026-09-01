@extends('layouts/layoutMaster')

@section('title', 'الحصص الدرسية')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" />
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

    .study-program-card {
        border-radius: 0.5rem;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .study-program-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.08);
        border-color: transparent !important;
    }

    .study-program-card-link:hover .study-program-arrow {
        transform: translateX(-4px);
    }

    .study-program-arrow {
        transition: transform 0.25s ease;
    }

    .study-program-icon {
        width: 44px;
        height: 44px;
        min-width: 44px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">البرامج الدراسية /</span> القائمة
</h4>

@if (Auth::user()->staff->staff_type === 'teacher')
<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">قائمة حصص المدرس {{ Auth::user()->name }} </h5>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover border-top">
            <thead>
                <tr>
                    <th class="align-middle">#</th>
                    <th class="align-middle">الصف</th>
                    <th class="align-middle">الشعبة</th>
                    <th class="text-center align-middle">التوقيت (من - إلى)</th>
                    <th class="text-center align-middle">حالة الحصة الدرسية</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse ($sectionSubjectTeachers as $index => $sectionSubjectTeacher)
                <tr>
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

                    <td class="align-middle">
                        <span class="fw-bold">
                            {{ $sectionSubjectTeacher->grade->name }}
                        </span>
                    </td>

                    <td class="align-middle">
                        <a href="{{ route('sections.show', $sectionSubjectTeacher->section->id) }}">{{ $sectionSubjectTeacher->section->name }}</a>
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

                    <td class="text-center align-middle">
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
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">لا يوجد حصص درسية لهذا المدرس</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 mt-1">
    @foreach ($sections as $section)
    <div class="col">
        <a href="{{ route('studySchedules.index', $section->id) }}" class="text-decoration-none study-program-card-link">
            <div class="card h-100 study-program-card border">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="study-program-icon bg-label-primary text-primary">
                        <i class="ri-book-2-line ri-24px"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold text-dark">{{ $section->grade->name }}</h6>
                        <span class="badge bg-label-primary text-primary border border-primary-subtle">
                            شعبة {{ $section->name }}
                        </span>
                    </div>
                    <div class="study-program-arrow text-muted">
                        <i class="ri-arrow-left-s-line ri-20px"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center py-2">
                    <small class="text-muted">عدد المواد</small>
                    <small class="fw-bold text-dark">{{ $section->section_subject_teachers->groupBy('subject_id')->count() }}</small> {{-- جيب عدد المواد الحقيقي --}}
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
@endsection