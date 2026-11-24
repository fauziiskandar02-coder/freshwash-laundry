<?php

namespace App\Models;

use CodeIgniter\Model;

class ChartOfAccountModel extends Model
{
    protected $table            = 'chart_of_accounts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['account_code', 'account_name', 'account_type', 'normal_balance', 'is_active', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;
}
