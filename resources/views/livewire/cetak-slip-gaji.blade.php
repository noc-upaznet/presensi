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
            font-family: Arial, Helvetica, sans-serif;
            margin: 20px auto;
            max-width: 900px;
            color: #000;
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
            color: darkblue;
            font-size: 16px;
            margin: 0;
            padding: 0;
        }

        .company-info p {
            margin: 2px 0;
        }

        .slip-info {
            text-align: right;
            font-weight: bold;
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
            background-color: #f3f3f3;
            padding: 6px;
            text-align: left;
            font-size: 12px;
        }

        td {
            padding: 4px 6px;
            border-bottom: 1px solid #ddd;
        }

        th.green,
        th.blue {
            background-color: #4CAF50;
            /* green */
            color: white;
        }

        th.blue {
            background-color: #2196F3;
            /* blue */
        }

        .summary-box {
            border: 2px solid #000;
            padding: 12px;
            margin: 15px auto;
            width: 260px;
            text-align: center;
        }

        .footer {
            font-size: 10px;
            margin-top: 20px;
            color: #666;
        }

        .footer div {
            display: inline-block;
            width: 50%;
            text-align: center;
        }
    </style>
</head>

<body>

    <table class="header-slip">
        <tr>
            <td style="width: 70px;">
                <img src="./assets/img/logo.png" alt="Logo" class="logo">
            </td>
            <td style="width: 65%;">
                <div class="company-info">
                    <h2>PT DIMENSI JARINGAN BERSINAR</h2>
                    <p>Tulungagung, Jawa Timur, Indonesia</p>
                    <p><a href="https://www.upaznet.com" style="color: blue;">www.upaznet.com</a></p>
                    <p class="slip-number">Slip Number&nbsp;&nbsp;&nbsp;: DJB/HR/SG/25/IV/013</p>
                </div>
            </td>
            <td class="slip-info">
                SLIP GAJI<br>
                <span style="font-weight: normal;">April 2025</span>
            </td>
        </tr>
    </table>

    <table style="width: 100%; font-size: 12px; margin-top: 5px; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; padding: 2px 2px 2px 20px; border: none;"><strong>Payroll cut off:</strong> 1 Mar –
                31 Mar 2025</td>
            <td style="width: 50%; padding: 2px; border: none;"><strong>Jabatan:</strong> Admin HR</td>
        </tr>
        <tr>
            <td style="padding: 2px 2px 2px 20px; border: none;"><strong>ID / Nama:</strong> UZ01 / Nadia Safira</td>
            <td style="padding: 2px; border: none;"><strong>PTKP:</strong> TK/0</td>
        </tr>
        <tr>
            <td style="padding: 2px 2px 2px 20px; border: none;"><strong>Departemen:</strong> HR & GA</td>
            <td style="padding: 2px; border: none;"><strong>NPWP:</strong> -</td>
        </tr>
        <tr>
            <td style="padding: 2px 2px 2px 20px; border: none;"><strong>Grade / Level:</strong> Staf</td>
            <td style="padding: 2px; border: none;"><strong>Tanggal Masuk:</strong> 22 Jan 2022</td>
        </tr>
    </table>


    <table style="width: calc(100% - 40px); margin: 10px 20px 0 20px; border-collapse: collapse;">
        <thead>
            <tr>
                <th class="green" colspan="2" style="text-align: left;">Earnings</th>
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


    <table style="width: calc(100% - 40px); margin: 10px 20px 0 20px; border-collapse: collapse;">
        <thead>
            <tr>
                <th colspan="2" style="text-align: left; background-color: #dc3545; color: white;">Deductions</th>

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


    <table style="width: calc(100% - 40px); margin: 10px 20px 0 20px; border-collapse: collapse; font-size: 14px;">
        <tr>
            <td colspan="2" style="padding: 0;">
                <div
                    style="background-color: #007bff; color: white; padding: 8px 16px; font-weight: bold; font-size: 16px; display: flex; justify-content: space-between;">
                    <span>Total Take Home Pay</span>
                    <span>Rp2.655.000</span>
                </div>
            </td>
        </tr>
    </table>


    <table
        style="width: calc(100% - 40px); margin: 20px 20px 0 20px; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px;">
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


    <div class="footer" style="width: calc(100% - 40px); margin: 30px 20px 0 20px; font-size: 12px;">
        <div style="text-align: left;">Generated on: 30/04/2025 10:23</div>
    </div>


</body>

</html>