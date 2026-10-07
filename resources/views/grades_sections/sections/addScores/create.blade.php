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
            const footerTotal = document.getElementById('footerTotal');

            // يشيل الأصفار الزائدة: 18.50 -> 18.5 ، 20.00 -> 20
            const fmt = (n) => (+n.toFixed(2)).toString();

            function recalc() {
                let allTotal = 0;

                body.querySelectorAll('tr').forEach(function (row) {
                    let rowTotal = 0;

                    row.querySelectorAll('.grade-input').forEach(function (input) {
                        const val = parseFloat(input.value);
                        const max = parseFloat(input.max);

                        // حقل فاضي (طالب غائب): ما بينحسب وما بينعلّم
                        if (isNaN(val)) {
                            input.classList.remove('is-invalid');
                            return;
                        }

                        // علامة سالبة أو أكبر من العلامة العظمى: بتتعلّم وما بتنحسب
                        if (val < 0 || (!isNaN(max) && val > max)) {
                            input.classList.add('is-invalid');
                            return;
                        }

                        input.classList.remove('is-invalid');
                        rowTotal += val;
                    });

                    const cell = row.querySelector('.row-total');
                    if (cell) {
                        cell.textContent = fmt(rowTotal);
                    }
                    allTotal += rowTotal;
                });

                footerTotal.textContent = fmt(allTotal);
            }

            body.addEventListener('input', function (e) {
                if (e.target.classList.contains('grade-input')) {
                    recalc();
                }
            });

            // حساب أولي (لو في علامات محفوظة مسبقاً)
            recalc();
        });
    </script>
@endsection
@section('content')
    <style>
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
            color:  #00352e;
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
    </style>
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
            <span class="d-block fs-6 fw-medium text-muted">إدارة العلامات</span>
            <a href="{{ route('sectionScores.index') }}" class="text-muted">القائمة</a> /
            <span class="border-bottom border-2 border-primary">إدخال النتائج</span>
        </h4>
    </div>

    @php
        // مجموع العلامات العظمى لكل المكونات (عمود الجدول اسمه max_score)
        $maxTotal = $scoreComponents->sum('max_score');
    @endphp

    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">جدول النتائج</h5>

            <div class="mt-4 text-end">
                <button type="submit" class="btn-staff-primary" form="updateScoresForm" id="saveScoresBtn">حفظ التعديلات <i
                        class="ti ti-device-floppy me-1"></i>
                </button>
                <a href="{{ url()->previous() }}" class="btn-staff-outline">رجوع</a>
            </div>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <form action="{{ route('sectionScores.store', $section->id) }}" id="updateScoresForm" method="POST">
                    @csrf
                </form>

                <table class="table table-bordered text-center align-middle" id="resultsTable">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2">اسم الطالب</th>
                            <th colspan="2" class="final-col">{{ $semester->name }}</th>
                        </tr>
                        <tr>
                            <th class="quizzes-col">الإختبارات</th>
                            <th class="total-col">المجموع</th>
                        </tr>
                    </thead>
                    <tbody id="resultsBody">
                        @foreach ($students as $indexStudent => $student)
                            <tr>
                                <td>
                                    {{ $student->name }}
                                    <input type="hidden" form="updateScoresForm" name="students[{{ $indexStudent }}][student]"
                                        value="{{ $student->id }}">
                                </td>
                                <td>
                                    @forelse ($scoreComponents as $scoreComponent)
                                        <div class="small mb-1 d-flex flex-column align-items-center justify-content-center gap-1">
                                            <span class="text-muted">{{ $scoreComponent->name }}:</span>
                                            <div class="d-flex align-items-center gap-1">
                                                <input type="number" class="form-control form-control-sm grade-input"
                                                    form="updateScoresForm"
                                                    name="students[{{ $indexStudent }}][scores][{{ $scoreComponent->id }}]" min="0"
                                                    max="{{ $scoreComponent->max_score }}" step="0.01" placeholder="0"
                                                    value="{{ old("students[$indexStudent][scores][$scoreComponent->id]") }}">
                                                <span class="text-muted">/{{ $scoreComponent->max_score }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted">لا يوجد اختبارات او امتحان منتهي بعد</p>
                                    @endforelse
                                </td>

                                <td class="total-col">
                                    <span class="fw-bold row-total">0</span>
                                    @if ($maxTotal > 0)
                                        <span class="text-muted">/{{ $maxTotal + 0 }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection