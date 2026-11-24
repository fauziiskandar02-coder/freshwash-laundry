<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.55rem; font-family: 'Nunito', sans-serif;">
            <i class="fas fa-book-open text-primary mr-2"></i> Buku Besar Pembantu Piutang Member Laundry
        </h1>
        <p class="text-muted mb-0 small">Buku mutasi piutang per pelanggan/member laundry (Debit = Penjualan Cuci, Kredit = Pelunasan &amp; Retur).</p>
    </div>
    <div class="no-print">
        <?php if (!empty($customerId)): ?>
            <a href="<?= base_url('reports/receivables/print?customer_id=' . esc($customerId)) ?>" target="_blank" class="btn btn-laundry-primary btn-sm">
                <i class="fas fa-print mr-1"></i> Cetak Kartu Piutang Resmi
            </a>
        <?php else: ?>
            <button class="btn btn-outline-secondary btn-sm" disabled title="Pilih pelanggan terlebih dahulu">
                <i class="fas fa-print mr-1"></i> Cetak Kartu Piutang
            </button>
        <?php endif; ?>
    </div>
</div>

<div class="card shadow-sm mb-4 no-print">
    <div class="card-body p-3">
        <form method="GET" action="<?= base_url('reports/receivables') ?>" class="row align-items-end">
            <div class="col-md-6 mb-2">
                <label class="form-label font-weight-bold small">Pilih Pelanggan / Klien:</label>
                <select class="form-control select2" name="customer_id" required>
                    <option value="">-- Pilih Pelanggan untuk Membuka Kartu Piutang --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $customerId == $c['id'] ? 'selected' : '' ?>>
                            <?= esc($c['code']) ?> &bull; <?= esc($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-search mr-1"></i> Buka Kartu
                </button>
            </div>
        </form>
    </div>
</div>

<?php if ($selectedCustomer): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4 bg-light border-bottom">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <span class="text-muted small text-uppercase font-weight-bold">Kartu Piutang Pelanggan:</span>
                    <h4 class="text-dark font-weight-bold mb-1"><?= esc($selectedCustomer['name']) ?> (<?= esc($selectedCustomer['code']) ?>)</h4>
                    <p class="small text-muted mb-0">Kontak: <?= esc($selectedCustomer['phone'] ?? '-') ?> &bull; Alamat: <?= esc($selectedCustomer['address'] ?? '-') ?></p>
                </div>
                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                    <span class="text-muted small text-uppercase font-weight-bold d-block">Sisa Saldo Piutang:</span>
                    <h3 class="<?= $finalBalance > 0 ? 'text-danger' : 'text-success' ?> font-weight-bold mb-0">
                        <?= format_rupiah($finalBalance) ?>
                    </h3>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 120px;">Tanggal</th>
                            <th>No. Dokumen</th>
                            <th>Jenis Transaksi</th>
                            <th>Keterangan</th>
                            <th class="text-right" style="width: 150px;">Debit (+)</th>
                            <th class="text-right" style="width: 150px;">Kredit (-)</th>
                            <th class="text-right" style="width: 170px;">Saldo Piutang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ledger)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    Belum ada catatan mutasi transaksi untuk pelanggan ini.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ledger as $tx): ?>
                                <tr>
                                    <td><?= format_tanggal_indo($tx['date']) ?></td>
                                    <td><strong><?= esc($tx['doc_number']) ?></strong></td>
                                    <td>
                                        <span class="badge badge-light"><?= esc($tx['type']) ?></span>
                                    </td>
                                    <td class="small text-muted"><?= esc($tx['description']) ?></td>
                                    <td class="text-right text-dark font-weight-bold">
                                        <?= (float)$tx['debit'] > 0 ? format_rupiah($tx['debit']) : '-' ?>
                                    </td>
                                    <td class="text-right text-success font-weight-bold">
                                        <?= (float)$tx['credit'] > 0 ? format_rupiah($tx['credit']) : '-' ?>
                                    </td>
                                    <td class="text-right font-weight-bold <?= $tx['balance'] > 0 ? 'text-danger' : 'text-success' ?>">
                                        <?= format_rupiah($tx['balance']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm text-center py-5">
        <div class="card-body">
            <i class="fas fa-book-open fa-3x text-black-50 mb-3"></i>
            <h5 class="text-dark font-weight-bold">Pilih Pelanggan</h5>
            <p class="text-muted small">Pilih salah satu mitra pelanggan di atas untuk melihat buku besar pembantu piutang dan histori saldo berjalannya.</p>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
