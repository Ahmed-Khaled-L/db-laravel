<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة المستودعات - لوحة التحكم</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-right">
                <div class="user-info">
                    <div class="user-avatar">
                        <i data-lucide="user"></i>
                    </div>
                    <div class="user-text">
                        <p class="name">{{ Auth::user()->name ?? 'مستخدم النظام' }}</p>
                        <p class="role">مدير النظام</p>
                    </div>
                    <div class="settings-btn" onclick="alert('صفحة الإعدادات')" title="الإعدادات">
                        <i data-lucide="settings" size="18"></i>
                    </div>
                </div>
                <div style="width: 1px; height: 24px; background: #e5e7eb; margin: 0 10px;"></div>

                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout">
                        <i data-lucide="log-out" size="18"></i>
                        <span>خروج</span>
                    </button>
                </form>
            </div>

            <div class="nav-left">
                <div class="nav-text text-right">
                    <h1>نظام إدارة المستودعات</h1>
                    <p>الجامعة / الكلية التقنية</p>
                </div>
                <div class="logo-box">
                    <i data-lucide="menu" size="24"></i>
                </div>
            </div>
        </div>
    </nav>

    <main class="main">

        <div class="title">
            <h2 id="pageTitle">القائمة الرئيسية</h2>
            <p id="pageSub">اختر العملية التي تريد القيام بها</p>
        </div>

        <div id="mainMenu" class="grid fade-in">

            <div class="card" onclick="alert('صفحة إضافة عهدة')">
                <div class="icon-wrapper bg-blue"><i data-lucide="package-plus" size="28"></i></div>
                <h3>إضافة عهدة</h3>
                <p>تسجيل عهدة جديدة</p>
                <span class="arrow">➜</span>
            </div>

            <div class="card" onclick="alert('صفحة تعديل العهدة')">
                <div class="icon-wrapper bg-indigo"><i data-lucide="edit" size="28"></i></div>
                <h3>تعديل حالة عهدة</h3>
                <p>تحديث حالة المواد</p>
                <span class="arrow">➜</span>
            </div>

            <div class="card" onclick="alert('صفحة الجرد')">
                <div class="icon-wrapper bg-emerald"><i data-lucide="clipboard-list" size="28"></i></div>
                <h3>إنشاء جرد</h3>
                <p>بدء عملية جرد جديدة</p>
                <span class="arrow">➜</span>
            </div>

            <div class="card" onclick="alert('صفحة المخزون')">
                <div class="icon-wrapper bg-emerald" style="opacity: 0.7;"><i data-lucide="box" size="28"></i>
                </div>
                <h3>المخزون الحالي</h3>
                <p>عرض الكميات الحالية</p>
                <span class="arrow">➜</span>
            </div>

            <div class="card" onclick="alert('صفحة التقارير')">
                <div class="icon-wrapper bg-purple"><i data-lucide="bar-chart-3" size="28"></i></div>
                <h3>إنشاء تقارير</h3>
                <p>طباعة تقارير الجرد</p>
                <span class="arrow">➜</span>
            </div>

            <div class="card" onclick="alert('صفحة المقايسة')">
                <div class="icon-wrapper bg-orange"><i data-lucide="calculator" size="28"></i></div>
                <h3>المقايسة السنوية</h3>
                <p>حساب التقديرات السنوية</p>
                <span class="arrow">➜</span>
            </div>

            <div class="card" onclick="alert('قسم الاستعلامات')">
                <div class="icon-wrapper bg-teal"><i data-lucide="search" size="28"></i></div>
                <h3>قسم الاستعلامات</h3>
                <p>البحث عن الأصناف</p>
                <span class="arrow">➜</span>
            </div>

            <div class="card" onclick="openDatabase()">
                <div class="icon-wrapper bg-indigo"><i data-lucide="database" size="28"></i></div>
                <h3>قواعد البيانات</h3>
                <p>البيانات الأساسية للنظام</p>
                <span class="arrow">➜</span>
            </div>

        </div>

        <div class="hidden fade-in" id="databaseSection">
            <div class="db-container">

                <div class="db-card" onclick="location.href='{{ route('employees.index') }}'">
                    <i data-lucide="users" size="24"></i>
                    <span>الموظفين</span>
                </div>

                <div class="db-card" onclick="location.href='{{ route('departments.index') }}'">
                    <i data-lucide="building" size="24"></i>
                    <span>الأقسام</span>
                </div>

                <div class="db-card" onclick="location.href='{{ route('stores.index') }}'">
                    <i data-lucide="warehouse" size="24"></i>
                    <span>المخازن</span>
                </div>

                <div class="db-card" onclick="location.href='{{ route('registers.index') }}'">
                    <i data-lucide="book" size="24"></i>
                    <span>سجلات الدفاتر</span>
                </div>

                <div class="db-card" onclick="location.href='{{ route('categories.index') }}'">
                    <i data-lucide="list" size="24"></i>
                    <span>البنود</span>
                </div>

                <div class="db-card" onclick="location.href='{{ route('items.index') }}'">
                    <i data-lucide="package" size="24"></i>
                    <span>الأصناف</span>
                </div>

                <div class="db-card" onclick="location.href='{{ route('custody.personnel.index') }}'">
                    <i data-lucide="user-check" size="24"></i>
                    <span>عهد شخصية</span>
                </div>

                <div class="db-card" onclick="location.href='{{ route('custody.inventory.index') }}'">
                    <i data-lucide="user-check" size="24"></i>
                    <span>جرد المخازن</span>
                </div>

                <div class="db-card" onclick="location.href='{{ route('custody.personnel.index') }}'">
                    <i data-lucide="user-check" size="24"></i>
                    <span>الأصول</span>
                </div>

                <div class="back-container">
                    <button class="back-btn" onclick="backToMain()">
                        <i data-lucide="arrow-right"></i>
                        رجوع للقائمة الرئيسية
                    </button>
                </div>
            </div>
        </div>

    </main>

    <footer class="footer">
        نظام إدارة المستودعات v1.0 © 2025
    </footer>

    <script>
        lucide.createIcons();

        function openDatabase() {
            document.getElementById("mainMenu").style.display = 'none';
            document.getElementById("databaseSection").classList.remove("hidden");
            document.getElementById("pageTitle").textContent = "قواعد البيانات";
            document.getElementById("pageSub").textContent = "إدارة البيانات الأساسية للنظام";
            lucide.createIcons();
        }

        function backToMain() {
            document.getElementById("databaseSection").classList.add("hidden");
            document.getElementById("mainMenu").style.display = 'grid';
            document.getElementById("pageTitle").textContent = "القائمة الرئيسية";
            document.getElementById("pageSub").textContent = "اختر العملية التي تريد القيام بها";
        }
    </script>
</body>

</html>
