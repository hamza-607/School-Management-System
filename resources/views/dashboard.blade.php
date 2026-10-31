@extends('layouts/layoutMaster')

@section('title', 'توزيع الطلاب على الشعب')

@section('vendor-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

{{-- ============================================================
     بيانات ثابتة للمعاينة فقط (بدون كونترولر ولا قاعدة بيانات)
     ============================================================ --}}
@php
    $year = '2026 - 2027';

    $grades = [
        ['id' => 1, 'name' => 'الصف الأول', 'pending' => 0],
        ['id' => 2, 'name' => 'الصف الثاني', 'pending' => 12],
        ['id' => 3, 'name' => 'الصف الثالث', 'pending' => 0],
    ];
    $gradeId = 2;

    $sections = [
        ['id' => 1, 'name' => 'الشعبة أ', 'capacity' => 30, 'count' => 18],
        ['id' => 2, 'name' => 'الشعبة ب', 'capacity' => 30, 'count' => 30],
        ['id' => 3, 'name' => 'الشعبة ج', 'capacity' => 25, 'count' => 12],
    ];

    $students = [
        ['name' => 'أحمد خالد الحلبي', 'e_name' => 'Ahmad Khaled Alhalabi', 'repeater' => false],
        ['name' => 'أمل محمد العلي', 'e_name' => 'Amal Mohammad Alali', 'repeater' => false],
        ['name' => 'بتول سامر يوسف', 'e_name' => 'Batoul Samer Yousef', 'repeater' => false],
        ['name' => 'جود ماهر النجار', 'e_name' => 'Joud Maher Alnajjar', 'repeater' => true],
        ['name' => 'حسن علي ديب', 'e_name' => 'Hasan Ali Deeb', 'repeater' => false],
        ['name' => 'ريم عمر السيد', 'e_name' => 'Reem Omar Alsayed', 'repeater' => false],
        ['name' => 'زياد فادي منصور', 'e_name' => 'Ziad Fadi Mansour', 'repeater' => false],
        ['name' => 'سلمى نبيل حداد', 'e_name' => 'Salma Nabil Haddad', 'repeater' => false],
        ['name' => 'عمر وليد شاهين', 'e_name' => 'Omar Walid Shaheen', 'repeater' => true],
        ['name' => 'لين هاني درويش', 'e_name' => 'Leen Hani Darwish', 'repeater' => false],
        ['name' => 'محمد أنس الخطيب', 'e_name' => 'Mohammad Anas Alkhatib', 'repeater' => false],
        ['name' => 'يارا طارق إبراهيم', 'e_name' => 'Yara Tarek Ibrahim', 'repeater' => false],
    ];
@endphp

@section('page-script')
    <script>
        $(document).ready(function () {

            // ---------- حالة الشعب من الـ DOM ----------
            const sections = {};
            $('.section-box').each(function () {
                const id = $(this).data('id');
                sections[id] = {
                    name: $(this).data('name'),
                    capacity: parseInt($(this).data('capacity')),
                    count: parseInt($(this).data('count'))
                };
            });

            const free = (id) => Math.max(0, sections[id].capacity - sections[id].count);

            function refreshSections() {
                Object.keys(sections).forEach(function (id) {
                    const s = sections[id];
                    const box = $('.section-box[data-id="' + id + '"]');
                    const percent = s.capacity > 0 ? Math.min(100, Math.round(s.count / s.capacity * 100)) : 100;
                    const isFull = s.count >= s.capacity;

                    box.find('.sec-count').text(s.count + ' / ' + s.capacity);
                    box.find('.progress-bar')
                        .css('width', percent + '%')
                        .toggleClass('bg-danger', isFull)
                        .toggleClass('bg-primary', !isFull);
                    box.toggleClass('is-full', isFull);
                    box.find('.sec-full').toggleClass('d-none', !isFull);

                    const opt = $('#sectionSelect option[value="' + id + '"]');
                    opt.data('free', free(id))
                        .prop('disabled', free(id) === 0)
                        .text(s.name + ' - متاح ' + free(id) + ' من ' + s.capacity);
                });
            }

            function refreshStudents() {
                const rows = $('.student-row');
                rows.each(function (i) { $(this).find('.row-index').text(i + 1); });
                $('#pendingCount').text(rows.length);
                $('#selectedCount').text($('.enroll-check:checked').length);
                $('#checkAll').prop('checked', rows.length > 0 && $('.enroll-check:checked').length === rows.length);

                if (rows.length === 0) {
                    $('#studentsBlock').addClass('d-none');
                    $('#emptyBlock').removeClass('d-none');
                    $('#autoCard').addClass('d-none');
                }
            }

            // ---------- التحديد ----------
            $('#checkAll').on('change', function () {
                $('.enroll-check').prop('checked', this.checked);
                refreshStudents();
            });

            $(document).on('change', '.enroll-check', refreshStudents);

            $(document).on('click', '.student-row', function (e) {
                if ($(e.target).is('input')) return;
                const box = $(this).find('.enroll-check');
                box.prop('checked', !box.prop('checked')).trigger('change');
            });

            // ---------- الصف: للمعاينة فقط ----------
            $('#gradeSelect').on('change', function () {
                Swal.fire({
                    icon: 'info',
                    title: 'معاينة ثابتة',
                    text: 'تبديل الصف غير مفعّل بهذه المعاينة.',
                    confirmButtonText: 'حسناً'
                });
                $(this).val('{{ $gradeId }}');
            });

            // ---------- توزيع يدوي (محاكاة) ----------
            $('#assignForm').on('submit', function (e) {
                e.preventDefault();

                const id = $('#sectionSelect').val();
                const selected = $('.enroll-check:checked');

                if (!id) {
                    Swal.fire({ icon: 'warning', title: 'اختر الشعبة أولاً', confirmButtonText: 'حسناً' });
                    return;
                }
                if (selected.length === 0) {
                    Swal.fire({ icon: 'warning', title: 'اختر طالباً واحداً على الأقل', confirmButtonText: 'حسناً' });
                    return;
                }
                if (selected.length > free(id)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'السعة غير كافية',
                        text: 'السعة المتبقية في هذه الشعبة ' + free(id) + ' فقط، وأنت حددت ' + selected.length + ' طالب.',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                const n = selected.length;
                selected.closest('tr').remove();
                sections[id].count += n;
                $('#sectionSelect').val('');
                refreshSections();
                refreshStudents();

                Swal.fire({
                    icon: 'success',
                    title: 'تم توزيع ' + n + ' طالب على ' + sections[id].name,
                    timer: 1800,
                    showConfirmButton: false
                });
            });

            // ---------- توزيع تلقائي (محاكاة) ----------
            $('#autoBtn').on('click', function () {
                const pending = $('.student-row').length;
                Swal.fire({
                    title: 'توزيع تلقائي',
                    html: 'سيتم توزيع <strong>' + pending + '</strong> طالب بشكل متوازن على شعب هذا الصف حسب السعة المتاحة.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، وزّع',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-primary ms-2',
                        cancelButton: 'btn btn-label-secondary'
                    }
                }).then(function (result) {
                    if (!result.isConfirmed) return;

                    let assigned = 0;
                    $('.student-row').each(function () {
                        // أقل شعبة عدداً وما زال فيها مكان
                        let target = null;
                        Object.keys(sections).forEach(function (id) {
                            if (free(id) > 0 && (target === null || sections[id].count < sections[target].count)) {
                                target = id;
                            }
                        });
                        if (target === null) return false;

                        sections[target].count++;
                        assigned++;
                        $(this).remove();
                    });

                    refreshSections();
                    refreshStudents();

                    const left = $('.student-row').length;
                    Swal.fire({
                        icon: left > 0 ? 'warning' : 'success',
                        title: 'تم توزيع ' + assigned + ' طالب تلقائياً',
                        text: left > 0 ? 'بقي ' + left + ' طالب بلا شعبة لأن سعة الشعب ممتلئة.' : '',
                        confirmButtonText: 'حسناً'
                    });
                });
            });
        });
    </script>
@endsection

@section('content')
    <style>
        :root {
            --ink: #00352e;
            --ink-soft: #3a5350;
            --primary: #006559;
            --cream: #f5f8f7;
            --line: #dbe6e3;
            --burgundy: #7a3540;
        }

        .dist-filter-bar {
            background: var(--cream);
            border-bottom: 1px solid var(--line);
            padding: 1.25rem 1.5rem;
        }

        .student-row {
            cursor: pointer;
        }

        .section-box {
            border: 1px solid var(--line);
            border-radius: .4rem;
            padding: .85rem 1rem;
            background: #fff;
        }

        .section-box.is-full {
            background: var(--cream);
        }

        .section-box .progress {
            height: 6px;
        }
    </style>

<h1 class="text-center">صفحة تجريبية</h1>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">السنة الدراسية {{ $year }}</span>
            <span class="border-bottom border-2 border-primary">توزيع الطلاب على الشعب</span>
        </h4>
    </div>

    <div class="card mb-4">
        <div class="dist-filter-bar">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1" for="gradeSelect">الصف</label>
                    <select id="gradeSelect" class="form-select">
                        @foreach ($grades as $grade)
                            <option value="{{ $grade['id'] }}" {{ $gradeId === $grade['id'] ? 'selected' : '' }}>
                                {{ $grade['name'] }} ({{ $grade['pending'] }} بلا شعبة)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-8 text-md-end">
                    <span class="text-muted">طلاب بلا شعبة في هذا الصف:</span>
                    <span class="badge bg-label-primary px-3" id="pendingCount">{{ count($students) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">طلاب بلا شعبة</h5>
                    <span class="text-muted">المحدد: <strong id="selectedCount">0</strong></span>
                </div>

                <div id="emptyBlock" class="card-body text-center py-5 d-none">
                    <i class="ti ti-circle-check ti-xl text-success mb-2"></i>
                    <h5 class="mb-1">كل طلاب هذا الصف موزّعون</h5>
                    <p class="text-muted mb-0">ما باقي أي طالب بلا شعبة بهذا الصف.</p>
                </div>

                <form id="assignForm" onsubmit="return false;">
                    <div id="studentsBlock">
                        <div class="dist-filter-bar">
                            <div class="row g-3 align-items-end">
                                <div class="col-12 col-md-7">
                                    <label class="fw-bold mb-1" for="sectionSelect">نقل المحدّدين إلى الشعبة</label>
                                    <select id="sectionSelect" class="form-select">
                                        <option value="">اختر الشعبة</option>
                                        @foreach ($sections as $section)
                                            @php $free = max(0, $section['capacity'] - $section['count']); @endphp
                                            <option value="{{ $section['id'] }}" data-free="{{ $free }}"
                                                {{ $free === 0 ? 'disabled' : '' }}>
                                                {{ $section['name'] }} - متاح {{ $free }} من {{ $section['capacity'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-5">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ti ti-arrows-exchange me-1"></i> نقل المحدّدين
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover border-top mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">
                                            <input type="checkbox" id="checkAll" class="form-check-input"
                                                aria-label="تحديد الكل">
                                        </th>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>الطالب</th>
                                        <th class="text-center">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($students as $index => $student)
                                        <tr class="student-row">
                                            <td class="text-center">
                                                <input type="checkbox" class="form-check-input enroll-check">
                                            </td>
                                            <td class="text-center row-index">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="fw-bold">{{ $student['name'] }}</span>
                                                <small class="d-block text-muted">{{ $student['e_name'] }}</small>
                                            </td>
                                            <td class="text-center">
                                                @if ($student['repeater'])
                                                    <span class="badge bg-label-danger">معيد</span>
                                                @else
                                                    <span class="badge bg-label-secondary">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card mb-4">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">شعب هذا الصف</h5>
                </div>
                <div class="card-body pt-3">
                    <div class="d-flex flex-column gap-3">
                        @foreach ($sections as $section)
                            @php
                                $percent = $section['capacity'] > 0
                                    ? min(100, round(($section['count'] / $section['capacity']) * 100))
                                    : 100;
                                $isFull = $section['count'] >= $section['capacity'];
                            @endphp
                            <div class="section-box {{ $isFull ? 'is-full' : '' }}" data-id="{{ $section['id'] }}"
                                data-name="{{ $section['name'] }}" data-capacity="{{ $section['capacity'] }}"
                                data-count="{{ $section['count'] }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <a href="#" class="fw-bold">{{ $section['name'] }}</a>
                                    <span class="text-muted sec-count">{{ $section['count'] }} / {{ $section['capacity'] }}</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar {{ $isFull ? 'bg-danger' : 'bg-primary' }}"
                                        role="progressbar" style="width: {{ $percent }}%"></div>
                                </div>
                                <small class="text-danger d-block mt-1 sec-full {{ $isFull ? '' : 'd-none' }}">الشعبة ممتلئة</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card" id="autoCard">
                <div class="card-body">
                    <h6 class="fw-bold mb-1">توزيع تلقائي</h6>
                    <p class="text-muted mb-3">
                        بيوزع كل طلاب الصف بلا شعبة بشكل متوازن على الشعب، حسب الترتيب الأبجدي والسعة المتاحة.
                    </p>
                    <button type="button" id="autoBtn" class="btn btn-label-primary w-100">
                        <i class="ti ti-wand me-1"></i> توزيع تلقائي
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection