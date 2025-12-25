<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>الموظفين</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>قواعد البيانات</h1>
                <p>إدارة الموظفين</p>
            </div>
            <button class="back-btn" onclick="location.href='#'">
                رجوع
            </button>
        </div>
    </header>

    <main class="main">

        <div class="title">
            <h2>الموظفين (Employees)</h2>
        </div>

        <!-- ===== Search & Filter ===== -->
        <div class="filter-bar">
            <input type="text" id="searchInput" placeholder="بحث بالاسم أو الرقم القومي" oninput="renderTable()">

            <select id="deptFilter" onchange="renderTable()">
                <option value="">كل الأقسام</option>
                <option value="IT">IT</option>
                <option value="HR">HR</option>
                <option value="Finance">Finance</option>
            </select>
        </div>

        <!-- ===== Employee Form ===== -->
        <div class="form-card">
            <div class="form-row">
                <input id="empId" placeholder="ID">
                <input id="empName" placeholder="اسم الموظف">
                <input id="ssn" placeholder="الرقم القومي">
                <input id="mobile" placeholder="رقم الموبايل">
                <input id="jobTitle" placeholder="الوظيفة">
                <input type="date" id="birthDate">
                <input id="department" placeholder="القسم">
            </div>



            <button type="button" onclick="addEmployee()">إضافة موظف</button>
        </div>

        <!-- ===== Table ===== -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الرقم القومي</th>
                        <th>الوظيفة</th>
                        <th>القسم</th>
                        <th>موبايلات</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody id="employeesTable"></tbody>
            </table>
        </div>

    </main>

    <script src="{{ asset('js/employees.js') }}"></script>
</body>

</html>
