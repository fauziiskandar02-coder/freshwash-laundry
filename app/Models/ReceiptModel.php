<?php

namespace App\Models;

use CodeIgniter\Model;

class ReceiptModel extends Model
{
    protected $table            = 'receipts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'receipt_number', 'invoice_id', 'created_by', 'payment_date',
        'amount', 'payment_method', 'account_code', 'notes',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps    = true;

    public function getReceiptsWithDetails($id = null)
    {
        $builder = $this->select('receipts.*, invoices.invoice_number, invoices.grand_total as invoice_total, customers.name as customer_name, users.name as creator_name, coa.account_name as cash_account_name')
            ->join('invoices', 'invoices.id = receipts.invoice_id', 'left')
            ->join('customers', 'customers.id = invoices.customer_id', 'left')
            ->join('users', 'users.id = receipts.created_by', 'left')
            ->join('chart_of_accounts coa', 'coa.account_code = receipts.account_code', 'left');

        if ($id !== null) {
            return $builder->where('receipts.id', $id)->first();
        }

        return $builder->orderBy('receipts.payment_date', 'DESC')->orderBy('receipts.id', 'DESC')->findAll();
    }
}
