<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table            = 'invoices';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'invoice_number', 'customer_id', 'created_by', 'invoice_date', 'due_date',
        'payment_type', 'is_pkp', 'tax_rate', 'subtotal', 'discount_total',
        'tax_amount', 'grand_total', 'paid_amount', 'status', 'process_status', 'notes',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps    = true;

    public function getInvoiceWithCustomer($id = null)
    {
        $builder = $this->select('invoices.*, customers.name as customer_name, customers.code as customer_code, customers.phone as customer_phone, customers.email as customer_email, customers.address as customer_address, users.name as creator_name')
            ->join('customers', 'customers.id = invoices.customer_id', 'left')
            ->join('users', 'users.id = invoices.created_by', 'left');

        if ($id !== null) {
            return $builder->where('invoices.id', $id)->first();
        }

        return $builder->orderBy('invoices.invoice_date', 'DESC')->orderBy('invoices.id', 'DESC')->findAll();
    }
}
