<!--begin::Header-->
<nav class="navbar navbar-expand" style="background-color: var(--bs-body-bg);">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Start Navbar links-->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="#" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>
    <!--end::Start Navbar links-->

    <!--begin::End Navbar links-->
    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown">
        <button class="btn btn-link nav-link py-2 px-2 dropdown-toggle d-flex align-items-center" id="dropdown-entitas"
          type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
          <span class="d-inline" id="dropdown-entitas-text">Entitas</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-entitas"
          style="--bs-dropdown-min-width: 10rem; z-index:1055;">
          <li>
            <button class="dropdown-item d-flex align-items-center" type="button" data-entitas="UHO">
              UHO
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button class="dropdown-item d-flex align-items-center" type="button" data-entitas="UNR">
              UNR
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button class="dropdown-item d-flex align-items-center" type="button" data-entitas="UNB">
              UNB
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button class="dropdown-item d-flex align-items-center" type="button" data-entitas="UN BLitar">
              UN Blitar
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button class="dropdown-item d-flex align-items-center" type="button" data-entitas="UBL">
              UBL
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button class="dropdown-item d-flex align-items-center" type="button" data-entitas="UDW">
              UDW
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button class="dropdown-item d-flex align-items-center" type="button" data-entitas="All">
              All Entitas
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
        </ul>
      </li>

      <li class="nav-item dropdown">
        <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center" id="bd-theme"
          type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static">
          <span class="theme-icon-active">
            <i class="my-1"></i>
          </span>
          <span class="d-lg-none ms-2" id="bd-theme-text"> Toggle theme</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text"
          style="--bs-dropdown-min-width: 8rem;">
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light"
              aria-pressed="false">
              <i class="bi bi-sun-fill me-2"></i>
              Light
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark"
              aria-pressed="false">
              <i class="bi bi-moon-fill me-2"></i>
              Dark
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto"
              aria-pressed="true">
              <i class="bi bi-circle-fill-half-stroke me-2"></i>
              Auto
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
      </li>
    </ul>
    <!--end::End Navbar links-->
  </div>

  <!--end::Container-->
</nav>
<!--end::Header-->