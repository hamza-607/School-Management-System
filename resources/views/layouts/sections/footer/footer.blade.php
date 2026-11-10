<!-- Footer-->


<style>
  #footerID{ display: none;}
  @media (width < 1200px) {
    #footerID{
      display: block;
    }
  }
</style>

<footer class="content-footer footer bg-footer-theme">
  <div class="{{ (!empty($containerNav) ? $containerNav : 'container-fluid') }}  p-0" id="footerID">
    <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
      <ul class="flex-row align-items-center m-0">
        <li class="d-flex align-items-center">
          <div class="avatar avatar-online">
            <img src="{{ Storage::url(Auth::user()->staff->picture ?? null) }}" alt="gf" class="h-auto rounded-circle">
          </div>
          <div class="ms-2 d-sm-flex flex-column">
            @if (Auth::check())
              <span class="fw-semibold" style="line-height:1;">{{ Auth::user()->name }}</span>
              <small class="text-muted">{{ Auth::user()->staff->staff_type }}</small>
            @else
              <span class="fw-semibold" style="line-height:1;">-</span>
              <small class="text-muted">-</small>
            @endif
          </div>
        </li>
      </ul>
    </div>
  </div>
</footer>
