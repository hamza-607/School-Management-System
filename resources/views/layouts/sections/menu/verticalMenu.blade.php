@php
$configData = Helper::appClasses();
@endphp

<style>
  #layout-menu {
    --menu-ink: #00352e;
    --menu-cream: #f5f8f7;
  }

  #layout-menu .menu-inner .menu-link {
    color: var(--menu-ink) !important;
  }
   
  #layout-menu .menu-inner .menu-sub .menu-item.active>.menu-link {
    background-color: var(--menu-cream) !important;
    color: white !important;
  }

  /* كلاس منفصل: يلوّن الرابط النشط باللون الأبيض */
  .menu-active-text-white,
  .menu-active-text-white * {
    color: #ffffff !important;
  }
</style>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme rounded-4 shadow-lg">
  <!-- ! Hide app brand if navbar-full -->
  @if(!isset($navbarFull))
  <div class="app-brand demo" style="margin: 5px;">
    <a href="{{ route('dashboard') }}" class="app-brand-link">
      <img src="{{ Storage::url('schoolLogo/logo.png') }}" alt="logo" width="65">
      <span class="app-brand-text demo menu-text fw-bold">مدرسة أُفق</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
      <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
    </a>
  </div>
  @endif

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1 h">
    {{-- الرئيسية --}}
    <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <a href="{{ route('dashboard') }}" class="menu-link {{ request()->routeIs('dashboard') ? 'menu-active-text-white' : '' }}">
        <i class="ti ti-home"></i>
        <div class="m-1">الرئيسية</div>
      </a>
    </li>

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">المواعيد والجلسات</span>
    </li>

    <style>
      .session-dot {
        font-size: 20px;
        margin-right: 8px;
        line-height: 1;
        color: #006559;
        transition: opacity 0.2s;
      }
    </style>

    @php

    $isActiveSession = App\Models\SectionSubjectTeacher::whereHas('appointment', function ($q){
    $q->where('status', 'active');
    })->get()->isEmpty();

    // dd(!$isActiveSession);
    @endphp
    @if (!$isActiveSession)
    <li class="menu-item {{ request()->routeIs('activeSessions') ||  request()->routeIs('controlSession')  ? 'active' : '' }}">
      <a href="{{ route('activeSessions') }}" class="menu-link d-flex flex-row-reverse justify-content-between {{ request()->routeIs('activeSessions') || request()->routeIs('controlSession') ? 'menu-active-text-white' : '' }}">
        <span id="dot" class="session-dot text-success">●</span>
        <div>
          <i class="fas fa-chalkboard-teacher me-2"></i>
          <span>الجلسات الفعالة</span>
        </div>
      </a>
    </li>
    @endif

    <li class="menu-item {{ request()->routeIs('studySchedules.*') ? 'active' : '' }}">
      <a href="{{ route('studySchedules.superIndex') }}" class="menu-link session-link {{ request()->routeIs('studySchedules.*') ? 'menu-active-text-white' : '' }}">
        <i class="fas fa-list-ul me-2"></i>
        <span>البرامج الدراسية</span>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('finishedSessions.index') ||  request()->routeIs('finishedSessions.show') ? 'active' : '' }}">
      <a href="{{ route('finishedSessions.index') }}" class="menu-link session-link {{ request()->routeIs('finishedSessions.index') || request()->routeIs('finishedSessions.show') ? 'menu-active-text-white' : '' }}">
        <i class="fas fa-check-circle me-2"></i>
        <span>سجل الجلسات</span>
      </a>
    </li>


    <script>
      const dot = document.getElementById('dot');

      setInterval(() => {
        dot.style.opacity = dot.style.opacity === '0' ? '1' : '0';
      }, 700);
    </script>


    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">الإدارة</span>
    </li>

    {{-- ادارة الصفوف والشعب --}}
    <li class="menu-item {{ request()->routeIs('grades.*') || request()->routeIs('sections.*') ? 'active open' : '' }} ">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="fas fa-door-open"></i>
        <div class="m-1">الصفوف والشعب</div>
      </a>

      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('grades.*') ? 'active' : '' }}">
          <a href="{{ route('grades.index') }}" class="menu-link">قائمة الصفوف</a>
        </li>
        <li class="menu-item {{ request()->routeIs('sections.*') ? 'active' : '' }}">
          <a href="{{ route('sections.index') }}" class="menu-link">قائمة الشعب</a>
        </li>
      </ul>
    </li>

    {{-- ادارة الطلاب --}}
    <li class="menu-item {{ request()->routeIs('students.*') || request()->routeIs('student.addFile') || request()->routeIs('penalties.*') ? 'active open' : '' }} ">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="fas fa-user-graduate"></i>
        <div class="m-1">الطلاب</div>
      </a>

      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('students.*') || request()->routeIs('student.addFile') || request()->routeIs('penalties.*') ? 'active' : '' }}">
          <a href="{{ route('students.index') }}" class="menu-link">قائمة الطلاب</a>
        </li>
      </ul>
    </li>

    {{-- ادارة اولياء الامور --}}
    <li class="menu-item {{ request()->routeIs('guardians.*') || request()->routeIs('guardian.addFile') ? 'active open' : '' }} ">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="fas fa-house-user"></i>
        <div class="m-1">أولياء الأمور</div>
      </a>

      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('guardians.*') || request()->routeIs('guardian.addFile') ? 'active' : '' }}">
          <a href="{{ route('guardians.index') }}" class="menu-link">قائمة أولياء الأمور</a>
        </li>
      </ul>
    </li>

    {{-- ادارة الموظفين --}}
    <li class="menu-item {{ request()->routeIs('staff_members.*') || request()->routeIs('employee_salary_adjustments.*') ? 'active open' : '' }} ">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="fas fa-user-tie"></i>
        {{-- <i class="fas fa-users-cog"></i>  --}}

        <div class="m-1">الموظفين</div>
      </a>

      @php
      $from = request()->query('from');
      @endphp

      <ul class="menu-sub">
        <li class="menu-item {{ (request()->routeIs('staff_members.*') || request()->routeIs('employee_salary_adjustments.*')) && $from === 'teacher'  ? 'active' : '' }}">
          <a href="{{ route('staff_members.index', ['from' => 'teacher']) }}" class="menu-link">قائمة المعلمين</a>
        </li>
        <li class="menu-item {{ (request()->routeIs('staff_members.*') || request()->routeIs('employee_salary_adjustments.*')) && $from === 'admin'? 'active' : '' }}">
          <a href="{{ route('staff_members.index', ['from' => 'admin']) }}" class="menu-link">قائمة الإداريين</a>
        </li>
        <li class="menu-item {{ (request()->routeIs('staff_members.*') || request()->routeIs('employee_salary_adjustments.*')) && $from === 'other' ? 'active' : '' }}">
          <a href="{{ route('staff_members.index', ['from' => 'other']) }}" class="menu-link">قائمة الكادر العام</a>
        </li>
      </ul>
    </li>

    {{-- ادارة المواد --}}
    <li class="menu-item {{ request()->routeIs('subjects.*') || request()->routeIs('subject.addFile') ? 'active open' : '' }} ">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="fas fa-book"></i>
        <div class="m-1">المواد</div>
      </a>

      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('subjects.*') || request()->routeIs('subject.addFile') ? 'active' : '' }}">
          <a href="{{ route('subjects.index') }}" class="menu-link">قائمة المواد</a>
        </li>
      </ul>
    </li>
  </ul>
</aside>