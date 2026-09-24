@extends('layouts/layoutMaster')

@section('title', 'العقوبات الطلابية')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">الطلاب /
        <a href="{{ route('students.index') }}" class="text-muted">القائمة</a> /
        <a href="{{ route('students.show', $theStudent->id) }}" class="text-muted">تفاصيل الطالب {{ $theStudent->name }}</a> /
    </span> النتائج
</h4>

<x-nav :student="$theStudent" />

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">جدول النتائج</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered text-center align-middle" id="resultsTable">
            <thead class="table-light">
                <tr>
                    <th>اسم المادة</th>
                    <th class="quizzes-col">الإختبارات</th>
                    <th class="final-col">الامتحان النهائي</th>
                    <th class="total-col">المجموع</th>
                </tr>
            </thead>
            <tbody id="resultsBody">
                @foreach ($theStudent->section->section_subject_teachers as $oneItem) 
                <tr>
                    <td>{{ $oneItem->subject->name }}</td>
                    <td class="quizzes-col">
                        <div class="quiz-inputs d-flex flex-wrap gap-1 justify-content-center mb-1"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary add-quiz-btn">
                            <i class="bx bx-plus">+</i> 
                        </button>
                    </td>
                    <td class="final-col"><input type="number" class="form-control form-control-sm final-input" value="" min="0" placeholder="0.00"></td>
                    <td class="total-col fw-bold">0</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-secondary fw-bold" id="totalsRow">
                    <td>المجموع النهائي</td>
                    <td class="quizzes-col" colspan="2"></td>
                    <td class="total-col" id="grandTotal">0</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.getElementById('resultsBody');

    function addQuizToRow(row) {
        const container = row.querySelector('.quiz-inputs');

        const wrapper = document.createElement('div');
        wrapper.classList.add('quiz-input-wrapper', 'position-relative');
        wrapper.style.width = '70px';

        const input = document.createElement('input');
        input.type = 'number';
        input.classList.add('form-control', 'form-control-sm', 'quiz-input');
        input.value = '';
        input.min = '0';
        input.placeholder = '0.00';
        input.addEventListener('input', calculateAll);

        wrapper.appendChild(input);
        container.appendChild(wrapper);

        calculateAll();
    }

    function attachRowButton(row) {
        const btn = row.querySelector('.add-quiz-btn');
        btn.addEventListener('click', function () {
            addQuizToRow(row);
        });
    }

    function attachFinalListener(row) {
        const finalInput = row.querySelector('.final-input');
        finalInput.addEventListener('input', calculateAll);
    }

    function calculateAll() {
        let grandTotal = 0;

        body.querySelectorAll('tr').forEach(function (row) {
            let rowTotal = 0;

            row.querySelectorAll('.quiz-input').forEach(function (input) {
                rowTotal += parseFloat(input.value) || 0;
            });

            const finalInput = row.querySelector('.final-input');
            rowTotal += parseFloat(finalInput.value) || 0;

            row.querySelector('.total-col').textContent = rowTotal;
            grandTotal += rowTotal;
        });

        document.getElementById('grandTotal').textContent = grandTotal;
    }

    body.querySelectorAll('tr').forEach(function (row) {
        attachRowButton(row);
        attachFinalListener(row);
    });

    calculateAll();
});
</script>
@endsection