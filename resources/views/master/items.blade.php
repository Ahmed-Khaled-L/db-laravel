<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>الأصناف</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>قواعد البيانات</h1>
                <p>إدارة الأصناف</p>
            </div>
            <button class="back-btn" onclick="location.href='#'">
                رجوع
            </button>
        </div>
    </header>

    <main class="main">

        <div class="title">
            <h2>الأصناف (Items)</h2>
        </div>

        <!-- ===== Search & Filter ===== -->
        <div class="filter-bar">
            <select id="unitFilter" onchange="renderTable()">
                <option value="">كل الوحدات</option>
                <option value="عدد">عدد</option>
                <option value="رزمة">رزمة</option>
                <option value="كجم">كجم</option>
            </select>

            <input type="text" id="searchInput" placeholder="بحث بالاسم أو الباركود" oninput="renderTable()">
        </div>

        <!-- ===== Form ===== -->
        <div class="form-card">
            <div class="form-row">
                <input id="itemId" placeholder="ID">
                <input id="itemName" placeholder="اسم الصنف">
                <input id="barcode" placeholder="Barcode">
                <input id="unit" placeholder="الوحدة">
                <input id="description" placeholder="الوصف">
            </div>

            <button type="button" onclick="addItem()">إضافة</button>
        </div>

        <!-- ===== Table ===== -->
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الصنف</th>
                        <th>Barcode</th>
                        <th>الوحدة</th>
                        <th>الوصف</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody id="itemsTable"></tbody>
            </table>
        </div>

    </main>

    <script src="{{ asset('js/items.js') }}"></script>
</body>

</html>
