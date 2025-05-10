<!--begin::Header-->
<nav class="navbar navbar-expand" style="background-color: var(--bs-body-bg);">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Start Navbar links-->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"
          ><i class="bi bi-list"></i
        ></a>
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
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
            <i class="bi bi-bell-fill"></i>
            @if($unreadCount > 0)
                <span class="navbar-badge badge text-bg-warning">
                    {{ $unreadCount }}
                </span>
            @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
            <span class="dropdown-item dropdown-header">
                {{ count($notifs) }} Notifikasi
            </span>
    
            <div class="dropdown-divider"></div>
    
            @foreach($notifs as $notif)
                <a href="#" class="dropdown-item">
                    <i class="bi bi-info-circle me-2"></i>
                    {{ $notif['nama_karyawan'] }} mengajukan {{ $notif['nama_shift'] }}
                    <span class="float-end text-secondary fs-7">{{ $notif['created_at'] }}</span>
                </a>
                <div class="dropdown-divider"></div>
            @endforeach
    
            <a href="#" class="dropdown-item dropdown-footer">Lihat Semua</a>
        </div>
      </li>
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
          {{-- <img src="assets/img/user2-160x160.jpg" class="user-image rounded-circle shadow" alt="User Image"/> --}}
          <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <!--begin::User Image-->
          <li class="user-header text-bg-primary">
            {{-- <img src="assets/img/user2-160x160.jpg" class="rounded-circle shadow" alt="User Image"/> --}}
            <p>
              Alexander Pierce - Web Developer
              <small>Member since Nov. 2023</small>
            </p>
          </li>
          <!--end::User Image-->
          
          <!--begin::Menu Footer-->
          <li class="user-footer" style="background-color: var(--bs-body-bg);">
            <a href="{{ route('profile') }}" class="btn btn-default btn-flat">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                {{-- <button type="submit">Logout</button> --}}
                <button type="submit" class="btn btn-default btn-flat float-end">Logout</button>
            </form>
          </li>
          <!--end::Menu Footer-->
        </ul>
      </li>
      <li class="nav-item dropdown">
        <button
          class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center"
          id="bd-theme"
          type="button"
          aria-expanded="false"
          data-bs-toggle="dropdown"
          data-bs-display="static"
        >
          <span class="theme-icon-active">
            <i class="my-1"></i>
          </span>
          <span class="d-lg-none ms-2" id="bd-theme-text">Toggle theme</span>
        </button>
        <ul
          class="dropdown-menu dropdown-menu-end"
          aria-labelledby="bd-theme-text"
          style="--bs-dropdown-min-width: 8rem;"
        >
          <li>
            <button
              type="button"
              class="dropdown-item d-flex align-items-center active"
              data-bs-theme-value="light"
              aria-pressed="false"
            >
              <i class="bi bi-sun-fill me-2"></i>
              Light
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button
              type="button"
              class="dropdown-item d-flex align-items-center"
              data-bs-theme-value="dark"
              aria-pressed="false"
            >
              <i class="bi bi-moon-fill me-2"></i>
              Dark
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button
              type="button"
              class="dropdown-item d-flex align-items-center"
              data-bs-theme-value="auto"
              aria-pressed="true"
            >
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