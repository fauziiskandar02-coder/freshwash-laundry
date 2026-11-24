<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\ReceiptModel;
use App\Models\CustomerModel;
use App\Models\JournalModel;
use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = Database::connect();
        helper('format');

        $currentMonth = date('Y-m');

        // 1. Total Pendapatan Faktur Bulan Ini
        $revRow = $db->table('invoices')
            ->selectSum('grand_total')
            ->like('invoice_date', $currentMonth, 'after')
            ->get()->getRow();
        $monthlyRevenue = (float) ($revRow->grand_total ?? 0);

        // 2. Total Piutang Belum Tertagih (Unpaid / Partial)
        $arRow = $db->table('invoices')
            ->select('SUM(grand_total - paid_amount) as total_receivable')
            ->whereIn('status', ['unpaid', 'partial'])
            ->get()->getRow();
        $totalReceivable = (float) ($arRow->total_receivable ?? 0);

        // 3. Total Kas Diterima Bulan Ini
        $rcpRow = $db->table('receipts')
            ->selectSum('amount')
            ->like('payment_date', $currentMonth, 'after')
            ->get()->getRow();
        $monthlyCashReceipts = (float) ($rcpRow->amount ?? 0);

        // 4. Hitung Jumlah Dokumen
        $totalInvoicesCount = $db->table('invoices')->countAllResults();
        $totalCustomersCount = $db->table('customers')->where('is_active', 1)->countAllResults();

        // 5. Faktur Terkini (5 terakhir)
        $invoiceModel = new InvoiceModel();
        $recentInvoices = $invoiceModel->getInvoiceWithCustomer();
        $recentInvoices = array_slice($recentInvoices, 0, 5);

        // 6. Jurnal Terkini untuk ringkasan AIS (5 terakhir)
        $journalModel = new JournalModel();
        $recentJournals = $journalModel->getJournalsWithDetails(5);

        $data = [
            'title'               => 'Dashboard Siklus Pendapatan',
            'monthlyRevenue'      => $monthlyRevenue,
            'totalReceivable'     => $totalReceivable,
            'monthlyCashReceipts' => $monthlyCashReceipts,
            'totalInvoicesCount'  => $totalInvoicesCount,
            'totalCustomersCount' => $totalCustomersCount,
            'recentInvoices'      => $recentInvoices,
            'recentJournals'      => $recentJournals,
        ];

        return view('dashboard/index', $data);
    }
}
