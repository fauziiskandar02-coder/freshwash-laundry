<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.55rem; font-family: 'Nunito', sans-serif;">
            <i class="fas fa-undo-alt text-warning mr-2"></i> Retur &amp; Garansi Cuci Ulang (Sales Returns)
        </h1>
        <p class="text-muted mb-0 small">Pencatatan klaim cuci ulang atau kompensasi pakaian yang memotong piutang atau mengembalikan kas.</p>
    </div>
    <div>
        <a href="<?= base_url('returns/create') ?>" class="btn btn-laundry-amber">
            <i class="fas fa-plus-circle mr-1"></i> + Proses Retur / Garansi
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-undo-alt text-warning mr-2"></i> Riwayat Retur &amp; Kompensasi Cuci</h4>
        <span class="badge badge-light border text-warning font-weight-bold px-3 py-2" style="border-radius: 12px;"><?= count($returns) ?> TOTAL RETUR</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No. Retur</th>
                        <th>Tanggal</th>
                        <th>Faktur Rujukan</th>
                        <th>Pelanggan</th>
                        <th>Metode Refund</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-right">PPN Retur</th>
                        <th class="text-right">Total Retur</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($returns)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-3x mb-3 text-black-50 d-block"></i>
                                Belum ada transaksi retur penjualan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($returns as $ret): ?>
                            <tr>
                                <td><strong class="text-danger"><?= esc($ret['return_number']) ?></strong></td>
                                <td><?= format_tanggal_indo($ret['return_date']) ?></td>
                                <td>
                                    <a href="<?= base_url("invoices/{$ret['invoice_id']}") ?>" class="font-weight-bold text-primary">
                                        <?= esc($ret['invoice_number']) ?>
                                    </a>
                                </td>
                                <td><span class="font-weight-600 text-dark"><?= esc($ret['customer_name'] ?? 'Pelanggan') ?></span></td>
                                <td>
                                    <span class="badge badge-<?= $ret['refund_method'] === 'potong_piutang' ? 'warning' : 'danger' ?> text-uppercase">
                                        <?= $ret['refund_method'] === 'potong_piutang' ? 'Potong Piutang' : 'Kembalikan Kas' ?>
                                    </span>
                                </td>
                                <td class="text-right"><?= format_rupiah($ret['subtotal']) ?></td>
                                <td class="text-right text-muted"><?= (float)$ret['tax_amount'] > 0 ? format_rupiah($ret['tax_amount']) : '-' ?></td>
                                <td class="text-right font-weight-bold text-danger"><?= format_rupiah($ret['grand_total']) ?></td>
                                <td class="small text-muted"><?= esc($ret['notes'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
