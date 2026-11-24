<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.55rem; font-family: 'Nunito', sans-serif;">
            <i class="fas fa-hand-holding-usd text-success mr-2"></i> Penerimaan Kas &bull; Pelunasan Nota Cuci
        </h1>
        <p class="text-muted mb-0 small">Pencatatan pembayaran pelunasan nota cucian dari pelanggan (Tunai / Transfer Bank / QRIS).</p>
    </div>
    <div>
        <a href="<?= base_url('receipts/create') ?>" class="btn btn-laundry-mint">
            <i class="fas fa-plus-circle mr-1"></i> + Catat Pelunasan Kas
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-money-check-alt text-success mr-2"></i> Daftar Pembayaran &amp; Pelunasan Diterima</h4>
        <span class="badge badge-light border text-success font-weight-bold px-3 py-2" style="border-radius: 12px;"><?= count($receipts) ?> TRANSAKSI KAS</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No. Bukti Kas</th>
                        <th>Tanggal Bayar</th>
                        <th>No. Faktur Rujukan</th>
                        <th>Pelanggan</th>
                        <th>Metode Bayar</th>
                        <th>Masuk Ke Akun</th>
                        <th class="text-right">Jumlah Diterima</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($receipts)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-wallet fa-3x mb-3 text-black-50 d-block"></i>
                                Belum ada transaksi penerimaan kas.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($receipts as $rcp): ?>
                            <tr>
                                <td>
                                    <strong class="text-success"><?= esc($rcp['receipt_number']) ?></strong>
                                </td>
                                <td><?= format_tanggal_indo($rcp['payment_date']) ?></td>
                                <td>
                                    <a href="<?= base_url("invoices/{$rcp['invoice_id']}") ?>" class="font-weight-bold text-primary">
                                        <?= esc($rcp['invoice_number']) ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="font-weight-600 text-dark"><?= esc($rcp['customer_name'] ?? 'Pelanggan') ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-light text-uppercase font-weight-bold"><?= esc($rcp['payment_method']) ?></span>
                                </td>
                                <td>
                                    <span class="small font-weight-600 text-dark"><?= esc($rcp['cash_account_name'] ?? 'Kas Tunai') ?></span>
                                    <small class="text-muted">(<?= esc($rcp['account_code']) ?>)</small>
                                </td>
                                <td class="text-right font-weight-bold text-success" style="font-size: 0.95rem;">
                                    <?= format_rupiah($rcp['amount']) ?>
                                </td>
                                <td class="small text-muted"><?= esc($rcp['notes'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
