<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk POS - <?= esc($invoice['invoice_number']) ?> - FreshWash Laundry</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🧼</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #f1f5f9;
            font-family: 'Consolas', 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #1e293b;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 10px;
        }

        /* Action bar outside print */
        .thermal-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn-thermal {
            background: #0284c7;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.25);
            transition: all 0.2s ease;
        }
        .btn-thermal:hover {
            background: #0369a1;
            transform: translateY(-1px);
        }
        .btn-thermal-secondary {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .btn-thermal-secondary:hover {
            background: #f8fafc;
            color: #1e293b;
        }

        /* Paper Sheet */
        .thermal-ticket {
            width: 320px; /* 80mm standard */
            background: #ffffff;
            padding: 16px 14px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            line-height: 1.35;
            position: relative;
        }
        .thermal-ticket.paper-58mm {
            width: 240px; /* 58mm compact */
            font-size: 11px;
            padding: 12px 10px;
        }

        /* Zigzag decorative paper edge */
        .thermal-ticket::after {
            content: "";
            position: absolute;
            bottom: -6px;
            left: 0;
            right: 0;
            height: 6px;
            background: radial-gradient(circle, transparent, transparent 50%, #ffffff 50%, #ffffff 100%);
            background-size: 10px 10px;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        .divider-dashed {
            border-top: 1px dashed #94a3b8;
            margin: 8px 0;
        }
        .divider-double {
            border-top: 2px double #64748b;
            margin: 8px 0;
        }

        /* Store Header */
        .store-logo {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .store-slogan {
            font-size: 10px;
            letter-spacing: 0.5px;
            color: #0284c7;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .store-info {
            font-size: 10px;
            color: #64748b;
        }

        /* Metadata */
        .meta-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 2px;
        }

        /* Items */
        .item-row {
            margin-bottom: 6px;
        }
        .item-name {
            font-weight: bold;
            font-size: 11.5px;
            color: #0f172a;
        }
        .item-calc {
            display: flex;
            justify-content: space-between;
            color: #334155;
            font-size: 11px;
        }

        /* Totals */
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11.5px;
        }
        .grand-total-row {
            display: flex;
            justify-content: space-between;
            font-size: 13.5px;
            font-weight: 900;
            color: #0f172a;
            padding: 4px 0;
        }

        /* Status Stamp */
        .status-stamp {
            display: inline-block;
            border: 2px dashed #059669;
            color: #059669;
            font-size: 11px;
            font-weight: 800;
            padding: 2px 10px;
            border-radius: 4px;
            letter-spacing: 1px;
            margin: 6px 0;
        }
        .status-stamp.stamp-unpaid {
            border-color: #dc2626;
            color: #dc2626;
        }
        .status-stamp.stamp-partial {
            border-color: #d97706;
            color: #d97706;
        }

        .process-badge {
            background: #e0f2fe;
            color: #0369a1;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10.5px;
            font-weight: 700;
            display: inline-block;
            margin: 4px 0;
        }

        /* Barcode simulation */
        .barcode-container {
            margin: 10px 0 6px 0;
            text-align: center;
        }
        .barcode-lines {
            height: 32px;
            width: 80%;
            margin: 0 auto;
            background: repeating-linear-gradient(
                90deg,
                #000,
                #000 2px,
                #fff 2px,
                #fff 4px,
                #000 4px,
                #000 5px,
                #fff 5px,
                #fff 7px,
                #000 7px,
                #000 10px,
                #fff 10px,
                #fff 12px
            );
        }
        .barcode-num {
            font-size: 10px;
            letter-spacing: 2px;
            margin-top: 3px;
            color: #475569;
        }

        /* Footer Notes */
        .ticket-footer {
            font-size: 9.5px;
            color: #64748b;
            text-align: center;
            line-height: 1.35;
        }

        /* Print Settings */
        @media print {
            body {
                background: #ffffff;
                padding: 0;
                margin: 0;
            }
            .thermal-actions {
                display: none !important;
            }
            .thermal-ticket {
                width: 100% !important;
                max-width: 100% !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 4px 0 !important;
            }
            .thermal-ticket::after {
                display: none !important;
            }
            @page {
                margin: 0;
                size: auto;
            }
        }
    </style>
</head>
<body>

    <!-- On-screen Action Toolbar -->
    <div class="thermal-actions no-print">
        <button onclick="window.print()" class="btn-thermal">
            <i class="fas fa-print"></i> Cetak Struk (Print)
        </button>
        <button type="button" class="btn-thermal btn-thermal-secondary" onclick="togglePaperSize()">
            <i class="fas fa-arrows-alt-h"></i> <span id="paperLabel">Ganti ke 58mm</span>
        </button>
        <a href="<?= base_url("invoices/{$invoice['id']}") ?>" class="btn-thermal btn-thermal-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Faktur
        </a>
    </div>

    <!-- Thermal Receipt Paper Card -->
    <div class="thermal-ticket" id="thermalTicket">
        <!-- Header -->
        <div class="text-center">
            <div class="store-logo">🧼 FRESHWASH</div>
            <div class="store-slogan">&bull; BERSIH, WANGI &amp; RAPI &bull;</div>
            <div class="store-info"><?= esc($companyAddress) ?></div>
            <div class="store-info">WA/Telp: <?= esc($companyPhone) ?></div>
        </div>

        <div class="divider-double"></div>

        <!-- Meta Information -->
        <div class="meta-row">
            <span>No. Nota</span>
            <span class="font-bold">#<?= esc($invoice['invoice_number']) ?></span>
        </div>
        <div class="meta-row">
            <span>Tanggal</span>
            <span><?= date('d/m/Y H:i', strtotime($invoice['created_at'] ?? $invoice['invoice_date'])) ?></span>
        </div>
        <div class="meta-row">
            <span>Kasir</span>
            <span><?= esc($invoice['creator_name'] ?? 'Admin FreshWash') ?></span>
        </div>
        <div class="meta-row">
            <span>Pelanggan</span>
            <span class="font-bold"><?= esc($invoice['customer_name'] ?? 'Pelanggan Umum') ?></span>
        </div>
        <?php if (!empty($invoice['customer_phone'])): ?>
        <div class="meta-row">
            <span>No. HP</span>
            <span><?= esc($invoice['customer_phone']) ?></span>
        </div>
        <?php endif; ?>

        <div class="divider-dashed"></div>

        <!-- Items Table -->
        <?php foreach ($lines as $line): ?>
            <div class="item-row">
                <div class="item-name"><?= esc($line['item_name']) ?></div>
                <div class="item-calc">
                    <span>
                        <?= (float)$line['quantity'] ?> <?= esc($line['unit'] ?? 'item') ?> x <?= number_format($line['unit_price'], 0, ',', '.') ?>
                        <?php if ((float)$line['discount_amount'] > 0): ?>
                            <span style="color: #dc2626;">(Disc: -<?= number_format($line['discount_amount'], 0, ',', '.') ?>)</span>
                        <?php endif; ?>
                    </span>
                    <span class="font-bold">Rp <?= number_format($line['line_total'], 0, ',', '.') ?></span>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="divider-dashed"></div>

        <!-- Calculations & Totals -->
        <div class="total-row">
            <span>Subtotal</span>
            <span>Rp <?= number_format($invoice['subtotal'], 0, ',', '.') ?></span>
        </div>

        <?php if ((float)$invoice['discount_total'] > 0): ?>
        <div class="total-row" style="color: #dc2626;">
            <span>Diskon Nota</span>
            <span>- Rp <?= number_format($invoice['discount_total'], 0, ',', '.') ?></span>
        </div>
        <?php endif; ?>

        <?php if ((float)$invoice['tax_amount'] > 0): ?>
        <div class="total-row">
            <span>PPN (<?= (float)$invoice['tax_rate'] ?>%)</span>
            <span>Rp <?= number_format($invoice['tax_amount'], 0, ',', '.') ?></span>
        </div>
        <?php endif; ?>

        <div class="divider-dashed"></div>

        <div class="grand-total-row">
            <span>TOTAL TAGIHAN</span>
            <span>Rp <?= number_format($invoice['grand_total'], 0, ',', '.') ?></span>
        </div>

        <div class="total-row">
            <span>Metode Bayar</span>
            <span class="uppercase font-bold"><?= esc($invoice['payment_type']) ?></span>
        </div>
        <div class="total-row">
            <span>Dibayar</span>
            <span>Rp <?= number_format($invoice['paid_amount'], 0, ',', '.') ?></span>
        </div>

        <?php 
            $sisaPiutang = max(0, (float)$invoice['grand_total'] - (float)$invoice['paid_amount']);
            if ($sisaPiutang > 0):
        ?>
        <div class="total-row font-bold" style="color: #dc2626;">
            <span>SISA PIUTANG</span>
            <span>Rp <?= number_format($sisaPiutang, 0, ',', '.') ?></span>
        </div>
        <?php if (!empty($invoice['due_date'])): ?>
        <div class="total-row" style="color: #dc2626; font-size: 10.5px;">
            <span>Jatuh Tempo:</span>
            <span><?= date('d/m/Y', strtotime($invoice['due_date'])) ?></span>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <div class="total-row">
            <span>Kembalian</span>
            <span>Rp 0</span>
        </div>
        <?php endif; ?>

        <!-- Status Bayar & Status Cucian -->
        <div class="text-center" style="margin-top: 8px;">
            <?php if ($invoice['status'] === 'paid'): ?>
                <div class="status-stamp">&bull; LUNAS &bull;</div>
            <?php elseif ($invoice['status'] === 'partial'): ?>
                <div class="status-stamp stamp-partial">&bull; BAYAR SEBAGIAN &bull;</div>
            <?php else: ?>
                <div class="status-stamp stamp-unpaid">&bull; BELUM LUNAS &bull;</div>
            <?php endif; ?>

            <?php 
                $pStatus = $invoice['process_status'] ?? 'antrean';
                $pLabels = [
                    'antrean'    => '🕒 STATUS: DALAM ANTREAN',
                    'cuci'       => '🫧 STATUS: SEDANG DICUCI',
                    'setrika'    => '👔 STATUS: SEDANG DISETRIKA',
                    'siap_ambil' => '✨ STATUS: SIAP DIAMBIL',
                    'diambil'    => '📦 STATUS: SUDAH DIAMBIL',
                ];
                $label = $pLabels[$pStatus] ?? '🕒 STATUS: DALAM PROSES';
            ?>
            <div>
                <span class="process-badge"><?= $label ?></span>
            </div>
        </div>

        <?php if (!empty($invoice['notes'])): ?>
        <div class="divider-dashed"></div>
        <div style="font-size: 10px; color: #475569;">
            <span class="font-bold">Catatan Cucian:</span> <?= esc($invoice['notes']) ?>
        </div>
        <?php endif; ?>

        <!-- Simulated Barcode -->
        <div class="barcode-container">
            <div class="barcode-lines"></div>
            <div class="barcode-num">*<?= esc($invoice['invoice_number']) ?>*</div>
        </div>

        <div class="divider-double"></div>

        <!-- Terms & Disclaimer -->
        <div class="ticket-footer">
            <p class="font-bold" style="color: #334155; margin-bottom: 3px;">SYARAT &amp; KETENTUAN CUCIAN:</p>
            <p>1. Garansi cuci ulang berlaku 1x24 jam sejak pakaian diambil dengan membawa struk ini.</p>
            <p>2. Cucian tidak diambil lebih dari 30 hari di luar tanggung jawab FreshWash Laundry.</p>
            <p>3. Hitung kembali jumlah dan kondisi pakaian saat serah terima di kasir.</p>
            <p style="margin-top: 6px; font-weight: bold; color: #0284c7;">
                Terima kasih atas kunjungan Anda! 🫧
            </p>
            <p style="font-size: 8.5px; margin-top: 2px;">FreshWash Laundry &bull; PJBL Komak TI SV IPB</p>
        </div>
    </div>

    <script>
        function togglePaperSize() {
            const ticket = document.getElementById('thermalTicket');
            const label = document.getElementById('paperLabel');
            if (ticket.classList.contains('paper-58mm')) {
                ticket.classList.remove('paper-58mm');
                label.textContent = 'Ganti ke 58mm';
            } else {
                ticket.classList.add('paper-58mm');
                label.textContent = 'Ganti ke 80mm';
            }
        }
    </script>
</body>
</html>
