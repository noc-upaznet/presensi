<?php

namespace App\Exports;

use App\Models\PayrollModel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PayrollSheet implements FromArray, WithTitle, WithStyles, ShouldAutoSize
{
    protected $periode;
    protected $status;
    protected $entitas;

    protected $uniqueTunjangan = [];
    protected $uniquePotongan = [];

    public function __construct($periode, $status, $entitas)
    {
        // dd($periode, $status, $entitas);

        $this->periode = $periode;
        $this->status = $status;
        $this->entitas = $entitas;
        // dd([
        //     'entitas' => $this->entitas,
        //     'periode' => $this->periode,
        // ]);
    }

    protected $headerRowCount = 1;

    public function array(): array
    {
        $query = PayrollModel::join('data_karyawan', 'payroll.karyawan_id', '=', 'data_karyawan.id')
            ->where('payroll.periode', $this->periode)
            ->where('payroll.entitas_id', $this->entitas);

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
            'data_karyawan.nip_karyawan',
        )->get();
        // dd($data);

        // Ambil semua nama tunjangan dan potongan
        foreach ($data as $item) {
            $tunjanganArray = json_decode($item->tunjangan, true) ?? [];
            foreach ($tunjanganArray as $t) {
                $nama = $t['nama'] ?? '';
                if ($nama && !in_array($nama, $this->uniqueTunjangan)) {
                    $this->uniqueTunjangan[] = $nama;
                }
            }

            $potonganArray = json_decode($item->potongan, true) ?? [];
            foreach ($potonganArray as $p) {
                $nama = $p['nama'] ?? '';
                if ($nama && !in_array($nama, $this->uniquePotongan)) {
                    $this->uniquePotongan[] = $nama;
                }
            }
        }

        // Header tetap
        $headerTetap = [
            'No Slip',
            'Nama Karyawan',
            'NIP',
            'Divisi',
            'Periode',
            'Tunjangan Jabatan',
            'Gaji Pokok',
            'Lembur',
            'Tunjangan Kebudayaan',
            'Transport',
            // 'Inovation Reward',
            'Izin',
            'Terlambat',
            'BPJS Kesehatan KA',
            'BPJS JHT KA',
            'BPJS Kesehatan PT',
            'BPJS JHT PT',
            'Fee Sharing',
            'Insentif',
            'Uang Makan',
            'Total Gaji',
        ];

        $headerTunjangan = array_map(fn($t) => "$t", $this->uniqueTunjangan);
        $headerPotongan = array_map(fn($p) => "$p", $this->uniquePotongan);

        $indexTotalGaji = array_search('Total Gaji', $headerTetap);
        $headerAwal = array_slice($headerTetap, 0, $indexTotalGaji); // Sebelum Total Gaji
        $headerAkhir = array_slice($headerTetap, $indexTotalGaji + 1); // Setelah Total Gaji (jika ada)

        $header = array_merge(
            $headerAwal,
            $headerTunjangan,
            $headerPotongan,
            ['Total Gaji'],
            $headerAkhir // opsional, kalau memang ada kolom setelah 'Total Gaji'
        );

        // Hitung jumlah kolom untuk styling
        $this->headerRowCount = count($header);
        $totals = []; // kosong dulu
        $rows = [];

        foreach ($data as $item) {
            $tunjanganArray = collect(json_decode($item->tunjangan, true) ?? []);
            $potonganArray = collect(json_decode($item->potongan, true) ?? []);

            $row = [
                $item->no_slip,
                $item->nama_karyawan,
                $item->nip_karyawan,
                $item->divisi,
                $item->periode,
                $item->tunjangan_jabatan,
                $item->gaji_pokok,
                ($item->lembur + $item->lembur_libur) ?? 0,
                $item->tunjangan_kebudayaan ?? 0,
                $item->transport ?? 0,
                $item->izin ?? 0,
                $item->terlambat ?? 0,
                $item->bpjs ?? 0,
                $item->bpjs_jht ?? 0,
                $item->bpjs_perusahaan ?? 0,
                $item->bpjs_jht_perusahaan ?? 0,
                $item->fee_sharing ?? 0,
                $item->insentif ?? 0,
                $item->uang_makan ?? 0,
                // $item->inov_reward ?? 0,
            ];

            // Tambah nilai tunjangan
            foreach ($this->uniqueTunjangan as $nama) {
                $match = $tunjanganArray->firstWhere('nama', $nama);
                $row[] = $match['nominal'] ?? 0;
            }

            // Tambah nilai potongan
            foreach ($this->uniquePotongan as $nama) {
                $match = $potonganArray->firstWhere('nama', $nama);
                $row[] = $match['nominal'] ?? 0;
            }

            $voucher = $potonganArray->firstWhere('nama', 'Voucher')['nominal'] ?? 0;
            $izin = $item->izin ?? 0;
            // dd($izin);
            // Jumlahkan semua nilai numeric dari row (kecuali identitas di depan)
            $pendapatan = array_sum(array_filter($row, fn($v, $i) =>
                !in_array($i, [0, 1, 2, 3, 4, 10, 12, 13, 14, 15]) && is_numeric($v) // skip
            , ARRAY_FILTER_USE_BOTH));

            // Total gaji = pendapatan - izin
            $totalGaji = $pendapatan - $voucher - $izin;
            // dd($totalGaji);

            $row[] = $totalGaji;

            // Hitung total per kolom
            $skipIndex = [0, 1, 2, 3, 4]; // kolom identitas tidak dijumlah
            foreach ($row as $i => $value) {
                if (!in_array($i, $skipIndex) && is_numeric($value)) {
                    $totals[$i] = ($totals[$i] ?? 0) + $value;
                }
            }

            $rows[] = $row;
        }

        // Buat footer
        $footer = [];

        if (count($rows) > 0) {
            foreach ($rows[0] as $i => $value) {
                if (isset($totals[$i]) && $totals[$i] > 0) {
                    $footer[$i] = $totals[$i];
                } else {
                    $footer[$i] = ($i === 0 ? 'TOTAL' : '');
                }
            }

            $rows[] = $footer;
        }

        return array_merge([$header], $rows);
    }

    public function title(): string
    {
        return $this->status === 'titip' ? 'Karyawan Titip' : 'Karyawan Tetap';
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFont()->setBold(true);

        // Border untuk semua sel yang terisi
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $highestCol . '1')->getFont()->setBold(true);

        // Bold & background untuk footer total
        $sheet->getStyle("A{$highestRow}:{$highestCol}{$highestRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFEFEFEF'],
            ],
        ]);

        // Border semua
        $sheet->getStyle("A1:{$highestCol}{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ]
            ]
        ]);
    }
}