<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.5rem;">Proses Retur Penjualan Jasa</h1>
        <p class="text-muted mb-0 small">Kompensasi atau pengembalian jasa yang otomatis memotong piutang atau kas.</p>
    </div>
    <div>
        <a href="<?= base_url('returns') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<form action="<?= base_url('returns/store') ?>" method="POST" id="form-return">
    <?= csrf_field() ?>

    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h4 class="text-dark"><i class="fas fa-undo text-danger mr-2"></i> Rujukan Faktur & Kebijakan Refund</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- No Retur Otomatis -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Nomor Dokumen Retur</label>
                            <input type="text" class="form-control bg-light font-weight-bold" name="return_number" value="<?= esc($autoNumber) ?>" readonly>
                        </div>

                        <!-- Tanggal Retur -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Tanggal Retur <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="return_date" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <!-- Pilih Faktur Penjualan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Pilih Faktur Penjualan Asal <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="invoice_id" id="invoice_id" required>
                                <option value="">-- Pilih Faktur Rujukan --</option>
                                <?php foreach ($invoices as $inv): 
                                    $isSelected = ($selectedInvoiceId == $inv['id']) ? 'selected' : '';
                                ?>
                                    <option value="<?= $inv['id'] ?>" <?= $isSelected ?>>
                                        <?= esc($inv['invoice_number']) ?> &bull; <?= esc($inv['customer_name']) ?> (<?= format_rupiah($inv['grand_total']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Metode Pengembalian -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Bentuk Kompensasi Refund <span class="text-danger">*</span></label>
                            <select class="form-control" name="refund_method" required>
                                <option value="potong_piutang">Potong Tagihan Piutang Faktur (Kredit)</option>
                                <option value="cash">Kembalikan Uang Kas Langsung (Tunai)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Item yang Diretur -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h4 class="text-dark"><i class="fas fa-list text-primary mr-2"></i> Pilih Item Layanan yang Diretur</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Deskripsi Layanan</th>
                                    <th style="width: 110px;" class="text-center">Qty Retur</th>
                                    <th style="width: 150px;" class="text-right">Harga Satuan (Rp)</th>
                                    <th style="width: 160px;" class="text-right">Total Retur (Rp)</th>
                                    <th style="width: 50px;" class="text-center">#</th>
                                </tr>
                            </thead>
                            <tbody id="return-lines-tbody">
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted" id="placeholder-text">
                                        Silakan pilih <strong>Faktur Penjualan Asal</strong> di atas untuk memuat item layanan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Catatan Retur -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <label class="form-label font-weight-bold">Alasan / Catatan Pengembalian Jasa</label>
                    <textarea class="form-control" name="notes" rows="2" placeholder="Jelaskan alasan retur atau kompensasi layanan..."></textarea>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Summary Retur & AIS Info -->
        <div class="col-lg-4 col-md-12">
            <div class="invoice-summary-card mb-4" style="background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%);">
                <h5 class="font-weight-bold mb-3 d-flex align-items-center">
                    <i class="fas fa-calculator mr-2 text-warning"></i> Total Retur Penjualan
                </h5>

                <div class="invoice-summary-row">
                    <span class="opacity-75">Subtotal Retur:</span>
                    <strong id="ret-subtotal">Rp 0</strong>
                </div>

                <div class="invoice-summary-row">
                    <span class="opacity-75">PPN Retur Diselaraskan:</span>
                    <strong class="text-warning" id="ret-tax">Rp 0</strong>
                </div>

                <div class="invoice-summary-row text-white pt-3">
                    <span>NILAI PENGURANGAN:</span>
                    <span class="text-warning font-weight-bold" id="ret-grand-total" style="font-size: 1.4rem;">Rp 0</span>
                </div>

                <div class="mt-4 pt-2">
                    <button type="submit" class="btn btn-warning btn-block font-weight-bold text-dark py-3 shadow" style="border-radius: 12px;">
                        <i class="fas fa-check mr-2"></i> Eksekusi Retur & Jurnal
                    </button>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <h6 class="font-weight-bold text-dark small text-uppercase mb-2">
                        <i class="fas fa-robot text-danger mr-1"></i> Background AIS Jurnal Retur
                    </h6>
                    <p class="small text-muted mb-2">Logika penjurnalan otomatis yang akan dieksekusi:</p>
                    <div class="bg-light p-2 rounded small" style="font-size: 0.78rem;">
                        <div>&bull; <strong>(Dr) 4200 Retur Penjualan Jasa</strong> [Subtotal]</div>
                        <div id="ret-journal-tax">&bull; <strong>(Dr) 2100 PPN Keluaran</strong> [PPN Dibatalkan]</div>
                        <div>&bull; <strong>(Cr) 1200 Piutang Usaha / 1100 Kas</strong> [Total Retur]</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let activeTaxRate = 0;
    let activeIsPkp = 0;

    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function calculateReturnTotals() {
        let subtotal = 0;
        $('#return-lines-tbody tr.ret-line-row').each(function() {
            const qty = parseFloat($(this).find('.ret-qty').val()) || 0;
            const price = parseFloat($(this).find('.ret-price').val()) || 0;
            const total = qty * price;
            $(this).find('.ret-total-text').text(formatRupiah(total));
            subtotal += total;
        });

        const taxAmount = activeIsPkp ? (subtotal * activeTaxRate) / 100 : 0;
        const grandTotal = subtotal + taxAmount;

        $('#ret-subtotal').text(formatRupiah(subtotal));
        $('#ret-tax').text(formatRupiah(taxAmount));
        $('#ret-grand-total').text(formatRupiah(grandTotal));
    }

    $(document).ready(function() {
        $('#invoice_id').on('change', function() {
            const invId = $(this).val();
            if (!invId) {
                $('#return-lines-tbody').html('<tr><td colspan="5" class="text-center py-4 text-muted">Pilih faktur rujukan di atas.</td></tr>');
                activeTaxRate = 0;
                activeIsPkp = 0;
                calculateReturnTotals();
                return;
            }

            $.getJSON('<?= base_url('returns/ajax-lines') ?>/' + invId, function(data) {
                if (data.status === 'success') {
                    activeIsPkp = parseInt(data.invoice.is_pkp);
                    activeTaxRate = parseFloat(data.invoice.tax_rate) || 0;

                    let rows = '';
                    $.each(data.lines, function(index, line) {
                        rows += `
                            <tr class="ret-line-row">
                                <td>
                                    <strong class="text-dark">${line.item_name}</strong>
                                    <input type="hidden" name="item_name[]" value="${line.item_name}">
                                    <input type="hidden" name="item_id[]" value="${line.item_id || ''}">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0.01" max="${line.quantity}" class="form-control form-control-sm text-center ret-qty" name="quantity[]" value="${line.quantity}">
                                    <small class="text-muted d-block text-center">Maks: ${line.quantity}</small>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm text-right ret-price" name="unit_price[]" value="${line.unit_price}" readonly>
                                </td>
                                <td class="text-right font-weight-bold ret-total-text">
                                    ${formatRupiah(line.line_total)}
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-del-ret-line" title="Abaikan item ini">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    $('#return-lines-tbody').html(rows);
                    calculateReturnTotals();
                }
            });
        });

        $(document).on('input change', '.ret-qty', function() {
            calculateReturnTotals();
        });

        $(document).on('click', '.btn-del-ret-line', function() {
            $(this).closest('tr.ret-line-row').remove();
            calculateReturnTotals();
        });

        if ($('#invoice_id').val()) {
            $('#invoice_id').trigger('change');
        }
    });
</script>
<?= $this->endSection() ?>
