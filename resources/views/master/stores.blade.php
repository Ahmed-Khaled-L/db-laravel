<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إدارة المخازن</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>قواعد البيانات</h1>
                <p>إدارة المخازن والعهد</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('dashboard') }}'">رجوع</button>
        </div>
    </header>

    <main class="main">
        <div id="toast" class="toast"></div>

        <div class="title">
            <h2>المخازن</h2>
        </div>

        <div class="filter-bar">
            <form method="GET" action="{{ route('stores.index') }}">
                <select name="responsible_employee_id" onchange="this.form.submit()">
                    <option value="">-- كل أصحاب العهد --</option>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}"
                            {{ request('responsible_employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </form>
            <button class="btn-primary" onclick="openModal('addModal')">➕ إضافة مخزن</button>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>اسم المخزن</th>
                        <th>أمين العهدة</th>
                        <th>التصنيف</th>
                        <th>النوع</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stores as $store)
                        <tr>
                            <td style="font-family: monospace;">{{ $store->code ?? '-' }}</td>
                            <td>{{ $store->name }}</td>
                            <td>{{ $store->manager->name ?? 'غير محدد' }}</td>
                            <td><span class="badge">{{ $store->classification }}</span></td>
                            <td>{{ $store->custody_type }}</td>
                            <td>
                                <button class="btn-icon edit"
                                    onclick='openEditModal(@json($store))'>✏️</button>
                                <button class="btn-icon delete"
                                    onclick='openDeleteModal({{ $store->id }})'>🗑️</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center">لا توجد مخازن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('addModal')">&times;</span>
            <h3>إضافة مخزن جديد</h3>
            <form action="{{ route('stores.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>اسم المخزن</label>
                    <input name="name" required>
                </div>
                <div class="form-group">
                    <label>الكود</label>
                    <input name="code">
                </div>
                <div class="form-group">
                    <label>أمين العهدة</label>
                    <select name="responsible_employee_id">
                        <option value="">-- اختر موظف --</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>التصنيف</label>
                    <input name="classification" list="classifications" required>
                    <datalist id="classifications">
                        <option value="المستديم">
                        <option value="المستهلك">
                    </datalist>
                </div>
                <div class="form-group">
                    <label>النوع</label>
                    <select name="custody_type" required>
                        <option value="مخزن رئيسي">مخزن رئيسي</option>
                        <option value="عهدة فرعية">عهدة فرعية</option>
                    </select>
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
            <h3>تعديل بيانات مخزن</h3>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>اسم المخزن</label>
                    <input name="name" id="edit_name" required>
                </div>
                <div class="form-group">
                    <label>الكود</label>
                    <input name="code" id="edit_code">
                </div>
                <div class="form-group">
                    <label>أمين العهدة</label>
                    <select name="responsible_employee_id" id="edit_emp">
                        <option value="">-- اختر موظف --</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>التصنيف</label>
                    <input name="classification" id="edit_class" list="classifications" required>
                </div>
                <div class="form-group">
                    <label>النوع</label>
                    <select name="custody_type" id="edit_type" required>
                        <option value="مخزن رئيسي">مخزن رئيسي</option>
                        <option value="عهدة فرعية">عهدة فرعية</option>
                    </select>
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
            <p>هل أنت متأكد من حذف هذا المخزن؟</p>
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
        .form-group select {
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

        function openEditModal(store) {
            document.getElementById('edit_name').value = store.name;
            document.getElementById('edit_code').value = store.code;
            document.getElementById('edit_emp').value = store.responsible_employee_id || '';
            document.getElementById('edit_class').value = store.classification;
            document.getElementById('edit_type').value = store.custody_type;
            document.getElementById('editForm').action = "/stores/" + store.id;
            openModal('editModal');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = "/stores/" + id;
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
