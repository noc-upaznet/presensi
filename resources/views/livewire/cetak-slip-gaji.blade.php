<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            width: 100%;
        }

        .row {
            display: flex;
        }

        .col {
            width: 50%;
            padding: 10px;
        }

        .table {
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .border {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .mt-5 {
            margin-top: 3rem;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Slip Gaji</h2>
        <h4>{{ $nama }} - {{ $jabatan }}</h4>
        <p>Periode: {{ $periode }}</p>
    </div>

    <div class="content">
        <div class="row">
            <div class="col">
                <h4>Pendapatan</h4>
                <table class="table">
                    <tr>
                        <td>Gaji Pokok</td>
                        <td class="text-right">Rp{{ number_format($gaji_pokok) }}</td>
                    </tr>
                    <tr>
                        <td>Tunjangan Jabatan</td>
                        <td class="text-right">Rp{{ number_format($tunjangan_jabatan) }}</td>
                    </tr>
                    <tr>
                        <td>Uang Makan</td>
                        <td class="text-right">Rp{{ number_format($uang_makan) }}</td>
                    </tr>
                </table>
            </div>

            <div class="col">
                <h4>Potongan</h4>
                <table class="table">
                    <tr>
                        <td>BPJS Kesehatan</td>
                        <td class="text-right">-Rp{{ number_format($bpjs_kesehatan) }}</td>
                    </tr>
                    <tr>
                        <td>BPJS TK JHT</td>
                        <td class="text-right">-Rp{{ number_format($bpjs_tk_jht) }}</td>
                    </tr>
                    <tr>
                        <td>BPJS TK JP</td>
                        <td class="text-right">-Rp{{ number_format($bpjs_tk_jp) }}</td>
                    </tr>
                    <tr>
                        <td>PPh21</td>
                        <td class="text-right">-Rp{{ number_format($pph21) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="text-center mt-5">
            <h3>Total Penerimaan: Rp{{ number_format($gaji_bersih) }}</h3>
        </div>
    </div>

</body>

</html>