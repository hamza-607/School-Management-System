@php
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');
@endphp

<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
  @endif
  @if(isset($navbarDetached) && $navbarDetached == '')
  <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
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

      <!-- ! Not required for layout-without-menu -->
      @if(!isset($navbarHideToggle))
      <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
          <i class="ti ti-menu-2 ti-sm"></i>
        </a>
      </div>
      @endif

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
          <!-- Style Switcher -->
          <li class="nav-item me-2 me-xl-0">
            <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
              <i class='ti ti-md'></i>
            </a>
          </li>
          <!--/ Style Switcher -->

          <!-- Quick links  -->
          <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
              <i class='ti ti-layout-grid-add ti-md'></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end py-0">
              <div class="dropdown-shortcuts-list scrollable-container">
                <div class="row row-bordered overflow-visible g-0">
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                      <i class="ti ti-calendar fs-4"></i>
                    </span>
                    <a href="{{url('app/calendar')}}" class="stretched-link">Calendar</a>
                    <small class="text-muted mb-0">Appointments</small>
                  </div>
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                      <i class="ti ti-file-invoice fs-4"></i>
                    </span>
                    <a href="{{url('app/invoice/list')}}" class="stretched-link">Invoice App</a>
                    <small class="text-muted mb-0">Manage Accounts</small>
                  </div>
                </div>
              </div>
            </div>
          </li>
          <!-- Quick links -->

          <!-- Notification -->
          {{-- <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
              <i class="ti ti-bell ti-md"></i>
              <span class="badge bg-danger rounded-pill badge-notifications">5</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end py-0">
              <li class="dropdown-menu-header border-bottom">
                <div class="dropdown-header d-flex align-items-center py-3">
                  <h5 class="text-body mb-0 me-auto">Notification</h5>
                  <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read"><i class="ti ti-mail-opened fs-4"></i></a>
                </div>
              </li>
              <li class="dropdown-notifications-list scrollable-container">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item list-group-item-action dropdown-notifications-item">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                          <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle">
      </div>
    </div>
    <div class="flex-grow-1">
      <h6 class="mb-1">Congratulation Lettie 🎉</h6>
      <p class="mb-0">Won the monthly best seller gold badge</p>
      <small class="text-muted">1h ago</small>
    </div>
    <div class="flex-shrink-0 dropdown-notifications-actions">
      <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
      <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
    </div>
    </div>
    </li>
    <li class="list-group-item list-group-item-action dropdown-notifications-item">
      <div class="d-flex">
        <div class="flex-shrink-0 me-3">
          <div class="avatar">
            <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
          </div>
        </div>
        <div class="flex-grow-1">
          <h6 class="mb-1">Charles Franklin</h6>
          <p class="mb-0">Accepted your connection</p>
          <small class="text-muted">12hr ago</small>
        </div>
        <div class="flex-shrink-0 dropdown-notifications-actions">
          <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
          <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
        </div>
      </div>
    </li>
    <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
      <div class="d-flex">
        <div class="flex-shrink-0 me-3">
          <div class="avatar">
            <img src="{{ asset('assets/img/avatars/2.png') }}" alt class="h-auto rounded-circle">
          </div>
        </div>
        <div class="flex-grow-1">
          <h6 class="mb-1">New Message ✉️</h6>
          <p class="mb-0">You have new message from Natalie</p>
          <small class="text-muted">1h ago</small>
        </div>
        <div class="flex-shrink-0 dropdown-notifications-actions">
          <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
          <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
        </div>
      </div>
    </li>
    <li class="list-group-item list-group-item-action dropdown-notifications-item">
      <div class="d-flex">
        <div class="flex-shrink-0 me-3">
          <div class="avatar">
            <span class="avatar-initial rounded-circle bg-label-success"><i class="ti ti-cart"></i></span>
          </div>
        </div>
        <div class="flex-grow-1">
          <h6 class="mb-1">Whoo! You have new order 🛒 </h6>
          <p class="mb-0">ACME Inc. made new order $1,154</p>
          <small class="text-muted">1 day ago</small>
        </div>
        <div class="flex-shrink-0 dropdown-notifications-actions">
          <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
          <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
        </div>
      </div>
    </li>
    <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
      <div class="d-flex">
        <div class="flex-shrink-0 me-3">
          <div class="avatar">
            <img src="{{ asset('assets/img/avatars/9.png') }}" alt class="h-auto rounded-circle">
          </div>
        </div>
        <div class="flex-grow-1">
          <h6 class="mb-1">Application has been approved 🚀 </h6>
          <p class="mb-0">Your ABC project application has been approved.</p>
          <small class="text-muted">2 days ago</small>
        </div>
        <div class="flex-shrink-0 dropdown-notifications-actions">
          <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
          <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
        </div>
      </div>
    </li>
    <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
      <div class="d-flex">
        <div class="flex-shrink-0 me-3">
          <div class="avatar">
            <span class="avatar-initial rounded-circle bg-label-success"><i class="ti ti-chart-pie"></i></span>
          </div>
        </div>
        <div class="flex-grow-1">
          <h6 class="mb-1">Monthly report is generated</h6>
          <p class="mb-0">July monthly financial report is generated </p>
          <small class="text-muted">3 days ago</small>
        </div>
        <div class="flex-shrink-0 dropdown-notifications-actions">
          <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
          <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
        </div>
      </div>
    </li>
    <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
      <div class="d-flex">
        <div class="flex-shrink-0 me-3">
          <div class="avatar">
            <img src="{{ asset('assets/img/avatars/5.png') }}" alt class="h-auto rounded-circle">
          </div>
        </div>
        <div class="flex-grow-1">
          <h6 class="mb-1">Send connection request</h6>
          <p class="mb-0">Peter sent you connection request</p>
          <small class="text-muted">4 days ago</small>
        </div>
        <div class="flex-shrink-0 dropdown-notifications-actions">
          <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
          <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
        </div>
      </div>
    </li>
    <li class="list-group-item list-group-item-action dropdown-notifications-item">
      <div class="d-flex">
        <div class="flex-shrink-0 me-3">
          <div class="avatar">
            <img src="{{ asset('assets/img/avatars/6.png') }}" alt class="h-auto rounded-circle">
          </div>
        </div>
        <div class="flex-grow-1">
          <h6 class="mb-1">New message from Jane</h6>
          <p class="mb-0">Your have new message from Jane</p>
          <small class="text-muted">5 days ago</small>
        </div>
        <div class="flex-shrink-0 dropdown-notifications-actions">
          <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
          <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
        </div>
      </div>
    </li>
    <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
      <div class="d-flex">
        <div class="flex-shrink-0 me-3">
          <div class="avatar">
            <span class="avatar-initial rounded-circle bg-label-warning"><i class="ti ti-alert-triangle"></i></span>
          </div>
        </div>
        <div class="flex-grow-1">
          <h6 class="mb-1">CPU is running high</h6>
          <p class="mb-0">CPU Utilization Percent is currently at 88.63%,</p>
          <small class="text-muted">5 days ago</small>
        </div>
        <div class="flex-shrink-0 dropdown-notifications-actions">
          <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
          <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
        </div>
      </div>
    </li>
    </ul>
    </li>
    <li class="dropdown-menu-footer border-top">
      <a href="javascript:void(0);" class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
        View all notifications
      </a>
    </li>
    </ul>
    </li> --}}
    <!--/ Notification -->

    <!-- User -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
          <img src="" alt="" class="h-auto rounded-circle">
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li class="border-bottom">
          <a class="dropdown-item" href="">
            <div class="d-flex">
              <div class="flex-grow-1">
                @if (Auth::check())
                <span class="fw-semibold d-block">
                  {{ Auth::user()->name }}
                </span>
                <small class="text-muted">{{ Auth::user()->staff->staff_type }}</small>
                @else
                <span class="fw-semibold d-block">
                  John Doe
                </span>
                <small class="text-muted">admin</small>
                @endif
              </div>
            </div>
          </a>
        </li>
        @if (Auth::check())
        <li>
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class='ti ti-logout me-2'></i>
            <span class="align-middle">Logout</span>
          </a>
        </li>
        <form method="POST" id="logout-form" action="{{ route('logout') }}">
          @csrf
        </form>
        @else
        <li>
          <a class="dropdown-item" href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
            <i class='ti ti-login me-2'></i>
            <span class="align-middle">Login</span>
          </a>
        </li>
        @endif
        <li>
          <a class="dropdown-item" href="{{  route('editPasswordPage') }}">
            <i class='ti ti-key me-2'></i>
            <span class="align-middle">Edit Password</span>
          </a>
        </li>
      </ul>
    </li>
    <!--/ User -->
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