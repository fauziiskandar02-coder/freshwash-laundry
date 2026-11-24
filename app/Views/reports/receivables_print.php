<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>KARTU PIUTANG - <?= esc($selectedCustomer['name'] ?? 'PELANGGAN') ?> - <?= date('d/m/Y') ?></title>
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
            <i class="fas fa-print mr-1"></i> Cetak Kartu Piutang (Print / PDF)
        </button>
        <button onclick="window.close()" class="btn btn-secondary px-3 ml-2">Tutup</button>
    </div>

    <div class="report-box">
        <!-- Kop Surat -->
        <div class="text-center pb-3 mb-4 border-bottom">
            <h3 class="font-weight-bold mb-1 text-uppercase"><?= esc($companyName) ?></h3>
            <p class="text-muted small mb-1"><?= esc($companyAddress) ?> | Telp: <?= esc($companyPhone) ?></p>
            <h5 class="font-weight-bold mt-3 mb-0 text-primary text-uppercase" style="letter-spacing: 0.5px;">Buku Besar Pembantu Piutang (Kartu Piutang)</h5>
            <small class="text-muted">Laporan Historis Mutasi Debit/Kredit Piutang Pelanggan</small>
        </div>

        <!-- Info Pelanggan & Saldo -->
        <div class="row mb-4 p-3 bg-light rounded border align-items-center">
            <div class="col-7">
                <span class="text-muted small text-uppercase font-weight-bold">Data Pelanggan:</span>
                <h5 class="font-weight-bold mb-1"><?= esc($selectedCustomer['name']) ?> (<?= esc($selectedCustomer['code']) ?>)</h5>
                <p class="small text-muted mb-0">
                    <i class="fas fa-phone mr-1"></i> <?= esc($selectedCustomer['phone'] ?? '-') ?> &bull; 
                    <i class="fas fa-map-marker-alt mr-1"></i> <?= esc($selectedCustomer['address'] ?? '-') ?>
                </p>
            </div>
            <div class="col-5 text-right">
                <span class="text-muted small text-uppercase font-weight-bold d-block">Sisa Saldo Piutang:</span>
                <h4 class="font-weight-bold mb-0 <?= $finalBalance > 0 ? 'text-danger' : 'text-success' ?>">
                    <?= format_rupiah($finalBalance) ?>
                </h4>
                <small class="text-muted"><?= $finalBalance > 0 ? 'Status: Belum Lunas' : 'Status: Lunas / Nihil' ?></small>
            </div>
        </div>

        <!-- Tabel Mutasi Kartu Piutang -->
        <table class="table table-bordered table-sm mb-4">
            <thead>
                <tr>
                    <th style="width: 35px;" class="text-center">No</th>
                    <th style="width: 100px;">Tanggal</th>
                    <th>No. Dokumen</th>
                    <th>Jenis Transaksi</th>
                    <th>Keterangan</th>
                    <th class="text-right" style="width: 140px;">Debit (+)</th>
                    <th class="text-right" style="width: 140px;">Kredit (-)</th>
                    <th class="text-right" style="width: 150px;">Saldo Berjalan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ledger)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            Belum ada riwayat mutasi transaksi piutang untuk pelanggan ini.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $no = 1;
                    $totDebit = 0;
                    $totCredit = 0;
                    foreach ($ledger as $tx): 
                        $totDebit += (float)$tx['debit'];
                        $totCredit += (float)$tx['credit'];
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $tx['date'] ?></td>
                            <td><strong><?= esc($tx['doc_number']) ?></strong></td>
                            <td><span class="badge badge-light border"><?= esc($tx['type']) ?></span></td>
                            <td class="small text-muted"><?= esc($tx['description']) ?></td>
                            <td class="text-right font-weight-bold">
                                <?= (float)$tx['debit'] > 0 ? format_rupiah($tx['debit'], false) : '-' ?>
                            </td>
                            <td class="text-right font-weight-bold text-success">
                                <?= (float)$tx['credit'] > 0 ? format_rupiah($tx['credit'], false) : '-' ?>
                            </td>
                            <td class="text-right font-weight-bold <?= $tx['balance'] > 0 ? 'text-danger' : 'text-success' ?>">
                                <?= format_rupiah($tx['balance'], false) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="font-weight-bold bg-light">
                        <td colspan="5" class="text-center">TOTAL MUTASI PERIODE INI</td>
                        <td class="text-right text-dark"><?= format_rupiah($totDebit) ?></td>
                        <td class="text-right text-success"><?= format_rupiah($totCredit) ?></td>
                        <td class="text-right <?= $finalBalance > 0 ? 'text-danger' : 'text-success' ?>">
                            <?= format_rupiah($finalBalance) ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Lembar Pengesahan -->
        <div class="row mt-5 pt-3">
            <div class="col-6 text-center">
                <small class="text-muted d-block mb-5">Dicetak pada: <?= date('d F Y H:i') ?></small>
                <div class="font-weight-bold border-bottom d-inline-block px-4 pb-1">Bagian Administrasi Piutang</div>
            </div>
            <div class="col-6 text-center">
                <small class="text-muted d-block mb-5">Mengetahui / Menyetujui,</small>
                <div class="font-weight-bold border-bottom d-inline-block px-4 pb-1">Pimpinan / Pemilik UMKM</div>
            </div>
        </div>
    </div>
</body>
</html>
