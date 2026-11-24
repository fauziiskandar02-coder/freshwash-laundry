<?php

namespace App\Models;

use CodeIgniter\Model;

class JournalModel extends Model
{
    protected $table            = 'journals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['journal_number', 'journal_date', 'ref_type', 'ref_id', 'description', 'created_at'];
    protected $useTimestamps    = false;

    public function getJournalsWithDetails($limit = 50)
    {
        $journals = $this->orderBy('journal_date', 'DESC')->orderBy('id', 'DESC')->findAll($limit);
        if (empty($journals)) {
            return [];
        }

        $journalIds = array_column($journals, 'id');
        $db = \Config\Database::connect();
        $details = $db->table('journal_details')->whereIn('journal_id', $journalIds)->get()->getResultArray();

        $grouped = [];
        foreach ($details as $d) {
            $grouped[$d['journal_id']][] = $d;
        }

        foreach ($journals as &$j) {
            $j['details'] = $grouped[$j['id']] ?? [];
        }

        return $journals;
    }
}
