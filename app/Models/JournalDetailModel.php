<?php

namespace App\Models;

use CodeIgniter\Model;

class JournalDetailModel extends Model
{
    protected $table            = 'journal_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['journal_id', 'account_code', 'account_name', 'debit', 'credit'];
}
