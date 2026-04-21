<!-- Sidebar Start -->
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="\" class="navbar-brand mx-4 mb-3">
            <h3 class="text-warning"></i>Temperature &</h3>
            <h3 class="text-warning"></i>Humidifier</h3>
        </a>

        <div class="navbar-nav w-100">
            <a href="/dashboard" class="nav-item nav-link {{ Request::is('dashboard') ? 'active' : '' }}"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            <a href="/dashboard/controls" class="nav-item nav-link {{ Request::is('dashboard/controls') ? 'active' : '' }}"><i class="fa fa-table me-2"></i>Rekap Data</a>
        </div>
    </nav>
</div>
<!-- Sidebar End -->