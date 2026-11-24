<?php

if (!function_exists('format_rupiah')) {
    /**
     * Format angka ke Rupiah (Rp 1.000.000)
     */
    function format_rupiah($nominal, $withPrefix = true)
    {
        $formatted = number_format((float) $nominal, 0, ',', '.');
        return $withPrefix ? 'Rp ' . $formatted : $formatted;
    }
}

if (!function_exists('format_tanggal_indo')) {
    /**
     * Format tanggal ke format Indonesia (e.g. 03 Oktober 2026)
     */
    function format_tanggal_indo($dateString)
    {
        if (empty($dateString) || $dateString === '0000-00-00') {
            return '-';
        }

        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $timestamp = strtotime($dateString);
        $tgl = date('d', $timestamp);
        $bln = (int) date('m', $timestamp);
        $thn = date('Y', $timestamp);

        return $tgl . ' ' . ($bulanIndo[$bln] ?? '') . ' ' . $thn;
    }
}

if (!function_exists('generate_doc_number')) {
    /**
     * Generate auto document number with format: PREFIX-YYYYMM-0001
     */
    function generate_doc_number($prefix, $tableName, $fieldName, $db = null)
    {
        if (!$db) {
            $db = \Config\Database::connect();
        }

        $yearMonth = date('Ym');
        $searchPrefix = "{$prefix}-{$yearMonth}-";

        $builder = $db->table($tableName);
        $builder->like($fieldName, $searchPrefix, 'after');
        $builder->orderBy($fieldName, 'DESC');
        $lastRecord = $builder->get(1)->getRow();

        if ($lastRecord && !empty($lastRecord->$fieldName)) {
            $lastCode = $lastRecord->$fieldName;
            $parts = explode('-', $lastCode);
            $lastSeq = (int) end($parts);
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $yearMonth, $nextSeq);
    }
}
