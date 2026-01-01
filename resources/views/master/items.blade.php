<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إدارة الأصناف</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>قواعد البيانات</h1>
                <p>إدارة الأصناف</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('dashboard') }}'">رجوع</button>
        </div>
    </header>

    <main class="main">
        <div id="toast" class="toast"></div>

        <div class="title">
            <h2>الأصناف</h2>
        </div>

        <div class="filter-bar">
            <form method="GET" action="{{ route('items.index') }}">
                <select name="unit" onchange="this.form.submit()">
                    <option value="">كل الوحدات</option>
                    @foreach ($units as $u)
                        <option value="{{ $u }}" {{ request('unit') == $u ? 'selected' : '' }}>
                            {{ $u }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث...">
            </form>
            <button class="btn-primary" onclick="openModal('addModal')">➕ إضافة صنف</button>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الصنف</th>
                        <th>باركود</th>
                        <th>الوحدة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->barcode ?? '-' }}</td>
                            <td><span class="badge">{{ $item->unit }}</span></td>
                            <td>
                                <button class="btn-icon edit"
                                    onclick='openEditModal(@json($item))'>✏️</button>
                                <button class="btn-icon delete"
                                    onclick='openDeleteModal({{ $item->id }})'>🗑️</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;">لا توجد أصناف.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding:10px">{{ $items->withQueryString()->links() }}</div>
        </div>
    </main>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('addModal')">&times;</span>
            <h3>إضافة صنف جديد</h3>
            <form action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>اسم الصنف</label>
                    <input name="item_name" required>
                </div>
                <div class="form-group">
                    <label>الوحدة</label>
                    <input name="unit" list="units" required>
                    <datalist id="units">
                        @foreach ($units as $u)
                            <option value="{{ $u }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="form-group">
                    <label>باركود</label>
                    <input name="barcode">
                </div>
                <div class="form-group">
                    <label>الوصف</label>
                    <textarea name="description"></textarea>
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
            <h3>تعديل الصنف</h3>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>اسم الصنف</label>
                    <input name="item_name" id="edit_name" required>
                </div>
                <div class="form-group">
                    <label>الوحدة</label>
                    <input name="unit" id="edit_unit" list="units" required>
                </div>
                <div class="form-group">
                    <label>باركود</label>
                    <input name="barcode" id="edit_barcode">
                </div>
                <div class="form-group">
                    <label>الوصف</label>
                    <textarea name="description" id="edit_desc"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-primary">تحديث</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content" style="max-width:400px; text-align:center;">
            <h3 style="color:red;">تأكيد الحذف</h3>
            <p>هل أنت متأكد من حذف هذا الصنف؟</p>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div class="modal-footer" style="justify-content:center;">
                    <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">إلغاء</button>
                    <button type="submit" class="btn-danger">حذف</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Paste the same CSS for Modal/Toast here */
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
            margin: 5% auto;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
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
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-secondary {
            background: #eee;
            border: 1px solid #ccc;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .toast {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 16px;
            position: fixed;
            z-index: 1000;
            left: 50%;
            bottom: 30px;
            transform: translateX(-50%);
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
        function openModal(id) {
            document.getElementById(id).style.display = 'block';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }
        window.onclick = function(e) {
            if (e.target.classList.contains('modal')) e.target.style.display = 'none';
        }

        function openEditModal(item) {
            document.getElementById('edit_name').value = item.item_name;
            document.getElementById('edit_unit').value = item.unit;
            document.getElementById('edit_barcode').value = item.barcode;
            document.getElementById('edit_desc').value = item.description;
            document.getElementById('editForm').action = "/items/" + item.id;
            openModal('editModal');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = "/items/" + id;
            openModal('deleteModal');
        }

        function showToast(msg, type) {
            var x = document.getElementById("toast");
            x.textContent = msg;
            x.className = "toast show " + type;
            setTimeout(function() {
                x.className = x.className.replace("show", "");
            }, 3000);
        }
        @if (session('success'))
            showToast("{{ session('success') }}", "success");
        @endif
        @if (session('error'))
            showToast("{{ session('error') }}", "error");
        @endif
        @if ($errors->any())
            showToast("خطأ في البيانات", "error");
        @endif
    </script>
</body>

</html>
