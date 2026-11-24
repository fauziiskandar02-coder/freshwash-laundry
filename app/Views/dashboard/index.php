<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<!-- Dynamic Greeting & Top Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3" style="gap: 12px;">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1" style="gap: 8px;">
            <span id="dynamic-greeting" class="laundry-badge-pill laundry-badge-mascot font-weight-bold">
                🫧 Selamat Datang
            </span>
            <span class="small text-muted font-weight-600">
                <i class="far fa-calendar-alt text-primary mr-1"></i> <?= date('l, d F Y') ?>
            </span>
        </div>
        <h1 class="font-weight-bold text-dark mb-0" style="font-size: 1.6rem; font-family: 'Nunito', sans-serif;">
            Dashboard Siklus Pendapatan Laundry
        </h1>
        <p class="text-muted mb-0 small">
            Ringkasan omzet cuci harian, sisa piutang member, penerimaan kas, dan penjurnalan akuntansi otomatis.
        </p>
    </div>
    <div class="d-none d-md-flex align-items-center" style="gap: 8px;">
        <span class="laundry-badge-pill laundry-badge-clean font-weight-bold shadow-sm">
            <i class="fas fa-check-circle mr-1"></i> Toko Siap Cuci
        </span>
        <span class="laundry-badge-pill laundry-badge-info font-weight-bold shadow-sm">
            <i class="fas fa-tint mr-1"></i> Busa Melimpah
        </span>
    </div>
</div>

<!-- ========================================================
     HERO BANNER - FRESH LAUNDRY THEME WITH 3D CHIBI MASCOT
     ======================================================== -->
<div class="card laundry-hero-card p-4 mb-4">
    <div class="row align-items-center">
        <!-- Left Side: Laundry System Info & Quick Action Buttons -->
        <div class="col-lg-7 col-md-12 mb-4 mb-lg-0">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2" style="gap: 8px;">
                <span class="laundry-badge-pill laundry-badge-clean font-weight-bold shadow-sm">
                    <i class="fas fa-sparkles mr-1"></i> 100% BERSIH, WANGI &amp; RAPI
                </span>
                <span class="laundry-badge-pill laundry-badge-info font-weight-bold shadow-sm">
                    🧺 FreshWash Laundry UMKM &bull; SV IPB
                </span>
            </div>

            <h1 class="hero-laundry-title">
                Sistem Informasi Akuntansi &bull; <span class="highlight-wash">FreshWash Laundry</span>
            </h1>
            <div class="hero-laundry-subtitle">
                <i class="fas fa-soap mr-1"></i> Modul Siklus Pendapatan Usaha Jasa Cuci Kiloan &amp; Dry Cleaning
            </div>

            <p class="hero-laundry-desc">
                Pencatatan transaksi laundry praktis dan cepat: terbitkan nota cucian kiloan &amp; satuan, terima pelunasan tunai/QRIS, proses garansi cuci ulang, dan pantau buku pembantu piutang member dengan penjurnalan akuntansi otomatis di background.
            </p>

            <div class="d-flex flex-wrap gap-2" style="gap: 10px;">
                <a href="<?= base_url('invoices/create') ?>" class="btn btn-laundry-primary">
                    <i class="fas fa-plus-circle mr-1"></i> + Buat Nota Cucian Baru
                </a>
                <a href="<?= base_url('reports/sales') ?>" class="btn btn-laundry-mint">
                    <i class="fas fa-chart-line mr-1"></i> Laporan Omzet
                </a>
                <a href="<?= base_url('reports/receivables') ?>" class="btn btn-laundry-amber">
                    <i class="fas fa-book-open mr-1"></i> Kartu Piutang Member
                </a>
            </div>

            <!-- Mini Service Highlights to Balance Height with 3D Mascot -->
            <div class="mt-4 pt-3 border-top d-flex flex-wrap align-items-center" style="gap: 16px; border-color: rgba(2, 132, 199, 0.15) !important;">
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #e0f2fe; display: flex; align-items: center; justify-content: center; font-size: 15px;">⚡</div>
                    <div>
                        <div class="font-weight-bold text-dark small" style="line-height: 1.1;">Cuci Kilat Express</div>
                        <div class="text-muted" style="font-size: 11px;">Selesai 4 Jam - 24 Jam</div>
                    </div>
                </div>
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #ecfdf5; display: flex; align-items: center; justify-content: center; font-size: 15px;">🧺</div>
                    <div>
                        <div class="font-weight-bold text-dark small" style="line-height: 1.1;">Kiloan &amp; Bed Cover</div>
                        <div class="text-muted" style="font-size: 11px;">Deterjen Ramah Serat</div>
                    </div>
                </div>
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #fef3c7; display: flex; align-items: center; justify-content: center; font-size: 15px;">✨</div>
                    <div>
                        <div class="font-weight-bold text-dark small" style="line-height: 1.1;">Garansi Cuci Ulang</div>
                        <div class="text-muted" style="font-size: 11px;">100% Bersih &amp; Rapi</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: 3D Cute Chibi Washing Machine Mascot (Si Wangi) -->
        <div class="col-lg-5 col-md-12">
            <div class="mascot-laundry-stage shadow-sm">
                <span class="stage-bubble-deco">🫧</span>

                <!-- Stage Header -->
                <div class="stage-header">
                    <div>
                        <i class="fas fa-soap text-primary mr-1"></i>
                        <span>MASKOT LAUNDRY &bull; SI WANGI</span>
                    </div>
                    <span class="badge badge-light border text-primary font-weight-bold"><i class="fas fa-check-circle text-success mr-1"></i> UNIT WASH-01</span>
                </div>

                <!-- 3D Three.js Washing Machine Canvas -->
                <div id="washbot3d-container"></div>

                <!-- Stage Footer Controls -->
                <div class="stage-footer">
                    <div>
                        <i class="fas fa-arrows-alt text-primary mr-1"></i> Drag / geser untuk memutar 360&deg;
                    </div>
                    <div class="text-success font-weight-bold">
                        <i class="fas fa-circle mr-1" style="font-size: 8px;"></i> Busa Siap!
                    </div>
                </div>

                <!-- Interactive Wash Spin Button -->
                <div class="mt-3 text-center">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-block font-weight-bold shadow-sm" style="border-radius: 12px; border-width: 1.5px; padding: 8px;" onclick="if(window.triggerWashSpin) window.triggerWashSpin();">
                        <i class="fas fa-sync-alt mr-1"></i> Putar Cepat Mesin Cuci (Busa Melimpah!) 🫧
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================
     4 SUMMARY KPI CARDS (CLEAN & BRIGHT)
     ======================================================== -->
<div class="row mb-4">
    <!-- Omzet Bulan Ini -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3 mb-lg-0">
        <div class="card card-kpi-laundry card-kpi-blue shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="kpi-title">Omzet Laundry Bulan Ini</span>
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%) !important;">
                        <i class="fas fa-wallet fa-sm"></i>
                    </div>
                </div>
                <div class="kpi-number text-primary"><?= format_rupiah($monthlyRevenue) ?></div>
                <div class="small mt-2 text-muted font-weight-600">
                    <i class="fas fa-calendar-alt mr-1 text-primary"></i> Periode: <?= date('F Y') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sisa Piutang Member -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3 mb-lg-0">
        <div class="card card-kpi-laundry card-kpi-amber shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="kpi-title">Sisa Piutang Member</span>
                    <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%) !important;">
                        <i class="fas fa-file-invoice-dollar fa-sm"></i>
                    </div>
                </div>
                <div class="kpi-number text-warning"><?= format_rupiah($totalReceivable) ?></div>
                <div class="small mt-2 text-muted font-weight-600">
                    <i class="fas fa-clock mr-1 text-warning"></i> Nota belum lunas
                </div>
            </div>
        </div>
    </div>

    <!-- Kas Masuk Pelunasan -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3 mb-lg-0">
        <div class="card card-kpi-laundry card-kpi-mint shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="kpi-title">Kas Pelunasan Masuk</span>
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, #059669 0%, #10b981 100%) !important;">
                        <i class="fas fa-hand-holding-usd fa-sm"></i>
                    </div>
                </div>
                <div class="kpi-number text-success"><?= format_rupiah($monthlyCashReceipts) ?></div>
                <div class="small mt-2 text-muted font-weight-600">
                    <i class="fas fa-check-double mr-1 text-success"></i> Tunai &amp; QRIS Laundry
                </div>
            </div>
        </div>
    </div>

    <!-- Total Nota & Pelanggan Aktif -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-kpi-laundry card-kpi-purple shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="kpi-title">Total Nota &bull; Member</span>
                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%) !important;">
                        <i class="fas fa-soap fa-sm"></i>
                    </div>
                </div>
                <div class="kpi-number" style="color: #8b5cf6;"><?= $totalInvoicesCount ?> / <?= $totalCustomersCount ?></div>
                <div class="small mt-2 text-muted font-weight-600">
                    <i class="fas fa-users mr-1" style="color: #8b5cf6;"></i> Pesanan cuci &amp; member
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================
     TABEL TRANSAKSI NOTA & AIS JOURNAL LOG
     ======================================================== -->
<div class="row">
    <!-- Nota Laundry Terkini -->
    <div class="col-lg-7 col-md-12 mb-4 mb-lg-0">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-tshirt text-primary mr-2"></i> Nota Pesanan Laundry Terkini</h4>
                <a href="<?= base_url('invoices') ?>" class="small font-weight-bold text-primary">
                    Lihat Semua Nota <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No. Nota</th>
                                <th>Member / Pelanggan</th>
                                <th>Total Biaya</th>
                                <th>Status Bayar</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentInvoices)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-soap fa-3x mb-3 text-primary d-block" style="opacity: 0.35;"></i>
                                        Belum ada nota pesanan cucian yang dibuat. Klik tombol <strong>"+ Buat Nota Baru"</strong> untuk memulai.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentInvoices as $inv): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url("invoices/{$inv['id']}") ?>" class="font-weight-bold text-primary">
                                                <?= esc($inv['invoice_number']) ?>
                                            </a>
                                            <div class="small text-muted">
                                                <?= format_tanggal_indo($inv['invoice_date']) ?> &bull; <?= strtoupper($inv['payment_type']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-dark"><?= esc($inv['customer_name'] ?? 'Pelanggan Umum') ?></span>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-dark"><?= format_rupiah($inv['grand_total']) ?></span>
                                            <?php if ($inv['is_pkp']): ?>
                                                <span class="badge badge-light border text-muted small ml-1">PPN</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($inv['status'] === 'paid'): ?>
                                                <span class="badge-status badge-paid"><i class="fas fa-check-circle"></i> LUNAS</span>
                                            <?php elseif ($inv['status'] === 'partial'): ?>
                                                <span class="badge-status badge-partial"><i class="fas fa-adjust"></i> SEBAGIAN</span>
                                            <?php else: ?>
                                                <span class="badge-status badge-unpaid"><i class="fas fa-hourglass-half"></i> BELUM LUNAS</span>
                                            <?php endif; ?>
                                            <?php 
                                                $pStatus = $inv['process_status'] ?? 'antrean';
                                                $pMap = [
                                                    'antrean'    => ['🕒 Antrean', 'secondary'],
                                                    'cuci'       => ['🫧 Dicuci', 'info'],
                                                    'setrika'    => ['👔 Disetrika', 'warning'],
                                                    'siap_ambil' => ['✨ Siap Ambil', 'success'],
                                                    'diambil'    => ['📦 Diambil', 'light border text-muted'],
                                                ];
                                                $st = $pMap[$pStatus] ?? ['🕒 Antrean', 'secondary'];
                                            ?>
                                            <div class="mt-1">
                                                <span class="badge badge-<?= $st[1] ?> font-weight-bold" style="border-radius: 6px; font-size: 10px; padding: 2px 6px;">
                                                    <?= $st[0] ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= base_url("invoices/{$inv['id']}") ?>" class="btn btn-sm btn-light border" title="Lihat Detail Nota">
                                                    <i class="fas fa-eye text-primary"></i>
                                                </a>
                                                <a href="<?= base_url("invoices/{$inv['id']}/print-thermal") ?>" target="_blank" class="btn btn-sm btn-light border text-info" title="Cetak Struk POS (58/80mm)">
                                                    <i class="fas fa-receipt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Automasi Jurnal Akuntansi (AIS Log) -->
    <div class="col-lg-5 col-md-12">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-book-reader text-success mr-2"></i> Automasi Jurnal (AIS Log)</h4>
                <span class="badge badge-success px-2 py-1 font-weight-bold" style="border-radius: 8px;">AUTO DOUBLE-ENTRY</span>
            </div>
            <div class="card-body p-3">
                <p class="small text-muted mb-3">
                    <i class="fas fa-info-circle text-primary mr-1"></i> Setiap nota cucian, pelunasan kas, & retur otomatis menjurnal debit-kredit seimbang ke Buku Besar tanpa perlu diinput manual.
                </p>

                <?php if (empty($recentJournals)): ?>
                    <div class="text-center py-4 text-muted small">
                        Belum ada riwayat jurnal akuntansi.
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentJournals as $j): ?>
                            <div class="list-group-item px-2 py-2 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-primary small"><?= esc($j['journal_number']) ?></strong>
                                    <span class="badge badge-light border font-weight-normal small"><?= $j['journal_date'] ?></span>
                                </div>
                                <div class="small text-dark font-weight-600 mb-2"><?= esc($j['description']) ?></div>
                                <div class="bg-light p-2 rounded small" style="border: 1px solid #e2e8f0; font-size: 0.78rem;">
                                    <?php foreach ($j['details'] as $det): ?>
                                        <div class="d-flex justify-content-between text-muted">
                                            <span>
                                                <?php if ((float)$det['credit'] > 0): ?>
                                                    &nbsp;&nbsp;&nbsp;&bull; (Cr) <?= esc($det['account_name']) ?>
                                                <?php else: ?>
                                                    &bull; (Dr) <?= esc($det['account_name']) ?>
                                                <?php endif; ?>
                                            </span>
                                            <span class="font-weight-bold text-dark">
                                                <?= (float)$det['debit'] > 0 ? format_rupiah($det['debit']) : format_rupiah($det['credit']) ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dynamic time-based greeting
        const greetingEl = document.getElementById('dynamic-greeting');
        if (greetingEl) {
            const hour = new Date().getHours();
            let greeting = 'Selamat Datang';
            if (hour >= 4 && hour < 11) greeting = '🌅 Selamat Pagi';
            else if (hour >= 11 && hour < 15) greeting = '☀️ Selamat Siang';
            else if (hour >= 15 && hour < 18) greeting = '🌇 Selamat Sore';
            else greeting = '🌙 Selamat Malam';
            greetingEl.textContent = `${greeting}, <?= esc(session()->get('user_name') ?? 'Admin') ?>!`;
        }

        // Initialize 3D Tencent HY3D Mascot
        if (typeof initWashBot3D === 'function') {
            initWashBot3D('washbot3d-container');
        }
    });
</script>
<?= $this->endSection() ?>