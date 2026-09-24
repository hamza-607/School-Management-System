@php
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');
@endphp


<style>
  /* شكل "طايفة" بالصفحة: مسافة متساوية من كل الجهات + راديوس + ظل خفيف */
  .floating-navbar {
    margin: 16px !important;
    /* width: calc(100% - 32px) !important; */
    border-radius: 6px !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
  }

  .icon-actions-group {
    gap: 4px;
  }

  .icon-action {
    display: flex;
    align-items: center;
    height: 34px;
    padding: 0 8px;
    border-radius: 30px;
    overflow: hidden;
    cursor: pointer;
    color: inherit;
    text-decoration: none;
    white-space: nowrap;
    transition: background-color .2s ease, padding-inline-end .3s ease;
  }

  .icon-action:hover {
    background-color: rgba(0, 0, 0, .06);
    padding-inline-end: 12px;
  }

  .icon-action-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 20px;
  }

  .icon-action-label {
    max-width: 0;
    opacity: 0;
    margin-inline-start: 0;
    overflow: hidden;
    font-size: .8125rem;
    transition: max-width .5s ease, opacity .25s ease, margin-inline-start .3s ease;
  }

  .icon-action:hover .icon-action-label {
    max-width: 160px;
    opacity: 1;
    margin-inline-start: 6px;
  }
</style>

@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme floating-navbar" id="layout-navbar">
  <div class="{{$containerNav}}">
    @endif

    <!--  Brand demo (display only for navbar-full and hide on below xl) -->
    @if(isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
      <a href="{{url('/')}}" class="app-brand-link gap-2">
        <span class="app-brand-logo demo">
          @include('_partials.macros',["height"=>20])
        </span>
        <span class="app-brand-text demo menu-text fw-bold">{{config('variables.templateName')}}</span>
      </a>
    </div>
    @endif

    @if(!isset($navbarHideToggle))
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
      <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="ti ti-menu-2 ti-sm"></i>
      </a>
    </div>
    @endif
    
    <!-- الحاوي الرئيسي: توزيع متساوي بين 3 مجموعات -->
    <div class="navbar-nav-right d-flex align-items-center justify-content-between w-100" id="navbar-collapse">

      <ul class="navbar-nav flex-row align-items-center m-0">
        <li class="nav-item d-flex align-items-center">
          <div class="avatar avatar-online">
            <img src="{{ Storage::url(Auth::user()->staff->picture ?? null) }}" alt="gf" class="h-auto rounded-circle">
          </div>
          <div class="ms-2 d-none d-sm-flex flex-column">
            @if (Auth::check())
            <span class="fw-semibold" style="line-height:1;">{{ Auth::user()->name }}</span>
            <small class="text-muted">{{ Auth::user()->staff->staff_type }}</small>
            @else
            <span class="fw-semibold" style="line-height:1;">John Doe</span>
            <small class="text-muted">admin</small>
            @endif
          </div>
        </li>
      </ul>

      <ul class="navbar-nav flex-row align-items-center m-0">
        <li class="nav-item">
          <div class="icon-actions-group d-flex align-items-center">

            <!-- تبديل الستايل (ليلي / نهاري) -->
            <div class="icon-action style-switcher-toggle hide-arrow" role="button">
              <span class="icon-action-icon">
                <i class='ti ti-md'></i>
              </span>
              <span class="icon-action-label">تبديل المظهر</span>
            </div>

            <!-- تغيير كلمة السر -->
            <a href="{{ route('editPasswordPage') }}" class="icon-action">
              <span class="icon-action-icon">
                <i class='ti ti-key ti-md'></i>
              </span>
              <span class="icon-action-label">تغيير كلمة السر</span>
            </a>

            <!-- تسجيل الخروج / الدخول -->
            @if (Auth::check())
            <a href="{{ route('logout') }}" class="icon-action" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <span class="icon-action-icon">
                <i class='ti ti-logout ti-md'></i>
              </span>
              <span class="icon-action-label">تسجيل خروج</span>
            </a>
            <form method="POST" id="logout-form" action="{{ route('logout') }}">
              @csrf
            </form>
            @else
            <a href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}" class="icon-action">
              <span class="icon-action-icon">
                <i class='ti ti-login ti-md'></i>
              </span>
              <span class="icon-action-label">تسجيل دخول</span>
            </a>
            @endif

          </div>
        </li>
      </ul>

    </div>
</nav>

<div class="{{ $containerNav }} mt-3">
  @if(session('success'))
  <div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif

  @if(session('error'))
  <div class="alert alert-danger alert-dismissible" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
</div>
<!-- / Navbar -->