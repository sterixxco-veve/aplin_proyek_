<aside class="sidebar p-4">
    <!-- Header Brand -->
    <div class="mb-5 px-2">
        <h4 class="fw-bold text-dark mb-0">GDGOC <span class="text-primary">Event</span></h4>
        <p class="text-muted small fw-bold mt-1">Planner</p>
        <div class="mt-2">
            <span class="badge bg-light text-muted border rounded-pill px-3 py-1 fw-medium" style="font-size: 10px;">
                {{ auth()->user()->role ?? 'Super Admin' }}
            </span>
        </div>
    </div>

    <!-- Menu Navigasi (Sesuai urutan image_5613e3.jpg) -->
    <nav class="nav flex-column gap-1 mb-auto">
        <a href="{{ route('dashboard') }}"
            class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->routeIs('dashboard') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
            <i class="bi bi-grid-fill"></i>
            <span class="fw-bold small">Dashboard</span>
        </a>

        <a href="/events"
            class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('events*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
            <i class="bi bi-calendar-event"></i>
            <span class="fw-bold small">Events</span>
        </a>

        <a href="/tasks"
            class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('tasks*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
            <i class="bi bi-check2-square"></i>
            <span class="fw-bold small">Tasks</span>
        </a>

        <a href="{{ route('web.budget.index') }}" class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3
        {{ request()->is('budgets*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
            <i class="bi bi-cash"></i>
            <span class="fw-bold small">Budget</span>
        </a>
        <a href="/finance"
            class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('finance*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
            <i class="bi bi-currency-dollar"></i>
            <span class="fw-bold small">Finance</span>
        </a>

        <a href="{{ route('web.documents.index') }}"
            class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('documents*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
            <i class="bi bi-file-earmark-text"></i>
            <span class="fw-bold small">Documents</span>
        </a>

        <a href="{{ route('web.rundown.index') }}"
            class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 text-muted hover-sidebar">
            <i class="bi bi-clock-history"></i>
            <span class="fw-bold small">Rundown</span>
        </a>

        <a href="{{ route('web.partners.index') }}"
            class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('partners*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
            <i class="bi bi-people"></i>
            <span class="fw-bold small">Partners</span>
        </a>
        <a href="/documentation"
            class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('documentation*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
            <i class="bi bi-camera"></i>
            <span class="fw-bold small">Documentation</span>
        </a>

        <a href="{{ route('web.certificates.index') }}"
            class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('certificates*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
            <i class="bi bi-patch-check"></i>
            <span class="fw-bold small">Certificates</span>
        </a>

        <a href="/organizations"
            class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('organizations*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
            <i class="bi bi-building"></i>
            <span class="fw-bold small">Organization</span>
        </a>
    </nav>

    <!-- Logout di Bawah -->
    <div class="mt-4 pt-4 border-top border-light">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 text-danger w-100 border-0 bg-transparent hover-danger-sidebar transition-all">
                <i class="bi bi-box-arrow-right"></i>
                <span class="fw-bold small">Logout</span>
            </button>
        </form>
    </div>
</aside>

<style>
    .sidebar {
        background: #fff;
        border-right: 1px solid #f1f3f4;
        height: 100vh;
        position: fixed;
        width: 260px;
    }

    .active-sidebar {
        background-color: #e8f0fe;
        color: #1a73e8 !important;
        font-weight: 600;
    }

    .hover-sidebar:hover {
        background-color: #f8f9fa;
        color: #202124 !important;
    }

    .hover-danger-sidebar:hover {
        background-color: #fef2f2;
        color: #d93025 !important;
    }

    .nav-link i {
        font-size: 1.1rem;
    }
</style>