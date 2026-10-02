@extends('layouts/layoutMaster')

@section('title', 'السنوات الدراسية')

@section('content')

<h4 class="fw-bold py-3 mb-4">السنوات الدراسية</h4>

<div class="card">
    <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
        <h5 class="mb-0">قائمة السنوات الدراسية</h5>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addYearModal">
            <i class="ti ti-plus me-1"></i> سنة دراسية جديدة
        </button>
    </div>

    <div class="card-body">
        <div class="accordion" id="yearsAccordion">
            @forelse ($academicYears as $year)
            <div class="accordion-item">
                <h2 class="accordion-header d-flex align-items-center">
                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#yearCollapse{{ $year->id }}">
                        <span class="fw-bold">{{ $year->name }}</span>
                        <span class="text-muted ms-2">
                            {{ $year->start_date }} &larr; {{ $year->end_date }}
                        </span>
                        @if ($year->is_current)
                        <span class="badge bg-label-success ms-2">السنة الحالية</span>
                        @endif
                    </button>
                </h2>

                <div id="yearCollapse{{ $year->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                    data-bs-parent="#yearsAccordion">
                    <div class="accordion-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            {{-- @if (!$year->is_current)
                            <form action="" method="POST"> 
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="ti ti-star me-1"></i> تعيين كسنة حالية
                                </button>
                            </form>
                            @else
                            <span></span>
                            @endif --}}

                            <button type="button" class="btn btn-sm btn-outline-primary add-semester-btn"
                                data-bs-toggle="modal" data-bs-target="#addSemesterModal"
                                data-year-id="{{ $year->id }}">
                                <i class="ti ti-plus me-1"></i> إضافة فصل
                            </button>
                        </div>

                        <ul class="list-group">
                            @forelse ($year->semesters as $semester)
                            <li class="list-group-item d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                                <div>
                                    <span class="fw-semibold">{{ $semester->name }}</span>
                                    <span class="text-muted ms-2">
                                        {{ $semester->start_date }} &larr; {{ $semester->end_date }}
                                    </span>
                                    @if ($semester->is_current)
                                    <span class="badge bg-label-success ms-2">الفصل الحالي</span>
                                    @endif
                                </div>

                                {{-- @if (!$semester->is_current)
                                <form action="" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        تعيين كحالي
                                    </button>
                                </form>
                                @endif --}}
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
            <div class="modal-header">
                <h5 class="modal-title">إضافة سنة دراسية جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">اسم السنة</label>
                    <input type="text" name="name" class="form-control" placeholder="مثال: 2025/2026" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">تاريخ البداية</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">تاريخ النهاية</label>
                        <input type="date" name="end_date" class="form-control" required>
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

{{-- ===== Modal: إضافة فصل ===== --}}
<div class="modal fade" id="addSemesterModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('semesters.store') }} " method="POST" class="modal-content">
            @csrf
            
            <input type="hidden" name="academic_year_id" id="semesterYearId" value="">
            <div class="modal-header">
                <h5 class="modal-title">إضافة فصل دراسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">اسم الفصل</label>
                    <input type="text" name="name" class="form-control" placeholder="مثال: الفصل الأول" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">تاريخ البداية</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">تاريخ النهاية</label>
                        <input type="date" name="end_date" class="form-control" required>
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

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // نمرر academic_year_id الصحيح لمودال "إضافة فصل" حسب أي سنة ضغط المستخدم زرها
        document.querySelectorAll('.add-semester-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('semesterYearId').value = btn.dataset.yearId;
            });
        });
    });
</script>
@endsection