<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
        <div class="search-element d-none d-md-block">
            <span class="laundry-badge-pill laundry-badge-info font-weight-bold">
                <i class="fas fa-soap mr-1"></i> FreshWash Laundry &bull; Bersih, Wangi &amp; Rapi
            </span>
        </div>
    </form>
    <ul class="navbar-nav navbar-right align-items-center" style="gap: 12px;">
        <li class="d-none d-sm-block">
            <div class="laundry-badge-pill laundry-badge-clean font-weight-bold">
                <i class="fas fa-check-circle mr-1"></i>
                <span>Kasir Bertugas: <?= esc(session()->get('user_name') ?? 'Admin') ?> (<?= strtoupper(esc(session()->get('user_role') ?? 'kasir')) ?>)</span>
            </div>
        </li>

        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <div class="d-inline-flex align-items-center">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2 shadow-sm" style="width: 36px; height: 36px; font-weight: 800; font-size: 0.95rem; background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%) !important;">
                        🧺
                    </div>
                    <div class="d-none d-lg-inline-block font-weight-bold text-dark" style="font-size: 0.88rem;">
                        <?= esc(session()->get('user_name') ?? 'Operator') ?> 
                        <span class="badge badge-primary text-uppercase ml-1" style="font-size: 0.65rem; border-radius: 12px; background: #0284c7 !important;"><?= esc(session()->get('user_role') ?? 'kasir') ?></span>
                    </div>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0" style="border-radius: 14px;">
                <div class="dropdown-title">Akses Laundry: <?= esc(session()->get('user_role') ?? 'kasir') ?></div>
                <div class="dropdown-divider"></div>
                <a href="<?= base_url('logout') ?>" class="dropdown-item has-icon text-danger font-weight-bold">
                    <i class="fas fa-sign-out-alt"></i> Keluar (Logout)
                </a>
            </div>
        </li>
    </ul>
</nav>
