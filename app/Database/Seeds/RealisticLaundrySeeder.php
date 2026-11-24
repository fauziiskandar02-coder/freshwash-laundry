<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Libraries\JournalService;
use Config\Database;

class RealisticLaundrySeeder extends Seeder
{
    public function run()
    {
        $db = Database::connect();

        // 1. Update Settings with FreshWash Laundry Details
        $laundrySettings = [
            'company_name'    => 'FreshWash Laundry & Dry Cleaning',
            'company_phone'   => '0812-9876-5432',
            'company_email'   => 'halo@freshwash.id',
            'company_address' => 'Jl. Kumbang No. 14, Babakan, Bogor Tengah (Kampus SV IPB)',
            'is_pkp'          => '0',
            'default_tax_rate'=> '0.00',
            'currency_symbol' => 'Rp',
        ];

        foreach ($laundrySettings as $key => $val) {
            $existing = $db->table('settings')->where('setting_key', $key)->get()->getRow();
            if ($existing) {
                $db->table('settings')->where('setting_key', $key)->update([
                    'setting_value' => $val,
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
            } else {
                $db->table('settings')->insert([
                    'setting_key'   => $key,
                    'setting_value' => $val,
                    'description'   => 'Pengaturan Toko Laundry',
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // 2. Clear old demo transaction data cleanly to re-seed realistic fresh transactions
        $db->table('journal_details')->emptyTable();
        $db->table('journals')->emptyTable();
        $db->table('return_lines')->emptyTable();
        $db->table('returns')->emptyTable();
        $db->table('receipts')->emptyTable();
        $db->table('invoice_lines')->emptyTable();
        $db->table('invoices')->emptyTable();

        // 3. Upsert Laundry Services (Items)
        $services = [
            [
                'code'        => 'LND-001',
                'name'        => 'Cuci Kiloan Reguler (2 Hari)',
                'description' => 'Cuci, pengeringan, setrika uap, dan packing plastik wangi semerbak',
                'unit'        => 'kg',
                'sell_price'  => 8000.00,
                'is_active'   => 1,
            ],
            [
                'code'        => 'LND-002',
                'name'        => 'Cuci Kilat Express (1 Hari)',
                'description' => 'Cuci kilat selesai dalam 24 jam dengan parfum premium antibakteri',
                'unit'        => 'kg',
                'sell_price'  => 13000.00,
                'is_active'   => 1,
            ],
            [
                'code'        => 'LND-003',
                'name'        => 'Cuci Kilat Super Express (4 Jam)',
                'description' => 'Prioritas antrean kilat 4 jam siap jemput/antar khusus area kampus IPB',
                'unit'        => 'kg',
                'sell_price'  => 18000.00,
                'is_active'   => 1,
            ],
            [
                'code'        => 'LND-004',
                'name'        => 'Cuci Bed Cover King Size',
                'description' => 'Pembersihan mendalam serat kain, anti-tungau dan pelembut serat mikro',
                'unit'        => 'pcs',
                'sell_price'  => 35000.00,
                'is_active'   => 1,
            ],
            [
                'code'        => 'LND-005',
                'name'        => 'Cuci Setrika Kemeja / Seragam',
                'description' => 'Pencucian satuan kerah rapi, kancing aman, disetrika dengan steamer uap',
                'unit'        => 'pcs',
                'sell_price'  => 6000.00,
                'is_active'   => 1,
            ],
            [
                'code'        => 'LND-006',
                'name'        => 'Dry Cleaning Jas & Blazer Premium',
                'description' => 'Perawatan khusus tanpa air dengan pelindung serat wol & jas formil',
                'unit'        => 'pcs',
                'sell_price'  => 45000.00,
                'is_active'   => 1,
            ],
            [
                'code'        => 'LND-007',
                'name'        => 'Cuci Sepatu & Sneakers Canvas',
                'description' => 'Deep clean sol luar dalam, unyellowing midsole dan pengharum anti-bau',
                'unit'        => 'pasang',
                'sell_price'  => 30000.00,
                'is_active'   => 1,
            ],
            [
                'code'        => 'LND-008',
                'name'        => 'Cuci Boneka & Helm Bogo',
                'description' => 'Disinfeksi busa helm atau boneka jumbo, wangi tahan lama',
                'unit'        => 'pcs',
                'sell_price'  => 25000.00,
                'is_active'   => 1,
            ],
        ];

        foreach ($services as $srv) {
            $existing = $db->table('items')->where('code', $srv['code'])->get()->getRow();
            if ($existing) {
                $db->table('items')->where('code', $srv['code'])->update(array_merge($srv, ['updated_at' => date('Y-m-d H:i:s')]));
            } else {
                $db->table('items')->insert(array_merge($srv, [
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]));
            }
        }

        // 4. Upsert Laundry Customers / Members
        $customers = [
            [
                'code'    => 'MBR-001',
                'name'    => 'Ananda Putri (Mahasiswi SV IPB)',
                'phone'   => '0812-1122-3344',
                'email'   => 'ananda.putri@apps.ipb.ac.id',
                'address' => 'Kost Aster Putri No. 12, Babakan Tengah, Bogor',
            ],
            [
                'code'    => 'MBR-002',
                'name'    => 'Bpk. Rahmat Hidayat (Warga Lodaya)',
                'phone'   => '0813-2233-4455',
                'email'   => 'rahmat.hidayat@gmail.com',
                'address' => 'Jl. Lodaya I No. 8, Babakan, Bogor Tengah',
            ],
            [
                'code'    => 'MBR-003',
                'name'    => 'Dimas Arya Pratama (Asrama IPB)',
                'phone'   => '0857-3344-5566',
                'email'   => 'dimas.arya@apps.ipb.ac.id',
                'address' => 'Asrama Sylvaplat A-102, Kampus IPB Dramaga',
            ],
            [
                'code'    => 'MBR-004',
                'name'    => 'Ibu Siti Rahmawati (Kost Pajajaran)',
                'phone'   => '0821-4455-6677',
                'email'   => 'siti.rahmawati@yahoo.com',
                'address' => 'Perumahan Pajajaran Indah Blok B-3, Bogor',
            ],
            [
                'code'    => 'MBR-005',
                'name'    => 'dr. Hendra Kusuma (Klinik Medika)',
                'phone'   => '0811-5566-7788',
                'email'   => 'dr.hendra@medikabogor.com',
                'address' => 'Jl. Malabar Ujung No. 19, Bogor',
            ],
        ];

        foreach ($customers as $cust) {
            $existing = $db->table('customers')->where('code', $cust['code'])->get()->getRow();
            if ($existing) {
                $db->table('customers')->where('code', $cust['code'])->update(array_merge($cust, ['is_active' => 1, 'updated_at' => date('Y-m-d H:i:s')]));
            } else {
                $db->table('customers')->insert(array_merge($cust, [
                    'is_active'  => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]));
            }
        }

        // Cache customer and item maps
        $allCust = $db->table('customers')->get()->getResultArray();
        $custMap = [];
        foreach ($allCust as $c) {
            $custMap[$c['code']] = $c['id'];
        }

        $allItems = $db->table('items')->get()->getResultArray();
        $itemMap = [];
        foreach ($allItems as $it) {
            $itemMap[$it['code']] = $it;
        }

        $adminUser = $db->table('users')->where('role', 'admin')->get()->getRowArray();
        $adminId = $adminUser ? (int)$adminUser['id'] : 1;

        $journalService = new JournalService();

        // 5. Create 7 Realistic Invoices for November 2026
        $seedInvoices = [
            // 1. Paid Cash - Regular Kiloan
            [
                'number'         => 'INV-202611-001',
                'cust_code'      => 'MBR-001',
                'date'           => '2026-11-18',
                'payment_type'   => 'tunai',
                'process_status' => 'diambil',
                'notes'          => 'Parfum: Sweet Lavender. Sudah diambil pelanggan.',
                'items'          => [
                    ['code' => 'LND-001', 'qty' => 6.00], // 6kg @ 8000 = 48.000
                ],
            ],
            // 2. Paid Cash - Bed Cover + Kiloan
            [
                'number'         => 'INV-202611-002',
                'cust_code'      => 'MBR-004',
                'date'           => '2026-11-19',
                'payment_type'   => 'tunai',
                'process_status' => 'siap_ambil',
                'notes'          => 'Bed cover king size + cuci kiloan rutin kost. Sudah selesai dipacking wangi.',
                'items'          => [
                    ['code' => 'LND-004', 'qty' => 2.00], // 2 pcs @ 35000 = 70.000
                    ['code' => 'LND-001', 'qty' => 15.00], // 15kg @ 8000 = 120.000
                ],
            ],
            // 3. Unpaid Member Kredit - Jas & Blazer
            [
                'number'         => 'INV-202611-003',
                'cust_code'      => 'MBR-005',
                'date'           => '2026-11-20',
                'payment_type'   => 'kredit',
                'due_date'       => '2026-11-30',
                'process_status' => 'setrika',
                'notes'          => 'Jas dokter & blazer seminar. Sedang tahap steam uap & hanger.',
                'items'          => [
                    ['code' => 'LND-006', 'qty' => 2.00], // 2 pcs @ 45000 = 90.000
                ],
            ],
            // 4. Partial Payment (Kredit with DP) - Kilat Express + Sepatu
            [
                'number'         => 'INV-202611-004',
                'cust_code'      => 'MBR-002',
                'date'           => '2026-11-20',
                'payment_type'   => 'kredit',
                'due_date'       => '2026-11-25',
                'process_status' => 'cuci',
                'partial_paid'   => 50000.00,
                'notes'          => 'Cuci kilat + deep clean sepatu Converse. DP Rp 50.000 tunai.',
                'items'          => [
                    ['code' => 'LND-002', 'qty' => 5.00], // 5kg @ 13000 = 65.000
                    ['code' => 'LND-007', 'qty' => 1.00], // 1 pasang @ 30000 = 30.000
                ], // Grand total: 95.000, partial 50.000, sisa 45.000
            ],
            // 5. Paid Cash - Super Express 4 Jam
            [
                'number'         => 'INV-202611-005',
                'cust_code'      => 'MBR-003',
                'date'           => '2026-11-21',
                'payment_type'   => 'tunai',
                'process_status' => 'siap_ambil',
                'notes'          => 'Baju seragam praktikum IPB mendesak. Selesai dan siap diambil di kasir.',
                'items'          => [
                    ['code' => 'LND-003', 'qty' => 4.00], // 4kg @ 18000 = 72.000
                ],
            ],
            // 6. Paid Cash - Cuci Setrika Kemeja Satuan
            [
                'number'         => 'INV-202611-006',
                'cust_code'      => 'MBR-001',
                'date'           => '2026-11-21',
                'payment_type'   => 'tunai',
                'process_status' => 'antrean',
                'notes'          => '8 pcs kemeja putih & batik kuliah. Sedang antre masuk washer 02.',
                'items'          => [
                    ['code' => 'LND-005', 'qty' => 8.00], // 8 pcs @ 6000 = 48.000
                ],
            ],
            // 7. Unpaid Member Kredit - Kiloan Regular Asrama
            [
                'number'         => 'INV-202611-007',
                'cust_code'      => 'MBR-004',
                'date'           => '2026-11-21',
                'payment_type'   => 'kredit',
                'due_date'       => '2026-11-28',
                'process_status' => 'cuci',
                'notes'          => 'Tagihan mingguan kost. Sedang diproses pencucian.',
                'items'          => [
                    ['code' => 'LND-001', 'qty' => 10.00], // 10kg @ 8000 = 80.000
                ],
            ],
        ];

        foreach ($seedInvoices as $inv) {
            $custId = $custMap[$inv['cust_code']] ?? 1;

            $subtotal = 0.00;
            $lineDetails = [];
            foreach ($inv['items'] as $it) {
                $itemInfo = $itemMap[$it['code']];
                $lineTot = (float)$it['qty'] * (float)$itemInfo['sell_price'];
                $subtotal += $lineTot;
                $lineDetails[] = [
                    'item_id'         => (int)$itemInfo['id'],
                    'item_name'       => $itemInfo['name'],
                    'quantity'        => (float)$it['qty'],
                    'unit_price'      => (float)$itemInfo['sell_price'],
                    'discount_type'   => 'none',
                    'discount_value'  => 0.00,
                    'discount_amount' => 0.00,
                    'line_total'      => $lineTot,
                ];
            }

            $grandTotal = $subtotal;
            $isPaid = ($inv['payment_type'] === 'tunai');
            $paidAmount = $isPaid ? $grandTotal : 0.00;
            $status = $isPaid ? 'paid' : 'unpaid';

            $invoiceRecord = [
                'invoice_number' => $inv['number'],
                'customer_id'    => $custId,
                'created_by'     => $adminId,
                'invoice_date'   => $inv['date'],
                'due_date'       => $inv['due_date'] ?? null,
                'payment_type'   => $inv['payment_type'],
                'is_pkp'         => 0,
                'tax_rate'       => 0.00,
                'subtotal'       => $subtotal,
                'discount_total' => 0.00,
                'tax_amount'     => 0.00,
                'grand_total'    => $grandTotal,
                'paid_amount'    => $paidAmount,
                'status'         => $status,
                'notes'          => $inv['notes'],
                'created_at'     => $inv['date'] . ' 09:30:00',
                'updated_at'     => $inv['date'] . ' 09:30:00',
            ];

            // If column process_status exists in invoices table
            $fields = $db->getFieldNames('invoices');
            if (in_array('process_status', $fields)) {
                $invoiceRecord['process_status'] = $inv['process_status'];
            }

            $db->table('invoices')->insert($invoiceRecord);
            $invoiceId = $db->insertID();

            foreach ($lineDetails as &$ld) {
                $ld['invoice_id'] = $invoiceId;
            }
            $db->table('invoice_lines')->insertBatch($lineDetails);

            // Record Invoice Journal (and auto cash receipt if tunai)
            $journalService->recordInvoiceJournal($invoiceId);

            // If there is partial payment for kredit invoice
            if (!empty($inv['partial_paid']) && $inv['partial_paid'] > 0) {
                $rcpNumber = generate_doc_number('RCP', 'receipts', 'receipt_number', $db);
                $rcpData = [
                    'receipt_number' => $rcpNumber,
                    'invoice_id'     => $invoiceId,
                    'created_by'     => $adminId,
                    'payment_date'   => $inv['date'],
                    'amount'         => (float)$inv['partial_paid'],
                    'payment_method' => 'cash',
                    'account_code'   => '1100',
                    'notes'          => 'Uang Muka (DP) cucian kilat express saat penyerahan pakaian',
                    'created_at'     => $inv['date'] . ' 10:00:00',
                    'updated_at'     => $inv['date'] . ' 10:00:00',
                ];
                $db->table('receipts')->insert($rcpData);
                $receiptId = $db->insertID();

                $journalService->recordReceiptJournal($receiptId);
            }
        }

        // 6. Add 1 Return / Garansi Cuci Ulang (INV-202611-002)
        $invTarget = $db->table('invoices')->where('invoice_number', 'INV-202611-002')->get()->getRowArray();
        if ($invTarget) {
            $retNumber = generate_doc_number('RET', 'returns', 'return_number', $db);
            $retGrandTotal = 35000.00; // 1 bed cover dicuci ulang karena noda minyak bandel
            $retData = [
                'return_number' => $retNumber,
                'invoice_id'    => $invTarget['id'],
                'created_by'    => $adminId,
                'return_date'   => '2026-11-20',
                'subtotal'      => $retGrandTotal,
                'tax_amount'    => 0.00,
                'grand_total'   => $retGrandTotal,
                'refund_method' => 'cash',
                'notes'         => 'Garansi cuci ulang 1 Bed Cover King karena ada sisa noda minyak kopi.',
                'created_at'    => '2026-11-20 14:00:00',
                'updated_at'    => '2026-11-20 14:00:00',
            ];
            $db->table('returns')->insert($retData);
            $returnId = $db->insertID();

            $retLine = [
                'return_id'  => $returnId,
                'item_id'    => $itemMap['LND-004']['id'] ?? null,
                'item_name'  => 'Cuci Bed Cover King Size (Garansi Cuci Ulang)',
                'quantity'   => 1.00,
                'unit_price' => 35000.00,
                'line_total' => 35000.00,
            ];
            $db->table('return_lines')->insert($retLine);

            $journalService->recordReturnJournal($returnId);
        }

        echo "RealisticLaundrySeeder completed successfully with 7 invoices, receipts, return & journals!\n";
    }
}
