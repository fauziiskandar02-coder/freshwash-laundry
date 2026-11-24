<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.55rem; font-family: 'Nunito', sans-serif;">
            <i class="fas fa-soap text-primary mr-2"></i> Nota Pesanan Laundry &amp; Faktur Jual
        </h1>
        <p class="text-muted mb-0 small">Pencatatan transaksi cuci kiloan dan satuan langsung tanpa ribet (efisien untuk kasir laundry).</p>
    </div>
    <div>
        <a href="<?= base_url('invoices/create') ?>" class="btn btn-laundry-primary">
            <i class="fas fa-plus-circle mr-1"></i> + Buat Nota Cucian Baru
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-receipt text-primary mr-2"></i> Riwayat Nota Pesanan Cucian</h4>
        <div class="card-header-action">
            <span class="badge badge-light border text-primary font-weight-bold px-3 py-2" style="border-radius: 12px;"><?= count($invoices) ?> TOTAL NOTA</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No. Faktur</th>
                        <th>Tanggal & Tempo</th>
                        <th>Pelanggan</th>
                        <th>Metode Bayar</th>
                        <th>Pajak</th>
                        <th class="text-right">Total Tagihan</th>
                        <th class="text-right">Sisa Piutang</th>
                        <th>Status Bayar</th>
                        <th>Status Cucian</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($invoices)): ?>
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fas fa-file-invoice fa-3x mb-3 text-black-50 d-block"></i>
                                Belum ada faktur penjualan yang dibuat. Klik tombol <strong>"Buat Faktur Baru"</strong> untuk memulai.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($invoices as $inv): 
                            $remaining = max(0, (float)$inv['grand_total'] - (float)$inv['paid_amount']);
                        ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url("invoices/{$inv['id']}") ?>" class="font-weight-bold text-primary">
                                        <?= esc($inv['invoice_number']) ?>
                                    </a>
                                </td>
                                <td>
                                    <div><?= format_tanggal_indo($inv['invoice_date']) ?></div>
                                    <?php if ($inv['payment_type'] === 'kredit'): ?>
                                        <small class="text-danger font-weight-600"><i class="fas fa-clock mr-1"></i> Jatuh Tempo: <?= format_tanggal_indo($inv['due_date']) ?></small>
                                    <?php else: ?>
                                        <small class="text-success"><i class="fas fa-check mr-1"></i> Bayar Tunai</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="font-weight-600 text-dark"><?= esc($inv['customer_name'] ?? 'Pelanggan Umum') ?></span>
                                    <div class="small text-muted"><?= esc($inv['customer_phone'] ?? '-') ?></div>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $inv['payment_type'] === 'tunai' ? 'success' : 'info' ?> text-uppercase px-2">
                                        <?= esc($inv['payment_type']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($inv['is_pkp']): ?>
                                        <span class="badge badge-light text-primary font-weight-bold">
                                            PKP (<?= (float)$inv['tax_rate'] ?>%)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-light text-muted">Non-PKP</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right font-weight-bold text-dark">
                                    <?= format_rupiah($inv['grand_total']) ?>
                                </td>
                                <td class="text-right font-weight-bold <?= $remaining > 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= format_rupiah($remaining) ?>
                                </td>
                                <td>
                                    <?php if ($inv['status'] === 'paid'): ?>
                                        <span class="badge-status badge-paid"><i class="fas fa-check-circle"></i> LUNAS</span>
                                    <?php elseif ($inv['status'] === 'partial'): ?>
                                        <span class="badge-status badge-partial"><i class="fas fa-adjust"></i> SEBAGIAN</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-unpaid"><i class="fas fa-hourglass-half"></i> BELUM LUNAS</span>
                                    <?php endif; ?>
                                </td>
                                <td>
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
                                    <span class="badge badge-<?= $st[1] ?> font-weight-bold px-2 py-1" style="border-radius: 8px;">
                                        <?= $st[0] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url("invoices/{$inv['id']}") ?>" class="btn btn-outline-primary" title="Lihat Detail & Jurnal">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url("invoices/{$inv['id']}/print-thermal") ?>" target="_blank" class="btn btn-outline-info" title="Cetak Struk POS (58/80mm)">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                        <a href="<?= base_url("invoices/{$inv['id']}/print") ?>" target="_blank" class="btn btn-outline-secondary" title="Cetak Faktur (A4)">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <?php if ($inv['status'] !== 'paid'): ?>
                                            <a href="<?= base_url("receipts/create?invoice_id={$inv['id']}") ?>" class="btn btn-outline-success" title="Terima Pembayaran">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= base_url("invoices/delete/{$inv['id']}") ?>" onclick="return confirm('Hapus faktur <?= esc($inv['invoice_number']) ?>? Seluruh jurnal akuntansinya akan ikut dihapus.')" class="btn btn-outline-danger" title="Hapus/Batalkan Faktur">
                                            <i class="fas fa-trash"></i>
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
<?= $this->endSection() ?>
