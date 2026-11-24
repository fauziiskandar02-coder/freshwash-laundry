<?php

namespace App\Controllers;

class InfoController extends BaseController
{
    public function index()
    {
        $members = [
            [
                'name'    => 'Fauzi Iskandar',
                'nim'     => 'J0414241332',
                'role'    => 'Ketua Tim & Cloud Architecture',
                'task'    => 'Moderator Presentasi, Dashboard KPI, Three.js 3D Mascot, TiDB Cloud DB & Vercel Serverless Deployment',
                'image'   => 'fauzi.jpg',
                'badge'   => 'Lead Developer',
                'avatar'  => 'Yu Ishigami (Cowo Rambut Hitam)',
                'color'   => '#0284c7'
            ],
            [
                'name'    => "Muhammad Febryan Ar-rifa'i",
                'nim'     => 'J0414241324',
                'role'    => 'Master Data & Store Config',
                'task'    => 'Master Pelanggan & Member, Master Jasa & Layanan Cuci, Pengaturan Toko & Simulasi Pajak PKP/PPN',
                'image'   => 'febryan.jpg',
                'badge'   => 'Master Data Spec.',
                'avatar'  => 'Miyuki Shirogane (Blonde Spec.)',
                'color'   => '#d97706'
            ],
            [
                'name'    => 'Intan Nuraini',
                'nim'     => 'J0414241042',
                'role'    => 'POS Cashier & Nota Workflow',
                'task'    => 'Input Nota Cucian POS, Tracking Status Workflow (Antrean -> Selesai), Cetak Struk POS 58mm & 80mm Thermal',
                'image'   => 'intan.jpg',
                'badge'   => 'POS & Workflow Spec.',
                'avatar'  => 'Kaguya Shinomiya (Red Ribbon Spec.)',
                'color'   => '#e11d48'
            ],
            [
                'name'    => 'Mabel Zoya Jeldan',
                'nim'     => 'J0414241236',
                'role'    => 'Cash Receipts & Accounting Journals',
                'task'    => 'Penerimaan Kas (Pelunasan Piutang DP), Retur Cuci Ulang Garansi, dan Otomasi Jurnal Double-Entry',
                'image'   => 'mabel.jpg',
                'badge'   => 'Accounting Spec.',
                'avatar'  => 'Miko Iino (Twin-tails Spec.)',
                'color'   => '#7c3aed'
            ],
            [
                'name'    => 'Jelikha Salsabilla',
                'nim'     => 'J0414241076',
                'role'    => 'Financial Reports & Analytics',
                'task'    => 'Laporan Omzet Penjualan (Harian/Bulanan), Kartu Piutang Member, Umur Nota, dan Closing Kas Harian',
                'image'   => 'jelikha.jpg',
                'badge'   => 'Reporting Spec.',
                'avatar'  => 'Chika Fujiwara (Pink Ribbon Spec.)',
                'color'   => '#db2777'
            ],
        ];

        $isLoggedIn = (bool) session()->get('is_logged_in');

        $data = [
            'title'       => 'Info Kelompok 5 & Tentang Sistem',
            'course'      => 'Komputerisasi Akuntansi (KOMAK TI) & Aplikasi Akuntansi Berbasis Web (AABW)',
            'classGroup'  => 'Kelas C / P2',
            'institution' => 'Sekolah Vokasi — IPB University (SV IPB)',
            'projectName' => 'FreshWash Laundry & Dry Cleaning (SIA Siklus Pendapatan UMKM)',
            'members'     => $members,
            'isLoggedIn'  => $isLoggedIn,
        ];

        if ($isLoggedIn) {
            return view('info/index', $data);
        }

        return view('info/public', $data);
    }
}
