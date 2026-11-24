<?php

namespace App\Models;

use CodeIgniter\Model;

class ReturnLineModel extends Model
{
    protected $table            = 'return_lines';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'return_id', 'item_id', 'item_name', 'quantity', 'unit_price', 'line_total'
    ];
}
