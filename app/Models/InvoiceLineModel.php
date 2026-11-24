<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceLineModel extends Model
{
    protected $table            = 'invoice_lines';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'invoice_id', 'item_id', 'item_name', 'quantity', 'unit_price',
        'discount_type', 'discount_value', 'discount_amount', 'line_total'
    ];
}
