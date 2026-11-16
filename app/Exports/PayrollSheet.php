<?php

namespace App\Exports;

use App\Models\PayrollModel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class PayrollSheet implements FromArray, WithTitle, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $periode;
    protected $status;
    protected $entitas;

    protected $uniqueTunjangan = [];
    protected $uniquePotongan = [];
    protected $headerRowCount = 1;

    public function __construct($periode, $status, $entitas)
    {
        $this->periode = $periode;
        $this->status  = $status;
        $this->entitas = $entitas;
    }

    public function array(): array
    {
        $query = PayrollModel::join('data_karyawan', 'payroll.karyawan_id', '=', 'data_karyawan.id')
            ->where('payroll.periode', $this->periode)
            ->where('payroll.entitas_id', $this->entitas)
            ->orderBy('data_karyawan.nik', 'asc');

        if ($this->status == 'titip') {
            $query->where('payroll.titip', 1);
        } else {
            $query->where(function ($q) {
                $q->whereNull('payroll.titip')->orWhere('payroll.titip', 0);
            });
        }

        $data = $query->select(
            'payroll.*',
            'data_karyawan.nama_karyawan',
            'data_karyawan.nik',
            'data_karyawan.jabatan',
            'data_karyawan.tax_status'
        )->get();

        foreach ($data as $item) {
            foreach (json_decode($item->tunjangan, true) ?? [] as $t) {
                if (!empty($t['nama']) && !in_array($t['nama'], $this->uniqueTunjangan)) {
                    $this->uniqueTunjangan[] = $t['nama'];
                }
            }
            foreach (json_decode($item->potongan, true) ?? [] as $p) {
                if (!empty($p['nama']) && !in_array($p['nama'], $this->uniquePotongan)) {
                    $this->uniquePotongan[] = $p['nama'];
                }
            }
        }

        // HEADER: KELOMPOKKAN BERDASARKAN WARNA
        // =====================================

        // 1. Hijau (Info Dasar & Pendapatan)
        $headerGreen = [
            'No Slip',
            'Nama Karyawan',
            'NIK',
            'Status Pajak',
            'Jabatan',
            'Periode',
            'Tunjangan Jabatan',
            'Gaji Pokok',
            'Lembur',
            'Tunjangan Kebudayaan',
            'Transport',
            'Inovation Reward',
            'Fee Sharing',
            'Insentif',
            'Uang Makan',
            'Tunjangan Standar Coc',
            'Tunjangan Kinerja',
            'Insentif Tot',
            'Achievement',
        ];
        $headerGreen = array_merge($headerGreen, $this->uniqueTunjangan);

        // 2. Biru (Potongan/Kewajiban Ka)
        $headerBlue = ['BPJS Kesehatan KA', 'BPJS JHT KA', 'Kasbon'];

        // 3. Merah (Potongan Indisipliner)
        $headerRed = ['Izin', 'Terlambat', 'Churn'];

        // 4. Kuning (Benefit PT & Total)
        $headerYellow = ['Voucher', 'BPJS Kesehatan PT', 'BPJS JHT PT'];

        // Alokasikan Potongan dinamis ke warnanya masing-masing
        foreach ($this->uniquePotongan as $p) {
            $pLower = strtolower($p);
            if (in_array($pLower, ['izin', 'churn', 'terlambat', 'potongan kebudayaan'])) {
                if (!in_array($p, $headerRed)) $headerRed[] = $p;
            } elseif (in_array($pLower, ['bpjs kesehatan ka', 'bpjs jht ka', 'kasbon', 'pph 21'])) {
                if (!in_array($p, $headerBlue)) $headerBlue[] = $p;
            } elseif (in_array($pLower, ['voucher', 'bpjs kesehatan pt', 'bpjs jht pt', 'total gaji'])) {
                if (!in_array($p, $headerYellow)) $headerYellow[] = $p;
            } else {
                if (!in_array($p, $headerGreen)) $headerGreen[] = $p; // Default (lainnya masuk ke hijau)
            }
        }

        // Tambahkan Total Gaji di paling ujung kuning
        $headerYellow[] = 'Total Gaji';

        // Gabungkan seluruh header sesuai urutan warnanya (HIJAU -> BIRU -> MERAH -> KUNING)
        $header = array_merge($headerGreen, $headerBlue, $headerRed, $headerYellow);

        $this->headerRowCount = count($header);
        $rows = [];
        $totals = [];

        // ROW DATA: PEMETAAN ASSOCIATIVE
        // ==============================
        foreach ($data as $item) {
            $tunjanganArray = collect(json_decode($item->tunjangan, true) ?? []);
            $potonganArray  = collect(json_decode($item->potongan, true) ?? []);

            // Buat penampung key-value mapping agar nilainya otomatis mengisi urutan header
            $rowData = [];
            $rowData['No Slip'] = $item->no_slip;
            $rowData['Nama Karyawan'] = $item->nama_karyawan;
            $rowData['NIK'] = "'" . $item->nik;
            $rowData['Status Pajak'] = $item->tax_status;
            $rowData['Jabatan'] = $item->jabatan;
            $rowData['Periode'] = $item->periode;
            $rowData['Tunjangan Jabatan'] = $item->tunjangan_jabatan;
            $rowData['Gaji Pokok'] = $item->gaji_pokok;
            $rowData['Lembur'] = ($item->lembur + $item->lembur_libur);
            $rowData['Tunjangan Kebudayaan'] = $item->tunjangan_kebudayaan;
            $rowData['Transport'] = $item->transport;
            $rowData['Inovation Reward'] = $item->inov_reward;
            $rowData['Izin'] = $item->izin;
            $rowData['Terlambat'] = $item->terlambat;
            $rowData['BPJS Kesehatan KA'] = $item->bpjs;
            $rowData['BPJS JHT KA'] = $item->bpjs_jht;
            $rowData['Voucher'] = $item->voucher;
            $rowData['BPJS Kesehatan PT'] = $item->bpjs_perusahaan;
            $rowData['BPJS JHT PT'] = $item->bpjs_jht_perusahaan;
            $rowData['Fee Sharing'] = $item->fee_sharing;
            $rowData['Insentif'] = $item->insentif;
            $rowData['Churn'] = $item->churn;
            $rowData['Uang Makan'] = $item->uang_makan;
            $rowData['Tunjangan Standar Coc'] = $item->tunjangan_coc;
            $rowData['Tunjangan Kinerja'] = $item->tunjangan_kinerja;
            $rowData['Insentif Tot'] = $item->insentif_tot;
            $rowData['Achievement'] = $item->achievement;
            $rowData['Kasbon'] = $item->kasbon ?? 0;

            foreach ($this->uniqueTunjangan as $nama) {
                $rowData[$nama] = $tunjanganArray->firstWhere('nama', $nama)['nominal'] ?? 0;
            }

            foreach ($this->uniquePotongan as $nama) {
                // Set only if not defined (mencegah overwriting properties utama)
                if (!isset($rowData[$nama])) {
                    $rowData[$nama] = $potonganArray->firstWhere('nama', $nama)['nominal'] ?? 0;
                }
            }

            // Perhitungan Gaji (Logika asli tetap dipertahankan)
            $pendapatan =
                ($item->gaji_pokok ?? 0)
                + ($item->tunjangan_jabatan ?? 0)
                + (($item->lembur ?? 0) + ($item->lembur_libur ?? 0))
                + ($item->tunjangan_kebudayaan ?? 0)
                + ($item->transport ?? 0)
                + ($item->uang_makan ?? 0)
                + ($item->fee_sharing ?? 0)
                + ($item->insentif ?? 0)
                + (intval($item->tunjangan_coc) ?? 0)
                + (intval($item->tunjangan_kinerja) ?? 0)
                + (intval($item->insentif_tot) ?? 0)
                + (intval($item->achievement) ?? 0)
                + ($item->inov_reward ?? 0);

            foreach ($this->uniqueTunjangan as $nama) {
                $pendapatan += $tunjanganArray->firstWhere('nama', $nama)['nominal'] ?? 0;
            }

            $potongan = ($item->izin ?? 0) + ($item->terlambat ?? 0) + ($item->churn ?? 0);

            $excludePotongan = ['pph 21', 'pph21', 'potongan kebudayaan'];
            foreach ($this->uniquePotongan as $nama) {
                if (in_array(strtolower($nama), $excludePotongan)) {
                    continue;
                }
                $potongan += $potonganArray->firstWhere('nama', $nama)['nominal'] ?? 0;
            }

            $totalGaji = $pendapatan - $potongan;
            $rowData['Total Gaji'] = $totalGaji;

            // Membangun array final per-baris sesuai dengan urutan struktur $header
            $row = [];
            foreach ($header as $colName) {
                $val = $rowData[$colName] ?? 0;
                $row[] = $val;
            }

            // Kalkulasi akumulasi total untuk di baris paling bawah (FOOTER)
            $excludeTotals = ['No Slip', 'Nama Karyawan', 'NIK', 'Status Pajak', 'Jabatan', 'Periode'];
            foreach ($row as $i => $val) {
                if (is_numeric($val) && !in_array($header[$i], $excludeTotals)) {
                    $totals[$i] = ($totals[$i] ?? 0) + $val;
                }
            }

            $rows[] = $row;
        }

        // FOOTER
        // =====================
        if (!empty($rows)) {
            $footer = [];
            foreach ($rows[0] as $i => $val) {
                $footer[$i] = $i === 0 ? 'TOTAL' : ($totals[$i] ?? '');
            }
            $rows[] = $footer;
        }

        return array_merge([$header], $rows);
    }

    public function columnFormats(): array
    {
        $formats = [];
        // Mulai memformat mata uang (Rp) mulai dari index kolom ke-7 (Kolom 'Gaji Pokok')
        for ($i = 7; $i <= $this->headerRowCount; $i++) {
            $formats[Coordinate::stringFromColumnIndex($i)] = '"Rp" #,##0';
        }
        return $formats;
    }

    public function title(): string
    {
        return $this->status === 'titip' ? 'Karyawan Titip' : 'Karyawan Tetap';
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestColIndex = Coordinate::columnIndexFromString($highestColumn);
        $highestRow = $sheet->getHighestRow();

        // Bold header
        $sheet->getStyle("A1:{$highestColumn}1")->getFont()->setBold(true);

        for ($col = 1; $col <= $highestColIndex; $col++) {

            $cellValue = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            $header = strtolower(trim((string)$cellValue));

            $colLetter = Coordinate::stringFromColumnIndex($col);

            if (in_array($header, ['izin', 'churn', 'terlambat', 'potongan kebudayaan'])) {
                $color = 'FFFF0000'; // MERAH
            } elseif (in_array($header, [
                'bpjs kesehatan ka',
                'bpjs jht ka',
                'kasbon',
                'pph 21'
            ])) {
                $color = 'FF0070C0'; // BIRU
            } elseif (in_array($header, [
                'voucher',
                'bpjs kesehatan pt',
                'bpjs jht pt',
                'total gaji'
            ])) {
                $color = 'FFFFFF00'; // KUNING
            } else {
                $color = 'FF00B050'; // HIJAU
            }

            $sheet->getStyle("{$colLetter}1")->getFill()->setFillType(
                \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
            )->getStartColor()->setARGB($color);
        }

        // Footer
        if ($highestRow > 1) {
            $sheet->getStyle("A{$highestRow}:{$highestColumn}{$highestRow}")
                ->getFont()->setBold(true);

            $sheet->getStyle("A{$highestRow}:{$highestColumn}{$highestRow}")
                ->getFill()->setFillType(
                    \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
                )->getStartColor()->setARGB('FFEFEFEF');
        }
    }
}
