<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-3 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.5rem; font-family: 'Nunito', sans-serif;">
            <i class="fas fa-soap text-primary mr-2"></i> Buat Nota Cucian Baru (Faktur Penjualan)
        </h1>
        <p class="text-muted mb-0 small">Input transaksi cucian pelanggan. Subtotal kiloan/satuan dihitung otomatis dan jurnal akuntansi langsung terbit di background.</p>
    </div>
    <div>
        <a href="<?= base_url('invoices') ?>" class="btn btn-outline-secondary btn-sm shadow-sm" style="border-radius: 10px;">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Nota
        </a>
    </div>
</div>

<form action="<?= base_url('invoices/store') ?>" method="POST" id="form-invoice">
    <?= csrf_field() ?>

    <div class="row">
        <!-- Form Utama Faktur -->
        <div class="col-lg-8 col-md-12">
            <!-- Informasi Header Faktur -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom-0 pb-0">
                    <h4 class="text-dark"><i class="fas fa-receipt text-primary mr-2"></i> Informasi Nota &amp; Pelanggan</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Nomor Faktur -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Nomor Faktur (Otomatis)</label>
                            <input type="text" class="form-control bg-light font-weight-bold" name="invoice_number" value="<?= esc($autoNumber) ?>" readonly>
                            <small class="text-muted">Format resmi: INV-YYYYMM-XXXX</small>
                        </div>

                        <!-- Pilih Pelanggan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Pelanggan / Klien <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="customer_id" id="customer_id" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                <?php foreach ($customers as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= old('customer_id') == $c['id'] ? 'selected' : '' ?>>
                                        <?= esc($c['code']) ?> &bull; <?= esc($c['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Tanggal Faktur -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Tanggal Faktur <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="invoice_date" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <!-- Metode Pembayaran: Tunai vs Kredit -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center mt-2" style="gap: 20px;">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="pay_tunai" name="payment_type" class="custom-control-input" value="tunai" checked>
                                    <label class="custom-control-label font-weight-bold text-success" for="pay_tunai">
                                        <i class="fas fa-money-bill-wave mr-1"></i> Tunai (Langsung Lunas)
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="pay_kredit" name="payment_type" class="custom-control-input" value="kredit">
                                    <label class="custom-control-label font-weight-bold text-primary" for="pay_kredit">
                                        <i class="fas fa-handshake mr-1"></i> Kredit (Piutang)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Jatuh Tempo (Hanya jika kredit) -->
                        <div class="col-md-6 mb-3" id="due-date-container" style="display: none;">
                            <label class="form-label font-weight-bold text-danger">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                            <input type="date" class="form-control border-danger" name="due_date" id="due_date" value="<?= date('Y-m-d', strtotime('+30 days')) ?>">
                            <small class="text-muted">Batas waktu pelunasan piutang klien.</small>
                        </div>

                        <!-- Status Pajak Hybrid (PKP / Non-PKP) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Status Pajak PPN (Hybrid Override)</label>
                            <div class="p-2 border rounded bg-light d-flex align-items-center justify-content-between">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_pkp" name="is_pkp" value="1" <?= $defaultPkp ? 'checked' : '' ?>>
                                    <label class="custom-control-label font-weight-bold" for="is_pkp">Pungut PPN (Faktur PKP)</label>
                                </div>
                                <div id="tax-rate-container" class="d-flex align-items-center" style="gap: 6px;">
                                    <input type="number" step="0.5" min="0" max="100" class="form-control form-control-sm text-right font-weight-bold" style="width: 75px;" id="tax_rate" name="tax_rate" value="<?= esc($defaultTaxRate) ?>">
                                    <span class="small font-weight-bold">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rincian Item Layanan Jasa (Dynamic Line Items) -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="text-dark"><i class="fas fa-tshirt text-primary mr-2"></i> Rincian Paket &amp; Layanan Cuci</h4>
                    <button type="button" class="btn btn-sm btn-primary shadow-sm" style="border-radius: 10px;" id="btn-add-line">
                        <i class="fas fa-plus mr-1"></i> Tambah Layanan
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0" id="table-invoice-lines">
                            <thead>
                                <tr>
                                    <th style="min-width: 250px;">Paket Cuci & Keterangan</th>
                                    <th style="width: 110px;">Qty (Kg/Pcs)</th>
                                    <th style="width: 150px;">Tarif Satuan (Rp)</th>
                                    <th style="width: 150px;">Diskon</th>
                                    <th style="width: 160px;" class="text-right">Subtotal (Rp)</th>
                                    <th style="width: 50px;" class="text-center">#</th>
                                </tr>
                            </thead>
                            <tbody id="invoice-lines-tbody">
                                <!-- Baris Awal -->
                                <tr class="line-row">
                                    <td>
                                        <select class="form-control item-select" name="item_id[]">
                                            <option value="">-- Pilih Paket Cuci --</option>
                                            <?php foreach ($items as $item): ?>
                                                <option value="<?= $item['id'] ?>" data-name="<?= esc($item['name']) ?>" data-price="<?= (float)$item['sell_price'] ?>">
                                                    <?= esc($item['code']) ?> &bull; <?= esc($item['name']) ?> (<?= format_rupiah($item['sell_price']) ?>/<?= esc($item['unit']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="text" class="form-control form-control-sm mt-1 item-name" name="item_name[]" placeholder="Keterangan cucian (misal: wangi lavender, baju putih pisah)..." required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control text-right item-qty" name="quantity[]" value="1.00" required>
                                    </td>
                                    <td>
                                        <input type="number" step="1000" min="0" class="form-control text-right item-price" name="unit_price[]" value="0" required>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control item-disc-type" name="discount_type[]" style="max-width: 80px;">
                                                <option value="none">0</option>
                                                <option value="percentage">%</option>
                                                <option value="amount">Rp</option>
                                            </select>
                                            <input type="number" step="any" min="0" class="form-control text-right item-disc-val" name="discount_value[]" value="0">
                                        </div>
                                        <input type="hidden" class="item-disc-amt" name="discount_amount[]" value="0">
                                    </td>
                                    <td class="text-right">
                                        <span class="font-weight-bold item-line-total-text">Rp 0</span>
                                        <input type="hidden" class="item-line-total" name="line_total[]" value="0">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-line" title="Hapus Baris">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light py-2">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i> Pilih layanan dari katalog atau ketik langsung deskripsi layanan kustom.
                    </small>
                </div>
            </div>

            <!-- Catatan Faktur -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <label class="form-label font-weight-bold">Catatan Faktur / Syarat Pembayaran</label>
                    <textarea class="form-control" name="notes" rows="2" placeholder="Contoh: Terima kasih atas kepercayaan Anda. Pembayaran transfer ke Rekening BCA 123-456-7890 an UMKM"></textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar Kalkulasi & Floating Summary Widget (Antigravity Look) -->
        <div class="col-lg-4 col-md-12">
            <div class="invoice-summary-card mb-4">
                <h5 class="font-weight-bold mb-3 d-flex align-items-center">
                    <i class="fas fa-calculator mr-2 text-warning"></i> Ringkasan Tagihan
                </h5>

                <div class="invoice-summary-row">
                    <span class="opacity-75">Subtotal Layanan:</span>
                    <strong id="summary-subtotal">Rp 0</strong>
                </div>

                <div class="invoice-summary-row">
                    <span class="opacity-75">Total Diskon:</span>
                    <strong class="text-warning" id="summary-discount">- Rp 0</strong>
                </div>

                <div class="invoice-summary-row">
                    <span class="opacity-75">Dasar Pengenaan Pajak (DPP):</span>
                    <strong id="summary-taxable">Rp 0</strong>
                </div>

                <div class="invoice-summary-row" id="tax-summary-row">
                    <span class="opacity-75">PPN Terhitung (<span id="summary-tax-rate-label">12%</span>):</span>
                    <strong class="text-info" id="summary-tax-amount">Rp 0</strong>
                </div>

                <div class="invoice-summary-row text-white pt-3">
                    <span>GRAND TOTAL:</span>
                    <span class="text-warning font-weight-bold" id="summary-grand-total" style="font-size: 1.4rem;">Rp 0</span>
                </div>

                <!-- Hidden inputs for calculated aggregates -->
                <input type="hidden" name="subtotal" id="input-subtotal" value="0">
                <input type="hidden" name="discount_total" id="input-discount-total" value="0">
                <input type="hidden" name="tax_amount" id="input-tax-amount" value="0">
                <input type="hidden" name="grand_total" id="input-grand-total" value="0">

                <div class="mt-4 pt-2">
                    <button type="submit" class="btn btn-warning btn-block font-weight-bold text-dark py-3 shadow" style="border-radius: 12px; font-size: 1rem;">
                        <i class="fas fa-check-circle mr-2"></i> Terbitkan & Jurnal Faktur
                    </button>
                </div>
            </div>

            <!-- Info AIS Penjurnalan Otomatis -->
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header bg-transparent pb-0">
                    <h5 class="text-dark font-weight-bold small text-uppercase">
                        <i class="fas fa-robot text-primary mr-1"></i> Automasi Jurnal (AIS Logic)
                    </h5>
                </div>
                <div class="card-body pt-2 pb-3">
                    <p class="small text-muted mb-2">Saat Anda mengklik simpan, sistem secara otomatis mengeksekusi jurnal berpasangan di background:</p>
                    <div class="bg-light p-2 rounded small" style="font-size: 0.78rem;">
                        <div class="font-weight-bold text-dark mb-1">Struktur Akun yang Terpengaruh:</div>
                        <div>&bull; <strong>(Dr) 1200 Piutang Usaha</strong> [Grand Total]</div>
                        <div>&bull; <strong>(Cr) 4100 Pendapatan Jasa</strong> [DPP]</div>
                        <div id="info-tax-journal">&bull; <strong>(Cr) 2100 PPN Keluaran</strong> [Pajak PPN]</div>
                        <div class="mt-2 text-success font-italic" id="info-cash-journal">
                            *Jika Tunai: Otomatis pelunasan (Dr) 1100 Kas | (Cr) 1200 Piutang Usaha.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Template options untuk dynamic item rows -->
<template id="item-options-template">
    <?php foreach ($items as $item): ?>
        <option value="<?= $item['id'] ?>" data-name="<?= esc($item['name']) ?>" data-price="<?= (float)$item['sell_price'] ?>">
            <?= esc($item['code']) ?> &bull; <?= esc($item['name']) ?> (<?= format_rupiah($item['sell_price']) ?>)
        </option>
    <?php endforeach; ?>
</template>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/invoice-form.js') ?>"></script>
<?= $this->endSection() ?>
