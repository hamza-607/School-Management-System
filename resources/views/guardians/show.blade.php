@extends('layouts.layoutMaster')

@section('title', 'تفاصيل ولي الأمر')
@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
<!-- استدعاء مكتبة أيقونات بوتستراب -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">أولياء الامور /</span> تفاصيل ولي الأمر {{ $theGuardian->name }}
</h4>

<x-nav :guardian="$theGuardian" />

<div class="container-fluid px-2 py-4" style="position:relative">

    <div class="card shadow-sm border-0 mb-4 header-card">
        <div class="card-body">
            <div class="d-flex align-items-start gap-3 mb-3">
                {{-- <div style="width:160px; border-radius: 50px;  border: 2px solid #000;">
                    <img style="border-radius: 50px; " src="{{ $theGuardian->picture ? url(Storage::url($theGuardian->picture)) : asset('storage/studentPhoto/def.png') }}" alt="logo" name="logo" width="160">
            </div> --}}
            <div>
                <h5 class="mb-1" style="font-size:xx-large;">{{ $theGuardian->name }}</h5>

                <div class="patient-details">
                    <div class="mb-1 text-muted" style="font-size:large;">
                        <i class="bi bi-person me-1"></i>
                        {{ $theGuardian->e_name }}
                    </div>
                    <div class="text-muted" style="font-size:medium;">
                        <i class="bi bi-geo-alt me-1"></i>
                        {{ $theGuardian->address }}
                    </div><br>

                    <div class="mt-3 text-muted d-flex align-items-center flex-wrap" style="font-size: 1rem;">
                        <i class="bi bi-telephone me-2"></i>
                        <span class="me-1">رقم الهاتف:</span>
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $theGuardian->phone) }}" target="_blank" class="text-decoration-none me-3">
                            {{ $theGuardian->phone }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
            <a href="{{ route('guardians.edit', $theGuardian->id) }}"
                class="btn btn-label-warning px-4">
                تعديل
            </a>
            <a href="{{ url()->previous() }}" class="btn btn-label-secondary px-4">
                رجوع
            </a>
        </div>
    </div>
</div>

<div class="row g-4">

    <div class="col-md-8">
        <!-- معلومات اضافية -->
        <div class="card shadow-sm border-0 header-card3">
            <div class="card-body">
                <h5 class="mb-3">معلومات إضافية</h5>

                <div class="row g-3">

                    <!-- 3. تاريخ الميلاد -->
                    <div class="col-6 col-md-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle"
                                style="width: 56px; height: 56px; min-width: 56px; background-color: #f1f3f5; color: #6c757d;">
                                <i class="bi bi-calendar-event fs-5"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">تاريخ الميلاد:</span>
                                <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                    {{ $theGuardian->date_of_birth ?? 'غير مسجل' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- 4. الجنس -->
                    <div class="col-6 col-md-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle"
                                style="width: 56px; height: 56px; min-width: 56px; background-color: #f1f3f5; color: #6c757d;">
                                <i class="bi bi-gender-ambiguous fs-5"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">الجنس:</span>
                                <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                    {{ $theGuardian->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- 4. عدد الطلاب -->
                    <div class="col-6 col-md-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle"
                                style="width: 56px; height: 56px; min-width: 56px; background-color: #f1f3f5; color: #6c757d;">
                                <i class="bi bi-gender-ambiguous fs-5"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">عدد الطلاب المسؤول عنهم:</span>
                                <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                    {{ $theGuardian->student_parents->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 header-card3 mt-4">
            <div class="card-body">

                <!-- العنوان -->
                <div class="tab-btn d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-people fs-4"></i>
                    <h5 class="mb-0 fw-bold">الطلاب المسؤول عنهم</h5>
                </div>

                <div class="d-flex flex-nowrap overflow-x-auto gap-3 pb-3" style="overflow-x: scroll; scrollbar-width: none;">
                    @foreach ($theGuardian->student_parents as $student_parent)
                    <div class="parent-card border rounded-3 p-3 bg-light-subtle" style="min-width: 300px; flex: 0 0 auto;">
                        <a href="{{ route('students.show', $student_parent->student->id) }}" class="d-flex align-items-center gap-3 mb-2">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $student_parent->student->name }}</h6>
                                <small class="text-muted">{{ $student_parent->relationship_to_student }}</small>
                            </div>
                        </a>
                        <div class="mt-2 pt-2 border-top">
                            <div>
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $student_parent->student->phoneNumber ) }}" target="_blank" class="me-3 small mb-1">
                                    <i class="bi bi-whatsapp me-2 text-success"></i>
                                    {{ $student_parent->student->phoneNumber }} </a>
                            </div>
                            {{-- <div>
                                <a href="https://mail.google.com/mail/?view=cm&to={{ $student_parent->student->email }}"
                            target="_blank"
                            class="text-decoration-none me-3 small">
                            <i class="bi bi-envelope me-2 text-secondary"></i>{{ $student_parent->student->email ?? '-' }}
                            </a>
                        </div> --}}
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

    <x-files-table :model="$theGuardian" mainTitle="ملفات ومرفقات ولي الأمر" secTitle="لا توجد ملفات مرفوعة."></x-files-table>

</div>


<div class="col-md-4">
    <div class="card shadow-sm border-0 header-card2">
        <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-telephone-fill fs-3 text-primary"></i>
                <h5 class="mb-0 fw-bold">معلومات التواصل</h5>
            </div>

            <div class="row g-3">

                <div class="col-12">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                            style="width: 56px; height: 56px; min-width: 56px; background-color: #f1f3f5; color: #6c757d;">
                            <i class="bi bi-whatsapp me-2 text-secondary"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">رقم الهاتف:</span>
                            <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $theGuardian->phone) }}" target="_blank" class="text-decoration-none me-3">
                                    {{ $theGuardian->phone }}
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                            style="width: 56px; height: 56px; min-width: 56px; background-color: #f1f3f5; color: #6c757d;">
                            <i class="bi bi-envelope me-2"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">الايميل:</span>
                            <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                                <a href="https://mail.google.com/mail/?view=cm&to={{ $theGuardian->email ?? '-' }}"
                                    target="_blank"
                                    class="text-decoration-none me-3 small">
                                    {{ $theGuardian->email ?? '-' }}
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<script>
    function switchTab(el) {

        let tabs = document.querySelectorAll('.tab-btn')

        tabs.forEach(t => t.classList.remove('active'))

        el.classList.add('active')

    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection