@extends('layouts.layoutMaster')

@section('title', 'الجلسة الفعالة')
@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
<style>
    .swal2-container {
        z-index: 20000 !important;
    }

    .swal2-popup-custom {
        border-radius: 0.5rem;
    }

    .student-row td {
        vertical-align: middle;
    }

    .avatar-circle {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 50%;
        background-color: #f1f3f5;
        color: #696cff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .penalty-count-badge {
        min-width: 22px;
    }
</style>

<script>
    let currentPenaltyStudentId = null;
    let currentPenaltyStudentName = null;
    let penalties = [];
    const attendanceDraftKey = 'attendance-draft-{{ $theSession->id }}';
    const oldAttendance = @json(old('attendance', []));
    const oldPenalties = @json(old('penalties', []));
    const studentNames = @json($students->pluck('name', 'id'));

    document.addEventListener('DOMContentLoaded', function() {
        if (@json(session()->has('success'))) {
            sessionStorage.removeItem(attendanceDraftKey);
        }

        const draft = JSON.parse(sessionStorage.getItem(attendanceDraftKey) || 'null');
        const attendance = oldAttendance.length ? oldAttendance : (draft?.attendance || []);
        const submittedPenalties = oldPenalties.length ? oldPenalties : (draft?.penalties || []);

        attendance.forEach(item => {
            const status = document.querySelector(`input[name^="attendance["][name$="[student_id]"][value="${item.student_id}"]`);

            if (status) {
                const index = status.name.match(/attendance\[(\d+)\]/)[1];
                const input = document.querySelector(`input[name="attendance[${index}][status]"][value="${item.status}"]`);

                if (input) input.checked = true;
            }
        });

        penalties = submittedPenalties.map(penalty => ({
            studentId: penalty.student_id,
            studentName: studentNames[penalty.student_id] || '',
            penaltyType: penalty.penalty_type,
            reason: penalty.reason,
            notes: penalty.notes || ''
        }));

        renderPenalties();
    });

    function openPenaltyModal(studentId, studentName) {
        currentPenaltyStudentId = studentId;
        currentPenaltyStudentName = studentName;

        document.getElementById('penaltyModalStudentName').textContent = studentName;
        document.getElementById('penaltyType').value = '';
        document.getElementById('penaltyReasonInput').value = '';
        document.getElementById('penaltynoteInput').value = '';

        const modal = new bootstrap.Modal(document.getElementById('addPenaltyModal'));
        modal.show();
    }

    function savePenalty() {
        const penaltyType = document.getElementById('penaltyType').value.trim();
        const reason = document.getElementById('penaltyReasonInput').value.trim();
        const notes = document.getElementById('penaltynoteInput').value.trim();

        if (!penaltyType || !reason) {
            alert('يرجى كتابة جميع الحقول المطلوبة');
            return;
        }

        penalties.push({
            studentId: currentPenaltyStudentId,
            studentName: currentPenaltyStudentName,
            penaltyType,
            reason,
            notes
        });

        renderPenalties();

        bootstrap.Modal.getInstance(document.getElementById('addPenaltyModal')).hide();
    }

    function renderPenalties() {
        const container = document.getElementById('penaltiesList');
        const inputs = document.getElementById('penaltiesInputs');

        container.innerHTML = '';
        inputs.innerHTML = '';

        penalties.forEach((penalty, index) => {
            container.insertAdjacentHTML('beforeend', `
                <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="fw-bold">${penalty.studentName}</div>
                        <div class="text-muted">نوع العقوبة: ${penalty.penaltyType}</div>
                        <div class="mt-1">السبب: ${penalty.reason}</div>
                        ${penalty.notes ? `<div class="mt-1">الملاحظات: ${penalty.notes}</div>` : ''}
                    </div>
                    <button type="button" class="btn btn-label-danger btn-sm" onclick="removePenalty(${index})">
                        <i class="bi bi-trash me-1"></i> حذف العقوبة
                    </button>
                </div>
            `);

            inputs.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="penalties[${index}][student_id]" value="${penalty.studentId}">
                <input type="hidden" name="penalties[${index}][penalty_type]" value="${penalty.penaltyType.replace(/&/g, '&amp;').replace(/"/g, '&quot;')}">
                <input type="hidden" name="penalties[${index}][reason]" value="${penalty.reason.replace(/&/g, '&amp;').replace(/"/g, '&quot;')}">
                <input type="hidden" name="penalties[${index}][notes]" value="${penalty.notes.replace(/&/g, '&amp;').replace(/"/g, '&quot;')}">
            `);
        });

        document.getElementById('penaltiesEmptyMessage').classList.toggle('d-none', penalties.length > 0);
        saveDraft();
    }

    function removePenalty(index) {
        penalties.splice(index, 1);
        renderPenalties();
    }

    function saveDraft() {
        const attendanceItems = {};
        const form = document.getElementById('attendanceForm');

        if (!form) return;

        for (const [name, value] of new FormData(form).entries()) {
            const match = name.match(/^attendance\[(\d+)\]\[(student_id|status)\]$/);

            if (match) {
                attendanceItems[match[1]] = attendanceItems[match[1]] || {};
                attendanceItems[match[1]][match[2]] = value;
            }
        }

        sessionStorage.setItem(attendanceDraftKey, JSON.stringify({
            attendance: Object.values(attendanceItems).filter(item => item.status),
            penalties
        }));
    }

    function confirmSaveAttendance(event) {
        event.preventDefault();

        saveDraft();

        Swal.fire({
            title: 'حفظ الحضور',
            html: 'هل تريد حفظ حالة الحضور والعقوبات؟ البيانات غير قابلة للتعديل بشكل مباشر بعد الحفظ.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'نعم، احفظ',
            cancelButtonText: 'إلغاء',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-primary ms-2',
                cancelButton: 'btn btn-secondary',
                popup: 'swal2-popup-custom'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit();
            }
        });

        return false;
    }

    document.addEventListener('click', function(event) {
        const button = event.target.closest('.add-penalty-btn');

        if (!button) {
            return;
        }

        openPenaltyModal(button.dataset.studentId, button.dataset.studentName);
    });

    document.addEventListener('change', function(event) {
        if (event.target.matches('input[name^="attendance["][name$="[status]"]')) {
            saveDraft();
        }
    });
</script>
@endsection

@section('content')

<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">الجلسات الفعالة / القائمة / </span> عرض
</h4>

<div class="container-fluid px-2 py-4">

    <div class="row g-4 mb-4">
        <!-- Header: معلومات الجلسة -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 header-card">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                            style="width: 70px; height: 70px; min-width: 70px; background-color: #f1f3f5; color: #696cff;">
                            <i class="bi bi-journal-bookmark fs-2 text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-1" style="font-size:xx-large;">
                                {{ $theSession->subject->name  }}
                                <span class="text-center align-middle fs-6">
                                    <span class="badge bg-label-secondary text-dark">
                                        {{ $theSession->type === 'regular' ? 'اساسية' : 'تعويضية' }}
                                    </span>
                                </span>
                            </h5>

                            <div class="mb-1 text-muted" style="font-size:large;">
                                <i class="bi bi-mortarboard me-1"></i>
                                الصف {{ $theSession->grade->name }} - الشعبة <a href="{{ route('sections.show', $theSession->section_id) }}">{{ $theSession->section->name }}</a>
                            </div>
                            <div class="text-muted" style="font-size:medium;">
                                <i class="bi bi-person-badge me-1"></i>
                                المدرس: <a href="{{ route('staff_members.show', [$theSession->staff->id, 'from' => $theSession->staff->staff_type]) }}">{{ $theSession->staff->name }}</a>
                            </div>

                            <div class="mt-3 text-muted d-flex align-items-center flex-wrap" style="font-size: 1rem;">
                                <i class="bi bi-clock-history me-2"></i>
                                <span class="me-1">التوقيت:</span>
                                <span class="me-3">
                                    <span class="badge bg-label-secondary text-dark">{{ $theSession->appointment->start_time }}</span>
                                    <span class="mx-1">-</span>
                                    <span class="badge bg-label-secondary text-dark">{{ $theSession->appointment->end_time }}</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- الأزرار -->
                    <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
                        <a href="" class="btn btn-label-danger px-4 d-flex align-items-center gap-2">
                            <i class="bi bi-power fs-5"></i>
                            <span>إنهاء الجلسة</span>
                        </a>

                        <a href="{{ url()->previous() }}" class="btn btn-label-secondary px-4 d-flex align-items-center gap-2">
                            <span>رجوع</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <x-files-table :model="$theSession->subject" mainTitle="ملفات ومرفقات المادة" secTitle="لا توجد ملفات مرفوعة." styl="height: 274px;"></x-files-table>
        </div>
    </div>

    <form method="POST" id="attendanceForm" action="{{ route('attendance.store', [$theSession->section_id, $theSession->id]) }}" onsubmit="return confirmSaveAttendance(event)">
        @csrf

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-clipboard-check fs-4 text-primary"></i>
                    <h5 class="mb-0 fw-bold">حضور الطلاب</h5>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>اسم الطالب</th>
                                <th class="text-center" style="width: 140px;">الحضور</th>
                                <th class="text-center" style="width: 180px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                            <tr class="student-row">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center  justify-content-center" style="width: 48px; height: 48px; min-width: 48px; background-color: #e6f0ef  !important; color: #006559 !important;">
                                            {{ mb_substr($student->name, 0, 1) }}
                                        </div>
                                        <span>{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <input type="hidden" name="attendance[{{ $index }}][student_id]" value="{{ $student->id }}">
                                    <div class="col-12 mt-3 d-flex gap-2" style="justify-content: center;">
                                        <label>
                                            <input class="form-check-input" type="radio" name="attendance[{{ $index }}][status]" value="present">
                                            حضور
                                        </label>
                                        <label>
                                            <input class="form-check-input" type="radio" name="attendance[{{ $index }}][status]" value="absent">
                                            غياب
                                        </label>
                                        <label>
                                            <input class="form-check-input" type="radio" name="attendance[{{ $index }}][status]" value="excused">
                                            عذر
                                        </label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-label-danger btn-sm add-penalty-btn"
                                        data-student-id="{{ $student->id }}" data-student-name="{{ $student->name }}">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        إضافة عقوبة
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 border-top pt-3">
                    <h6 class="fw-bold mb-3">العقوبات المضافة</h6>
                    <div id="penaltiesList"></div>
                    <div id="penaltiesEmptyMessage" class="text-muted">لا توجد عقوبات مضافة</div>
                    <div id="penaltiesInputs"></div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3 border-top pt-3">
                    <button type="submit" class="btn btn-primary px-4">
                        حفظ الحضور والعقوبات
                    </button>
                </div>
            </div>
    </form>

</div>

<!-- Modal: إضافة عقوبة -->
<div class="modal fade" id="addPenaltyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header  border-bottom">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    إضافة عقوبة
                </h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label text-muted">الطالب</label>
                    <div class="fw-bold" id="penaltyModalStudentName">-</div>
                </div>

                <div class="mb-3">
                    <label for="penaltyType" class="form-label">نوع العقوبة<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="penaltyType" placeholder="نوع العقوبة...">
                </div>

                <div class="mb-3">
                    <label for="penaltyReasonInput" class="form-label">السبب<span class="text-danger">*</span></label>
                    <textarea class="form-control" id="penaltyReasonInput" rows="3"
                        placeholder="سبب العقوبة..."></textarea>
                </div>

                <div class="mb-3">
                    <label for="penaltynoteInput" class="form-label">ملاحظات</label>
                    <textarea class="form-control" id="penaltynoteInput" rows="3"
                        placeholder="ملاحظات..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" onclick="savePenalty()">حفظ العقوبة</button>
            </div>
        </div>
    </div>
</div>

@endsection