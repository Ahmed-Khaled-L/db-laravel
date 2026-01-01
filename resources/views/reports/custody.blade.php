<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الجرد السنوي</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Amiri', serif;
            background-color: #f9fafb;
            padding: 20px;
            direction: rtl;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #1f2937;
            font-size: 24px;
        }

        .header p {
            color: #6b7280;
            margin-top: 5px;
        }

        .section-title {
            background-color: #e5e7eb;
            padding: 10px 15px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 10px;
            border-radius: 4px;
            color: #374151;
            display: flex;
            justify-content: space-between;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px 12px;
            text-align: center;
        }

        th {
            background-color: #f3f4f6;
            color: #111827;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .total-row td {
            background-color: #e5e7eb;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .print-btn {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            font-family: inherit;
        }

        .print-btn:hover {
            background-color: #1d4ed8;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #2563eb;
            text-decoration: none;
        }

        @media print {

            .print-btn,
            .back-link {
                display: none;
            }

            .container {
                box-shadow: none;
                padding: 0;
            }

            body {
                background: white;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="{{ route('dashboard') }}" class="back-link">← عودة للرئيسية</a>
        <button onclick="window.print()" class="print-btn" style="float: left;">طباعة التقرير</button>

        <div class="header">
            <h1>تقرير الجرد السنوي للعهد </h1>
            <p>كلية الهندسة بشبرا - تاريخ التقرير: {{ date('Y-m-d') }}</p>
        </div>

        @foreach ($reportData as $type => $rows)
            <div class="section-title">
                <span>نوع البند: {{ $type }}</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th rowspan="2" width="5%">رقم البند</th>
                        <th rowspan="2" width="35%">اسم البند</th>
                        <th colspan="3">قيمة العهدة (بالجنية المصري)</th>
                        <th rowspan="2" width="15%">الإجمالي</th>
                    </tr>
                    <tr>
                        <th>عهدة شخصية</th>
                        <th>مخزن رئيسي</th>
                        <th>عهدة فرعية</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sumPersonal = 0;
                        $sumMain = 0;
                        $sumBranch = 0;
                        $sumTotal = 0;
                    @endphp

                    @foreach ($rows as $row)
                        <tr>
                            <td>{{ $row['id'] }}</td>
                            <td class="text-right">{{ $row['name'] }}</td>
                            <td>{{ number_format($row['personnel_value'], 2) }}</td>
                            <td>{{ number_format($row['main_wh_value'], 2) }}</td>
                            <td>{{ number_format($row['branch_wh_value'], 2) }}</td>
                            <td>{{ number_format($row['total'], 2) }}</td>
                        </tr>
                        @php
                            $sumPersonal += $row['personnel_value'];
                            $sumMain += $row['main_wh_value'];
                            $sumBranch += $row['branch_wh_value'];
                            $sumTotal += $row['total'];
                        @endphp
                    @endforeach

                    <tr class="total-row">
                        <td colspan="2">إجمالي {{ $type }}</td>
                        <td>{{ number_format($sumPersonal, 2) }}</td>
                        <td>{{ number_format($sumMain, 2) }}</td>
                        <td>{{ number_format($sumBranch, 2) }}</td>
                        <td>{{ number_format($sumTotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach

    </div>

</body>

</html>
