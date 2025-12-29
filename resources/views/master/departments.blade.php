<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>الأقسام</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>قواعد البيانات</h1>
                <p>إدارة الأقسام</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ url()->previous() }}'">
                رجوع
            </button>
        </div>
    </header>

    <main class="main">

        <div class="title">
            <h2>الأقسام (Departments)</h2>
        </div>

        <!-- ===== Search ===== -->
        <div class="filter-bar">
            <input type="text" id="searchInput" placeholder="بحث باسم القسم أو الـ ID" oninput="renderTable()">
        </div>

        <!-- ===== Form ===== -->
        <div class="form-card">
            <div class="form-row">
                <input id="deptId" placeholder="ID">
                <input id="deptName" placeholder="اسم القسم">
            </div>

            <button type="button" onclick="addDepartment()">إضافة قسم</button>
        </div>

        <!-- ===== Table ===== -->
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>اسم القسم</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody id="departmentsTable"></tbody>
            </table>
        </div>

    </main>

    <script src="{{ asset('js/departments.js') }}"></script>
</body>

</html>
