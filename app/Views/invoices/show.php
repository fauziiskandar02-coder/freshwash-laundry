<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.5rem;">Faktur #<?= esc($invoice['invoice_number']) ?></h1>
        <p class="text-muted mb-0 small">Diterbitkan pada <?= format_tanggal_indo($invoice['invoice_date']) ?> oleh <?= esc($invoice['creator_name'] ?? 'Admin') ?></p>
    </div>
    <div class="d-flex" style="gap: 8px;">
        <a href="<?= base_url('invoices') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Daftar
        </a>
        <a href="<?= base_url("invoices/{$invoice['id']}/print-thermal") ?>" target="_blank" class="btn btn-info btn-sm shadow-sm" style="background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%); border: none;">
            <i class="fas fa-receipt mr-1"></i> Cetak Struk POS (58/80mm)
        </a>
        <a href="<?= base_url("invoices/{$invoice['id']}/print") ?>" target="_blank" class="btn btn-outline-primary btn-sm shadow-sm">
            <i class="fas fa-print mr-1"></i> Cetak Faktur (A4)
        </a>
        <?php if ($invoice['status'] !== 'paid'): ?>
            <a href="<?= base_url("receipts/create?invoice_id={$invoice['id']}") ?>" class="btn btn-success btn-sm shadow-sm">
                <i class="fas fa-hand-holding-usd mr-1"></i> Terima Pembayaran
            </a>
        <?php endif; ?>
        <a href="<?= base_url("returns/create?invoice_id={$invoice['id']}") ?>" class="btn btn-outline-danger btn-sm">
            <i class="fas fa-undo mr-1"></i> Ajukan Retur
        </a>
    </div>
</div>

<div class="row">
    <!-- Kolom Kiri: Rincian Faktur & Pelanggan -->
    <div class="col-lg-8 col-md-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h4 class="text-dark"><i class="fas fa-info-circle text-primary mr-2"></i> Rincian Transaksi Faktur</h4>
                <div>
                    <?php if ($invoice['status'] === 'paid'): ?>
                        <span class="badge-status badge-paid"><i class="fas fa-check-circle"></i> LUNAS</span>
                    <?php elseif ($invoice['status'] === 'partial'): ?>
                        <span class="badge-status badge-partial"><i class="fas fa-adjust"></i> SEBAGIAN</span>
                    <?php else: ?>
                        <span class="badge-status badge-unpaid"><i class="fas fa-hourglass-half"></i> BELUM LUNAS</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="text-muted text-uppercase small font-weight-bold mb-2">Ditagihkan Kepada:</h6>
                        <h5 class="text-dark font-weight-bold mb-1"><?= esc($invoice['customer_name'] ?? 'Pelanggan Umum') ?></h5>
                        <p class="text-muted small mb-1"><i class="fas fa-map-marker-alt mr-1"></i> <?= esc($invoice['customer_address'] ?? 'Alamat tidak diatur') ?></p>
                        <p class="text-muted small mb-0"><i class="fas fa-phone mr-1"></i> <?= esc($invoice['customer_phone'] ?? '-') ?> &bull; <?= esc($invoice['customer_email'] ?? '-') ?></p>
                    </div>
                    <div class="col-sm-6 text-sm-right mt-3 mt-sm-0">
                        <h6 class="text-muted text-uppercase small font-weight-bold mb-2">Informasi Faktur:</h6>
                        <div class="small mb-1"><strong>Metode:</strong> <span class="badge badge-<?= $invoice['payment_type'] === 'tunai' ? 'success' : 'info' ?> text-uppercase"><?= esc($invoice['payment_type']) ?></span></div>
                        <div class="small mb-1"><strong>Status Pajak:</strong> <?= $invoice['is_pkp'] ? "PKP (PPN {$invoice['tax_rate']}%)" : 'Non-PKP (Bebas PPN)' ?></div>
                        <?php if ($invoice['payment_type'] === 'kredit'): ?>
                            <div class="small text-danger font-weight-bold"><strong>Jatuh Tempo:</strong> <?= format_tanggal_indo($invoice['due_date']) ?></div>
                        <?php endif; ?>
                        <div class="mt-2 pt-2 border-top">
                            <form action="<?= base_url("invoices/{$invoice['id']}/update-status") ?>" method="POST" class="d-inline-flex align-items-center justify-content-sm-end" style="gap: 6px;">
                                <?= csrf_field() ?>
                                <label class="small font-weight-bold text-muted mb-0 mr-1"><i class="fas fa-tshirt text-primary mr-1"></i> Status Cucian:</label>
                                <select name="process_status" class="form-control form-control-sm d-inline-block font-weight-bold" style="width: auto; height: 32px; border-radius: 8px; font-size: 11.5px; border-color: #38bdf8;" onchange="this.form.submit()">
                                    <?php $ps = $invoice['process_status'] ?? 'antrean'; ?>
                                    <option value="antrean" <?= $ps === 'antrean' ? 'selected' : '' ?>>🕒 Antrean / Masuk</option>
                                    <option value="cuci" <?= $ps === 'cuci' ? 'selected' : '' ?>>🫧 Sedang Dicuci</option>
                                    <option value="setrika" <?= $ps === 'setrika' ? 'selected' : '' ?>>👔 Sedang Disetrika</option>
                                    <option value="siap_ambil" <?= $ps === 'siap_ambil' ? 'selected' : '' ?>>✨ Siap Diambil</option>
                                    <option value="diambil" <?= $ps === 'diambil' ? 'selected' : '' ?>>📦 Sudah Diambil</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tabel Item Layanan -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th>Deskripsi Layanan</th>
                                <th class="text-center" style="width: 90px;">Qty</th>
                                <th class="text-right" style="width: 140px;">Harga Satuan</th>
                                <th class="text-right" style="width: 120px;">Diskon</th>
                                <th class="text-right" style="width: 160px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lines as $line): ?>
                                <tr>
                                    <td>
                                        <strong class="text-dark"><?= esc($line['item_name']) ?></strong>
                                    </td>
                                    <td class="text-center"><?= (float)$line['quantity'] ?></td>
                                    <td class="text-right"><?= format_rupiah($line['unit_price']) ?></td>
                                    <td class="text-right text-muted">
                                        <?= (float)$line['discount_amount'] > 0 ? '- ' . format_rupiah($line['discount_amount']) : '-' ?>
                                    </td>
                                    <td class="text-right font-weight-bold text-dark"><?= format_rupiah($line['line_total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Rincian Total -->
                <div class="row justify-content-end mt-4">
                    <div class="col-md-6 col-lg-5">
                        <div class="p-3 bg-light rounded">
                            <div class="d-flex justify-content-between py-1 small">
                                <span class="text-muted">Subtotal:</span>
                                <strong><?= format_rupiah($invoice['subtotal']) ?></strong>
                            </div>
                            <?php if ((float)$invoice['discount_total'] > 0): ?>
                                <div class="d-flex justify-content-between py-1 small">
                                    <span class="text-muted">Diskon:</span>
                                    <strong class="text-danger">- <?= format_rupiah($invoice['discount_total']) ?></strong>
                                </div>
                            <?php endif; ?>
                            <?php if ($invoice['is_pkp']): ?>
                                <div class="d-flex justify-content-between py-1 small">
                                    <span class="text-muted">PPN (<?= (float)$invoice['tax_rate'] ?>%):</span>
                                    <strong><?= format_rupiah($invoice['tax_amount']) ?></strong>
                                </div>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between py-2 border-top mt-2">
                                <span class="font-weight-bold text-dark">Grand Total:</span>
                                <h5 class="text-primary font-weight-bold mb-0"><?= format_rupiah($invoice['grand_total']) ?></h5>
                            </div>
                            <div class="d-flex justify-content-between py-1 small border-top">
                                <span class="text-muted">Sudah Dibayar:</span>
                                <strong class="text-success"><?= format_rupiah($invoice['paid_amount']) ?></strong>
                            </div>
                            <?php 
                                $remaining = max(0, (float)$invoice['grand_total'] - (float)$invoice['paid_amount']);
                            ?>
                            <div class="d-flex justify-content-between py-1 small">
                                <span class="text-muted">Sisa Piutang:</span>
                                <strong class="<?= $remaining > 0 ? 'text-danger' : 'text-muted' ?>"><?= format_rupiah($remaining) ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($invoice['notes'])): ?>
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-muted text-uppercase small font-weight-bold mb-1">Catatan Tambahan:</h6>
                        <p class="small text-muted mb-0"><?= nl2br(esc($invoice['notes'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Riwayat Pembayaran (Receipts) -->
        <?php if (!empty($receipts)): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="text-dark font-weight-bold mb-0"><i class="fas fa-receipt text-success mr-2"></i> Riwayat Penerimaan Kas</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>No. Bukti Kas</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Metode</th>
                                    <th class="text-right">Jumlah Dibayar</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($receipts as $rcp): ?>
                                    <tr>
                                        <td><strong><?= esc($rcp['receipt_number']) ?></strong></td>
                                        <td><?= format_tanggal_indo($rcp['payment_date']) ?></td>
                                        <td><span class="badge badge-light text-uppercase"><?= esc($rcp['payment_method']) ?></span></td>
                                        <td class="text-right font-weight-bold text-success"><?= format_rupiah($rcp['amount']) ?></td>
                                        <td class="small text-muted"><?= esc($rcp['notes'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Kolom Kanan: Audit Trail Jurnal Akuntansi AIS -->
    <div class="col-lg-4 col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white rounded-top">
                <h5 class="mb-0 text-white font-weight-bold small text-uppercase">
                    <i class="fas fa-book mr-2 text-warning"></i> Audit Jurnal Akuntansi (AIS)
                </h5>
            </div>
            <div class="card-body p-3">
                <p class="small text-muted mb-3">
                    Transparansi entri debit/kredit yang dibentuk secara otomatis oleh sistem saat transaksi terjadi:
                </p>

                <?php if (empty($journals)): ?>
                    <div class="text-center py-4 text-muted small">
                        Tidak ada catatan jurnal.
                    </div>
                <?php else: ?>
                    <?php foreach ($journals as $j): ?>
                        <div class="p-3 bg-light rounded mb-3 border">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-primary small"><?= esc($j['journal_number']) ?></strong>
                                <span class="badge badge-light small"><?= $j['journal_date'] ?></span>
                            </div>
                            <div class="small text-dark font-weight-600 mb-2"><?= esc($j['description']) ?></div>

                            <table class="table table-sm table-borderless mb-0" style="font-size: 0.78rem;">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>Akun</th>
                                        <th class="text-right">Debit</th>
                                        <th class="text-right">Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $totD = 0; $totC = 0;
                                    foreach ($journalDetails as $det): 
                                        if ($det['journal_id'] == $j['id']):
                                            $totD += (float)$det['debit'];
                                            $totC += (float)$det['credit'];
                                    ?>
                                        <tr>
                                            <td>
                                                <?php if ((float)$det['credit'] > 0): ?>
                                                    <span class="pl-2">&bull; (Cr) <?= esc($det['account_name']) ?></span>
                                                <?php else: ?>
                                                    &bull; (Dr) <?= esc($det['account_name']) ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-right font-weight-bold">
                                                <?= (float)$det['debit'] > 0 ? format_rupiah($det['debit']) : '-' ?>
                                            </td>
                                            <td class="text-right font-weight-bold">
                                                <?= (float)$det['credit'] > 0 ? format_rupiah($det['credit']) : '-' ?>
                                            </td>
                                        </tr>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                    <tr class="border-top font-weight-bold">
                                        <td>TOTAL SEIMBANG:</td>
                                        <td class="text-right text-success"><?= format_rupiah($totD) ?></td>
                                        <td class="text-right text-success"><?= format_rupiah($totC) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
