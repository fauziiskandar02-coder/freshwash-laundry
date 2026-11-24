<?php

namespace App\Controllers;

use App\Models\ReceiptModel;
use App\Models\InvoiceModel;
use App\Models\ChartOfAccountModel;
use App\Libraries\JournalService;
use Config\Database;
use Exception;

class ReceiptController extends BaseController
{
    protected $receiptModel;
    protected $invoiceModel;
    protected $coaModel;

    public function __construct()
    {
        $this->receiptModel = new ReceiptModel();
        $this->invoiceModel = new InvoiceModel();
        $this->coaModel     = new ChartOfAccountModel();
        helper('format');
    }

    public function index()
    {
        $receipts = $this->receiptModel->getReceiptsWithDetails();
        return view('receipts/index', [
            'title'    => 'Penerimaan Kas & Pelunasan Piutang',
            'receipts' => $receipts,
        ]);
    }

    public function create()
    {
        // Hanya tampilkan faktur kredit yang belum lunas
        $db = Database::connect();
        $unpaidInvoices = $db->table('invoices')
            ->select('invoices.*, customers.name as customer_name, (invoices.grand_total - invoices.paid_amount) as remaining_balance')
            ->join('customers', 'customers.id = invoices.customer_id', 'left')
            ->whereIn('invoices.status', ['unpaid', 'partial'])
            ->orderBy('invoices.invoice_date', 'ASC')
            ->get()->getResultArray();

        $cashAccounts = $this->coaModel
            ->whereIn('account_code', ['1100', '1110'])
            ->where('is_active', 1)
            ->findAll();

        $autoNumber = generate_doc_number('RCP', 'receipts', 'receipt_number', $db);

        // Cek jika ada invoice_id dari query string (tombol "Bayar" dari halaman faktur)
        $selectedInvoiceId = $this->request->getGet('invoice_id');

        return view('receipts/create', [
            'title'             => 'Catat Penerimaan Kas',
            'unpaidInvoices'    => $unpaidInvoices,
            'cashAccounts'      => $cashAccounts,
            'autoNumber'        => $autoNumber,
            'selectedInvoiceId' => $selectedInvoiceId,
        ]);
    }

    public function store()
    {
        $db = Database::connect();

        $rules = [
            'invoice_id'     => 'required|numeric',
            'payment_date'   => 'required|valid_date',
            'amount'         => 'required|numeric|greater_than[0]',
            'payment_method' => 'required|in_list[cash,transfer,other]',
            'account_code'   => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $invoiceId = (int) $this->request->getPost('invoice_id');
        $amount    = (float) $this->request->getPost('amount');

        $invoice = $this->invoiceModel->find($invoiceId);
        if (!$invoice) {
            return redirect()->back()->withInput()->with('error', 'Faktur Penjualan tidak valid.');
        }

        $remaining = (float)$invoice['grand_total'] - (float)$invoice['paid_amount'];
        if ($amount > ($remaining + 0.01)) {
            return redirect()->back()->withInput()->with('error', 'Jumlah pembayaran melebihi sisa piutang (Sisa: ' . format_rupiah($remaining) . ').');
        }

        $db->transBegin();

        try {
            $receiptNumber = trim($this->request->getPost('receipt_number') ?? '');
            if (empty($receiptNumber)) {
                $receiptNumber = generate_doc_number('RCP', 'receipts', 'receipt_number', $db);
            }

            $receiptData = [
                'receipt_number' => $receiptNumber,
                'invoice_id'     => $invoiceId,
                'created_by'     => session()->get('user_id'),
                'payment_date'   => $this->request->getPost('payment_date'),
                'amount'         => $amount,
                'payment_method' => $this->request->getPost('payment_method'),
                'account_code'   => $this->request->getPost('account_code'),
                'notes'          => $this->request->getPost('notes'),
            ];

            $this->receiptModel->insert($receiptData);
            $receiptId = $this->receiptModel->getInsertID();

            // Eksekusi Journal Service
            $journalService = new JournalService();
            $journalService->recordReceiptJournal($receiptId);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Gagal memproses pelunasan kas.');
            }

            $db->transCommit();
            return redirect()->to(base_url('receipts'))->with('success', "Penerimaan Kas {$receiptNumber} berhasil dicatat dan piutang telah berkurang.");
        } catch (Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
