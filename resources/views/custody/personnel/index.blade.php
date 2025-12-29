<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>عهدة شخصية</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>إدارة العهد</h1>
                <p>سجل العهد الشخصية (Personnel Custody)</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('dashboard') }}'">
                الرئيسية
            </button>
        </div>
    </header>

    <main class="main">
        <div class="title">
            <h2>قائمة العهد الشخصية</h2>
        </div>

        <div class="filter-bar">
            <input type="text" placeholder="بحث باسم الموظف أو الصنف...">
            <a href="{{ route('custody.personnel.create') }}" class="btn-primary"
                style="text-decoration: none; padding: 8px 16px; background: #2563eb; color: white; border-radius: 6px;">
                + إضافة جديد
            </a>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>التاريخ</th>
                        <th>الموظف</th>
                        <th>الصنف</th>
                        <th>الدفتر / صفحة</th>
                        <th>العدد</th>
                        <th>السعر</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($audits as $audit)
                        <tr>
                            <td>{{ $audit->id }}</td>
                            <td>{{ $audit->date }}</td>
                            <td>{{ $audit->personnelDetail->employee->name ?? 'غير محدد' }}</td>
                            <td>{{ $audit->item->item_name ?? '' }}</td>
                            <td>{{ $audit->register->register_name ?? '' }} / {{ $audit->page_no }}</td>
                            <td>{{ $audit->personnelDetail->quantity ?? 0 }}</td>
                            <td>{{ $audit->unit_price }}</td>
                            <td>
                                <button class="btn-icon">✏️</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
