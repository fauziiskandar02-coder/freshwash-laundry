<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.55rem; font-family: 'Nunito', sans-serif;">
            <i class="fas fa-chart-line text-primary mr-2"></i> Laporan Omzet Penjualan Laundry
        </h1>
        <p class="text-muted mb-0 small">Rekapitulasi omzet cucian kiloan &amp; satuan, pungutan PPN, diskon, dan status pelunasan nota.</p>
    </div>
    <div class="d-flex" style="gap: 8px;">
        <a href="<?= base_url('reports/sales/export-csv?' . http_build_query($_GET)) ?>" class="btn btn-laundry-mint btn-sm">
            <i class="fas fa-file-excel mr-1"></i> Ekspor CSV / Excel
        </a>
        <a href="<?= base_url('reports/sales/print?' . http_build_query($_GET)) ?>" target="_blank" class="btn btn-laundry-primary btn-sm">
            <i class="fas fa-print mr-1"></i> Cetak Lembar Resmi PDF
        </a>
    </div>
</div>

<!-- Filter Box -->
<div class="card shadow-sm mb-4 no-print">
    <div class="card-body p-3">
        <form method="GET" action="<?= base_url('reports/sales') ?>" class="row align-items-end">
            <div class="col-md-3 mb-2">
                <label class="form-label small font-weight-bold">Dari Tanggal:</label>
                <input type="date" class="form-control form-control-sm" name="start_date" value="<?= esc($startDate) ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small font-weight-bold">Sampai Tanggal:</label>
                <input type="date" class="form-control form-control-sm" name="end_date" value="<?= esc($endDate) ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small font-weight-bold">Pelanggan:</label>
                <select class="form-control form-control-sm select2" name="customer_id">
                    <option value="">-- Semua Pelanggan --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $customerId == $c['id'] ? 'selected' : '' ?>>
                            <?= esc($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small font-weight-bold">Status Faktur:</label>
                <select class="form-control form-control-sm" name="status">
                    <option value="">-- Semua --</option>
                    <option value="paid" <?= $status === 'paid' ? 'selected' : '' ?>>Lunas</option>
                    <option value="partial" <?= $status === 'partial' ? 'selected' : '' ?>>Sebagian</option>
                    <option value="unpaid" <?= $status === 'unpaid' ? 'selected' : '' ?>>Belum Lunas</option>
                </select>
            </div>
            <div class="col-md-1 mb-2">
                <button type="submit" class="btn btn-primary btn-sm btn-block">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan Nilai Finansial -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="p-3 bg-white rounded border shadow-sm">
            <span class="text-muted small text-uppercase font-weight-bold d-block">Subtotal Penjualan</span>
            <h5 class="text-dark font-weight-bold mb-0 mt-1"><?= format_rupiah($sumSubtotal) ?></h5>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="p-3 bg-white rounded border shadow-sm">
            <span class="text-muted small text-uppercase font-weight-bold d-block">Total Diskon Diberikan</span>
            <h5 class="text-danger font-weight-bold mb-0 mt-1"><?= format_rupiah($sumDiscount) ?></h5>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="p-3 bg-white rounded border shadow-sm">
            <span class="text-muted small text-uppercase font-weight-bold d-block">Total PPN Dipungut</span>
            <h5 class="text-info font-weight-bold mb-0 mt-1"><?= format_rupiah($sumTax) ?></h5>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="p-3 bg-white rounded border shadow-sm">
            <span class="text-muted small text-uppercase font-weight-bold d-block">Total Omset (Grand Total)</span>
            <h5 class="text-primary font-weight-bold mb-0 mt-1"><?= format_rupiah($sumTotal) ?></h5>
        </div>
    </div>
</div>

<!-- Tabel Laporan -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No. Faktur</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Tipe</th>
                        <th>Pajak</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-right">Diskon</th>
                        <th class="text-right">PPN</th>
                        <th class="text-right">Grand Total</th>
                        <th class="text-right">Dibayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($invoices)): ?>
                        <tr>
                            <td colspan="11" class="text-center py-5 text-muted">
                                Tidak ada data transaksi penjualan pada rentang filter ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($invoices as $inv): ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url("invoices/{$inv['id']}") ?>" class="font-weight-bold text-primary">
                                        <?= esc($inv['invoice_number']) ?>
                                    </a>
                                </td>
                                <td><?= $inv['invoice_date'] ?></td>
                                <td><?= esc($inv['customer_name'] ?? 'Pelanggan Umum') ?></td>
                                <td><span class="badge badge-light text-uppercase"><?= esc($inv['payment_type']) ?></span></td>
                                <td><?= $inv['is_pkp'] ? 'PKP' : 'Non-PKP' ?></td>
                                <td class="text-right"><?= format_rupiah($inv['subtotal']) ?></td>
                                <td class="text-right text-muted"><?= (float)$inv['discount_total'] > 0 ? format_rupiah($inv['discount_total']) : '-' ?></td>
                                <td class="text-right text-muted"><?= (float)$inv['tax_amount'] > 0 ? format_rupiah($inv['tax_amount']) : '-' ?></td>
                                <td class="text-right font-weight-bold text-dark"><?= format_rupiah($inv['grand_total']) ?></td>
                                <td class="text-right text-success"><?= format_rupiah($inv['paid_amount']) ?></td>
                                <td>
                                    <?php if ($inv['status'] === 'paid'): ?>
                                        <span class="badge-status badge-paid"><i class="fas fa-check-circle"></i> LUNAS</span>
                                    <?php elseif ($inv['status'] === 'partial'): ?>
                                        <span class="badge-status badge-partial"><i class="fas fa-adjust"></i> SEBAGIAN</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-unpaid"><i class="fas fa-hourglass-half"></i> BELUM LUNAS</span>
                                    <?php endif; ?>
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
