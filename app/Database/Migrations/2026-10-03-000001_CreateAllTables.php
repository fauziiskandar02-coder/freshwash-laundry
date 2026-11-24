<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAllTables extends Migration
{
    public function up()
    {
        // 1. Users
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role' => ['type' => 'ENUM', 'constraint' => ['admin', 'kasir'], 'default' => 'kasir'],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true);

        // 2. Settings
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'setting_key' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'setting_value' => ['type' => 'TEXT', 'null' => true],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('settings', true);

        // 3. Chart of Accounts
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'account_code' => ['type' => 'VARCHAR', 'constraint' => 20, 'unique' => true],
            'account_name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'account_type' => ['type' => 'ENUM', 'constraint' => ['asset', 'liability', 'equity', 'revenue', 'expense']],
            'normal_balance' => ['type' => 'ENUM', 'constraint' => ['debit', 'credit'], 'default' => 'debit'],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('chart_of_accounts', true);

        // 4. Customers
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => 30, 'unique' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'address' => ['type' => 'TEXT', 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('customers', true);

        // 5. Items (Services/Jasa)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => 30, 'unique' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'description' => ['type' => 'TEXT', 'null' => true],
            'unit' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'transaksi'],
            'sell_price' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('items', true);

        // 6. Invoices
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'invoice_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'customer_id' => ['type' => 'INT', 'unsigned' => true],
            'created_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'invoice_date' => ['type' => 'DATE'],
            'due_date' => ['type' => 'DATE', 'null' => true],
            'payment_type' => ['type' => 'ENUM', 'constraint' => ['tunai', 'kredit'], 'default' => 'tunai'],
            'is_pkp' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'tax_rate' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => '0.00'],
            'subtotal' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'discount_total' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'tax_amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'grand_total' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'paid_amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'status' => ['type' => 'ENUM', 'constraint' => ['unpaid', 'partial', 'paid'], 'default' => 'unpaid'],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('invoices', true);

        // 7. Invoice Lines
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'invoice_id' => ['type' => 'INT', 'unsigned' => true],
            'item_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'item_name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'quantity' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '1.00'],
            'unit_price' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'discount_type' => ['type' => 'ENUM', 'constraint' => ['none', 'percentage', 'amount'], 'default' => 'none'],
            'discount_value' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'discount_amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'line_total' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('invoice_lines', true);

        // 8. Receipts
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'receipt_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'invoice_id' => ['type' => 'INT', 'unsigned' => true],
            'created_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'payment_date' => ['type' => 'DATE'],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'payment_method' => ['type' => 'ENUM', 'constraint' => ['cash', 'transfer', 'other'], 'default' => 'cash'],
            'account_code' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => '1100'],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('receipts', true);

        // 9. Returns
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'return_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'invoice_id' => ['type' => 'INT', 'unsigned' => true],
            'created_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'return_date' => ['type' => 'DATE'],
            'subtotal' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'tax_amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'grand_total' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'refund_method' => ['type' => 'ENUM', 'constraint' => ['potong_piutang', 'cash'], 'default' => 'potong_piutang'],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('returns', true);

        // 10. Return Lines
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'return_id' => ['type' => 'INT', 'unsigned' => true],
            'item_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'item_name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'quantity' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '1.00'],
            'unit_price' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'line_total' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('return_lines', true);

        // 11. Journals
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'journal_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'journal_date' => ['type' => 'DATE'],
            'ref_type' => ['type' => 'ENUM', 'constraint' => ['invoice', 'receipt', 'return', 'manual']],
            'ref_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('journals', true);

        // 12. Journal Details
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'journal_id' => ['type' => 'INT', 'unsigned' => true],
            'account_code' => ['type' => 'VARCHAR', 'constraint' => 20],
            'account_name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'debit' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            'credit' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('journal_details', true);
    }

    public function down()
    {
        $this->forge->dropTable('journal_details', true);
        $this->forge->dropTable('journals', true);
        $this->forge->dropTable('return_lines', true);
        $this->forge->dropTable('returns', true);
        $this->forge->dropTable('receipts', true);
        $this->forge->dropTable('invoice_lines', true);
        $this->forge->dropTable('invoices', true);
        $this->forge->dropTable('items', true);
        $this->forge->dropTable('customers', true);
        $this->forge->dropTable('chart_of_accounts', true);
        $this->forge->dropTable('settings', true);
        $this->forge->dropTable('users', true);
    }
}
