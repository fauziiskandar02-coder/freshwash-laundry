<?php

namespace App\Libraries;

use Config\Database;
use Exception;

/**
 * JournalService
 * AIS Automated Double-Entry Bookkeeping Engine for Revenue Cycle.
 */
class JournalService
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
        helper('format');
    }

    /**
     * Otomatis membuat jurnal untuk Faktur Penjualan (Invoice)
     * Menggunakan Database Transaction & Validasi Balancing Debit/Credit
     *
     * @param int $invoiceId
     * @return int ID Journal
     * @throws Exception
     */
    public function recordInvoiceJournal(int $invoiceId): int
    {
        $invoice = $this->db->table('invoices')->where('id', $invoiceId)->get()->getRowArray();
        if (!$invoice) {
            throw new Exception("Faktur Penjualan ID {$invoiceId} tidak ditemukan.");
        }

        $customer = $this->db->table('customers')->where('id', $invoice['customer_id'])->get()->getRowArray();
        $customerName = $customer ? $customer['name'] : 'Pelanggan Umum';

        $subtotal     = (float) $invoice['subtotal'];
        $discount     = (float) $invoice['discount_total'];
        $netRevenue   = $subtotal - $discount;
        $taxAmount    = (float) $invoice['tax_amount'];
        $grandTotal   = (float) $invoice['grand_total'];
        $isPkp        = (int) $invoice['is_pkp'];

        // Siapkan Baris Jurnal
        $journalDetails = [];

        // 1. (Dr) Piutang Usaha sebesar Grand Total
        $journalDetails[] = [
            'account_code' => '1200',
            'account_name' => 'Piutang Usaha',
            'debit'        => $grandTotal,
            'credit'       => 0.00,
        ];

        // 2. (Cr) Pendapatan Jasa Penjualan
        $journalDetails[] = [
            'account_code' => '4100',
            'account_name' => 'Pendapatan Jasa Penjualan',
            'debit'        => 0.00,
            'credit'       => $netRevenue,
        ];

        // 3. (Cr) PPN Keluaran jika PKP dan ada pajak
        if ($isPkp === 1 && $taxAmount > 0) {
            $journalDetails[] = [
                'account_code' => '2100',
                'account_name' => 'PPN Keluaran',
                'debit'        => 0.00,
                'credit'       => $taxAmount,
            ];
        }

        // Simpan Jurnal Header & Detail
        $journalNumber = generate_doc_number('JRN', 'journals', 'journal_number', $this->db);
        $journalId = $this->saveJournal(
            $journalNumber,
            $invoice['invoice_date'],
            'invoice',
            $invoiceId,
            "Penjualan Faktur {$invoice['invoice_number']} - {$customerName}",
            $journalDetails
        );

        // Jika transaksi TUNAI, otomatis buat Receipt dan Jurnal Pelunasan Kas
        if ($invoice['payment_type'] === 'tunai') {
            $this->processAutoCashReceipt($invoice, $customerName);
        }

        return $journalId;
    }

    /**
     * Memproses penerimaan kas otomatis untuk faktur tunai
     */
    protected function processAutoCashReceipt(array $invoice, string $customerName)
    {
        $grandTotal = (float) $invoice['grand_total'];

        // 1. Buat Record Receipt
        $receiptNumber = generate_doc_number('RCP', 'receipts', 'receipt_number', $this->db);
        $receiptData = [
            'receipt_number' => $receiptNumber,
            'invoice_id'     => $invoice['id'],
            'created_by'     => $invoice['created_by'] ?? null,
            'payment_date'   => $invoice['invoice_date'],
            'amount'         => $grandTotal,
            'payment_method' => 'cash',
            'account_code'   => '1100', // Kas
            'notes'          => 'Pelunasan otomatis faktur tunai saat penerbitan faktur',
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];
        $this->db->table('receipts')->insert($receiptData);
        $receiptId = $this->db->insertID();

        // 2. Update status invoice menjadi 'paid'
        $this->db->table('invoices')->where('id', $invoice['id'])->update([
            'paid_amount' => $grandTotal,
            'status'      => 'paid',
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        // 3. Jurnal Penerimaan Kas: (Dr) Kas | (Cr) Piutang Usaha
        $journalDetails = [
            [
                'account_code' => '1100',
                'account_name' => 'Kas Kasir / Tunai',
                'debit'        => $grandTotal,
                'credit'       => 0.00,
            ],
            [
                'account_code' => '1200',
                'account_name' => 'Piutang Usaha',
                'debit'        => 0.00,
                'credit'       => $grandTotal,
            ]
        ];

        $journalNumber = generate_doc_number('JRN', 'journals', 'journal_number', $this->db);
        $this->saveJournal(
            $journalNumber,
            $invoice['invoice_date'],
            'receipt',
            $receiptId,
            "Penerimaan Kas Tunai Faktur {$invoice['invoice_number']} - {$customerName}",
            $journalDetails
        );
    }

    /**
     * Otomatis membuat jurnal untuk Penerimaan Kas / Pelunasan Piutang manual
     *
     * @param int $receiptId
     * @return int
     * @throws Exception
     */
    public function recordReceiptJournal(int $receiptId): int
    {
        $receipt = $this->db->table('receipts')->where('id', $receiptId)->get()->getRowArray();
        if (!$receipt) {
            throw new Exception("Penerimaan kas ID {$receiptId} tidak ditemukan.");
        }

        $invoice = $this->db->table('invoices')->where('id', $receipt['invoice_id'])->get()->getRowArray();
        $customer = $this->db->table('customers')->where('id', $invoice['customer_id'])->get()->getRowArray();
        $customerName = $customer ? $customer['name'] : 'Pelanggan';

        $amount = (float) $receipt['amount'];
        $cashAccountCode = !empty($receipt['account_code']) ? $receipt['account_code'] : '1100';
        $accountInfo = $this->db->table('chart_of_accounts')->where('account_code', $cashAccountCode)->get()->getRowArray();
        $cashAccountName = $accountInfo ? $accountInfo['account_name'] : 'Kas / Bank';

        // Update Faktur: Akumulasi paid_amount & evaluasi status
        $newPaidAmount = (float) $invoice['paid_amount'] + $amount;
        $grandTotal    = (float) $invoice['grand_total'];

        $newStatus = 'partial';
        if ($newPaidAmount >= ($grandTotal - 0.01)) { // toleransi pembulatan float
            $newStatus = 'paid';
        }

        $this->db->table('invoices')->where('id', $invoice['id'])->update([
            'paid_amount' => $newPaidAmount,
            'status'      => $newStatus,
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        // Jurnal Penerimaan Kas:
        // (Dr) Kas / Bank
        // (Cr) Piutang Usaha
        $journalDetails = [
            [
                'account_code' => $cashAccountCode,
                'account_name' => $cashAccountName,
                'debit'        => $amount,
                'credit'       => 0.00,
            ],
            [
                'account_code' => '1200',
                'account_name' => 'Piutang Usaha',
                'debit'        => 0.00,
                'credit'       => $amount,
            ],
        ];

        $journalNumber = generate_doc_number('JRN', 'journals', 'journal_number', $this->db);
        return $this->saveJournal(
            $journalNumber,
            $receipt['payment_date'],
            'receipt',
            $receiptId,
            "Pelunasan Piutang {$invoice['invoice_number']} via {$cashAccountName} - {$customerName}",
            $journalDetails
        );
    }

    /**
     * Otomatis membuat jurnal untuk Retur Penjualan Jasa
     *
     * @param int $returnId
     * @return int
     * @throws Exception
     */
    public function recordReturnJournal(int $returnId): int
    {
        $return = $this->db->table('returns')->where('id', $returnId)->get()->getRowArray();
        if (!$return) {
            throw new Exception("Data Retur Penjualan ID {$returnId} tidak ditemukan.");
        }

        $invoice = $this->db->table('invoices')->where('id', $return['invoice_id'])->get()->getRowArray();
        $customer = $this->db->table('customers')->where('id', $invoice['customer_id'])->get()->getRowArray();
        $customerName = $customer ? $customer['name'] : 'Pelanggan';

        $subtotal    = (float) $return['subtotal'];
        $taxAmount   = (float) $return['tax_amount'];
        $grandTotal  = (float) $return['grand_total'];
        $refundMethod= $return['refund_method']; // 'potong_piutang' atau 'cash'
        $isPkp       = (int) $invoice['is_pkp'];

        $journalDetails = [];

        // 1. (Dr) Retur Penjualan Jasa sebesar Subtotal
        $journalDetails[] = [
            'account_code' => '4200',
            'account_name' => 'Retur Penjualan Jasa',
            'debit'        => $subtotal,
            'credit'       => 0.00,
        ];

        // 2. (Dr) PPN Keluaran jika transaksi asal PKP
        if ($isPkp === 1 && $taxAmount > 0) {
            $journalDetails[] = [
                'account_code' => '2100',
                'account_name' => 'PPN Keluaran',
                'debit'        => $taxAmount,
                'credit'       => 0.00,
            ];
        }

        // 3. Kredit:
        // Jika potong piutang -> (Cr) Piutang Usaha
        // Jika refund kas -> (Cr) Kas
        if ($refundMethod === 'cash') {
            $journalDetails[] = [
                'account_code' => '1100',
                'account_name' => 'Kas Kasir / Tunai',
                'debit'        => 0.00,
                'credit'       => $grandTotal,
            ];
        } else {
            $journalDetails[] = [
                'account_code' => '1200',
                'account_name' => 'Piutang Usaha',
                'debit'        => 0.00,
                'credit'       => $grandTotal,
            ];

            // Sesuaikan nilai grand_total invoice asal
            $newInvoiceTotal = max(0, (float)$invoice['grand_total'] - $grandTotal);
            $paidAmount      = (float)$invoice['paid_amount'];
            $newStatus       = 'unpaid';
            if ($paidAmount >= ($newInvoiceTotal - 0.01)) {
                $newStatus = 'paid';
            } elseif ($paidAmount > 0) {
                $newStatus = 'partial';
            }

            $this->db->table('invoices')->where('id', $invoice['id'])->update([
                'grand_total' => $newInvoiceTotal,
                'status'      => $newStatus,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        $journalNumber = generate_doc_number('JRN', 'journals', 'journal_number', $this->db);
        return $this->saveJournal(
            $journalNumber,
            $return['return_date'],
            'return',
            $returnId,
            "Retur Penjualan Faktur {$invoice['invoice_number']} - {$customerName}",
            $journalDetails
        );
    }

    /**
     * Menyimpan Header & Detail Jurnal dengan validasi Debit == Credit
     *
     * @param string $journalNumber
     * @param string $journalDate
     * @param string $refType
     * @param int $refId
     * @param string $description
     * @param array $details
     * @return int
     * @throws Exception
     */
    protected function saveJournal(string $journalNumber, string $journalDate, string $refType, int $refId, string $description, array $details): int
    {
        // Validasi Balancing
        $totalDebit  = 0.00;
        $totalCredit = 0.00;

        foreach ($details as $row) {
            $totalDebit  += (float) $row['debit'];
            $totalCredit += (float) $row['credit'];
        }

        // Toleransi selisih floating point 0.001
        if (abs($totalDebit - $totalCredit) > 0.01) {
            throw new Exception("Jurnal Tidak Seimbang! Total Debit (Rp " . number_format($totalDebit, 2) . ") != Total Kredit (Rp " . number_format($totalCredit, 2) . ")");
        }

        // Simpan Header
        $journalHeader = [
            'journal_number' => $journalNumber,
            'journal_date'   => $journalDate,
            'ref_type'       => $refType,
            'ref_id'         => $refId,
            'description'    => $description,
            'created_at'     => date('Y-m-d H:i:s'),
        ];
        $this->db->table('journals')->insert($journalHeader);
        $journalId = $this->db->insertID();

        // Simpan Details
        $detailRows = [];
        foreach ($details as $row) {
            $detailRows[] = [
                'journal_id'   => $journalId,
                'account_code' => $row['account_code'],
                'account_name' => $row['account_name'],
                'debit'        => $row['debit'],
                'credit'       => $row['credit'],
            ];
        }
        $this->db->table('journal_details')->insertBatch($detailRows);

        return $journalId;
    }
}
