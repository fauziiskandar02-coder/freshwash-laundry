<?php

namespace App\Controllers;

use App\Models\ReturnModel;
use App\Models\ReturnLineModel;
use App\Models\InvoiceModel;
use App\Models\InvoiceLineModel;
use App\Libraries\JournalService;
use Config\Database;
use Exception;

class ReturnController extends BaseController
{
    protected $returnModel;
    protected $returnLineModel;
    protected $invoiceModel;
    protected $invoiceLineModel;

    public function __construct()
    {
        $this->returnModel      = new ReturnModel();
        $this->returnLineModel  = new ReturnLineModel();
        $this->invoiceModel     = new InvoiceModel();
        $this->invoiceLineModel = new InvoiceLineModel();
        helper('format');
    }

    public function index()
    {
        $returns = $this->returnModel->getReturnsWithDetails();
        return view('returns/index', [
            'title'   => 'Daftar Retur Penjualan',
            'returns' => $returns,
        ]);
    }

    public function create()
    {
        $db = Database::connect();
        $invoices = $db->table('invoices')
            ->select('invoices.*, customers.name as customer_name')
            ->join('customers', 'customers.id = invoices.customer_id', 'left')
            ->orderBy('invoices.invoice_date', 'DESC')
            ->get()->getResultArray();

        $autoNumber = generate_doc_number('RET', 'returns', 'return_number', $db);
        $selectedInvoiceId = $this->request->getGet('invoice_id');

        return view('returns/create', [
            'title'             => 'Proses Retur Penjualan Baru',
            'invoices'          => $invoices,
            'autoNumber'        => $autoNumber,
            'selectedInvoiceId' => $selectedInvoiceId,
        ]);
    }

    /**
     * AJAX endpoint untuk mengambil baris item faktur saat faktur dipilih di form retur
     */
    public function getInvoiceLinesJson($invoiceId)
    {
        $invoice = $this->invoiceModel->find($invoiceId);
        if (!$invoice) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invoice not found'])->setStatusCode(404);
        }

        $lines = $this->invoiceLineModel->where('invoice_id', $invoiceId)->findAll();
        return $this->response->setJSON([
            'status'     => 'success',
            'invoice'    => $invoice,
            'lines'      => $lines,
        ]);
    }

    public function store()
    {
        $db = Database::connect();

        $rules = [
            'invoice_id'    => 'required|numeric',
            'return_date'   => 'required|valid_date',
            'refund_method' => 'required|in_list[potong_piutang,cash]',
            'item_name.*'   => 'required',
            'quantity.*'    => 'required|numeric|greater_than[0]',
            'unit_price.*'  => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $invoiceId = (int) $this->request->getPost('invoice_id');
        $invoice = $this->invoiceModel->find($invoiceId);
        if (!$invoice) {
            return redirect()->back()->withInput()->with('error', 'Faktur Penjualan tidak valid.');
        }

        $itemNames  = $this->request->getPost('item_name') ?? [];
        $itemIds    = $this->request->getPost('item_id') ?? [];
        $quantities = $this->request->getPost('quantity') ?? [];
        $unitPrices = $this->request->getPost('unit_price') ?? [];

        if (empty($itemNames)) {
            return redirect()->back()->withInput()->with('error', 'Minimal satu item harus dipilih untuk retur.');
        }

        $db->transBegin();

        try {
            $returnNumber = trim($this->request->getPost('return_number') ?? '');
            if (empty($returnNumber)) {
                $returnNumber = generate_doc_number('RET', 'returns', 'return_number', $db);
            }

            $subtotal = 0.00;
            $linesData = [];

            for ($i = 0; $i < count($itemNames); $i++) {
                $qty = (float) ($quantities[$i] ?? 1);
                $price = (float) ($unitPrices[$i] ?? 0);
                $lineTotal = $qty * $price;
                $subtotal += $lineTotal;

                $linesData[] = [
                    'item_id'    => !empty($itemIds[$i]) ? (int) $itemIds[$i] : null,
                    'item_name'  => trim($itemNames[$i]),
                    'quantity'   => $qty,
                    'unit_price' => $price,
                    'line_total' => $lineTotal,
                ];
            }

            // Hitung Pajak Retur sesuai status PKP faktur asal
            $isPkp = (int) $invoice['is_pkp'];
            $taxRate = $isPkp ? (float) $invoice['tax_rate'] : 0.00;
            $taxAmount = $isPkp ? round(($subtotal * $taxRate) / 100, 2) : 0.00;
            $grandTotal = $subtotal + $taxAmount;

            $returnData = [
                'return_number' => $returnNumber,
                'invoice_id'    => $invoiceId,
                'created_by'    => session()->get('user_id'),
                'return_date'   => $this->request->getPost('return_date'),
                'subtotal'      => $subtotal,
                'tax_amount'    => $taxAmount,
                'grand_total'   => $grandTotal,
                'refund_method' => $this->request->getPost('refund_method'),
                'notes'         => $this->request->getPost('notes'),
            ];

            $this->returnModel->insert($returnData);
            $returnId = $this->returnModel->getInsertID();

            foreach ($linesData as &$line) {
                $line['return_id'] = $returnId;
            }
            $this->returnLineModel->insertBatch($linesData);

            // Eksekusi Journal Service Retur
            $journalService = new JournalService();
            $journalService->recordReturnJournal($returnId);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Gagal memproses transaksi retur.');
            }

            $db->transCommit();
            return redirect()->to(base_url('returns'))->with('success', "Retur Penjualan {$returnNumber} berhasil diproses dan jurnal akuntansi telah disesuaikan.");
        } catch (Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
