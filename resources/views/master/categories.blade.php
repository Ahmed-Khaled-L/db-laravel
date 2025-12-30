<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>البنود المخزنية</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>قواعد البيانات</h1>
                <p>إدارة البنود والتصنيفات</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('dashboard') }}'">رجوع</button>
        </div>
    </header>

    <main class="main">
        <div class="title">
            <h2>البنود (Categories)</h2>
        </div>

        <div id="toast" class="toast"></div>

        <div class="filter-bar">
            <form method="GET" action="{{ route('categories.index') }}">
                <select name="type" onchange="this.form.submit()">
                    <option value="">كل الأنواع</option>
                    @foreach ($types as $t)
                        <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                            {{ $t }}</option>
                    @endforeach
                </select>
            </form>
            <button class="btn-primary" onclick="openModal('addModal')">➕ إضافة بند جديد</button>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>رقم البند</th>
                        <th>النوع</th>
                        <th>اسم البند</th>
                        <th>الجهة</th>
                        <th>ملاحظات</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="id-col">{{ $category->id }}</td>
                            <td><span class="badge">{{ $category->type }}</span></td>
                            <td><strong>{{ $category->cat_name }}</strong></td>
                            <td>{{ $category->organization ?? '-' }}</td>
                            <td>{{ $category->notes }}</td>
                            <td class="actions">
                                <button class="btn-icon edit" onclick='openEditModal(@json($category))'
                                    title="تعديل">✏️</button>
                                <button class="btn-icon delete"
                                    onclick='openDeleteModal("{{ $category->id }}", "{{ $category->type }}")'
                                    title="حذف">🗑️</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;">لا توجد بيانات.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>إضافة بند جديد</h3>
                <span class="close-btn" onclick="closeModal('addModal')">&times;</span>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>رقم البند (ID)</label>
                    <input type="number" name="id" required>
                </div>
                <div class="form-group">
                    <label>النوع</label>
                    <input type="text" name="type" required list="typeList">
                    <datalist id="typeList">
                        @foreach ($types as $t)
                            <option value="{{ $t }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="form-group">
                    <label>اسم البند</label>
                    <input type="text" name="cat_name" required>
                </div>
                <div class="form-group">
                    <label>الجهة (اختياري)</label>
                    <input type="text" name="organization">
                </div>
                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('addModal')">إلغاء</button>
                    <button type="submit" class="btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>تعديل بيانات البند</h3>
                <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>رقم البند</label>
                    <input type="number" name="id" id="edit_id" required>
                </div>
                <div class="form-group">
                    <label>النوع</label>
                    <input type="text" name="type" id="edit_type" required>
                </div>
                <div class="form-group">
                    <label>اسم البند</label>
                    <input type="text" name="cat_name" id="edit_name" required>
                </div>
                <div class="form-group">
                    <label>الجهة</label>
                    <input type="text" name="organization" id="edit_org">
                </div>
                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes" id="edit_notes"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('editModal')">إلغاء</button>
                    <button type="submit" class="btn-primary">تحديث</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content" style="max-width: 400px; text-align: center;">
            <div class="modal-header" style="justify-content: center;">
                <h3 style="color: #dc2626;">⚠️ تأكيد الحذف</h3>
            </div>
            <p>هل أنت متأكد من حذف هذا البند؟ لا يمكن التراجع عن هذا الإجراء.</p>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">إلغاء</button>
                    <button type="submit" class="btn-danger">نعم، احذف</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Modal & Toast Styles */
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
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
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
            color: #888;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: right;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-danger {
            background-color: #dc2626;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Toast */
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
        // Modal Logic
        function openModal(id) {
            document.getElementById(id).style.display = 'block';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Edit Logic for Composite Key
        function openEditModal(cat) {
            document.getElementById('edit_id').value = cat.id;
            document.getElementById('edit_type').value = cat.type;
            document.getElementById('edit_name').value = cat.cat_name;
            document.getElementById('edit_org').value = cat.organization;
            document.getElementById('edit_notes').value = cat.notes;

            // Build Route: /categories/{id}/{type}
            let url = "{{ route('categories.update', [':id', ':type']) }}";
            url = url.replace(':id', cat.id).replace(':type', encodeURIComponent(cat.type));

            document.getElementById('editForm').action = url;
            openModal('editModal');
        }

        // Delete Logic for Composite Key
        function openDeleteModal(id, type) {
            let url = "{{ route('categories.destroy', [':id', ':type']) }}";
            url = url.replace(':id', id).replace(':type', encodeURIComponent(type));

            document.getElementById('deleteForm').action = url;
            openModal('deleteModal');
        }

        // Toast Logic
        function showToast(message, type) {
            var x = document.getElementById("toast");
            x.textContent = message;
            x.className = "toast show " + type;
            setTimeout(function() {
                x.className = x.className.replace("show", "");
            }, 3000);
        }

        // Trigger Toast from Session
        @if (session('success'))
            showToast("{{ session('success') }}", "success");
        @endif
        @if (session('error'))
            showToast("{{ session('error') }}", "error");
        @endif
        @if ($errors->any())
            showToast("يوجد خطأ في البيانات المدخلة", "error");
        @endif
    </script>
</body>

</html>
