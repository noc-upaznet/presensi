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
            font-family: 'Segoe UI', Tahoma, Arial, Helvetica, sans-serif;
            margin: 20px auto;
            max-width: 900px;
            color: #000;
            font-size: 12px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .logo {
            height: 30px;
        }

        h2 {
            margin: 2px 0;
            font-size: 16px;
        }

        .header .logo-container {
            text-align: center;
        }

        .header .logo-container h2 {
            margin: 0;
        }

        .header .logo-container p {
            margin: 0;
            font-size: 12px;
        }

        .header .info {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .info {
            margin-top: 10px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            line-height: 1.4;
        }

        .section {
            margin-top: 20px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th {
            background-color: #f3f3f3;
            padding: 6px;
            text-align: left;
            font-size: 12px;
        }

        td {
            padding: 4px 6px;
            border-bottom: 1px solid #ddd;
        }

        .summary-box {
            border: 2px solid #000;
            padding: 12px;
            margin: 15px auto;
            width: 260px;
            text-align: center;
        }

        .summary-box h2 {
            margin: 0;
            font-size: 18px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            margin-top: 20px;
            color: #666;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 6px;
            margin-top: 10px;
        }

        p {
            margin: 2px 0;
        }

        /* Menyelaraskan "Benefits" dan "Attendance Summary" */
        .section.grid-2 {
            display: flex;
            justify-content: space-between;
            gap: 40px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="logo-container">
            <div style="display: flex; align-items: center; text-align: left; line-height: 1.5;">
                <img src="./assets/img/upaznet-logonew.png" alt="Logo" style="height: 40px; margin-right: 15px;">
                <h2 style="margin: 0; font-size: 14px;">PT Dimensi Jaringan Bersinar</h2>
            </div>

            <div style="flex: 2; text-align: right;">
                <h3 style="margin: 0; font-size: 14px; color: navy; font-weight: bold;">SLIP GAJI</h3>
            </div>
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse; line-height: 1.5; text-align: justify;">
        <tr>
            <td style="border: none;"><strong>Payroll cut off:</strong> 1 Mar – 31 Mar 2025</td>
            <td style="border: none;"><strong>Jabatan:</strong> Admin HR</td>
        </tr>
        <tr>
            <td style="border: none;"><strong>ID / Nama:</strong> UZ01 / Nadia Safira</td>
            <td style="border: none;"><strong>PTKP:</strong> TK/0</td>
        </tr>
        <tr>
            <td style="border: none;"><strong>Departemen:</strong> HR & GA</td>
            <td style="border: none;"><strong>NPWP:</strong> -</td>
        </tr>
        <tr>
            <td style="border: none;"><strong>Grade / Level:</strong> Staf</td>
            <td style="border: none;"><strong>Tanggal Masuk:</strong> 22 Jan 2022</td>
        </tr>
    </table>

    <div class="section grid-2">
        <div>
            <table>
                <thead>
                    <tr>
                        <th colspan="2">Earnings</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Gaji Pokok</td>
                        <td>Rp2.000.000</td>
                    </tr>
                    <tr>
                        <td>Tunjangan Jabatan</td>
                        <td>Rp500.000</td>
                    </tr>
                    <tr>
                        <td>Uang Makan</td>
                        <td>Rp260.000</td>
                    </tr>
                    <tr>
                        <td>Lembur</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Bonus/PPh 21 DTP</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <th>Rp2.760.000</th>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <table>
                <thead>
                    <tr>
                        <th colspan="2">Deductions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>BPJS Kesehatan</td>
                        <td>Rp20.000</td>
                    </tr>
                    <tr>
                        <td>BPJS TK JHT</td>
                        <td>Rp40.000</td>
                    </tr>
                    <tr>
                        <td>BPJS TK JP</td>
                        <td>Rp30.000</td>
                    </tr>
                    <tr>
                        <td>PPh 21</td>
                        <td>Rp15.000</td>
                    </tr>
                    <tr>
                        <td>Potongan Absen</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <th>Rp105.000</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="summary-box">
        <p>Total Take Home Pay</p>
        <h2>Rp2.655.000</h2>
    </div>

    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px;">
        <tr>
            <td style="vertical-align: top; width: 50%; padding: 0 8px;">
                <div style="font-weight: bold; border-bottom: 1px solid #ccc; padding-bottom: 4px; margin-bottom: 6px;">
                    Benefits</div>
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr>
                        <td style="border: none;">Total PPh 21 DTP Received</td>
                        <td style="text-align: right; border: none;">Rp0</td>
                    </tr>
                    <tr>
                        <td style="border: none;">JKK</td>
                        <td style="text-align: right; border: none;">Rp50.000</td>
                    </tr>
                    <tr>
                        <td style="border: none;">JKM</td>
                        <td style="text-align: right; border: none;">Rp50.000</td>
                    </tr>
                    <tr>
                        <td style="border: none;">JHT Company</td>
                        <td style="text-align: right; border: none;">Rp100.000</td>
                    </tr>
                    <tr>
                        <td style="border: none;">JP Company</td>
                        <td style="text-align: right; border: none;">Rp80.000</td>
                    </tr>
                    <tr>
                        <td style="border: none;">BPJS Kesehatan Company</td>
                        <td style="text-align: right; border: none;">Rp120.000</td>
                    </tr>
                </table>
            </td>
            <td style="vertical-align: top; width: 50%; padding: 0 8px;">
                <div style="font-weight: bold; border-bottom: 1px solid #ccc; padding-bottom: 4px; margin-bottom: 6px;">
                    Attendance Summary</div>
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr>
                        <td style="border: none;">Working Days</td>
                        <td style="text-align: right; border: none;">23</td>
                    </tr>
                    <tr>
                        <td style="border: none;">Days Off</td>
                        <td style="text-align: right; border: none;">0</td>
                    </tr>
                    <tr>
                        <td style="border: none;">National Holiday</td>
                        <td style="text-align: right; border: none;">0</td>
                    </tr>
                    <tr>
                        <td style="border: none;">Company Holiday</td>
                        <td style="text-align: right; border: none;">0</td>
                    </tr>
                    <tr>
                        <td style="border: none;">Attendance Code</td>
                        <td style="text-align: right; border: none;">H:1 A:0 SL:2</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>


    <div class="footer" style="display: flex; justify-content: space-between;">
        <div>Generated by Zemangat</div>
        <div>Generated on: 30/04/2025 10:23</div>
    </div>

</body>

</html>