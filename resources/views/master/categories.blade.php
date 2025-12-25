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

            <button class="back-btn" onclick="location.href='#'">
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
        <form action="{{ route('categories.store') }}" method="POST" class="form-card">
            @csrf

            <h3>إضافة تصنيف</h3>

            <div class="form-row">
                <input type="number" name="id" placeholder="ID" required>

                <input type="text" name="type" placeholder="Type" required>

                <input type="text" name="cat_name" placeholder="اسم التصنيف">

                <input type="text" name="organization" placeholder="الجهة">

                <input type="text" name="notes" placeholder="ملاحظات">
            </div>

            <button type="submit">إضافة</button>
        </form>

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
                <tbody>
                    @foreach ($categories as $index => $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td>{{ $c->type }}</td>
                            <td>{{ $c->cat_name }}</td>
                            <td>{{ $c->organization }}</td>
                            <td>{{ $c->notes }}</td>
                            <td>
                                <form action="#" method="GET" style="display:inline">
                                    <button type="submit">✏️</button>
                                </form>

                                <form action="{{ route('categories.destroy', ['id' => $c->id, 'type' => $c->type]) }}"
                                    method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('هل أنت متأكد؟')">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </main>

    <script src="{{ asset('js/categories.js') }}"></script>
</body>

</html>
