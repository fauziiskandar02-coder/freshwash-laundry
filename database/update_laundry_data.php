<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=web_umkm", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Update Settings
    $settings = [
        'company_name'    => 'FreshWash Laundry & Dry Cleaning',
        'company_phone'   => '0812-9988-7766',
        'company_email'   => 'halo@freshwashlaundry.id',
        'company_address' => 'Jl. Pajajaran No. 88, Bogor (Dekat Kampus SV IPB)',
        'is_pkp'          => '1',
        'default_tax_rate'=> '12.00',
        'currency_symbol' => 'Rp'
    ];
    foreach ($settings as $k => $v) {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$k, $v, $v]);
    }

    // 2. Update Items to Laundry Services
    $pdo->exec("DELETE FROM items");
    $items = [
        ['LND-001', 'Cuci Kiloan Reguler (Cuci + Kering + Lipat Rapi)', 'Layanan cuci baju harian wangi dan higienis, pengerjaan 2 hari.', 'kg', 8000.00],
        ['LND-002', 'Cuci Kiloan Komplit + Setrika Uap Wangi', 'Paket lengkap cuci, kering, dan setrika uap halus wangi premium.', 'kg', 10000.00],
        ['LND-003', 'Paket Cuci Kilat Express (3 Jam Selesai)', 'Layanan prioritas cepat selesai dalam 3 jam, cocok untuk kebutuhan mendesak.', 'kg', 18000.00],
        ['LND-004', 'Cuci Bed Cover Besar & Selimut King Size', 'Pencucian khusus selimut tebal dan bed cover menggunakan mesin kapasitas besar.', 'pcs', 35000.00],
        ['LND-005', 'Dry Clean Jas / Blazer / Gaun Pesta', 'Pembersihan profesional untuk pakaian berbahan wol, sutra, atau berpayet.', 'pcs', 30000.00],
        ['LND-006', 'Cuci Sepatu Sneakers & Deep Clean', 'Perawatan cuci sepatu khusus bahan canvas, suede, dan leather dengan anti-bacterial.', 'pasang', 35000.00],
        ['LND-007', 'Cuci Karpet Tebal & Permadani', 'Cuci basah & vakum karpet tebal pembersih debu dan tungau.', 'meter', 15000.00],
        ['LND-008', 'Jasa Setrika Uap Rapi & Pewangi Premium', 'Hanya setrika uap rapi tanpa cuci, pakaian rapi siap pakai.', 'kg', 5000.00]
    ];
    $stmtItem = $pdo->prepare("INSERT INTO items (code, name, description, unit, sell_price, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    foreach ($items as $it) {
        $stmtItem->execute($it);
    }

    // 3. Update Customers to Laundry Members
    $pdo->exec("DELETE FROM customers");
    $customers = [
        ['MBR-001', 'Kak Annisa Rahma (Member Mahasiswi SV IPB)', '0812-1122-3344', 'annisa@student.ipb.ac.id', 'Kost Putri Aster No. 12, Cilibende, Bogor'],
        ['MBR-002', 'Bpk. Hendra Gunawan (Pelanggan Keluarga)', '0813-8899-7711', 'hendra.gunawan@gmail.com', 'Perumahan Baranangsiang Indah Blok C3 No. 8, Bogor'],
        ['MBR-003', 'Ibu Maya Indriani (Langganan Bed Cover & Gorden)', '0857-4433-8822', 'maya.indri@yahoo.com', 'Jl. Pajajaran Indah V No. 22, Bogor Timur'],
        ['MBR-004', 'Asrama Putra SV IPB Cilibende', '0811-9900-1122', 'asrama.cilibende@ipb.ac.id', 'Komplek Asrama Mahasiswa SV IPB, Bogor'],
        ['MBR-005', 'Hotel Salak Heritage (Mitra B2B Linen)', '0251-8373040', 'purchasing@hotelsalak.co.id', 'Jl. Ir. H. Juanda No. 8, Pabaton, Bogor Tengah']
    ];
    $stmtCust = $pdo->prepare("INSERT INTO customers (code, name, phone, email, address, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    foreach ($customers as $c) {
        $stmtCust->execute($c);
    }

    echo "SUCCESS: Laundry settings, items, and customers updated!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
