<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\SettingModel;
use Config\Database;

class ReportController extends BaseController
{
    protected $customerModel;
    protected $settingModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->settingModel  = new SettingModel();
        helper('format');
    }

    /**
     * Laporan Penjualan (Sales Report)
     */
    public function sales()
    {
        $db = Database::connect();

        $startDate  = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate    = $this->request->getGet('end_date') ?? date('Y-m-d');
        $customerId = $this->request->getGet('customer_id');
        $status     = $this->request->getGet('status');

        $builder = $db->table('invoices')
            ->select('invoices.*, customers.name as customer_name')
            ->join('customers', 'customers.id = invoices.customer_id', 'left')
            ->where('invoices.invoice_date >=', $startDate)
            ->where('invoices.invoice_date <=', $endDate);

        if (!empty($customerId)) {
            $builder->where('invoices.customer_id', $customerId);
        }
        if (!empty($status)) {
            $builder->where('invoices.status', $status);
        }

        $invoices = $builder->orderBy('invoices.invoice_date', 'ASC')->get()->getResultArray();

        // Hitung total ringkasan
        $sumSubtotal = 0;
        $sumDiscount = 0;
        $sumTax      = 0;
        $sumTotal    = 0;
        $sumPaid     = 0;

        foreach ($invoices as $inv) {
            $sumSubtotal += (float)$inv['subtotal'];
            $sumDiscount += (float)$inv['discount_total'];
            $sumTax      += (float)$inv['tax_amount'];
            $sumTotal    += (float)$inv['grand_total'];
            $sumPaid     += (float)$inv['paid_amount'];
        }

        $customers = $this->customerModel->orderBy('name', 'ASC')->findAll();

        return view('reports/sales', [
            'title'       => 'Laporan Penjualan',
            'invoices'    => $invoices,
            'customers'   => $customers,
            'startDate'   => $startDate,
            'endDate'     => $endDate,
            'customerId'  => $customerId,
            'status'      => $status,
            'sumSubtotal' => $sumSubtotal,
            'sumDiscount' => $sumDiscount,
            'sumTax'      => $sumTax,
            'sumTotal'    => $sumTotal,
            'sumPaid'     => $sumPaid,
        ]);
    }

    /**
     * Buku Besar Pembantu Piutang (Kartu Piutang per Pelanggan)
     */
    public function receivables()
    {
        $db = Database::connect();
        $customerId = $this->request->getGet('customer_id');

        $customers = $this->customerModel->orderBy('name', 'ASC')->findAll();
        $ledger = [];
        $selectedCustomer = null;
        $finalBalance = 0;

        if (!empty($customerId)) {
            $selectedCustomer = $this->customerModel->find($customerId);

            // 1. Ambil semua Faktur Penjualan kredit/belum lunas untuk pelanggan ini
            $invoices = $db->table('invoices')
                ->where('customer_id', $customerId)
                ->orderBy('invoice_date', 'ASC')
                ->get()->getResultArray();

            $invoiceIds = array_column($invoices, 'id');

            // 2. Ambil semua Receipt terkait faktur pelanggan ini
            $receipts = [];
            if (!empty($invoiceIds)) {
                $receipts = $db->table('receipts')
                    ->whereIn('invoice_id', $invoiceIds)
                    ->orderBy('payment_date', 'ASC')
                    ->get()->getResultArray();
            }

            // 3. Ambil semua Return terkait faktur pelanggan ini
            $returns = [];
            if (!empty($invoiceIds)) {
                $returns = $db->table('returns')
                    ->whereIn('invoice_id', $invoiceIds)
                    ->where('refund_method', 'potong_piutang')
                    ->orderBy('return_date', 'ASC')
                    ->get()->getResultArray();
            }

            // 4. Gabungkan mutasi secara kronologis
            $transactions = [];

            foreach ($invoices as $inv) {
                $transactions[] = [
                    'date'        => $inv['invoice_date'],
                    'doc_number'  => $inv['invoice_number'],
                    'type'        => 'Faktur Penjualan',
                    'description' => "Penjualan Faktur ({$inv['payment_type']})",
                    'debit'       => (float) $inv['grand_total'],
                    'credit'      => 0.00,
                ];
            }

            foreach ($receipts as $rcp) {
                $transactions[] = [
                    'date'        => $rcp['payment_date'],
                    'doc_number'  => $rcp['receipt_number'],
                    'type'        => 'Penerimaan Kas',
                    'description' => "Pelunasan via {$rcp['payment_method']}",
                    'debit'       => 0.00,
                    'credit'      => (float) $rcp['amount'],
                ];
            }

            foreach ($returns as $ret) {
                $transactions[] = [
                    'date'        => $ret['return_date'],
                    'doc_number'  => $ret['return_number'],
                    'type'        => 'Retur Penjualan',
                    'description' => 'Potong Piutang Retur Layanan',
                    'debit'       => 0.00,
                    'credit'      => (float) $ret['grand_total'],
                ];
            }

            // Urutkan berdasarkan tanggal ASC
            usort($transactions, function ($a, $b) {
                return strcmp($a['date'], $b['date']);
            });

            // Hitung saldo berjalan (Running Balance)
            $runningBalance = 0;
            foreach ($transactions as $tx) {
                $runningBalance += ($tx['debit'] - $tx['credit']);
                $tx['balance'] = $runningBalance;
                $ledger[] = $tx;
            }

            $finalBalance = $runningBalance;
        }

        return view('reports/receivables', [
            'title'            => 'Buku Besar Pembantu Piutang',
            'customers'        => $customers,
            'customerId'       => $customerId,
            'selectedCustomer' => $selectedCustomer,
            'ledger'           => $ledger,
            'finalBalance'     => $finalBalance,
        ]);
    }

    /**
     * Laporan Status Faktur (Lunas / Belum Lunas / Sebagian)
     */
    public function invoiceStatus()
    {
        $db = Database::connect();
        $statusFilter = $this->request->getGet('status');

        $builder = $db->table('invoices')
            ->select('invoices.*, customers.name as customer_name, (invoices.grand_total - invoices.paid_amount) as remaining_amount')
            ->join('customers', 'customers.id = invoices.customer_id', 'left');

        if (!empty($statusFilter)) {
            $builder->where('invoices.status', $statusFilter);
        }

        $invoices = $builder->orderBy('invoices.due_date', 'ASC')->get()->getResultArray();

        return view('reports/invoice_status', [
            'title'        => 'Laporan Status & Umur Faktur',
            'invoices'     => $invoices,
            'statusFilter' => $statusFilter,
        ]);
    }

    /**
     * Export Laporan Penjualan ke format CSV / Excel
     */
    public function exportSalesCsv()
    {
        $db = Database::connect();

        $startDate  = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate    = $this->request->getGet('end_date') ?? date('Y-m-d');
        $customerId = $this->request->getGet('customer_id');
        $status     = $this->request->getGet('status');

        $builder = $db->table('invoices')
            ->select('invoices.*, customers.name as customer_name')
            ->join('customers', 'customers.id = invoices.customer_id', 'left')
            ->where('invoices.invoice_date >=', $startDate)
            ->where('invoices.invoice_date <=', $endDate);

        if (!empty($customerId)) {
            $builder->where('invoices.customer_id', $customerId);
        }
        if (!empty($status)) {
            $builder->where('invoices.status', $status);
        }

        $invoices = $builder->orderBy('invoices.invoice_date', 'ASC')->get()->getResultArray();

        $filename = "Laporan_Penjualan_{$startDate}_sd_{$endDate}.csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        // Add UTF-8 BOM for Excel
        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, ['No. Faktur', 'Tanggal', 'Pelanggan', 'Tipe Bayar', 'Status Pajak', 'Subtotal', 'Diskon', 'PPN', 'Grand Total', 'Sudah Dibayar', 'Status']);

        foreach ($invoices as $row) {
            fputcsv($output, [
                $row['invoice_number'],
                $row['invoice_date'],
                $row['customer_name'],
                strtoupper($row['payment_type']),
                $row['is_pkp'] ? 'PKP' : 'Non-PKP',
                $row['subtotal'],
                $row['discount_total'],
                $row['tax_amount'],
                $row['grand_total'],
                $row['paid_amount'],
                strtoupper($row['status']),
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Print View Resmi Laporan Penjualan (A4 Print-Ready)
     */
    public function salesPrint()
    {
        $db = Database::connect();

        $startDate  = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate    = $this->request->getGet('end_date') ?? date('Y-m-d');
        $customerId = $this->request->getGet('customer_id');
        $status     = $this->request->getGet('status');

        $builder = $db->table('invoices')
            ->select('invoices.*, customers.name as customer_name')
            ->join('customers', 'customers.id = invoices.customer_id', 'left')
            ->where('invoices.invoice_date >=', $startDate)
            ->where('invoices.invoice_date <=', $endDate);

        if (!empty($customerId)) {
            $builder->where('invoices.customer_id', $customerId);
        }
        if (!empty($status)) {
            $builder->where('invoices.status', $status);
        }

        $invoices = $builder->orderBy('invoices.invoice_date', 'ASC')->get()->getResultArray();

        $companyName    = $this->settingModel->getVal('company_name', 'Web UMKM Jasa');
        $companyAddress = $this->settingModel->getVal('company_address', '-');
        $companyPhone   = $this->settingModel->getVal('company_phone', '-');

        return view('reports/sales_print', [
            'invoices'       => $invoices,
            'startDate'      => $startDate,
            'endDate'        => $endDate,
            'companyName'    => $companyName,
            'companyAddress' => $companyAddress,
            'companyPhone'   => $companyPhone,
        ]);
    }

    /**
     * Print View Resmi Kartu Piutang Pelanggan
     */
    public function receivablesPrint()
    {
        $db = Database::connect();
        $customerId = $this->request->getGet('customer_id');

        $selectedCustomer = $this->customerModel->find($customerId);
        if (!$selectedCustomer) {
            return redirect()->to(base_url('reports/receivables'))->with('error', 'Silakan pilih pelanggan terlebih dahulu.');
        }

        $invoices = $db->table('invoices')
            ->where('customer_id', $customerId)
            ->orderBy('invoice_date', 'ASC')
            ->get()->getResultArray();

        $invoiceIds = array_column($invoices, 'id');

        $receipts = [];
        if (!empty($invoiceIds)) {
            $receipts = $db->table('receipts')
                ->whereIn('invoice_id', $invoiceIds)
                ->orderBy('payment_date', 'ASC')
                ->get()->getResultArray();
        }

        $returns = [];
        if (!empty($invoiceIds)) {
            $returns = $db->table('returns')
                ->whereIn('invoice_id', $invoiceIds)
                ->where('refund_method', 'potong_piutang')
                ->orderBy('return_date', 'ASC')
                ->get()->getResultArray();
        }

        $transactions = [];
        foreach ($invoices as $inv) {
            $transactions[] = [
                'date'        => $inv['invoice_date'],
                'doc_number'  => $inv['invoice_number'],
                'type'        => 'Faktur Penjualan',
                'description' => "Penjualan Faktur ({$inv['payment_type']})",
                'debit'       => (float) $inv['grand_total'],
                'credit'      => 0.00,
            ];
        }

        foreach ($receipts as $rcp) {
            $transactions[] = [
                'date'        => $rcp['payment_date'],
                'doc_number'  => $rcp['receipt_number'],
                'type'        => 'Penerimaan Kas',
                'description' => "Pelunasan via {$rcp['payment_method']}",
                'debit'       => 0.00,
                'credit'      => (float) $rcp['amount'],
            ];
        }

        foreach ($returns as $ret) {
            $transactions[] = [
                'date'        => $ret['return_date'],
                'doc_number'  => $ret['return_number'],
                'type'        => 'Retur Penjualan',
                'description' => 'Potong Piutang Retur Layanan',
                'debit'       => 0.00,
                'credit'      => (float) $ret['grand_total'],
            ];
        }

        usort($transactions, function ($a, $b) {
            return strcmp($a['date'], $b['date']);
        });

        $ledger = [];
        $runningBalance = 0;
        foreach ($transactions as $tx) {
            $runningBalance += ($tx['debit'] - $tx['credit']);
            $tx['balance'] = $runningBalance;
            $ledger[] = $tx;
        }

        $companyName    = $this->settingModel->getVal('company_name', 'Web UMKM Jasa');
        $companyAddress = $this->settingModel->getVal('company_address', '-');
        $companyPhone   = $this->settingModel->getVal('company_phone', '-');

        return view('reports/receivables_print', [
            'selectedCustomer' => $selectedCustomer,
            'ledger'           => $ledger,
            'finalBalance'     => $runningBalance,
            'companyName'      => $companyName,
            'companyAddress'   => $companyAddress,
            'companyPhone'     => $companyPhone,
        ]);
    }
}
