<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.5rem;">Catat Penerimaan Kas (Pelunasan Piutang)</h1>
        <p class="text-muted mb-0 small">Pelunasan piutang pelanggan langsung mengurangi saldo piutang dan menjurnal kas masuk.</p>
    </div>
    <div>
        <a href="<?= base_url('receipts') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8 col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h4 class="text-dark"><i class="fas fa-hand-holding-usd text-success mr-2"></i> Formulir Penerimaan Kas</h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('receipts/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row">
                        <!-- No. Bukti Kas Otomatis -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Nomor Bukti Kas (Otomatis)</label>
                            <input type="text" class="form-control bg-light font-weight-bold" name="receipt_number" value="<?= esc($autoNumber) ?>" readonly>
                        </div>

                        <!-- Tanggal Pembayaran -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Tanggal Pembayaran <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="payment_date" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <!-- Pilih Faktur Penjualan -->
                        <div class="col-12 mb-3">
                            <label class="form-label font-weight-bold">Pilih Faktur Penjualan yang Dilunasi <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="invoice_id" id="invoice_id" required>
                                <option value="">-- Pilih Faktur (Hanya yang belum lunas) --</option>
                                <?php foreach ($unpaidInvoices as $inv): 
                                    $rem = (float)$inv['remaining_balance'];
                                    $isSelected = ($selectedInvoiceId == $inv['id']) ? 'selected' : '';
                                ?>
                                    <option value="<?= $inv['id'] ?>" data-remaining="<?= $rem ?>" <?= $isSelected ?>>
                                        <?= esc($inv['invoice_number']) ?> &bull; <?= esc($inv['customer_name']) ?> (Sisa Piutang: <?= format_rupiah($rem) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Hanya menampilkan faktur dengan status Belum Lunas atau Pembayaran Sebagian.</small>
                        </div>

                        <!-- Info Sisa Piutang Box -->
                        <div class="col-12 mb-3" id="remaining-box" style="display: none;">
                            <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center border">
                                <div>
                                    <span class="text-muted small d-block">Sisa Piutang Faktur Saat Ini:</span>
                                    <h4 class="text-danger font-weight-bold mb-0" id="remaining-text">Rp 0</h4>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success font-weight-bold" id="btn-pay-full">
                                    <i class="fas fa-check-double mr-1"></i> Lunasi Penuh
                                </button>
                            </div>
                        </div>

                        <!-- Jumlah Uang yang Diterima -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Jumlah Pembayaran Diterima (Rp) <span class="text-danger">*</span></label>
                            <input type="number" step="1000" min="1" class="form-control font-weight-bold text-dark" name="amount" id="amount" placeholder="0" required>
                            <small class="text-muted">Bisa diisi nominal penuh atau sebagian (cicilan).</small>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Metode Transaksi <span class="text-danger">*</span></label>
                            <select class="form-control" name="payment_method" required>
                                <option value="cash">Tunai (Cash)</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="other">Lainnya (QRIS / Giro)</option>
                            </select>
                        </div>

                        <!-- Masuk ke Akun Kas/Bank -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Setor ke Akun Akuntansi <span class="text-danger">*</span></label>
                            <select class="form-control" name="account_code" required>
                                <?php foreach ($cashAccounts as $acc): ?>
                                    <option value="<?= esc($acc['account_code']) ?>">
                                        <?= esc($acc['account_code']) ?> - <?= esc($acc['account_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Catatan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Keterangan / No. Referensi Transfer</label>
                            <input type="text" class="form-control" name="notes" placeholder="Misal: Bukti transfer BCA ref#8921">
                        </div>
                    </div>

                    <!-- AIS Automasi Info -->
                    <div class="p-3 bg-light rounded mt-2 mb-4 border" style="font-size: 0.82rem;">
                        <strong class="text-primary"><i class="fas fa-robot mr-1"></i> Background AIS Logic:</strong>
                        <span class="text-muted">Sistem akan secara otomatis men-generate jurnal:</span>
                        <div class="mt-1 font-weight-bold text-dark">
                            &bull; (Dr) Akun Kas/Bank Dipilih [Jumlah Bayar]<br>
                            &bull; (Cr) 1200 Piutang Usaha [Jumlah Bayar]
                        </div>
                    </div>

                    <div class="d-flex justify-content-end" style="gap: 10px;">
                        <a href="<?= base_url('receipts') ?>" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-success px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Pembayaran Kas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        function checkSelectedInvoice() {
            const selected = $('#invoice_id').find('option:selected');
            const remaining = parseFloat(selected.data('remaining')) || 0;
            if (remaining > 0) {
                $('#remaining-box').slideDown();
                $('#remaining-text').text('Rp ' + remaining.toLocaleString('id-ID'));
                if (!$('#amount').val() || $('#amount').val() == 0) {
                    $('#amount').val(remaining);
                }
            } else {
                $('#remaining-box').slideUp();
            }
        }

        $('#invoice_id').on('change', function() {
            const remaining = parseFloat($(this).find('option:selected').data('remaining')) || 0;
            $('#amount').val(remaining);
            checkSelectedInvoice();
        });

        $('#btn-pay-full').on('click', function() {
            const remaining = parseFloat($('#invoice_id').find('option:selected').data('remaining')) || 0;
            $('#amount').val(remaining);
        });

        checkSelectedInvoice();
    });
</script>
<?= $this->endSection() ?>
