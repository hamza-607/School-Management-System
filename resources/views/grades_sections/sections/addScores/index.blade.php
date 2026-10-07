@extends('layouts/layoutMaster')

@section('title', 'العلامات')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" />
@endsection

@section('page-style')
    <style>
        :root {
            --ink: #00352e;
            --ink-soft: #3a5350;
            --primary: #006559;
            --cream: #f5f8f7;
            --line: #dbe6e3;
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

        .btn-staff-outline {
            border: 1px solid var(--line);
            background: var(--cream);
            color: var(--ink-soft);
            border-radius: .3rem;
            padding: .28rem 1.1rem;
            font-size: .9rem;
            font-weight: 600;
            transition: border-color .15s ease, color .15s ease;
        }

        .btn-staff-outline:hover {
            border-color: var(--primary);
            color: var(--ink);
        }
    </style>
@endsection

@section('content')
    <div class="theme-page">

        <div class="mb-4">
            <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
                <span class="d-block fs-6 fw-medium text-muted">إدارة العلامات</span>
                <span class="border-bottom border-2 border-primary">القائمة</span>
            </h4>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            @forelse ($sections as $section)
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
                                <i class="ti ti-users ti-xs me-1"></i>{{ $section->students->count() }} طالب
                            </div>
                        </div>
                        @php
                            $semesterID = session('newCurrentSemesterID') ?? \App\Models\Semester::where('is_current', 1)->first()->id;
                            $hasScores = \App\Models\StudentScore::whereHas('score_component', function ($q) use ($semesterID, $section) {
                                $q->where('semester_id', $semesterID)->whereHas('finished_session', function ($q2) use ($section) {
                                    $q2->where('section_id', $section->id);
                                });
                            })
                                ->exists();
                        @endphp
                        <div class="card-footer d-flex gap-2 py-2 px-3">
                            @if (!$hasScores)
                                <a href="{{ route('sectionScores.create', $section->id) }}"
                                    class="btn btn-primary btn-sm py-1 flex-grow-1">
                                    <i class="ti ti-edit ti-xs me-1"></i>إدخال العلامات
                                </a>
                            @else
                                <span class="btn btn-sm btn-light-success disabled py-1 flex-grow-1 text-success fw-medium">
                                    <i class="ti ti-check ti-xs me-1"></i>تم إدخال العلامات
                                </span>
                            @endif

                            <button class="btn-staff-outline">
                                <i class="ti ti-download me-1"></i> طباعة
                            </button>
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
@endsection