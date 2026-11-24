<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\InvoiceLineModel;
use App\Models\CustomerModel;
use App\Models\ItemModel;
use App\Models\SettingModel;
use App\Models\ReceiptModel;
use App\Models\ReturnModel;
use App\Models\JournalModel;
use App\Libraries\JournalService;
use Config\Database;
use Exception;

class InvoiceController extends BaseController
{
    protected $invoiceModel;
    protected $lineModel;
    protected $customerModel;
    protected $itemModel;
    protected $settingModel;

    public function __construct()
    {
        $this->invoiceModel  = new InvoiceModel();
        $this->lineModel     = new InvoiceLineModel();
        $this->customerModel = new CustomerModel();
        $this->itemModel     = new ItemModel();
        $this->settingModel  = new SettingModel();
        helper('format');
    }

    public function index()
    {
        $invoices = $this->invoiceModel->getInvoiceWithCustomer();

        return view('invoices/index', [
            'title'    => 'Daftar Faktur Penjualan',
            'invoices' => $invoices,
        ]);
    }

    public function create()
    {
        $customers = $this->customerModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
        $items     = $this->itemModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        $defaultPkp     = (int) $this->settingModel->getVal('is_pkp', 1);
        $defaultTaxRate = (float) $this->settingModel->getVal('default_tax_rate', 12.00);

        $db = Database::connect();
        $autoNumber = generate_doc_number('INV', 'invoices', 'invoice_number', $db);

        return view('invoices/create', [
            'title'          => 'Buat Faktur Penjualan Baru',
            'customers'      => $customers,
            'items'          => $items,
            'autoNumber'     => $autoNumber,
            'defaultPkp'     => $defaultPkp,
            'defaultTaxRate' => $defaultTaxRate,
        ]);
    }

    public function store()
    {
        $db = Database::connect();

        $rules = [
            'customer_id'   => 'required|numeric',
            'invoice_date'  => 'required|valid_date',
            'payment_type'  => 'required|in_list[tunai,kredit]',
            'item_id.*'     => 'permit_empty',
            'item_name.*'   => 'required',
            'quantity.*'    => 'required|numeric|greater_than[0]',
            'unit_price.*'  => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $itemsPost = $this->request->getPost('item_name');
        if (empty($itemsPost) || !is_array($itemsPost)) {
            return redirect()->back()->withInput()->with('error', 'Minimal satu item layanan harus dimasukkan dalam faktur.');
        }

        $db->transBegin();

        try {
            $invoiceNumber = trim($this->request->getPost('invoice_number') ?? '');
            if (empty($invoiceNumber)) {
                $invoiceNumber = generate_doc_number('INV', 'invoices', 'invoice_number', $db);
            }

            $customerId  = (int) $this->request->getPost('customer_id');
            $invoiceDate = $this->request->getPost('invoice_date');
            $paymentType = $this->request->getPost('payment_type');
            $dueDate     = ($paymentType === 'kredit') ? $this->request->getPost('due_date') : null;
            $isPkp       = $this->request->getPost('is_pkp') !== null ? 1 : 0;
            $taxRate     = $isPkp ? (float) $this->request->getPost('tax_rate') : 0.00;
            $notes       = $this->request->getPost('notes');

            // Kalkulasi Baris Item
            $itemIds        = $this->request->getPost('item_id') ?? [];
            $itemNames      = $this->request->getPost('item_name') ?? [];
            $quantities     = $this->request->getPost('quantity') ?? [];
            $unitPrices     = $this->request->getPost('unit_price') ?? [];
            $discountTypes  = $this->request->getPost('discount_type') ?? [];
            $discountValues = $this->request->getPost('discount_value') ?? [];

            $subtotal      = 0.00;
            $discountTotal = 0.00;
            $linesData     = [];

            for ($i = 0; $i < count($itemNames); $i++) {
                $qty       = (float) ($quantities[$i] ?? 1);
                $price     = (float) ($unitPrices[$i] ?? 0);
                $rawTotal  = $qty * $price;
                $discType  = $discountTypes[$i] ?? 'none';
                $discVal   = (float) ($discountValues[$i] ?? 0);
                $discAmt   = 0.00;

                if ($discType === 'percentage') {
                    $discAmt = ($rawTotal * $discVal) / 100;
                } elseif ($discType === 'amount') {
                    $discAmt = min($rawTotal, $discVal);
                }

                $lineTotal = max(0, $rawTotal - $discAmt);

                $subtotal      += $rawTotal;
                $discountTotal += $discAmt;

                $linesData[] = [
                    'item_id'         => !empty($itemIds[$i]) ? (int) $itemIds[$i] : null,
                    'item_name'       => trim($itemNames[$i]),
                    'quantity'        => $qty,
                    'unit_price'      => $price,
                    'discount_type'   => $discType,
                    'discount_value'  => $discVal,
                    'discount_amount' => $discAmt,
                    'line_total'      => $lineTotal,
                ];
            }

            // Hitung Pajak dan Grand Total
            $taxableAmount = max(0, $subtotal - $discountTotal);
            $taxAmount     = $isPkp ? round(($taxableAmount * $taxRate) / 100, 2) : 0.00;
            $grandTotal    = $taxableAmount + $taxAmount;

            $initialStatus = ($paymentType === 'tunai') ? 'paid' : 'unpaid';
            $paidAmount    = ($paymentType === 'tunai') ? $grandTotal : 0.00;

            // 1. Simpan Header Faktur
            $invoiceData = [
                'invoice_number' => $invoiceNumber,
                'customer_id'    => $customerId,
                'created_by'     => session()->get('user_id'),
                'invoice_date'   => $invoiceDate,
                'due_date'       => $dueDate,
                'payment_type'   => $paymentType,
                'is_pkp'         => $isPkp,
                'tax_rate'       => $taxRate,
                'subtotal'       => $subtotal,
                'discount_total' => $discountTotal,
                'tax_amount'     => $taxAmount,
                'grand_total'    => $grandTotal,
                'paid_amount'    => $paidAmount,
                'status'         => $initialStatus,
                'process_status' => $this->request->getPost('process_status') ?: 'antrean',
                'notes'          => $notes,
            ];

            $this->invoiceModel->insert($invoiceData);
            $invoiceId = $this->invoiceModel->getInsertID();

            // 2. Simpan Lines
            foreach ($linesData as &$line) {
                $line['invoice_id'] = $invoiceId;
            }
            $this->lineModel->insertBatch($linesData);

            // 3. Automasi Jurnal Akuntansi (AIS Engine)
            $journalService = new JournalService();
            $journalService->recordInvoiceJournal($invoiceId);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Gagal memproses transaksi faktur. Database rollback dijalankan.');
            }

            $db->transCommit();
            return redirect()->to(base_url("invoices/{$invoiceId}"))->with('success', "Faktur Penjualan {$invoiceNumber} berhasil diterbitkan dan otomatis dijurnal ke buku besar.");
        } catch (Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $invoice = $this->invoiceModel->getInvoiceWithCustomer($id);
        if (!$invoice) {
            return redirect()->to(base_url('invoices'))->with('error', 'Faktur tidak ditemukan.');
        }

        $lines = $this->lineModel->where('invoice_id', $id)->findAll();

        $receiptModel = new ReceiptModel();
        $receipts = $receiptModel->where('invoice_id', $id)->orderBy('payment_date', 'ASC')->findAll();

        $returnModel = new ReturnModel();
        $returns = $returnModel->where('invoice_id', $id)->orderBy('return_date', 'ASC')->findAll();

        $db = Database::connect();
        $journals = $db->table('journals')
            ->where('ref_type', 'invoice')
            ->where('ref_id', $id)
            ->get()->getResultArray();

        $journalDetails = [];
        if (!empty($journals)) {
            $jIds = array_column($journals, 'id');
            $journalDetails = $db->table('journal_details')->whereIn('journal_id', $jIds)->get()->getResultArray();
        }

        return view('invoices/show', [
            'title'          => "Detail Faktur {$invoice['invoice_number']}",
            'invoice'        => $invoice,
            'lines'          => $lines,
            'receipts'       => $receipts,
            'returns'        => $returns,
            'journals'       => $journals,
            'journalDetails' => $journalDetails,
        ]);
    }

    public function printInvoice($id)
    {
        $invoice = $this->invoiceModel->getInvoiceWithCustomer($id);
        if (!$invoice) {
            return redirect()->to(base_url('invoices'))->with('error', 'Faktur tidak ditemukan.');
        }

        $lines = $this->lineModel->where('invoice_id', $id)->findAll();
        $companyName    = $this->settingModel->getVal('company_name', 'Web UMKM Jasa');
        $companyPhone   = $this->settingModel->getVal('company_phone', '-');
        $companyEmail   = $this->settingModel->getVal('company_email', '-');
        $companyAddress = $this->settingModel->getVal('company_address', '-');

        return view('invoices/print', [
            'invoice'        => $invoice,
            'lines'          => $lines,
            'companyName'    => $companyName,
            'companyPhone'   => $companyPhone,
            'companyEmail'   => $companyEmail,
            'companyAddress' => $companyAddress,
        ]);
    }

    public function printThermal($id)
    {
        $invoice = $this->invoiceModel->getInvoiceWithCustomer($id);
        if (!$invoice) {
            return redirect()->to(base_url('invoices'))->with('error', 'Faktur tidak ditemukan.');
        }

        $lines = $this->lineModel->where('invoice_id', $id)->findAll();
        $companyName    = $this->settingModel->getVal('company_name', 'FreshWash Laundry & Dry Cleaning');
        $companyPhone   = $this->settingModel->getVal('company_phone', '0812-9876-5432');
        $companyEmail   = $this->settingModel->getVal('company_email', 'halo@freshwash.id');
        $companyAddress = $this->settingModel->getVal('company_address', 'Jl. Kumbang No. 14, Babakan, Bogor Tengah');

        return view('invoices/print_thermal', [
            'invoice'        => $invoice,
            'lines'          => $lines,
            'companyName'    => $companyName,
            'companyPhone'   => $companyPhone,
            'companyEmail'   => $companyEmail,
            'companyAddress' => $companyAddress,
        ]);
    }

    public function updateProcessStatus($id)
    {
        $invoice = $this->invoiceModel->find($id);
        if (!$invoice) {
            return redirect()->to(base_url('invoices'))->with('error', 'Faktur tidak ditemukan.');
        }

        $newStatus = $this->request->getPost('process_status');
        $allowed = ['antrean', 'cuci', 'setrika', 'siap_ambil', 'diambil'];
        if (in_array($newStatus, $allowed)) {
            $this->invoiceModel->update($id, ['process_status' => $newStatus]);
            return redirect()->back()->with('success', "Status pengerjaan cucian faktur #{$invoice['invoice_number']} berhasil diperbarui menjadi: " . strtoupper(str_replace('_', ' ', $newStatus)));
        }

        return redirect()->back()->with('error', 'Status pengerjaan tidak valid.');
    }

    public function delete($id)
    {
        $invoice = $this->invoiceModel->find($id);
        if (!$invoice) {
            return redirect()->to(base_url('invoices'))->with('error', 'Faktur tidak ditemukan.');
        }

        $db = Database::connect();
        $db->transBegin();

        try {
            // 1. Hapus Jurnal terkait Faktur ini
            $journals = $db->table('journals')->where('ref_type', 'invoice')->where('ref_id', $id)->get()->getResultArray();
            foreach ($journals as $j) {
                $db->table('journal_details')->where('journal_id', $j['id'])->delete();
                $db->table('journals')->where('id', $j['id'])->delete();
            }

            // 2. Hapus Receipt & Jurnal Pelunasan terkait Faktur ini jika ada
            $receipts = $db->table('receipts')->where('invoice_id', $id)->get()->getResultArray();
            foreach ($receipts as $rcp) {
                $rJournals = $db->table('journals')->where('ref_type', 'receipt')->where('ref_id', $rcp['id'])->get()->getResultArray();
                foreach ($rJournals as $rj) {
                    $db->table('journal_details')->where('journal_id', $rj['id'])->delete();
                    $db->table('journals')->where('id', $rj['id'])->delete();
                }
            }
            $db->table('receipts')->where('invoice_id', $id)->delete();

            // 3. Hapus Return jika ada
            $returns = $db->table('returns')->where('invoice_id', $id)->get()->getResultArray();
            foreach ($returns as $ret) {
                $retJournals = $db->table('journals')->where('ref_type', 'return')->where('ref_id', $ret['id'])->get()->getResultArray();
                foreach ($retJournals as $retJ) {
                    $db->table('journal_details')->where('journal_id', $retJ['id'])->delete();
                    $db->table('journals')->where('id', $retJ['id'])->delete();
                }
                $db->table('return_lines')->where('return_id', $ret['id'])->delete();
            }
            $db->table('returns')->where('invoice_id', $id)->delete();

            // 4. Hapus Lines & Faktur
            $this->lineModel->where('invoice_id', $id)->delete();
            $this->invoiceModel->delete($id);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->to(base_url('invoices'))->with('error', 'Gagal membatalkan faktur.');
            }

            $db->transCommit();
            return redirect()->to(base_url('invoices'))->with('success', "Faktur {$invoice['invoice_number']} berhasil dihapus dan jurnal akuntansinya telah dibersihkan.");
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to(base_url('invoices'))->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
