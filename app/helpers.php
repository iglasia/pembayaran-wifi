<?php

if (!function_exists('indonesian_currency')) {
    /**
     * Mengubah format mata uang menjadi format rupiah Indonesia.
     *
     * @param integer
     * @return String
     */
    function indonesian_currency(int $value): String
    {
        return 'Rp' . number_format($value, 2, ',', '.');
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key = null)
    {
        $setting = \App\Models\Setting::first();
        
        if (!$setting) {
            return null;
        }
        
        if ($key) {
            return $setting->$key ?? null;
        }
        
        return $setting;
    }
}

if (!function_exists('convert_month_to_indonesian')) {
    function convert_month_to_indonesian($month)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $months[intval($month)] ?? $month;
    }
}
