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

@section('page-style')
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

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل tooltips للحصص المقفولة
        var tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(function(el) {
            new bootstrap.Tooltip(el);
        });

        // تأكيد قبل الإلغاء
        document.querySelectorAll('.js-cancel-session').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                let href = this.getAttribute('href');

                Swal.fire({
                    title: 'تأكيد إلغاء الحصة',
                    html: `
                <div>
                    هل أنت متأكد أنك تريد إلغاء هذه الحصة الدرسية؟
                    <br>
                    <strong>بعد الإلغاء لن تتمكن من التراجع أو إعادة جدولتها بنفسك، ويجب التواصل مع قسم  الإدارة.</strong>
                </div>
            `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، إلغاء الحصة',
                    cancelButtonText: 'تراجع',
                    reverseButtons: true,
                    focusCancel: true,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-danger ms-2',
                        cancelButton: 'btn btn-secondary',
                        popup: 'swal2-popup-custom'
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    backdrop: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        });
    });
</script>

@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">البرامج الدراسية /</span> القائمة
</h4>

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

$statusMeta = [
'scheduled' => ['label' => 'مجدولة', 'badgeClass' => 'bg-label-warning text-warning border border-warning-subtle', 'dotClass' => 'badge bg-label-warning text-warning p-1 me-2'],
'active' => ['label' => 'نشطة', 'badgeClass' => 'bg-label-success text-success border border-success-subtle', 'dotClass' => 'badge bg-label-success text-success p-1 me-2'],
'canceled' => ['label' => 'ملغية', 'badgeClass' => 'bg-label-danger text-danger border border-danger-subtle', 'dotClass' => 'badge bg-label-danger text-danger p-1 me-2'],
];
@endphp

@if (Auth::user()->staff->staff_type === 'teacher')
<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">قائمة حصص المدرس {{ Auth::user()->name }}</h5>
    </div>

    {{-- كاردات أيام الأسبوع (فلتر) --}}
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
                @forelse ($sectionSubjectTeachers as $index => $sectionSubjectTeacher)
                @php
                $appointmentDay = $sectionSubjectTeacher->appointment->day;
                $currentStatus = $sectionSubjectTeacher->appointment->status; // scheduled | active | canceled
                $isToday = $appointmentDay === $today;
                $isLocked = $currentStatus === 'canceled'; // ← قفل نهائي بعد الإلغاء

                $alternatives = array_diff(array_keys($statusMeta), [$currentStatus]);
                if (!$isToday) {
                $alternatives = array_diff($alternatives, ['active']);
                }

                if($currentStatus === 'active'){
                $alternatives = array_diff($alternatives, ['active','scheduled','canceled']);
                }

                $statusInfo = $statusMeta[$currentStatus] ?? $statusMeta['canceled'];
                @endphp
                <tr>
                    <td class="align-middle fw-bold text-dark">{{ $index + 1 }}</td>
                    <td class="align-middle"><span class="fw-bold">{{ $sectionSubjectTeacher->grade->name }}</span></td>
                    <td class="align-middle">
                        <a href="{{ route('sections.show', $sectionSubjectTeacher->section->id) }}">{{ $sectionSubjectTeacher->section->name }}</a>
                    </td>
                    <td class="text-center align-middle">
                        <span class="badge bg-label-secondary text-dark">{{ \Carbon\Carbon::parse($sectionSubjectTeacher->appointment->start_time)->format('h:i A') }}</span>
                        <span class="mx-1">-</span>
                        <span class="badge bg-label-secondary text-dark">{{ \Carbon\Carbon::parse($sectionSubjectTeacher->appointment->end_time)->format('h:i A') }}</span>
                    </td>

                    <td class="align-middle">
                        @if ($isLocked)
                        {{-- حصة ملغية: قفل نهائي، لا تعديل من الأستاذ --}}
                        <span class="badge {{ $statusInfo['badgeClass'] }} px-3 py-2"
                            data-bs-toggle="tooltip"
                            title="تم إلغاء هذه الحصة نهائياً. لإعادة جدولتها يرجى التواصل مع قسم الجداول الدراسية.">
                            <i class="ti ti-lock ti-xs me-1"></i>{{ $statusInfo['label'] }}
                        </span>
                        @else
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="badge {{ $statusInfo['badgeClass'] }} px-3 py-2">{{ $statusInfo['label'] }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @forelse ($alternatives as $altKey)
                                <li>
                                    @if ($altKey === 'canceled')
                                    <a class="dropdown-item d-flex align-items-center js-cancel-session"
                                        href="{{ route('sessionStatus', [$sectionSubjectTeacher->id, 'sessionStatus' => $statusMeta[$altKey]['label']]) }}">
                                        <span class="{{ $statusMeta[$altKey]['dotClass'] }}"><i class="ti ti-circle-filled ti-xs"></i></span>
                                        {{ $statusMeta[$altKey]['label'] }}
                                    </a>
                                    @else
                                    <a class="dropdown-item d-flex align-items-center"
                                        href="{{ route('sessionStatus', [$sectionSubjectTeacher->id, 'sessionStatus' => $statusMeta[$altKey]['label']]) }}">
                                        <span class="{{ $statusMeta[$altKey]['dotClass'] }}"><i class="ti ti-circle-filled ti-xs"></i></span>
                                        {{ $statusMeta[$altKey]['label'] }}
                                    </a>
                                    @endif
                                </li>
                                @empty
                                <li><span class="dropdown-item text-muted">لا يوجد خيارات متاحة</span></li>
                                @endforelse
                            </ul>
                        </div>
                        @endif
                    </td>

                    <td class="text-center align-middle">
                        <span class="badge bg-label-secondary text-dark">{{ $sectionSubjectTeacher->type === 'regular' ? 'اساسية' : 'تعويضية' }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">لا يوجد حصص درسية لهذا المدرس</td>
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