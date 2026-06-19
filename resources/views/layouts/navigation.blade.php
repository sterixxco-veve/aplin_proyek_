<nav class="navbar navbar-expand-lg bg-white border-bottom py-3 custom-dashboard-navbar">
    <div class="container-fluid px-4 px-lg-5">

        <div class="ms-auto d-flex align-items-center gap-3">
            @auth
                <div class="dropdown">
                    <button class="btn border-0 d-flex align-items-center gap-3 p-0 text-start" type="button"
                        data-bs-toggle="dropdown">
                        <div class="d-none d-sm-block text-end">
                            <p class="mb-0 fw-bold text-dark small" style="line-height: 1.2;">{{ auth()->user()->name }}</p>
                            <p class="mb-0 text-muted" style="font-size: 11px;">{{ auth()->user()->email }}</p>
                        </div>

                        <!-- <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 40px; height: 40px; background-color: #4f46e5 !important;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div> -->

                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                class="rounded-circle shadow-sm" 
                                style="width: 40px; height: 40px; object-fit: cover;" 
                                alt="User Avatar">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 40px; height: 40px; background-color: #4f46e5 !important; font-weight: 600;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif

                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 p-2"
                        style="border-radius: 16px; min-width: 200px;">
                        <li>
                            <a class="dropdown-item rounded-3 py-2" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person me-2"></i>Profil
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider opacity-50">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-3 py-2 text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
</nav>

<style>
    .custom-dashboard-navbar {
        position: fixed !important;
        top: 0 !important;
        left: 260px !important;
        width: calc(100% - 260px) !important;
        height: 70px !important;
        z-index: 1020 !important;
        background-color: rgba(255, 255, 255, 0.98) !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.01);
    }

    .dropdown-menu {
        z-index: 1060;
    }
</style>
