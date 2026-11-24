<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.55rem; font-family: 'Nunito', sans-serif;">
            <i class="fas fa-tasks text-primary mr-2"></i> Laporan Status &amp; Umur Nota Laundry
        </h1>
        <p class="text-muted mb-0 small">Monitoring nota cucian belum lunas, sebagian, dan status jatuh tempo piutang member.</p>
    </div>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-laundry-primary btn-sm">
            <i class="fas fa-print mr-1"></i> Cetak Laporan Status
        </button>
    </div>
</div>

<!-- Filter Status Tab -->
<div class="card shadow-sm mb-4 no-print">
    <div class="card-body p-2">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link <?= empty($statusFilter) ? 'active' : '' ?>" href="<?= base_url('reports/invoice-status') ?>">
                    Semua Faktur
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $statusFilter === 'unpaid' ? 'active' : '' ?>" href="<?= base_url('reports/invoice-status?status=unpaid') ?>">
                    <span class="text-danger font-weight-bold">&bull;</span> Belum Lunas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $statusFilter === 'partial' ? 'active' : '' ?>" href="<?= base_url('reports/invoice-status?status=partial') ?>">
                    <span class="text-warning font-weight-bold">&bull;</span> Sebagian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $statusFilter === 'paid' ? 'active' : '' ?>" href="<?= base_url('reports/invoice-status?status=paid') ?>">
                    <span class="text-success font-weight-bold">&bull;</span> Lunas
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No. Faktur</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Terbit</th>
                        <th>Jatuh Tempo</th>
                        <th class="text-right">Total Tagihan</th>
                        <th class="text-right">Sudah Dibayar</th>
                        <th class="text-right">Sisa Piutang</th>
                        <th>Status & Umur</th>
                        <th class="text-center no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($invoices)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                Tidak ada data faktur pada filter ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($invoices as $inv): 
                            $rem = max(0, (float)$inv['remaining_amount']);
                            $isOverdue = false;
                            if ($inv['payment_type'] === 'kredit' && $inv['status'] !== 'paid' && !empty($inv['due_date'])) {
                                $isOverdue = (strtotime($inv['due_date']) < strtotime(date('Y-m-d')));
                            }
                        ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url("invoices/{$inv['id']}") ?>" class="font-weight-bold text-primary">
                                        <?= esc($inv['invoice_number']) ?>
                                    </a>
                                </td>
                                <td><span class="font-weight-600 text-dark"><?= esc($inv['customer_name'] ?? 'Pelanggan Umum') ?></span></td>
                                <td><?= format_tanggal_indo($inv['invoice_date']) ?></td>
                                <td>
                                    <?php if ($inv['payment_type'] === 'kredit' && !empty($inv['due_date'])): ?>
                                        <span class="<?= $isOverdue ? 'text-danger font-weight-bold' : '' ?>">
                                            <?= format_tanggal_indo($inv['due_date']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Tunai</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right font-weight-bold"><?= format_rupiah($inv['grand_total']) ?></td>
                                <td class="text-right text-success"><?= format_rupiah($inv['paid_amount']) ?></td>
                                <td class="text-right font-weight-bold <?= $rem > 0 ? 'text-danger' : 'text-muted' ?>">
                                    <?= format_rupiah($rem) ?>
                                </td>
                                <td>
                                    <?php if ($inv['status'] === 'paid'): ?>
                                        <span class="badge-status badge-paid"><i class="fas fa-check-circle"></i> LUNAS</span>
                                    <?php elseif ($isOverdue): ?>
                                        <span class="badge-status badge-unpaid"><i class="fas fa-exclamation-triangle"></i> JATUH TEMPO</span>
                                    <?php elseif ($inv['status'] === 'partial'): ?>
                                        <span class="badge-status badge-partial"><i class="fas fa-adjust"></i> SEBAGIAN</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-unpaid"><i class="fas fa-clock"></i> BELUM LUNAS</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center no-print">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url("invoices/{$inv['id']}") ?>" class="btn btn-outline-primary" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($inv['status'] !== 'paid'): ?>
                                            <a href="<?= base_url("receipts/create?invoice_id={$inv['id']}") ?>" class="btn btn-outline-success" title="Bayar">
                                                <i class="fas fa-hand-holding-usd"></i>
                                            </a>
                                        <?php endif; ?>
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
<?= $this->endSection() ?>
