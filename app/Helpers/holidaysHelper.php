<?php
    function cekHariLibur($tanggal)
    {
        $path = storage_path('app/libur.json');
        if (!file_exists($path)) return false;

        $data = json_decode(file_get_contents($path), true);
        return $data[$tanggal] ?? false;
    }
?>