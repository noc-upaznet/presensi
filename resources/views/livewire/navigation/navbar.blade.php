<!--begin::Header-->
<nav class="navbar navbar-expand fixed-top shadow-sm" style="background-color: var(--bs-body-bg); z-index: 1030;">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Start Navbar links-->
    <ul class="navbar-nav align-items-center">
      {{-- Sidebar Toggle --}}
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>

      {{-- Nama Entitas --}}
      @if (auth()->user()->role === 'admin')
        <li class="nav-item">
          <span class="fw-bold fs-4 text-primary ms-auto">
            {{ session('selected_entitas', 'UHO') }}
          </span>
        </li>
      @endif
    </ul>
    <!--end::Start Navbar links-->

    <!--begin::End Navbar links-->
    <ul class="navbar-nav ms-auto">
      @if (auth()->user()->role == 'admin')
        <li class="nav-item dropdown border border-secondary rounded ms-2">
          <button class="btn btn-link nav-link py-2 px-2 dropdown-toggle d-flex align-items-center rounded text-secondary" 
          id="dropdown-entitas"
          type="button"
          data-bs-toggle="dropdown"
          aria-expanded="false">
          <span id="dropdown-entitas-text">{{ $selectedEntitas ?? 'Branch' }}</span>
          </button>

          <ul class="dropdown-menu dropdown-menu-end border border-secondary rounded shadow"
              aria-labelledby="dropdown-entitas"
              style="--bs-dropdown-min-width: 10rem; z-index:1055;">
            @foreach ($entitasList as $entitas)
              <li>
                <button class="dropdown-item d-flex align-items-center text-secondary"
                  wire:click="selectEntitas('{{ $entitas }}')">
                  {{ $entitas }}
                  @if ($selectedEntitas === $entitas)
                    <i class="bi bi-check-lg ms-auto"></i>
                  @else
                    <i class="bi bi-check-lg ms-auto d-none"></i>
                  @endif
                </button>
              </li>
            @endforeach
          </ul>
        </li>
      @endif
      <li class="nav-item dropdown user-menu">
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle text-secondary" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                <div>{{ auth()->user()->name }}</div>
            </a>
          <ul class="dropdown-menu dropdown-menu-end shadow rounded-3" style="min-width: 6rem; padding: 0.25rem 0;">
            <li>
              <a href="{{ route('logout') }}" class="dropdown-item">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
              </a>
            </li>
          </ul>
        </li>
      </li>
      <li class="nav-item dropdown">
        <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center" id="bd-theme"
          type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static">
          <span class="theme-icon-active">
            <i class="my-1"></i>
          </span>
          <span class="d-lg-none ms-2" id="bd-theme-text"></span>
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
        </ul>
      </li>
    </ul>
    <!--end::End Navbar links-->
  </div>

  <!--end::Container-->
</nav>
<!--end::Header-->