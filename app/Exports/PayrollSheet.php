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

        // HEADER
        // =====================
        $header = [
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
            'Izin',
            'Terlambat',
            'BPJS Kesehatan KA',
            'BPJS JHT KA',
            'Voucher',
            'BPJS Kesehatan PT',
            'BPJS JHT PT',
            'Fee Sharing',
            'Insentif',
            'Churn',
            'Uang Makan',
        ];

        $header = array_merge(
            $header,
            $this->uniqueTunjangan,
            $this->uniquePotongan,
            ['Kasbon'],
            ['Total Gaji']
        );

        $this->headerRowCount = count($header);
        $rows = [];
        $totals = [];

        // ROW DATA
        // =====================
        foreach ($data as $item) {
            $tunjanganArray = collect(json_decode($item->tunjangan, true) ?? []);
            $potonganArray  = collect(json_decode($item->potongan, true) ?? []);

            $row = [
                $item->no_slip,
                $item->nama_karyawan,
                "'" . $item->nik,
                $item->tax_status,
                $item->jabatan,
                $item->periode,
                $item->tunjangan_jabatan,
                $item->gaji_pokok,
                ($item->lembur + $item->lembur_libur),
                $item->tunjangan_kebudayaan,
                $item->transport,
                $item->inov_reward,
                $item->izin,
                $item->terlambat,
                $item->bpjs,
                $item->bpjs_jht,
                $item->voucher,
                $item->bpjs_perusahaan,
                $item->bpjs_jht_perusahaan,
                $item->fee_sharing,
                $item->insentif,
                $item->churn,
                $item->uang_makan,
            ];

            foreach ($this->uniqueTunjangan as $nama) {
                $row[] = $tunjanganArray->firstWhere('nama', $nama)['nominal'] ?? 0;
            }

            foreach ($this->uniquePotongan as $nama) {
                $row[] = $potonganArray->firstWhere('nama', $nama)['nominal'] ?? 0;
            }

            $row[] = $item->kasbon ?? 0;
            // $row[] = $potonganMigrasi;

            $pendapatan =
                ($item->gaji_pokok ?? 0)
                + ($item->tunjangan_jabatan ?? 0)
                + (($item->lembur ?? 0) + ($item->lembur_libur ?? 0))
                + ($item->tunjangan_kebudayaan ?? 0)
                + ($item->transport ?? 0)
                + ($item->uang_makan ?? 0)
                + ($item->fee_sharing ?? 0)
                + ($item->insentif ?? 0)
                + ($item->inov_reward ?? 0);
            // dd($pendapatan);

            foreach ($this->uniqueTunjangan as $nama) {
                $pendapatan += $tunjanganArray->firstWhere('nama', $nama)['nominal'] ?? 0;
            }

            $potongan =
                ($item->izin ?? 0) + ($item->terlambat ?? 0) + ($item->churn ?? 0);

            $excludePotongan = ['pph 21', 'pph21', 'potongan kebudayaan'];
            foreach ($this->uniquePotongan as $nama) {
                if (in_array(strtolower($nama), $excludePotongan)) {
                    continue;
                }
                $potongan += $potonganArray->firstWhere('nama', $nama)['nominal'] ?? 0;
            }

            $totalGaji = $pendapatan - $potongan;

            $row[] = $totalGaji;

            foreach ($row as $i => $val) {
                if (is_numeric($val) && $i !== 2) {
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
        for ($i = 6; $i <= $this->headerRowCount; $i++) {
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
        $sheet->getStyle("A1:{$highestColumn}1")
            ->getFont()->setBold(true);

        for ($col = 1; $col <= $highestColIndex; $col++) {

            $cellValue = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            $header = strtolower(trim((string)$cellValue));

            $colLetter = Coordinate::stringFromColumnIndex($col);

            if (in_array($header, ['izin', 'churn', 'terlambat'])) {
                $color = 'FFFF0000';
            } elseif (in_array($header, [
                'terlambat',
                'bpjs kesehatan ka',
                'bpjs jht ka',
                'kasbon',
                'pph 21'
            ])) {
                $color = 'FF0070C0';
            } elseif (in_array($header, [
                'voucher',
                'bpjs kesehatan pt',
                'bpjs jht pt',
                'total gaji'
            ])) {
                $color = 'FFFFFF00';
            } else {
                $color = 'FF00B050';
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
