<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>التصنيفات</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
</head>

<body>
    <header class="navbar">
        <div class="nav-container">
            <div class="nav-left">
                <h1>قواعد البيانات</h1>
                <p>إدارة التصنيفات</p>
            </div>

            <button class="back-btn" onclick="location.href='../dashboard/dashboard.html'">
                رجوع
            </button>
        </div>
    </header>

    <main class="main">

        <div class="title">
            <h2>التصنيفات (Categories)</h2>
            <p>المفتاح الأساسي: (ID + Type)</p>
        </div>
        <div class="filter-bar">
            <input type="text" id="searchInput" placeholder="بحث بالاسم أو الباركود" oninput="renderTable()">

            <select id="unitFilter" onchange="renderTable()">
                <option value="">كل الوحدات</option>
                <option value="عدد">عدد</option>
                <option value="رزمة">رزمة</option>
                <option value="كجم">كجم</option>
            </select>
        </div>

        <!-- ===== Add Form ===== -->
        <div class="form-card">
            <h3>إضافة تصنيف</h3>

            <form class="form-row" action="" method="post">
                <input type="number" id="catId" placeholder="ID">
                <input type="text" id="catType" placeholder="Type">
                <input type="text" id="catName" placeholder="اسم التصنيف">
                <input type="text" id="org" placeholder="الجهة">
                <input type="text" id="notes" placeholder="ملاحظات">
            </form>

            <button type="button" onclick="addCategory()">إضافة</button>
        </div>

        <!-- ===== Table ===== -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>اسم التصنيف</th>
                        <th>الجهة</th>
                        <th>ملاحظات</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody id="categoryTable"></tbody>
            </table>
        </div>

    </main>

    <script src="{{ asset('js/categories.js') }}"></script>
</body>

</html>
