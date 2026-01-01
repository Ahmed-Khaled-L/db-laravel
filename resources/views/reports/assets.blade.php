<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بيان الأصول والممتلكات الخاصة</title>
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
        <a href="{{ url()->previous() }}" class="back-link">← عودة للأصول</a>
        <button onclick="window.print()" class="print-btn" style="float: left;">طباعة التقرير</button>

        <div class="header">
            <h1>بيان الأصول والممتلكات الخاصة</h1>
            <p>تاريخ التقرير: {{ date('Y-m-d') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="10%">مسلسل</th>
                    <th width="60%">البيان</th>
                    <th width="30%">القيمة</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assets as $asset)
                    <tr>
                        <td>{{ $asset->id }}</td>
                        <td class="text-right">{{ $asset->name }}</td>
                        <td>{{ number_format($asset->value, 2) }}</td>
                    </tr>
                @endforeach

                <tr class="total-row">
                    <td colspan="2">الإجمالي</td>
                    <td>{{ number_format($totalValue, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
