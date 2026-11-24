<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        // 1. Settings
        $settings = [
            ['setting_key' => 'company_name', 'setting_value' => 'Solusi Kreatif UMKM Indonesia', 'description' => 'Nama Perusahaan / Bisnis Jasa'],
            ['setting_key' => 'company_phone', 'setting_value' => '0812-3456-7890', 'description' => 'Nomor Telepon'],
            ['setting_key' => 'company_email', 'setting_value' => 'admin@solusikreatif.id', 'description' => 'Email Perusahaan'],
            ['setting_key' => 'company_address', 'setting_value' => 'Jl. Sudirman No. 45, Jakarta Selatan', 'description' => 'Alamat Lengkap Perusahaan'],
            ['setting_key' => 'is_pkp', 'setting_value' => '1', 'description' => 'Status PKP Global (1 = PKP, 0 = Non-PKP)'],
            ['setting_key' => 'default_tax_rate', 'setting_value' => '12.00', 'description' => 'Tarif PPN Standar (%)'],
            ['setting_key' => 'currency_symbol', 'setting_value' => 'Rp', 'description' => 'Simbol Mata Uang'],
        ];
        $this->db->table('settings')->insertBatch($settings);

        // 2. Users (admin: admin123, kasir: kasir123)
        $users = [
            [
                'name' => 'Administrator UMKM',
                'email' => 'admin@umkm.id',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
                'role' => 'admin',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Kasir Penjualan',
                'email' => 'kasir@umkm.id',
                'password' => password_hash('kasir123', PASSWORD_BCRYPT),
                'role' => 'kasir',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('users')->insertBatch($users);

        // 3. Chart of Accounts
        $coa = [
            ['account_code' => '1100', 'account_name' => 'Kas Kasir / Tunai', 'account_type' => 'asset', 'normal_balance' => 'debit', 'is_active' => 1],
            ['account_code' => '1110', 'account_name' => 'Bank Operasional', 'account_type' => 'asset', 'normal_balance' => 'debit', 'is_active' => 1],
            ['account_code' => '1200', 'account_name' => 'Piutang Usaha', 'account_type' => 'asset', 'normal_balance' => 'debit', 'is_active' => 1],
            ['account_code' => '2100', 'account_name' => 'PPN Keluaran', 'account_type' => 'liability', 'normal_balance' => 'credit', 'is_active' => 1],
            ['account_code' => '4100', 'account_name' => 'Pendapatan Jasa Penjualan', 'account_type' => 'revenue', 'normal_balance' => 'credit', 'is_active' => 1],
            ['account_code' => '4110', 'account_name' => 'Diskon Penjualan', 'account_type' => 'revenue', 'normal_balance' => 'debit', 'is_active' => 1],
            ['account_code' => '4200', 'account_name' => 'Retur Penjualan Jasa', 'account_type' => 'revenue', 'normal_balance' => 'debit', 'is_active' => 1],
        ];
        $this->db->table('chart_of_accounts')->insertBatch($coa);

        // 4. Customers
        $customers = [
            [
                'code' => 'CUST-001',
                'name' => 'PT Maju Bersama Digital',
                'phone' => '0811-2233-4455',
                'email' => 'finance@majubersama.com',
                'address' => 'Gedung Menara Prima Lt. 8, Jakarta',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'CUST-002',
                'name' => 'CV Sumber Rejeki Abadi',
                'phone' => '0813-8899-0011',
                'email' => 'contact@sumberrejeki.co.id',
                'address' => 'Jl. Pemuda No. 12, Bandung',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'CUST-003',
                'name' => 'Ibu Rina Wulandari (Personal)',
                'phone' => '0857-4433-2211',
                'email' => 'rina.wulandari@gmail.com',
                'address' => 'Komplek Permata Hijau Blok C-4, Surabaya',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('customers')->insertBatch($customers);

        // 5. Items (Services/Jasa)
        $items = [
            [
                'code' => 'SRV-001',
                'name' => 'Jasa Konsultasi Keuangan & Akuntansi',
                'description' => 'Pendampingan laporan keuangan bulanan dan pembukuan',
                'unit' => 'bulan',
                'sell_price' => 2500000.00,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'SRV-002',
                'name' => 'Jasa Pembuatan Website & Branding',
                'description' => 'Desain landing page modern responsif dan company profile',
                'unit' => 'paket',
                'sell_price' => 5000000.00,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'SRV-003',
                'name' => 'Jasa Maintenance Sistem & IT Support',
                'description' => 'Dukungan teknis berkala sistem dan cloud hosting',
                'unit' => 'bulan',
                'sell_price' => 1200000.00,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'SRV-004',
                'name' => 'Jasa Pelatihan & Workshop Staff',
                'description' => 'Pelatihan operasional kasir & pencatatan digital 1 hari',
                'unit' => 'sesi',
                'sell_price' => 1800000.00,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('items')->insertBatch($items);
    }
}
