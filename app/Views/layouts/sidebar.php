<?php
$uri = service('uri');
$seg1 = $uri->getSegment(1) ?? '';
$seg2 = $uri->getSegment(2) ?? '';
$userRole = session()->get('user_role') ?? 'kasir';
?>
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?= base_url('dashboard') ?>">
                <div class="brand-bubble-icon">
                    <i class="fas fa-soap"></i>
                </div>
                <span>FreshWash</span>
            </a>
        </div>
        <div class="sidebar-brand-sm">
            <a href="<?= base_url('dashboard') ?>">
                <i class="fas fa-soap text-primary"></i>
            </a>
        </div>
        
        <ul class="sidebar-menu">
            <li class="menu-header">Menu Utama</li>
            <li class="<?= ($seg1 === 'dashboard' || empty($seg1)) ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('dashboard') ?>">
                    <i class="fas fa-home"></i> <span>Dashboard Utama</span>
                </a>
            </li>

            <li class="menu-header">Operasional Laundry (Core)</li>
            <li class="<?= ($seg1 === 'invoices' && $seg2 === 'create') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('invoices/create') ?>">
                    <i class="fas fa-plus-circle text-primary"></i> <span>+ Buat Nota Baru</span>
                </a>
            </li>
            <li class="<?= ($seg1 === 'invoices' && $seg2 !== 'create') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('invoices') ?>">
                    <i class="fas fa-receipt"></i> <span>Nota & Faktur Cuci</span>
                </a>
            </li>
            <li class="<?= ($seg1 === 'receipts') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('receipts') ?>">
                    <i class="fas fa-hand-holding-usd"></i> <span>Penerimaan Kas (Bayar)</span>
                </a>
            </li>
            <li class="<?= ($seg1 === 'returns') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('returns') ?>">
                    <i class="fas fa-undo-alt"></i> <span>Retur & Cuci Ulang</span>
                </a>
            </li>

            <li class="menu-header">Master Data Laundry</li>
            <li class="<?= ($seg1 === 'customers') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('customers') ?>">
                    <i class="fas fa-users"></i> <span>Pelanggan & Member</span>
                </a>
            </li>
            <li class="<?= ($seg1 === 'items') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('items') ?>">
                    <i class="fas fa-tshirt"></i> <span>Paket & Layanan Cuci</span>
                </a>
            </li>

            <?php if ($userRole === 'admin'): ?>
            <li class="menu-header">Laporan Keuangan & Akuntansi</li>
            <li class="<?= ($seg1 === 'reports' && $seg2 === 'sales') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('reports/sales') ?>">
                    <i class="fas fa-chart-line"></i> <span>Laporan Omzet Penjualan</span>
                </a>
            </li>
            <li class="<?= ($seg1 === 'reports' && $seg2 === 'receivables') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('reports/receivables') ?>">
                    <i class="fas fa-book-open"></i> <span>Kartu Piutang Member</span>
                </a>
            </li>
            <li class="<?= ($seg1 === 'reports' && $seg2 === 'invoice-status') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('reports/invoice-status') ?>">
                    <i class="fas fa-tasks"></i> <span>Status & Umur Nota</span>
                </a>
            </li>

            <li class="menu-header">Pengaturan & Keamanan</li>
            <li class="<?= ($seg1 === 'settings') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('settings') ?>">
                    <i class="fas fa-sliders-h"></i> <span>Pengaturan Toko Laundry</span>
                </a>
            </li>
            <li class="<?= ($seg1 === 'users') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('users') ?>">
                    <i class="fas fa-users-cog"></i> <span>Manajemen Kasir & User</span>
                </a>
            </li>
            <?php endif; ?>

            <li class="menu-header">Informasi Tim &amp; Proyek</li>
            <li class="<?= ($seg1 === 'info') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('info') ?>">
                    <i class="fas fa-users text-primary"></i> <span>Info Kelompok 5</span>
                </a>
            </li>
        </ul>

        <!-- Bottom Laundry Badge -->
        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <div class="p-3 bg-light rounded-lg text-center" style="border-radius: 14px; border: 1px solid #e0f2fe; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);">
                <span class="d-block font-weight-bold text-primary mb-1" style="font-size: 0.82rem;">
                    🫧 100% Bersih & Harum
                </span>
                <span class="small text-muted d-block" style="font-size: 0.72rem;">
                    Siklus Pendapatan UMKM SV-IPB
                </span>
            </div>
        </div>
    </aside>
</div>
