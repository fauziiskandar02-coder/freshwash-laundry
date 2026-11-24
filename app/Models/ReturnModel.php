<?php

namespace App\Models;

use CodeIgniter\Model;

class ReturnModel extends Model
{
    protected $table            = 'returns';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'return_number', 'invoice_id', 'created_by', 'return_date',
        'subtotal', 'tax_amount', 'grand_total', 'refund_method', 'notes',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps    = true;

    public function getReturnsWithDetails($id = null)
    {
        $builder = $this->select('returns.*, invoices.invoice_number, customers.name as customer_name, users.name as creator_name')
            ->join('invoices', 'invoices.id = returns.invoice_id', 'left')
            ->join('customers', 'customers.id = invoices.customer_id', 'left')
            ->join('users', 'users.id = returns.created_by', 'left');

        if ($id !== null) {
            return $builder->where('returns.id', $id)->first();
        }

        return $builder->orderBy('returns.return_date', 'DESC')->orderBy('returns.id', 'DESC')->findAll();
    }
}
