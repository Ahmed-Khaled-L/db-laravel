<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>السجلات</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>قواعد البيانات</h1>
                <p>إدارة السجلات</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ url()->previous() }}'">
                رجوع
            </button>
        </div>
    </header>

    <main class="main">

        <div class="title">
            <h2>السجلات (Register)</h2>
        </div>

        <!-- ===== Search ===== -->
        <div class="filter-bar">
            <input type="text" id="searchInput" placeholder="بحث باسم السجل" oninput="renderTable()">
        </div>

        <!-- ===== Form ===== -->
        <div class="form-card">
            <div class="form-row">
                <input id="regId" placeholder="ID">
                <input id="regName" placeholder="اسم السجل">
            </div>

            <button type="button" onclick="addRegister()">إضافة سجل</button>
        </div>

        <!-- ===== Table ===== -->
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>اسم السجل</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody id="registerTable"></tbody>
            </table>
        </div>

    </main>

    <script src="{{ asset('js/register.js') }}"></script>
</body>

</html>
