@php
$configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- ! Hide app brand if navbar-full -->
  @if(!isset($navbarFull))
  <div class="app-brand demo" style="margin-right: 0px;">
    <a href="{{url('/')}}" class="app-brand-link">
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

  <ul class="menu-inner py-1">
    {{-- الرئيسية --}}
    <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <a href="{{ route('dashboard') }}" class="menu-link">
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
        color: green;
        transition: opacity 0.2s;
      }
    </style>

    <li class="menu-item {{ false ? 'active' : '' }}">
      <a href="" class="menu-link d-flex flex-row-reverse justify-content-between">
        <span id="dot" class="session-dot text-success">●</span>
        <div>
          <i class="fas fa-chalkboard-teacher me-2"></i>
          <span>الجلسات الفعالة</span>
        </div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('studySchedules.*') ? 'active' : '' }}">
      <a href="{{ route('studySchedules.superIndex') }}" class="menu-link session-link">
        <i class="fas fa-list-ul me-2"></i>
        <span>البرامج الدراسية</span>
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
    <li class="menu-item {{ request()->routeIs('students.*') || request()->routeIs('student.addFile') ? 'active open' : '' }} ">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="fas fa-user-graduate"></i>
        <div class="m-1">الطلاب</div>
      </a>

      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('students.*') || request()->routeIs('student.addFile') ? 'active' : '' }}">
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