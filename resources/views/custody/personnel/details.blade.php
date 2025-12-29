<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إضافة تفاصيل الأصناف</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <style>
        .details-container {
            max-width: 900px;
            margin: 20px auto;
        }

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <h1>تفاصيل العهدة</h1>
        </div>
    </header>

    <main class="main">
        <div class="details-container">

            <div class="info-box">
                <div>
                    <strong>الصنف:</strong> {{ $audit->item->item_name }} <br>
                    <strong>الكمية المطلوبة:</strong> {{ $quantity }}
                </div>
                <div>رقم القيد: #{{ $audit->id }}</div>
            </div>

            @if ($errors->any())
                <div style="background: #fee2e2; color: #b91c1c; padding: 10px; margin-bottom:15px; border-radius:6px;">
                    يرجى التأكد من تعبئة الحقول بشكل صحيح وعدم تكرار الأرقام التسلسلية.
                </div>
            @endif

            <form action="{{ route('custody.details.store', $audit->id) }}" method="POST">
                @csrf

                <div class="table-card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>الرقم التسلسلي (Serial No) <span style="color:red">*</span></th>
                                <th>تاريخ الانتهاء (اختياري)</th>
                            </tr>
                        </thead>
                        <tbody id="detailsTableBody">
                            @for ($i = 0; $i < $quantity; $i++)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <input type="text" class="table-input"
                                            name="details[{{ $i }}][serial_no]" required
                                            placeholder="ادخل الرقم التسلسلي...">
                                    </td>
                                    <td>
                                        <input type="date" class="table-input"
                                            name="details[{{ $i }}][expiry_date]">
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 15px; display: flex; gap: 10px;">
                    <button type="button" onclick="addExtraRow()"
                        style="background:#f3f4f6; border:1px solid #d1d5db; padding: 8px 15px; border-radius: 6px; cursor: pointer;">
                        + إضافة صف إضافي
                    </button>
                </div>

                <div class="actions" style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 15px;">
                    <a href="{{ route('custody.personnel.index') }}"
                        style="padding: 10px 20px; color: #6b7280; text-decoration: none;">تخطي</a>
                    <button type="submit" class="save-btn"
                        style="background-color: #2563eb; color: white; padding: 10px 30px; border: none; border-radius: 6px; cursor: pointer;">
                        حفظ وإنهاء
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        let rowCount = {{ $quantity }};

        function addExtraRow() {
            const tbody = document.getElementById('detailsTableBody');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td>${rowCount + 1}</td>
                <td>
                    <input type="text" class="table-input" 
                           name="details[${rowCount}][serial_no]" 
                           required placeholder="ادخل الرقم التسلسلي...">
                </td>
                <td>
                    <input type="date" class="table-input" 
                           name="details[${rowCount}][expiry_date]">
                </td>
            `;

            tbody.appendChild(newRow);
            rowCount++;
        }
    </script>

</body>

</html>
