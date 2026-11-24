<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LAPORAN PENJUALAN - <?= date('d/m/Y') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #222; background: #fff; padding: 25px; }
        .report-box { max-width: 950px; margin: auto; }
        .table th { background-color: #f1f5f9 !important; font-size: 12px; text-transform: uppercase; border-top: 1px solid #cbd5e1; }
        .table td { font-size: 12.5px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-primary px-4 font-weight-bold">
            <i class="fas fa-print mr-1"></i> Cetak Laporan (Print / PDF)
        </button>
        <button onclick="window.close()" class="btn btn-secondary px-3 ml-2">Tutup</button>
    </div>

    <div class="report-box">
        <!-- Kop Laporan -->
        <div class="text-center pb-3 mb-4 border-bottom">
            <h3 class="font-weight-bold mb-1 text-uppercase"><?= esc($companyName) ?></h3>
            <p class="text-muted small mb-1"><?= esc($companyAddress) ?> | Telp: <?= esc($companyPhone) ?></p>
            <h5 class="font-weight-bold mt-3 mb-0 text-primary text-uppercase" style="letter-spacing: 0.5px;">Laporan Penjualan (Siklus Pendapatan)</h5>
            <small class="text-muted">Periode: <?= format_tanggal_indo($startDate) ?> s/d <?= format_tanggal_indo($endDate) ?></small>
        </div>

        <table class="table table-bordered table-sm mb-4">
            <thead>
                <tr>
                    <th style="width: 35px;" class="text-center">No</th>
                    <th>No. Faktur</th>
                    <th>Tanggal</th>
                    <th>Nama Pelanggan</th>
                    <th>Metode</th>
                    <th>Pajak</th>
                    <th class="text-right">Subtotal</th>
                    <th class="text-right">Diskon</th>
                    <th class="text-right">PPN</th>
                    <th class="text-right">Total Tagihan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $totSub = 0; $totDisc = 0; $totTax = 0; $totGrand = 0;
                foreach ($invoices as $inv): 
                    $totSub   += (float)$inv['subtotal'];
                    $totDisc  += (float)$inv['discount_total'];
                    $totTax   += (float)$inv['tax_amount'];
                    $totGrand += (float)$inv['grand_total'];
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><strong><?= esc($inv['invoice_number']) ?></strong></td>
                        <td><?= $inv['invoice_date'] ?></td>
                        <td><?= esc($inv['customer_name'] ?? 'Umum') ?></td>
                        <td class="text-uppercase small"><?= esc($inv['payment_type']) ?></td>
                        <td><?= $inv['is_pkp'] ? 'PKP' : 'Non' ?></td>
                        <td class="text-right"><?= format_rupiah($inv['subtotal'], false) ?></td>
                        <td class="text-right"><?= (float)$inv['discount_total'] > 0 ? format_rupiah($inv['discount_total'], false) : '-' ?></td>
                        <td class="text-right"><?= (float)$inv['tax_amount'] > 0 ? format_rupiah($inv['tax_amount'], false) : '-' ?></td>
                        <td class="text-right font-weight-bold"><?= format_rupiah($inv['grand_total'], false) ?></td>
                        <td class="text-uppercase small"><?= esc($inv['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="font-weight-bold bg-light">
                    <td colspan="6" class="text-center">TOTAL KESELURUHAN</td>
                    <td class="text-right"><?= format_rupiah($totSub) ?></td>
                    <td class="text-right text-danger"><?= format_rupiah($totDisc) ?></td>
                    <td class="text-right"><?= format_rupiah($totTax) ?></td>
                    <td class="text-right text-primary"><?= format_rupiah($totGrand) ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <!-- Lembar Tanda Tangan Resmi -->
        <div class="row mt-5 pt-3">
            <div class="col-6 text-center">
                <small class="text-muted d-block mb-5">Dicetak pada: <?= date('d F Y H:i') ?></small>
                <div class="font-weight-bold border-bottom d-inline-block px-4 pb-1">Bagian Administrasi Keuangan</div>
            </div>
            <div class="col-6 text-center">
                <small class="text-muted d-block mb-5">Mengetahui / Menyetujui,</small>
                <div class="font-weight-bold border-bottom d-inline-block px-4 pb-1">Pimpinan / Pemilik UMKM</div>
            </div>
        </div>
    </div>
</body>
</html>
