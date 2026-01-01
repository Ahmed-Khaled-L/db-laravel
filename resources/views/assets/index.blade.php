<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>الأصول والممتلكات</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>قواعد البيانات</h1>
                <p>إدارة الأصول والممتلكات الخاصة</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('dashboard') }}'">رجوع</button>
        </div>
    </header>

    <main class="main">
        <div class="title">
            <h2>الأصول</h2>
        </div>

        <div id="toast" class="toast"></div>

        <div class="filter-bar">
            <a href="{{ route('reports.assets') }}" class="btn-secondary" target="_blank">🖨️ طباعة تقرير الأصول</a>

            <button class="btn-primary" onclick="openModal('addModal')">➕ إضافة أصل جديد</button>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>مسلسل</th>
                        <th>البيان (اسم الأصل)</th>
                        <th>القيمة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                        <tr>
                            <td class="id-col">{{ $asset->id }}</td>
                            <td><strong>{{ $asset->name }}</strong></td>
                            <td>{{ number_format($asset->value, 2) }}</td>
                            <td class="actions">
                                <button class="btn-icon edit" onclick='openEditModal(@json($asset))'
                                    title="تعديل">✏️</button>
                                <button class="btn-icon delete" onclick='openDeleteModal("{{ $asset->id }}")'
                                    title="حذف">🗑️</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;">لا توجد بيانات.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>إضافة أصل جديد</h3>
                <span class="close-btn" onclick="closeModal('addModal')">&times;</span>
            </div>
            <form action="{{ route('assets.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>مسلسل</label>
                    <input type="number" name="id" required placeholder="مثال: 1">
                </div>
                <div class="form-group">
                    <label>البيان</label>
                    <input type="text" name="name" required placeholder="مثال: أراضي مقام عليها مباني">
                </div>
                <div class="form-group">
                    <label>القيمة</label>
                    <input type="number" step="0.01" name="value" placeholder="0.00">
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
                <h3>تعديل بيانات الأصل</h3>
                <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>مسلسل</label>
                    <input type="number" name="id" id="edit_id" required>
                </div>
                <div class="form-group">
                    <label>البيان</label>
                    <input type="text" name="name" id="edit_name" required>
                </div>
                <div class="form-group">
                    <label>القيمة</label>
                    <input type="number" step="0.01" name="value" id="edit_value">
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
            <p>هل أنت متأكد من حذف هذا الأصل؟ لا يمكن التراجع عن هذا الإجراء.</p>
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

        .form-group input {
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

        /* Toast Styles */
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

        // Edit Logic
        function openEditModal(asset) {
            document.getElementById('edit_id').value = asset.id;
            document.getElementById('edit_name').value = asset.name;
            document.getElementById('edit_value').value = asset.value;

            let url = "{{ route('assets.update', ':id') }}";
            url = url.replace(':id', asset.id);
            document.getElementById('editForm').action = url;

            openModal('editModal');
        }

        // Delete Logic
        function openDeleteModal(id) {
            let url = "{{ route('assets.destroy', ':id') }}";
            url = url.replace(':id', id);
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
