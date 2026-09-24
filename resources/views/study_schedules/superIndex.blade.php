@extends('layouts/layoutMaster')

@section('title', 'الحصص الدرسية')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-style')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>

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

    .theme-page {
     /* font-family: 'Cairo', sans-serif; */
     color: var(--ink-soft); }

    /* .theme-breadcrumb { font-family: 'Cairo', sans-serif; } */
    .theme-breadcrumb a { color: #8b8577; text-decoration: none; }

    .theme-card {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: .35rem;
        box-shadow: 0 1px 3px rgba(0,53,46,.05);
    }
    .theme-card-header {
        border-bottom: 1px solid var(--line);
        padding: 1.35rem 1.5rem;
    }
    .theme-card-title {
        /* font-family: 'Amiri', serif; */
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }

    /* Day picker */
    .theme-day-pill {
        display: block;
        min-width: 92px;
        border: 1px solid var(--line);
        border-radius: .4rem;
        padding: .55rem .9rem;
        text-align: center;
        font-weight: 700;
        font-size: .9rem;
        color: var(--ink-soft);
        background: #fff;
        transition: border-color .15s ease, background .15s ease, color .15s ease;
    }
    .theme-day-pill:hover { border-color: var(--primary); color: var(--ink); }
    .theme-day-pill.active { background: var(--primary); border-color: var(--primary); color: #fff; }
    .theme-day-pill small { display: block; font-size: .68rem; font-weight: 600; opacity: .85; margin-top: .15rem; }

    .table-theme { margin: 0; }
    .table-theme thead th {
        /* font-family: 'Cairo', sans-serif; */
        font-weight: 700;
        font-size: .86rem;
        color: var(--ink);
        background: var(--cream);
        border-bottom: 1px solid var(--line);
        border-top: none;
        padding: .9rem 1rem;
        white-space: nowrap;
    }
    .table-theme tbody td {
        padding: .85rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--line);
        font-size: .92rem;
    }
    .table-theme .theme-link { color: var(--ink-soft); text-decoration: none; border-bottom: 1px dashed var(--line); }
    .table-theme .theme-link:hover { color: var(--ink); border-color: var(--primary); }

    .theme-badge-neutral {
        display: inline-flex;
        align-items: center;
        padding: .3rem .75rem;
        border-radius: .3rem;
        background: var(--cream);
        border: 1px solid var(--line);
        color: var(--ink-soft);
        font-size: .82rem;
        font-weight: 600;
    }

    /* حالة الحصة */
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
    .theme-status-pill .dot { width: 6px; height: 6px; border-radius: 50%; }
    .theme-status-active { background: var(--sage-bg); color: var(--sage); border-color: #cfe3df; }
    .theme-status-active .dot { background: var(--sage); }
    .theme-status-inactive { background: var(--burgundy-bg); color: var(--burgundy); border-color: #e6d4d4; }
    .theme-status-inactive .dot { background: var(--burgundy); }
    .theme-status-pending { background: var(--amber-bg); color: var(--amber); border-color: #e6dab8; }
    .theme-status-pending .dot { background: var(--amber); }
    .theme-status-locked { cursor: default; }

    .theme-status-menu {
        border: 1px solid var(--line);
        border-radius: .35rem;
        padding: .4rem;
        box-shadow: 0 4px 14px rgba(0,53,46,.08);
    }
    .theme-status-menu .dropdown-item {
        border-radius: .3rem;
        font-size: .88rem;
        padding: .5rem .7rem;
        /* font-family: 'Cairo', sans-serif; */
    }
    .theme-status-menu .dropdown-item:hover { background: var(--cream); }
    .theme-status-menu .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-inline-end: .5rem; }
    .status-dot-active { background: var(--sage); }
    .status-dot-canceled { background: var(--burgundy); }
    .status-dot-scheduled { background: var(--amber); }

    .theme-empty-row { padding: 2rem; text-align: center; color: #8b8577;
     /* font-family: 'Amiri', serif; font-size: 1.05rem; */
     }

    /* بطاقات البرامج الدراسية */
    .col-count { width: 150px !important; text-align: center; }
    .address-truncate { max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .study-program-card {
        border-radius: .5rem;
        border: 1px solid var(--line) !important;
        transition: all .25s ease;
        cursor: pointer;
    }
    .study-program-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .25rem 1rem rgba(0,53,46,.08);
        border-color: var(--primary) !important;
    }
    .study-program-card-link:hover .study-program-arrow { transform: translateX(-4px); color: var(--primary) !important; }
    .study-program-arrow { transition: transform .25s ease, color .15s ease; }

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
        /* font-family: 'Amiri', serif;  */
        color: var(--ink); font-weight: 700; }
    .study-program-badge {
        display: inline-flex;
        padding: .25rem .7rem;
        border-radius: 1rem;
        background: var(--sage-bg);
        color: var(--sage);
        border: 1px solid #cfe3df;
        font-size: .8rem;
        font-weight: 600;
    }
    .study-program-card .card-footer {
        background: transparent;
        border-top: 1px solid var(--line) !important;
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
<div class="theme-page">

    <h4 class="fw-bold py-3 mb-4 theme-breadcrumb">
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
    'scheduled' => ['label' => 'مجدولة', 'badgeClass' => 'theme-status-pending', 'dotClass' => 'status-dot status-dot-scheduled'],
    'active' => ['label' => 'نشطة', 'badgeClass' => 'theme-status-active', 'dotClass' => 'status-dot status-dot-active'],
    'canceled' => ['label' => 'ملغية', 'badgeClass' => 'theme-status-inactive', 'dotClass' => 'status-dot status-dot-canceled'],
    ];
    @endphp

    @if (Auth::user()->staff->staff_type === 'teacher')
    <div class="theme-card">
        <div class="theme-card-header">
            <h5 class="theme-card-title">قائمة حصص المدرس {{ Auth::user()->name }}</h5>
        </div>

        {{-- كاردات أيام الأسبوع (فلتر) --}}
        <div class="p-4 pb-2">
            <div class="row row-cols-auto g-2">
                @foreach ($daysMap as $dayKey => $dayLabel)
                <div class="col">
                    <a href="{{ request()->fullUrlWithQuery(['day' => $dayKey]) }}" class="text-decoration-none">
                        <div class="theme-day-pill {{ $selectedDay === $dayKey ? 'active' : '' }}">
                            {{ $dayLabel }}
                            @if ($dayKey === $today)
                            <small>اليوم</small>
                            @endif
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-theme">
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
                <tbody>
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
                        <td class="align-middle fw-bold" style="color: var(--ink);">{{ $index + 1 }}</td>
                        <td class="align-middle"><span class="fw-bold">{{ $sectionSubjectTeacher->grade->name }}</span></td>
                        <td class="align-middle">
                            <a href="{{ route('sections.show', $sectionSubjectTeacher->section->id) }}" class="theme-link">{{ $sectionSubjectTeacher->section->name }}</a>
                        </td>
                        <td class="text-center align-middle">
                            <span class="theme-badge-neutral">{{ \Carbon\Carbon::parse($sectionSubjectTeacher->appointment->start_time)->format('h:i A') }}</span>
                            <span class="mx-1">-</span>
                            <span class="theme-badge-neutral">{{ \Carbon\Carbon::parse($sectionSubjectTeacher->appointment->end_time)->format('h:i A') }}</span>
                        </td>

                        <td class="align-middle">
                            @if ($isLocked)
                            {{-- حصة ملغية: قفل نهائي، لا تعديل من الأستاذ --}}
                            <span class="theme-status-pill {{ $statusInfo['badgeClass'] }} theme-status-locked"
                                data-bs-toggle="tooltip"
                                title="تم إلغاء هذه الحصة نهائياً. لإعادة جدولتها يرجى التواصل مع قسم الجداول الدراسية.">
                                <i class="ti ti-lock ti-xs"></i>{{ $statusInfo['label'] }}
                            </span>
                            @else
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="theme-status-pill {{ $statusInfo['badgeClass'] }}"><span class="dot"></span>{{ $statusInfo['label'] }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end theme-status-menu">
                                    @forelse ($alternatives as $altKey)
                                    <li>
                                        @if ($altKey === 'canceled')
                                        <a class="dropdown-item d-flex align-items-center js-cancel-session"
                                            href="{{ route('sessionStatus', [$sectionSubjectTeacher->id, 'sessionStatus' => $statusMeta[$altKey]['label']]) }}">
                                            <span class="{{ $statusMeta[$altKey]['dotClass'] }}"></span>
                                            {{ $statusMeta[$altKey]['label'] }}
                                        </a>
                                        @else
                                        <a class="dropdown-item d-flex align-items-center"
                                            href="{{ route('sessionStatus', [$sectionSubjectTeacher->id, 'sessionStatus' => $statusMeta[$altKey]['label']]) }}">
                                            <span class="{{ $statusMeta[$altKey]['dotClass'] }}"></span>
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
                            <span class="theme-badge-neutral">{{ $sectionSubjectTeacher->type === 'regular' ? 'اساسية' : 'تعويضية' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="theme-empty-row">لا يوجد حصص درسية لهذا المدرس</div>
                        </td>
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
                        <div class="study-program-icon">
                            <i class="ri-book-2-line ri-24px"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $section->grade->name }}</h6>
                            <span class="study-program-badge">شعبة {{ $section->name }}</span>
                        </div>
                        <div class="study-program-arrow text-muted">
                            <i class="ri-arrow-left-s-line ri-20px"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center py-2">
                        <small class="text-muted">عدد المواد</small>
                        <small class="fw-bold" style="color: var(--ink);">{{ $section->section_subject_teachers->groupBy('subject_id')->count() }}</small> {{-- جيب عدد المواد الحقيقي --}}
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection