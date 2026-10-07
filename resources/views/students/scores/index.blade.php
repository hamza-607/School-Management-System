@extends('layouts/layoutMaster')

@section('title', 'العقوبات الطلابية')

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.getElementById('resultsBody');
            const semesterCount = {{
        count($semesters)
                }};

            function getValue(el) {
                if (!el) return 0;
                const raw = 'value' in el ? el.value : el.textContent;
                return parseFloat(raw) || 0;
            }

            function calculateRow(row) {
                const cells = row.querySelectorAll(':scope > td');
                const totals = [];

                for (let s = 0; s < semesterCount; s++) {
                    const startIndex = 1 + (s * 3);
                    const quizzesCell = cells[startIndex];
                    const finalCell = cells[startIndex + 1];
                    const totalCell = cells[startIndex + 2];

                    let sum = 0;

                    quizzesCell.querySelectorAll('.grade-input').forEach(function (el) {
                        sum += getValue(el);
                    });

                    const finalEl = finalCell.querySelector('.final-input');
                    sum += getValue(finalEl);

                    totalCell.textContent = sum;
                    totals.push(sum);
                }

                return totals;
            }

            function calculateAll() {
                const grandTotals = new Array(semesterCount).fill(0);

                body.querySelectorAll(':scope > tr').forEach(function (row) {
                    const rowTotals = calculateRow(row);
                    rowTotals.forEach(function (total, i) {
                        grandTotals[i] += total;
                    });
                });

                document.querySelectorAll('.grand-total-cell').forEach(function (cell) {
                    const i = parseInt(cell.dataset.semesterIndex, 10);
                    cell.textContent = grandTotals[i];
                });
            }

            body.querySelectorAll('input.grade-input, input.final-input').forEach(function (input) {
                input.addEventListener('input', calculateAll);
            });

            calculateAll();
        });
    </script>
@endsection
@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">إدارة الطلاب</span>
            <a href="{{ route('students.index') }}" class="text-muted">القائمة / </a>
            <a href="{{ route('students.show', $theStudent->id) }}" class="text-muted">تفاصيل الطالب
                {{ $theStudent->name }}</a> /
            <span class="border-bottom border-2 border-primary"> النتائج</span>
        </h4>
    </div>

    <x-nav :student="$theStudent" />

    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">جدول النتائج</h5>

            <div class="d-flex align-items-center gap-2">
                @if (!$updateScores)
                    <a href="{{ route('scores.index', [$theStudent->id, 'updateScores' => true]) }}"
                        class="btn btn-sm btn-outline-primary text-primary">
                        <i class="ti ti-edit-circle me-1 "></i> تعديل
                    </a>
                @endif

                @if ($updateScores)
                    <button type="submit" id="saveScoresBtn" class="btn btn-sm btn-success" form="updateScoresForm">
                        <i class="ti ti-device-floppy me-1"></i> حفظ التعديلات
                    </button>
                @endif

                @if ($updateScores)
                    <a href="{{ route('scores.index', $theStudent->id) }}" class="btn btn-secondary">
                        رجوع
                    </a>
                @endif

                @if (!$updateScores)
                    <div class="vr mx-1"></div>
                    <button type="button" class="btn btn-sm btn-label-secondary">
                        <i class="ti ti-download me-1"></i> طباعة
                    </button>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <form action="{{ route('scores.update', [$theStudent->id, null]) }}" id="updateScoresForm" method="POST">
                    @csrf
                    @method('PUT')
                </form>
                <table class="table table-bordered text-center align-middle" id="resultsTable">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2">اسم المادة</th>
                            @foreach ($semesters as $oneSemester)
                                <th colspan="3" class="final-col">{{ $oneSemester['name'] }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($semesters as $s)
                                <th class="quizzes-col">الإختبارات</th>
                                <th class="final-col">الامتحان النهائي</th>
                                <th class="total-col">المجموع</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody id="resultsBody">

                        @foreach ($scoresAsArray as $subjectIndex => $oneSubectScore)

                            <tr>
                                <td>
                                    {{ $oneSubectScore['name'] }}
                                    @if ($updateScores)
                                        <input type="hidden" form="updateScoresForm" name="scores[{{ $subjectIndex }}][subjectID]"
                                            value="{{ $oneSubectScore['id'] }}">
                                    @endif
                                </td>

                                @foreach ($oneSubectScore['semesters'] as $semesterIndex => $oneSemester)

                                    @if ($updateScores)
                                        <input type="hidden" form="updateScoresForm"
                                            name="scores[{{ $subjectIndex }}][semesters][{{ $semesterIndex }}][id]"
                                            value="{{ $oneSemester['id'] }}">
                                    @endif

                                    <td class="quizzes-col">
                                        @forelse($oneSemester['quizzes'] as $index => $oneQuiz)

                                            @if ($updateScores)
                                                <div class="small mb-1 d-flex flex-column align-items-center justify-content-center gap-1">
                                                    <span class="text-muted">{{ $oneQuiz['scoreName'] }}:</span>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <input type="number" form="updateScoresForm"
                                                            class="form-control form-control-sm grade-input"
                                                            name="scores[{{ $subjectIndex }}][semesters][{{ $semesterIndex }}][quizzes][{{ $index }}][value]"
                                                            value="{{ $oneQuiz['scoreValue'] }}" min="0"
                                                            max="{{ $oneQuiz['scoreMaxValue'] }}" placeholder="0"
                                                            title="العلامة هي : {{ $oneQuiz['scoreValue'] }}">
                                                        <span class="text-muted">/{{ $oneQuiz['scoreMaxValue'] }}</span>
                                                    </div>
                                                </div>

                                                <input type="hidden" form="updateScoresForm"
                                                    name="scores[{{ $subjectIndex }}][semesters][{{ $semesterIndex }}][quizzes][{{ $index }}][id]"
                                                    value="{{ $oneQuiz['scoreID'] }}">
                                            @else
                                                <div class="small mb-1 d-flex flex-column align-items-center justify-content-center gap-1">
                                                    <span class="text-muted">{{ $oneQuiz['scoreName'] }}:</span>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <div class="form-control form-control-sm grade-input"
                                                            title="العلامة هي : {{ $oneQuiz['scoreValue'] }}">{{ $oneQuiz['scoreValue'] }}
                                                        </div>
                                                        <span class="text-muted">/{{ $oneQuiz['scoreMaxValue'] }}</span>
                                                    </div>
                                                </div>
                                            @endif

                                        @empty
                                            <div class="small mb-1 d-flex align-items-center justify-content-center gap-1">غير محدد
                                            </div>
                                        @endforelse
                                    </td>

                                    @if ($oneSemester['final'])
                                        @if ($updateScores)
                                            <td class="final-col">
                                                <div class="small mb-1 d-flex align-items-center justify-content-center gap-1">
                                                    <input type="number" form="updateScoresForm"
                                                        class="form-control form-control-sm final-input"
                                                        name="scores[{{ $subjectIndex }}][semesters][{{ $semesterIndex }}][final][value]"
                                                        value="{{ $oneSemester['final']['scoreValue'] }}" min="0" placeholder="0.00"
                                                        title="العلامة هي : {{ $oneSemester['final']['scoreValue'] }}">

                                                    <span class="text-muted">/{{ $oneSemester['final']['scoreMaxValue']  }}</span>
                                                </div>

                                                <input type="hidden" form="updateScoresForm"
                                                    name="scores[{{ $subjectIndex }}][semesters][{{ $semesterIndex }}][final][id]"
                                                    value="{{ $oneSemester['final']['scoreID'] }}">
                                            </td>
                                        @else
                                            <td class="final-col">
                                                <div class="small mb-1 d-flex align-items-center justify-content-center gap-1">
                                                    <div class="form-control form-control-sm final-input"
                                                        title="العلامة هي : {{ $oneSemester['final']['scoreValue'] }}">
                                                        {{ $oneSemester['final']['scoreValue'] }}</div>
                                                    <span class="text-muted">/{{ $oneSemester['final']['scoreMaxValue']  }}</span>
                                                </div>
                                            </td>
                                        @endif
                                    @else
                                        <td class="final-col">
                                            <div class="small mb-1 d-flex align-items-center justify-content-center gap-1">
                                                غير محدد
                                            </div>
                                        </td>
                                    @endif
                                    <td class="total-col fw-bold">0</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr class="table-secondary fw-bold" id="totalsRow">
                            <td>المجموع النهائي</td>
                            @foreach ($semesters as $semesterIndex => $s)
                                <td class="quizzes-col" colspan="2"></td>
                                <td class="total-col grand-total-cell" data-semester-index="{{ $semesterIndex }}">0</td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


@endsection