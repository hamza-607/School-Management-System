@props([
'student' => null,
'subject' => null,
'guardian' => null,
'staff' => null,
'section' => null,
])

@php
$showUrl = null;
$showActive = false;
$filesUrl = null;
$filesActive = false;

if ($student){
$showUrl = route('students.show', $student->id);
$filesUrl = route('student.addFile', $student->id);
}
if ($subject) {
$showUrl = route('subjects.show', $subject->id);
$filesUrl = route('subject.addFile', $subject->id);
}
if($guardian){
$showUrl = route('guardians.show', $guardian->id);
$filesUrl = route('guardian.addFile', $guardian->id);
}
if($staff){
$showUrl = route('staff_members.show', [$staff->id, 'from' => $staff->staff_type === 'teacher' ? 'teacher' : ($staff->staff_type === 'admin' ? 'admin' : 'other')]);
$filesUrl = route('staff_members.addFile', [$staff->id, 'from' => $staff->staff_type === 'teacher' ? 'teacher' : ($staff->staff_type === 'admin' ? 'admin' : 'other')]);
}

if($section){
$showUrl = route('sections.show', $section->id);
$filesUrl = route('sections.addFile', $section->id);
}

$showActive = request()->routeIs('students.show')
|| request()->routeIs('subjects.show')
|| request()->routeIs('staff_members.show')
|| request()->routeIs('guardians.show')
|| request()->routeIs('sections.show');
$filesActive = request()->routeIs('student.addFile')
|| request()->routeIs('subject.addFile')
|| request()->routeIs('staff_members.addFile')
|| request()->routeIs('guardian.addFile')
|| request()->routeIs('sections.addFile');
@endphp

<ul class="nav nav-pills flex-column flex-md-row mb-4 ms-2">
    <li class="nav-item"><a
            class="nav-link {{ $showActive ? 'active' : '' }}"
            href="{{ $showUrl }}"><i
                class="ti-xs ti ti-eye me-1"></i> العرض</a></li>
    @if ($student)

    <li class="nav-item"><a
            class="nav-link {{ false ? 'active' : '' }}" {{-- زبط شرط الهوفر --}}
            href=""><i
                class="ti-xs ti ti-file-description me-1"></i>النتائج</a></li>

    <li class="nav-item"><a
            class="nav-link {{ false ? 'active' : '' }}" {{-- زبط شرط الهوفر --}}
            href=""><i
                class="ti-xs ti ti-file-alert me-1"></i>عقوبات</a></li>
    <li class="nav-item"><a
            class="nav-link {{ false ? 'active' : '' }}" {{-- زبط شرط الهوفر --}}
            href=""><i
                class="ti ti-report-money ti-xs me-1"></i>مالية</a></li>
    @endif

    @if ($staff)
    <li class="nav-item"><a
            class="nav-link {{ request()->routeIs('employee_salary_adjustments.*') ? 'active' : '' }}" {{-- زبط شرط الهوفر --}}
            href="{{ route('employee_salary_adjustments.index',[$staff->id, 'from' => $staff->staff_type === 'teacher' ? 'teacher' : ($staff->staff_type === 'admin' ? 'admin' : 'other')]) }}"><i class="ti ti-arrows-right-left ti-xs me-1"></i> {{-- --}}
            التعديلات على الراتب</a></li>
    @endif

    @if ($subject || $student || $guardian || $staff || $section)
    <li class="nav-item"><a
            class="nav-link {{ $filesActive ? 'active' : '' }}"
            href="{{ $filesUrl }}"> <i
                class="ti ti-file-upload ti-xs me-1"></i>إضافة
            ملف</a></li>
    @endif
</ul>