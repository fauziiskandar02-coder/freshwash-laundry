<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProcessStatusToInvoices extends Migration
{
    public function up()
    {
        $fields = [
            'process_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'antrean',
                'after'      => 'status',
            ],
        ];

        // Check if column already exists before adding
        if (!$this->db->fieldExists('process_status', 'invoices')) {
            $this->forge->addColumn('invoices', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('process_status', 'invoices')) {
            $this->forge->dropColumn('invoices', 'process_status');
        }
    }
}
