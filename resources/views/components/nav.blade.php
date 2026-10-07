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

// if($session){
// $showUrl = route('studySchedules.show', [$session->id,$session->section_id]);
// }

$showActive = request()->routeIs('students.show')
|| request()->routeIs('subjects.show')
|| request()->routeIs('staff_members.show')
|| request()->routeIs('guardians.show')
|| request()->routeIs('sections.show')
|| request()->routeIs('studySchedules.show');

$filesActive = request()->routeIs('student.addFile')
|| request()->routeIs('subject.addFile')
|| request()->routeIs('staff_members.addFile')
|| request()->routeIs('guardian.addFile')
|| request()->routeIs('sections.addFile');

// $isScoresActive = request()->routeIs('scores.*');
@endphp

<style>
    :root {
        --ink: #00352e;
        --ink-soft: #3a5350;
        --primary: #006559;
        --primary-dark: #00473f;
        --gold: #ab8347;
        --cream: #f5f8f7;
        --line: #dbe6e3;
    }

    .app-tabs {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: .4rem;
        list-style: none;
        margin: 0 0 1.75rem;
        padding: .5rem;
        background: var(--cream);
        border: 1px solid var(--line);
        border-radius: .5rem;
    }
    .app-tabs .nav-item { margin: 0; }
    .app-tabs .nav-link {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        font-family: 'Cairo', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--ink-soft);
        background: transparent;
        border: none;
        border-radius: .4rem;
        padding: .75rem 1.4rem;
        text-decoration: none;
        transition: color .15s ease, background .15s ease;
    }
    .app-tabs .nav-link i { font-size: 1.05rem; color: var(--gold); transition: color .15s ease; }
    .app-tabs .nav-link:hover { color: var(--ink); background: #fff; }
    .app-tabs .nav-link.active {
        color: #fff;
        background: var(--primary);
        box-shadow: 0 2px 6px rgba(0,101,89,.25);
    }
    .app-tabs .nav-link.active i { color: #fff; }
</style>

<ul class="nav nav-pills flex-column flex-md-row mb-4 ms-2 app-tabs" style="padding-right: 7px;">
        <li class="nav-item"><a
                        class="nav-link {{ $showActive ? 'active' : '' }}"
                        href="{{ $showUrl }}"><i
                                class="ti-xs ti ti-eye me-1"></i> العرض</a></li>
        @if ($student)
       
    <li class="nav-item"><a
            class="nav-link {{ request()->routeIs('scores.*') ? 'active' : '' }}"
        href="{{ route('scores.index' , $student->id) }}"><i
                class="ti-xs ti ti-file-description me-1"></i>النتائج</a></li> 

        <li class="nav-item"><a
                        class="nav-link {{ request()->routeIs('penalties.*') ? 'active' : '' }}" {{-- زبط شرط الهوفر --}}
                        href="{{ route('penalties.index', $student->id) }}"><i
                                class="ti-xs ti ti-file-alert me-1"></i>عقوبات</a></li>
        {{-- <li class="nav-item"><a
            class="nav-link {{ false ? 'active' : '' }}"
        href=""><i
                class="ti ti-report-money ti-xs me-1"></i>مالية</a></li> --}}
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

        {{-- @if ($session)
    <li class="nav-item"><a
            class="nav-link {{ false ? 'active' : '' }}"
        href="">
        <i class="ti ti-adjustments-horizontal ti-xs me-1"></i>
        التحكم بالجلسة
        </a></li>
        @endif --}}
</ul>

