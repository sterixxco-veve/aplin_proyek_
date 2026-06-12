<aside class="sidebar d-flex flex-column p-4">
    <div class="mb-4 px-2">
        <h4 class="fw-bold text-dark mb-0">GDGOC <span class="text-primary" style="color: #4f46e5 !important;">Event</span>
        </h4>
        <p class="text-muted small fw-bold mt-1 mb-2">Planner</p>
        <div>
            <span class="badge rounded-pill px-3 py-1 fw-medium"
                style="font-size: 10px; background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
                {{ auth()->user()->role ?? 'Super Admin' }}
            </span>
        </div>
    </div>

    <div class="sidebar-scroll-only mb-auto">
        <div class="d-flex flex-column gap-1">

            {{-- Menu Dashboard --}}
            <a href="{{ route('dashboard') }}"
                class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->routeIs('dashboard') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
                <i class="bi bi-grid-fill"></i>
                <span class="fw-bold small">Dashboard</span>
            </a>

            {{-- Menu Utama: Events (Trigger Dropdown Sub-Menu) --}}
            <a class="nav-link d-flex align-items-center justify-content-between py-2 px-3 rounded-3 text-muted hover-sidebar"
                data-bs-toggle="collapse" href="#eventsSubMenu" role="button"
                aria-expanded="{{ request()->is('events*') || request()->is('categories*') || request()->is('tasks*') || request()->is('budgets*') || request()->is('finance*') || request()->is('documents*') || request()->is('rundown*') || request()->is('partners*') || request()->is('documentation*') || request()->is('certificates*') ? 'true' : 'false' }}"
                aria-controls="eventsSubMenu" style="cursor: pointer;">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-calendar-event"></i>
                    <span class="fw-bold small">Events</span>
                </div>
                <i class="bi bi-chevron-down dropdown-arrow-icon"
                    style="font-size: 0.8rem; transition: transform 0.2s ease;"></i>
            </a>

            {{-- Wadah Sub-Menu Dropdown --}}
            <div class="collapse {{ request()->is('events*') || request()->is('categories*') || request()->is('tasks*') || request()->is('budgets*') || request()->is('finance*') || request()->is('documents*') || request()->is('rundown*') || request()->is('partners*') || request()->is('documentation*') || request()->is('certificates*') ? 'show' : '' }}"
                id="eventsSubMenu">
                <div class="submenu-list">

                    <a href="/events"
                        class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->routeIs('web.events.index') || (request()->is('events') && !request()->is('events/*/details')) ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
                        <i class="bi bi-list-ul flex-shrink-0"></i>
                        <span class="fw-bold small menu-text">All Events List</span>
                    </a>

                    <a href="/categories"
                        class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('categories*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
                        <i class="bi bi-tags flex-shrink-0"></i>
                        <span class="fw-bold small menu-text">Categories</span>
                    </a>

                    <a href="/tasks"
                        class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('tasks*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
                        <i class="bi bi-check2-square flex-shrink-0"></i>
                        <span class="fw-bold small menu-text">Tasks</span>
                    </a>

                    <a href="{{ route('web.budget.index') }}"
                        class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('budgets*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
                        <i class="bi bi-cash flex-shrink-0"></i>
                        <span class="fw-bold small menu-text">Budget</span>
                    </a>

                    <a href="/finance"
                        class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('finance*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
                        <i class="bi bi-currency-dollar flex-shrink-0"></i>
                        <span class="fw-bold small menu-text">Finance</span>
                    </a>

                    <a href="{{ route('web.documents.index') }}"
                        class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('documents*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
                        <i class="bi bi-file-earmark-text flex-shrink-0"></i>
                        <span class="fw-bold small menu-text">Documents</span>
                    </a>

                    <a href="{{ route('web.rundown.index') }}"
                        class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('rundown*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
                        <i class="bi bi-clock-history flex-shrink-0"></i>
                        <span class="fw-bold small menu-text">Rundown</span>
                    </a>

                    <a href="{{ route('web.partners.index') }}"
                        class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('partners*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
                        <i class="bi bi-people flex-shrink-0"></i>
                        <span class="fw-bold small menu-text">Partners</span>
                    </a>

                    <a href="/documentation"
                        class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('documentation*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
                        <i class="bi bi-camera flex-shrink-0"></i>
                        <span class="fw-bold small menu-text">Documentation</span>
                    </a>

                    <a href="{{ route('web.certificates.index') }}"
                        class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('certificates*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
                        <i class="bi bi-patch-check flex-shrink-0"></i>
                        <span class="fw-bold small menu-text">Certificates</span>
                    </a>
                </div>
            </div>

            {{-- Menu Luar Dropdown: Organization --}}
            <a href="/organizations"
                class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->is('organizations*') ? 'active-sidebar' : 'text-muted hover-sidebar' }}">
                <i class="bi bi-building"></i>
                <span class="fw-bold small">Organization</span>
            </a>

        </div>
    </div>

    <div class="mt-3 pt-2">
        <a href="{{ route('profile.password') }}"
            class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 {{ request()->routeIs('profile.password') ? 'active-sidebar' : 'text-muted hover-sidebar' }} mb-1">
            <i class="bi bi-key"></i>
            <span class="fw-bold small">Change Password</span>
        </a>
    </div>

    <div class="pt-3 border-top" style="border-color: #f1f5f9 !important;">
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
        background: #ffffff;
        border-right: 1px solid #e2e8f0;
        height: 100vh;
        position: fixed;
        width: 260px;
        top: 0;
        left: 0;
        z-index: 1030;
    }

    .sidebar-scroll-only {
        overflow-y: auto;
        overflow-x: hidden !important;
        max-height: calc(100vh - 250px);
        width: 100%;
    }

    .submenu-list {
        padding-left: 1.25rem;
        margin-left: 0.75rem;
        border-left: 1.5px dashed #e2e8f0;
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        width: calc(100% - 1.5rem);
    }

    .submenu-list .nav-link {
        display: flex !important;
        width: 100% !important;
    }

    .sidebar-scroll-only::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-scroll-only::-webkit-scrollbar-thumb {
        background-color: #e2e8f0;
        border-radius: 4px;
    }

    a[aria-expanded="true"] .dropdown-arrow-icon {
        transform: rotate(180deg);
        color: #4f46e5 !important;
    }

    .active-sidebar {
        background-color: #eef2ff;
        color: #4f46e5 !important;
        font-weight: 600;
    }

    .hover-sidebar:hover {
        background-color: #f8fafc;
        color: #0f172a !important;
    }

    .hover-danger-sidebar:hover {
        background-color: #fef2f2;
        color: #ef4444 !important;
    }

    .nav-link i {
        font-size: 1.1rem;
    }

    .nav-link {
        transition: all 0.15s ease;
    }
</style>
