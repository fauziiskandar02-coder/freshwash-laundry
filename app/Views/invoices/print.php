<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FAKTUR PENJUALAN - <?= esc($invoice['invoice_number']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            background: #fff;
            padding: 30px;
        }
        .invoice-box {
            max-width: 850px;
            margin: auto;
            border: 1px solid #eee;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        .header-title {
            font-size: 28px;
            font-weight: 800;
            color: #4f46e5;
            letter-spacing: -0.5px;
        }
        .table-items th {
            background-color: #f8fafc !important;
            border-top: 1px solid #dee2e6;
            border-bottom: 2px solid #cbd5e1;
            font-size: 13px;
            text-transform: uppercase;
        }
        .table-items td {
            font-size: 14px;
        }
        @media print {
            body { padding: 0; }
            .invoice-box { border: none; box-shadow: none; padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-primary px-4 font-weight-bold">
            <i class="fas fa-print mr-2"></i> Cetak Dokumen (Print)
        </button>
        <button onclick="window.close()" class="btn btn-secondary px-3 ml-2">
            Tutup
        </button>
    </div>

    <div class="invoice-box">
        <!-- Kop Surat UMKM -->
        <div class="row align-items-center pb-4 mb-4 border-bottom">
            <div class="col-7">
                <h3 class="font-weight-bold text-dark mb-1"><?= esc($companyName) ?></h3>
                <p class="text-muted small mb-0"><?= esc($companyAddress) ?></p>
                <p class="text-muted small mb-0">Telp: <?= esc($companyPhone) ?> | Email: <?= esc($companyEmail) ?></p>
            </div>
            <div class="col-5 text-right">
                <div class="header-title" style="color: #0284c7;">NOTA CUCI LAUNDRY</div>
                <div class="font-weight-bold text-dark" style="font-size: 16px;">#<?= esc($invoice['invoice_number']) ?></div>
                <div class="text-muted small"><?= $invoice['is_pkp'] ? 'Faktur Pajak Standar (PKP)' : 'Nota Faktur Penjualan (Non-PKP)' ?></div>
            </div>
        </div>

        <!-- Detail Tagihan & Klien -->
        <div class="row mb-4">
            <div class="col-6">
                <div class="text-muted small text-uppercase font-weight-bold mb-1">Ditagihkan Kepada:</div>
                <h5 class="font-weight-bold text-dark mb-1"><?= esc($invoice['customer_name'] ?? 'Pelanggan Umum') ?></h5>
                <p class="text-muted small mb-0"><?= esc($invoice['customer_address'] ?? '-') ?></p>
                <p class="text-muted small mb-0">Kontak: <?= esc($invoice['customer_phone'] ?? '-') ?></p>
            </div>
            <div class="col-6 text-right">
                <table class="table table-sm table-borderless d-inline-block text-left" style="width: auto;">
                    <tr>
                        <td class="text-muted small">Tanggal Faktur:</td>
                        <td class="font-weight-bold small"><?= format_tanggal_indo($invoice['invoice_date']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Metode:</td>
                        <td class="font-weight-bold small text-uppercase"><?= esc($invoice['payment_type']) ?></td>
                    </tr>
                    <?php if ($invoice['payment_type'] === 'kredit'): ?>
                    <tr>
                        <td class="text-danger small font-weight-bold">Jatuh Tempo:</td>
                        <td class="font-weight-bold small text-danger"><?= format_tanggal_indo($invoice['due_date']) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="text-muted small">Status:</td>
                        <td class="font-weight-bold small text-uppercase text-<?= $invoice['status'] === 'paid' ? 'success' : 'danger' ?>">
                            <?= esc($invoice['status']) ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Tabel Layanan -->
        <table class="table table-items mb-4">
            <thead>
                <tr>
                    <th style="width: 40px;" class="text-center">No</th>
                    <th>Deskripsi Layanan Jasa</th>
                    <th style="width: 90px;" class="text-center">Qty</th>
                    <th style="width: 140px;" class="text-right">Harga (Rp)</th>
                    <th style="width: 120px;" class="text-right">Diskon</th>
                    <th style="width: 160px;" class="text-right">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($lines as $line): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><strong><?= esc($line['item_name']) ?></strong></td>
                        <td class="text-center"><?= (float)$line['quantity'] ?></td>
                        <td class="text-right"><?= format_rupiah($line['unit_price'], false) ?></td>
                        <td class="text-right text-muted">
                            <?= (float)$line['discount_amount'] > 0 ? format_rupiah($line['discount_amount'], false) : '-' ?>
                        </td>
                        <td class="text-right font-weight-bold"><?= format_rupiah($line['line_total'], false) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Ringkasan Perhitungan Total -->
        <div class="row justify-content-end mb-4">
            <div class="col-5">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Subtotal:</td>
                        <td class="text-right font-weight-bold"><?= format_rupiah($invoice['subtotal']) ?></td>
                    </tr>
                    <?php if ((float)$invoice['discount_total'] > 0): ?>
                    <tr>
                        <td class="text-muted">Total Diskon:</td>
                        <td class="text-right text-danger">- <?= format_rupiah($invoice['discount_total']) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($invoice['is_pkp']): ?>
                    <tr>
                        <td class="text-muted">PPN (<?= (float)$invoice['tax_rate'] ?>%):</td>
                        <td class="text-right font-weight-bold"><?= format_rupiah($invoice['tax_amount']) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr class="border-top">
                        <td class="font-weight-bold" style="font-size: 16px;">GRAND TOTAL:</td>
                        <td class="text-right font-weight-bold text-primary" style="font-size: 16px;"><?= format_rupiah($invoice['grand_total']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Sudah Dibayar:</td>
                        <td class="text-right small text-success font-weight-bold"><?= format_rupiah($invoice['paid_amount']) ?></td>
                    </tr>
                    <?php $rem = max(0, (float)$invoice['grand_total'] - (float)$invoice['paid_amount']); ?>
                    <tr class="border-top">
                        <td class="text-muted font-weight-bold">Sisa Pembayaran:</td>
                        <td class="text-right font-weight-bold text-danger"><?= format_rupiah($rem) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Catatan & Tanda Tangan -->
        <div class="row mt-5 pt-3 border-top">
            <div class="col-7">
                <div class="text-muted small font-weight-bold mb-1">Catatan & Ketentuan:</div>
                <p class="small text-muted mb-0">
                    <?= !empty($invoice['notes']) ? nl2br(esc($invoice['notes'])) : 'Pembayaran dilakukan secara penuh sesuai dengan tanggal jatuh tempo yang tertera. Dokumen ini merupakan bukti tagihan yang sah.' ?>
                </p>
            </div>
            <div class="col-5 text-center">
                <div class="small text-muted mb-5">Hormat Kami,</div>
                <div class="font-weight-bold text-dark border-bottom d-inline-block px-4 pb-1"><?= esc($companyName) ?></div>
                <div class="small text-muted">Bagian Administrasi Keuangan</div>
            </div>
        </div>
    </div>
</body>
</html>
