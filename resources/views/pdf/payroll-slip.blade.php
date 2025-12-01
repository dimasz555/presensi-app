<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $payroll->period_label }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            padding: 15px;
        }

        .header {
            background: linear-gradient(135deg, #D81D76, #AE1679);
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            margin-bottom: 12px;
            text-align: center;
        }

        .logo-container {
            float: left;
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .header-content {
            overflow: hidden;
            text-align: center;
        }

        .title {
            color: black;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .subtitle {
            font-size: 11px;
        }

        .info-section {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px;
            background: #f9f9f9;
        }

        .info-row {
            margin-bottom: 3px;
            font-size: 9px;
            display: table;
            width: 100%;
        }

        .info-label {
            display: table-cell;
            width: 130px;
            font-weight: bold;
            padding-right: 5px;
        }

        .info-separator {
            display: table-cell;
            width: 10px;
            text-align: center;
        }

        .info-value {
            display: table-cell;
        }

        .section-header {
            background: #D81D76;
            color: white;
            padding: 4px 8px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 3px;
            margin-top: 8px;
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 9px;
        }

        table th,
        table td {
            padding: 4px 8px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        table th {
            background: #f5f5f5;
            font-weight: bold;
            font-size: 9px;
        }

        .amount {
            text-align: right;
            font-family: 'Courier New', monospace;
        }

        .total-row {
            background: #fff8e1;
            font-weight: bold;
            border-top: 2px solid #D81D76 !important;
        }

        .net-salary-box {
            background: #FBE8F1;
            border: 2px solid #D81D76;
            border-radius: 4px;
            padding: 10px;
            margin-top: 10px;
            text-align: center;
        }

        .net-salary-label {
            font-size: 10px;
            font-weight: bold;
            color: #666;
            margin-bottom: 3px;
        }

        .net-salary-amount {
            font-size: 16px;
            font-weight: bold;
            color: #D81D76;
            font-family: 'Courier New', monospace;
        }

        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 7px;
            color: #999;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /* Layout untuk landscape */
        .container {
            max-width: 100%;
        }

        .columns {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .column {
            display: table-cell;
            width: 50%;
            padding: 0 5px;
            vertical-align: top;
        }

        .column:first-child {
            padding-left: 0;
        }

        .column:last-child {
            padding-right: 0;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header clearfix">
        <div class="header-content">
            <div class="title">SLIP GAJI BULAN {{ strtoupper($payroll->month_name) }} {{ $payroll->period_year }}</div>
        </div>
    </div>

    <!-- Info Karyawan -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Nama</span>
            <span class="info-separator">:</span>
            <span class="info-value">{{ $user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Divisi</span>
            <span class="info-separator">:</span>
            <span class="info-value">{{ strtoupper($position) }}</span>
        </div>
    </div>

    <!-- Layout 2 Kolom untuk Penghasilan dan Potongan -->
    <div class="columns">
        <!-- Kolom Kiri: Penghasilan & Bonus -->
        <div class="column">
            <!-- Penghasilan -->
            <div class="section-header">PENGHASILAN</div>
            <table>
                <tbody>
                    <tr>
                        <td>Honor Pencapaian KPI</td>
                        <td class="amount">Rp{{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Bonus -->
            @if ($bonuses->count() > 0)
                <div class="section-header">BONUS</div>
                <table>
                    <tbody>
                        @foreach ($bonuses as $bonus)
                            <tr>
                                <td>{{ $bonus->name }}</td>
                                <td class="amount">Rp{{ number_format($bonus->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Kolom Kanan: Potongan -->
        <div class="column">
            @if ($deductions->count() > 0)
                <div class="section-header">POTONGAN</div>
                <table>
                    <tbody>
                        @foreach ($deductions as $deduction)
                            <tr>
                                <td>{{ $deduction->name }}</td>
                                <td class="amount">Rp{{ number_format($deduction->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="section-header">POTONGAN</div>
                <table>
                    <tbody>
                        <tr>
                            <td>Tidak ada potongan</td>
                            <td class="amount">Rp-</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Total Gaji Bersih -->
    <div class="net-salary-box">
        <div class="net-salary-label">PENDAPATAN DITERIMA</div>
        <div class="net-salary-amount">Rp{{ number_format($payroll->net_salary, 0, ',', '.') }}</div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak pada {{ now()->locale('id')->isoFormat('D MMMM YYYY HH:mm') }}</p>
    </div>
</body>

</html>
