<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top py-3">
    <div class="container-fluid px-4 px-lg-5">
        <!-- Search Bar (Sesuai Gambar) -->
        <div class="d-none d-md-flex align-items-center">
            <div class="position-relative">
                <input type="text" class="form-control-search" placeholder="Cari event, tugas, dokumen...">
            </div>
        </div>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0 d-lg-none" type="button" onclick="document.querySelector('.sidebar').classList.toggle('active')">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Right Side Icons & Profile -->
        <div class="ms-auto d-flex align-items-center gap-3">
            <!-- Icons -->
            <div class="d-flex align-items-center gap-2 me-2">
                <button class="btn btn-link text-dark position-relative p-2">
                    <i class="bi bi-chat-dots fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle"></span>
                </button>
                <button class="btn btn-link text-dark position-relative p-2">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </button>
            </div>

            <!-- User Profile -->
            @auth
                <div class="dropdown">
                    <button class="btn border-0 d-flex align-items-center gap-3 p-0 text-start" type="button" data-bs-toggle="dropdown">
                        <div class="d-none d-sm-block">
                            <p class="mb-0 fw-bold text-dark small" style="line-height: 1.2;">{{ auth()->user()->name }}</p>
                            <p class="mb-0 text-muted" style="font-size: 11px;">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 p-2" style="border-radius: 16px; min-width: 200px;">
                        <li><a class="dropdown-item rounded-3 py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-3 py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
</nav>