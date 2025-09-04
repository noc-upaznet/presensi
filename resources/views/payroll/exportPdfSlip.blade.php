

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Slip Gaji</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
            margin: 20px auto;
            max-width: 900px;
            color: #111827;
            font-size: 12px;
        }

        .logo {
            height: 30px;
        }

        .header-slip {
            width: 100%;
            padding: 10px;
            font-size: 12px;
            color: #000;
        }

        .header-slip td {
            vertical-align: top;
        }

        .header-slip .logo {
            width: 60px;
            height: auto;
        }

        .company-info {
            font-family: Arial, sans-serif;
            padding-left: 10px;
        }

        .company-info h2 {
            color: #1E3A8A; /* biru tua */
            font-size: 18px;
            margin: 0;
            padding: 0;
        }

        .company-info p {
            margin: 2px 0;
        }

        .slip-info {
            text-align: right;
            font-weight: bold;
            color: #1E3A8A;
        }

        .slip-number {
            margin-top: 5px;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #D1D5DB; /* abu silver */
            padding: 6px;
            text-align: left;
            font-size: 12px;
            color: #000;
            font-weight: bold;
        }

        td {
            padding: 4px 6px;
            border-bottom: 1px solid #D1D5DB;
            color: #111827;
        }

        th.green {
            background-color: #10B981; /* hijau emerald */
            color: white;
        }

        th.blue {
            background-color: #1E3A8A; /* biru tua */
            color: white;
        }

        .signature {
            /* margin-top: 0px; */
            width: 100%;
        }

        .signature td {
            text-align: center;
            padding-top: 20px;
        }

        .summary-box {
            border: 2px solid #10B981;
            background-color: #ECFDF5; /* hijau muda */
            color: #065F46;
            padding: 12px;
            margin: 15px auto;
            width: 260px;
            text-align: center;
        }

        .text-right {
            text-align: right;
            color: #111827;
        }

        .footer {
            font-size: 10px;
            margin-top: 20px;
            color: #6B7280; /* abu gelap */
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background: #fff;
            border-top: 1px solid #bebebe;
            z-index: 10;
            padding: 8px 0;
        }

        .footer div {
            display: inline-block;
            width: 50%;
            text-align: center;
        }

        .watermark {
            position: fixed;
            top: 45%;
            left: 45%;
            width: 400px;
            opacity: 0.05;
            transform: translate(-50%, -50%);
            z-index: 0;
            pointer-events: none;
        }
        .content-wrapper {
            position: relative;
            z-index: 1;
        }
    </style>

</head>

<body>
    <img src="./assets/img/logo.png" alt="Watermark Logo" class="watermark">
    <div class="content-wrapper">
        <table class="header-slip">
            <tr>
                <td style="width: 70px;">
                    <img src="./assets/img/logo.png" alt="Logo" class="logo">
                </td>
                <td style="width: 65%;">
                    <div class="company-info">
                        <h2>PT DIMENSI JARINGAN BERSINAR</h2>
                        <p>Tulungagung, Jawa Timur, Indonesia</p>
                        {{-- <p><a href="https://www.upaznet.com" style="color: blue;">www.upaznet.com</a></p> --}}
                        <p class="slip-number">Slip Number&nbsp;&nbsp;&nbsp;: {{ $data->no_slip }}</p>
                    </div>
                </td>
                <td class="slip-info">
                    SLIP GAJI<br>
                    <span style="font-weight: normal;">{{ \Carbon\Carbon::parse($data->periode)->locale('id')->translatedFormat('F Y') }}</span>
                </td>
            </tr>
        </table>

        <table style="width: 100%; font-size: 12px; margin-top: 5px; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; padding: 2px 2px 2px 20px; border: none;"><strong>NPK/NIP:</strong>
                    {{ $data->nip_karyawan }}</td>
            </tr>
            <tr>
                <td style="padding: 2px 2px 2px 20px; border: none;"><strong>Nama:</strong> {{ $data->getKaryawan->nama_karyawan }}</td>
            </tr>
            <tr>
                <td style="padding: 2px 2px 2px 20px; border: none;"><strong>Departemen:</strong> {{ $data->divisi }}</td>
            </tr>
        </table>

        <table style="width: calc(100% - 40px); margin: 15px 20px 0 20px; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px;">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: left;">KEHADIRAN</th>
                </tr>
            </thead>
            @php
                $rekap = json_decode($data->rekap, true);
            @endphp
            <tbody>
                <tr>
                    <td>Terlambat</td>
                    <td style="text-align: right;">{{ $rekap['terlambat'] ?? 0 }} Hari</td>
                </tr>
                <tr>
                    <td>Ijin</td>
                    <td style="text-align: right;">{{ $rekap['izin'] ?? 0 }} Hari</td>
                </tr>
                <tr>
                    <td>Cuti</td>
                    <td style="text-align: right;">{{ $rekap['cuti'] ?? 0 }} Hari</td>
                </tr>
                <tr>
                    <td>Kehadiran</td>
                    <td style="text-align: right;">{{ $rekap['kehadiran'] ?? 0 }} Hari</td>
                </tr>
                <tr>
                    <td>Lembur</td>
                    <td style="text-align: right;">{{ $rekap['lembur'] ?? 0 }} Jam</td>
                </tr>
            </tbody>
        </table>

        @php
            $tunjangan = json_decode($data->tunjangan, true);
            $potongan = json_decode($data->potongan, true);
        @endphp

        <table style="width: calc(100% - 40px); margin: 10px 20px 0 20px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: left;">PENDAPATAN</th>
                </tr>
            </thead>
            @php
                $lembur = $data->lembur + $data->lembur_libur;
                $totalPendapatan = $data->gaji_pokok
                    + $data->tunjangan_jabatan
                    + $data->tunjangan_kebudayaan
                    + $lembur
                    + $data->uang_makan
                    + $data->transport
                    + $data->fee_sharing
                    + $data->insentif
                    + $data->inov_reward;
            @endphp

            <tbody>
                <tr>
                    <td>Upah Pokok</td>
                    <td class="text-right">Rp. {{ number_format($data->gaji_pokok) }}</td>
                </tr>
                <tr>
                    <td>Tunjangan Jabatan</td>
                    <td class="text-right">Rp. {{ number_format($data->tunjangan_jabatan) }}</td>
                </tr>
                <tr>
                    <td>Tunjangan Kebudayaan</td>
                    <td class="text-right">Rp. {{ number_format($data->tunjangan_kebudayaan) }}</td>
                </tr>
                @foreach ($tunjangan as $item)
                    @if (!empty($item['nama']) && $item['nominal'] >= 0)
                        @php
                            $totalPendapatan += $item['nominal'];
                        @endphp
                        <tr>
                            <td>{{ $item['nama'] }}</td>
                            <td class="text-right">Rp. {{ number_format($item['nominal']) }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td>Upah Lembur</td>
                    <td class="text-right">Rp. {{ number_format($lembur) }}</td>
                </tr>
                <tr>
                    <td>Uang Makan</td>
                    <td class="text-right">Rp. {{ number_format($data->uang_makan) }}</td>
                </tr>
                <tr>
                    <td>Transport</td>
                    <td class="text-right">Rp. {{ number_format($data->transport) }}</td>
                </tr>
                @if ($data->inov_reward > 0)
                    <tr>
                        <td>Inovation Reward</td>
                        <td class="text-right">Rp. {{ number_format($data->inov_reward) }}</td>
                    </tr>
                @endif
                <tr>
                    <td>Fee Sharing</td>
                    <td class="text-right">Rp. {{ number_format($data->fee_sharing) }}</td>
                </tr>

                @if ($data->divisi == 'Sales Marketing' || $data->divisi == 'Sales & Marketing')
                    <tr>
                        <td>Insentif</td>
                        <td class="text-right">Rp. {{ number_format($data->insentif) }}</td>
                    </tr>
                @endif

                <tr style="border-bottom: 1px solid blue;">
                    <th>Total Pendapatan</th>
                    <th class="text-right">Rp. {{ number_format($totalPendapatan) }}</th>
                </tr>
            </tbody>

        </table>

        <table style="width: calc(100% - 40px); margin: 10px 20px 0 20px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: left;">POTONGAN</th>
                </tr>
            </thead>
            @php
                $totalPotongan = $data->bpjs_jht + $data->bpjs + $data->izin + $data->terlambat;
            @endphp

            <tbody>
                <tr>
                    <td>Potongan Izin</td>
                    <td class="text-right">Rp. {{ number_format($data->izin) }}</td>
                </tr>
                <tr>
                    <td>Potongan Terlambat</td>
                    <td class="text-right">Rp. {{ number_format($data->terlambat) }}</td>
                </tr>
                @foreach ($potongan as $item)
                    @if (!empty($item['nama']) && $item['nominal'] >= 0)
                        @php
                            $totalPotongan += $item['nominal'];
                        @endphp
                        <tr>
                            <td>{{ $item['nama'] }}</td>
                            <td class="text-right">Rp. {{ number_format($item['nominal']) }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td>JHT</td>
                    <td class="text-right">Rp. {{ number_format($data->bpjs_jht) }}</td>
                </tr>
                <tr>
                    <td>Kesehatan</td>
                    <td class="text-right">Rp. {{ number_format($data->bpjs) }}</td>
                </tr>
                @if ($data->jabatan == 'Branch Manager' || $data->entitas == 'UNB')
                    <tr>
                        <td>JHT PT</td>
                        <td class="text-right">Rp. {{ number_format($data->bpjs_jht_perusahaan) }}</td>
                    </tr>
                @endif
                <tr style="border-bottom: 1px solid red;">
                    <th>Total Potongan</th>
                    <th class="text-right">Rp. {{ number_format($totalPotongan) }}</th>
                </tr>
            </tbody>
        </table>

        <table style="width: calc(100% - 40px); margin: 0px 20px 0 20px; border-collapse: collapse; font-size: 14px;">
            <tr>
                <td colspan="2" style="padding: 0;">
                    <table style="width: 100%; font-weight: bold;">
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #4bf43b;">Upah Diterima</td>
                            <td style="text-align: right; padding: 8px; border-bottom: 1px solid #4bf43b;">Rp. {{ number_format($data->total_gaji) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table style="width: calc(100% - 40px); margin: -10px 20px 0 20px; border-collapse: collapse; font-size: 14px;">
            <tr>
                <td colspan="2" style="padding: 0;">
                <table style="width: 100%;">
                    <tr>
                    <td style="padding: 8px;">Terbilang</td>
                    <td style="text-align: right; padding: 8px;"><i>{{ terbilang($data->total_gaji) }} Rupiah</i></td>
                    </tr>
                </table>
                </td>
            </tr>
        </table>

        <table class="signature">
            <tr>
                <td style="border: none">
                    Dibuat oleh:
                </td>
                <td style="border: none">
                    Tulungagung, {{ \Carbon\Carbon::parse($data->tanggal)->locale('id')->translatedFormat('d F Y') }}<br>
                    Diterima oleh:
                </td>
            </tr>
            <tr>
                <td style="vertical-align: bottom; height: 50px; border: none;">
                    <img src="./assets/img/ttd_hrd.jpg" alt="Tanda Tangan HRD" style="width: 120px; margin-top: -30px;"><br>
                    <strong>M. Amin Syukron</strong><br>HRD
                </td>
                <td style="vertical-align: bottom; height: 50px; border: none;">
                    <br><br>
                    <strong>{{ $data->getKaryawan->nama_karyawan }}</strong><br>Karyawan
                </td>
            </tr>    </table>


        <div class="footer" style="width: calc(100% - 40px); margin: 20px 20px 0 20px; border-top: 1px solid #bebebe; font-size: 12px;">
            <div style="text-align: left;">Generated on: {{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y H:i:s') }}</div>
        </div>
    </div>
</body>

</html>