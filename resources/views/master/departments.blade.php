<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إدارة الأقسام</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>قواعد البيانات</h1>
                <p>إدارة الأقسام</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('dashboard') }}'">رجوع</button>
        </div>
    </header>

    <main class="main">
        <div id="toast" class="toast"></div>

        <div class="title">
            <h2>الأقسام (Departments)</h2>
        </div>

        <div class="filter-bar">
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="بحث باسم القسم...">

            <button class="btn-primary" onclick="openModal('addModal')">➕ إضافة قسم</button>
        </div>

        <div class="table-card">
            <table id="deptTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم القسم</th>
                        <th>تاريخ الإضافة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr>
                            <td class="id-col">{{ $dept->id }}</td>
                            <td class="name-col" style="font-weight: bold;">{{ $dept->name }}</td>
                            <td style="direction:ltr; text-align:right">
                                {{ $dept->created_at ? $dept->created_at->format('Y-m-d') : '-' }}</td>
                            <td>
                                <button class="btn-icon edit" onclick='openEditModal(@json($dept))'
                                    title="تعديل">✏️</button>
                                <button class="btn-icon delete" onclick='openDeleteModal({{ $dept->id }})'
                                    title="حذف">🗑️</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center">لا يوجد أقسام مسجلة.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('addModal')">&times;</span>
            <h3>إضافة قسم جديد</h3>
            <form action="{{ route('departments.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>اسم القسم</label>
                    <input name="name" required placeholder="مثال: قسم الحسابات">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
            <h3>تعديل اسم القسم</h3>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>اسم القسم</label>
                    <input name="name" id="edit_name" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-primary">تحديث</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content" style="max-width:400px; text-align:center;">
            <h3 style="color:red">تأكيد الحذف</h3>
            <p>هل أنت متأكد من حذف هذا القسم؟</p>
            <p style="font-size:0.9em; color:#666;">(لن يتم الحذف إذا كان القسم يحتوي على موظفين)</p>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div class="modal-footer" style="justify-content:center">
                    <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">إلغاء</button>
                    <button type="submit" class="btn-danger">حذف</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Modal & Overlay */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .close-btn {
            float: left;
            font-size: 24px;
            cursor: pointer;
            color: #888;
            transition: 0.2s;
        }

        .close-btn:hover {
            color: #333;
        }

        /* Forms */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #374151;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            outline: none;
            transition: 0.2s;
        }

        .form-group input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Buttons */
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.2s;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.2s;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .btn-secondary {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.2s;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        /* Toast */
        .toast {
            visibility: hidden;
            min-width: 280px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            padding: 16px;
            position: fixed;
            z-index: 1000;
            left: 50%;
            bottom: 30px;
            transform: translateX(-50%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-weight: bold;
        }

        .toast.show {
            visibility: visible;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        .toast.success {
            background-color: #10b981;
        }

        .toast.error {
            background-color: #ef4444;
        }

        @keyframes fadein {
            from {
                bottom: 0;
                opacity: 0;
            }

            to {
                bottom: 30px;
                opacity: 1;
            }
        }

        @keyframes fadeout {
            from {
                bottom: 30px;
                opacity: 1;
            }

            to {
                bottom: 0;
                opacity: 0;
            }
        }
    </style>

    <script>
        // --- Modal Functions ---
        function openModal(id) {
            document.getElementById(id).style.display = 'block';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // Close modal if clicked outside
        window.onclick = function(e) {
            if (e.target.classList.contains('modal')) e.target.style.display = 'none';
        }

        // --- Edit Modal Logic ---
        function openEditModal(dept) {
            document.getElementById('edit_name').value = dept.name;
            document.getElementById('editForm').action = "/departments/" + dept.id;
            openModal('editModal');
        }

        // --- Delete Modal Logic ---
        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = "/departments/" + id;
            openModal('deleteModal');
        }

        // --- Toast Notification Logic ---
        function showToast(msg, type) {
            var x = document.getElementById("toast");
            x.textContent = msg;
            x.className = "toast show " + type;
            setTimeout(function() {
                x.className = x.className.replace("show", "");
            }, 3000);
        }

        // --- Client Side Search ---
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("deptTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Column 1 is Name
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        // --- Trigger Toasts from Laravel Session ---
        @if (session('success'))
            showToast("{{ session('success') }}", "success");
        @endif
        @if (session('error'))
            showToast("{{ session('error') }}", "error");
        @endif
        @if ($errors->any())
            showToast("يرجى التحقق من البيانات المدخلة", "error");
        @endif
    </script>
</body>

</html>
