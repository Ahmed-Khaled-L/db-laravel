<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>محضر جرد الأصناف</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Amiri', Tahoma, sans-serif;
            background: #f3f4f6;
            padding: 20px;
        }

        .paper {
            background: white;
            width: 297mm;
            min-height: 210mm;
            margin: 0 auto;
            padding: 15mm;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            margin-bottom: 20px;
            align-items: start;
        }

        .center-title {
            text-align: center;
            font-weight: bold;
        }

        .filter-box {
            background: #e0f2fe;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #bae6fd;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            align-items: flex-end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 0.9em;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 200px;
        }

        .btn-submit {
            background: #0ea5e9;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .audit-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 10px;
        }

        .audit-table th,
        .audit-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .audit-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            white-space: pre-wrap;
            vertical-align: middle;
        }

        @media print {

            .filter-box,
            .no-print {
                display: none;
            }

            body {
                background: white;
                padding: 0;
            }

            .paper {
                box-shadow: none;
                width: 100%;
                margin: 0;
                padding: 10mm;
            }
        }
    </style>
</head>

<body>

    <div class="filter-box">
        <form method="GET" action="{{ route('audit.report') }}">
            <input type="hidden" name="type" value="{{ $auditType }}">

            <div class="form-row">
                @if ($auditType === 'personnel')
                    <div class="form-group">
                        <label>اسم صاحب العهدة</label>
                        <select name="employee_id" class="form-select">
                            <option value="">-- جميع الموظفين --</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="form-group">
                        <label>اسم المخزن</label>
                        <select name="store_id" class="form-select">
                            <option value="">-- جميع المخازن --</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}"
                                    {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-group">
                    <label>بند رقم (Category)</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- جميع البنود --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->id }} - {{ $cat->cat_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" name="submit_filter" value="1" class="btn-submit">عرض التقرير</button>
                </div>

                <div class="form-group" style="margin-right: auto;">
                    <button type="button" onclick="window.print()" class="btn-submit"
                        style="background: #4b5563;">طباعة</button>
                    <a href="{{ route('dashboard') }}" class="btn-submit"
                        style="background: #ef4444; text-decoration:none;">خروج</a>
                </div>
            </div>
        </form>
    </div>

    <div class="paper">

        <div class="header-grid">
            <div style="text-align: right;">
                <p><strong>اسم الجهة:</strong> كلية هندسة شبرا</p>
                <p><strong>اسم المخزن:</strong>
                    {{ $selectedStore->name ?? ($selectedEmployee ? 'عهدة شخصية' : 'جميع المخازن / العهد') }}</p>
            </div>

            <div class="center-title">
                <p>( نموذج 6 مخازن حكومة )</p>
                <h2>محضر جرد الاصناف</h2>
            </div>

            <div style="text-align: left;">
                <p><strong>تاريخ:</strong> {{ date('Y/m/d') }}</p>
                <p><strong>بند رقم:</strong> {{ $selectedCategory->id ?? 'جميع البنود' }}</p>
                @if ($auditType === 'personnel')
                    <p><strong>اسم صاحب العهدة:</strong> {{ $selectedEmployee->name ?? 'جميع الموظفين' }}</p>
                @endif
            </div>
        </div>

        <div style="text-align: right; margin-bottom: 10px;">
            <strong>إسم البند:</strong> {{ $selectedCategory->cat_name ?? 'تقرير شامل' }}
        </div>

        <table class="audit-table">
            <thead>
                <tr>
                    <th rowspan="2">رقم<br>الصنف</th>
                    <th rowspan="2">اسم الصنف</th>
                    <th rowspan="2">الوحدة</th>
                    <th rowspan="2">الموجود من<br>واقع الجرد</th>
                    <th rowspan="2">حالة<br>الصنف</th>
                    <th rowspan="2">الرصيد<br>الدفتري</th>
                    <th rowspan="2">حاله<br>الصنف</th>
                    <th colspan="2">الزيادة</th>
                    <th colspan="2">العجز</th>
                    <th rowspan="2">سعر<br>الوحدة</th>
                    <th rowspan="2">القيمة</th>
                </tr>
                <tr>
                    <th>عدد</th>
                    <th>قيمة</th>
                    <th>عدد</th>
                    <th>قيمة</th>
                </tr>
            </thead>
            <tbody>
                @php $totalValue = 0; @endphp

                @forelse($records as $row)
                    @php
                        $surplusQty = max(0, $row->actual_qty - $row->book_qty);
                        $deficitQty = max(0, $row->book_qty - $row->actual_qty);

                        $surplusVal = $surplusQty * $row->unit_price;
                        $deficitVal = $deficitQty * $row->unit_price;

                        $rowValue = $row->actual_qty * $row->unit_price;
                        $totalValue += $rowValue;
                    @endphp
                    <tr>
                        <td>{{ $row->item_number }}</td>
                        <td>{{ $row->item_name }}</td>
                        <td>{{ $row->unit }}</td>
                        <td>{{ $row->actual_qty }}</td>
                        <td>{{ $row->item_state }}</td>
                        <td>{{ $row->book_qty }}</td>
                        <td>{{ $row->item_state }}</td>

                        <td>{{ $surplusQty > 0 ? $surplusQty : '0' }}</td>
                        <td>{{ $surplusVal > 0 ? number_format($surplusVal, 2) : '0' }}</td>

                        <td>{{ $deficitQty > 0 ? $deficitQty : '0' }}</td>
                        <td>{{ $deficitVal > 0 ? number_format($deficitVal, 2) : '0' }}</td>

                        <td>{{ number_format($row->unit_price, 2) }}</td>
                        <td>{{ number_format($rowValue, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" style="padding: 20px;">لا توجد بيانات للعرض.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="12" style="text-align: left; font-weight: bold; padding-left: 10px;">الإجمالي</td>
                    <td style="font-weight: bold;">{{ number_format($totalValue, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top: 40px; display: flex; justify-content: space-between; text-align: center;">
            <div>
                <p>لجنة الجرد</p>
                <p>..................</p>
            </div>
            <div>
                <p>أمين المخزن / صاحب العهدة</p>
                <p>..................</p>
            </div>
            <div>
                <p>اعتماد المدير</p>
                <p>..................</p>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>
