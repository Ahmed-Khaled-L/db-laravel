<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المخازن والعهد</title>
    <!-- Use a font that supports Arabic nicely -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- User Provided Styles --- */
        * { box-sizing: border-box; font-family: "Cairo", "Segoe UI", Tahoma, sans-serif; }
        body { margin: 0; background: #f4f6f8; }
        .page { max-width: 1200px; margin: auto; padding: 40px 24px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px; }
        .page-header h1 { margin: 0; font-size: 26px; color: #1f2937; }
        .header-actions { display: flex; gap: 10px; align-items: center; }
        .filter-select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-family: "Cairo"; background: white; min-width: 150px; cursor: pointer; }
        .btn-back { background: none; border: 1px solid #ddd; padding: 8px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 5px; color: #555; transition: 0.2s; text-decoration: none; font-size: 14px; }
        .btn-back:hover { background: #f3f4f6; }
        .btn-primary { background: #2563eb; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: 0.2s; font-size: 14px; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-secondary { background: white; border: 1px solid #ddd; padding: 10px 16px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 5px; color: #374151; transition: 0.2s; font-size: 14px; }
        .btn-secondary:hover { background: #f3f4f6; }
        .table-card { background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        thead { background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
        th, td { padding: 16px; text-align: right; border-bottom: 1px solid #f1f5f9; white-space: nowrap; }
        th { font-weight: bold; color: #475569; font-size: 15px; }
        tbody tr:hover { background-color: #f8fafc; }
        .btn-icon { border: none; background: none; cursor: pointer; font-size: 1.1rem; padding: 6px; border-radius: 4px; transition: background 0.2s; display: inline-flex; align-items: center; justify-content: center; }
        .btn-icon:hover { background: #e2e8f0; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); backdrop-filter: blur(2px); }
        .modal-content { background-color: #fefefe; margin: 5% auto; padding: 25px; border: 1px solid #888; width: 90%; max-width: 500px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); animation: slideDown 0.3s ease-out; }
        @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px; }
        .modal-header h2 { margin: 0; color: #1f2937; font-size: 20px; }
        .close-btn { color: #9ca3af; font-size: 24px; font-weight: bold; cursor: pointer; line-height: 1; }
        .close-btn:hover { color: #4b5563; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; outline: none; transition: border-color 0.2s; }
        .form-group input:focus, .form-group select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
        .row { display: flex; gap: 15px; }
        .col { flex: 1; }
        .modal-footer { margin-top: 25px; display: flex; justify-content: flex-end; gap: 10px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
    </style>
</head>
<body>

    <div class="page">
        <div class="page-header">
            <a href="{{ route('dashboard') }}" class="btn-back">
                <span>←</span> رجوع للوحة التحكم
            </a>
            <h1>بيان المخازن والعهد</h1>
            <div class="header-actions">
                <form method="GET" action="{{ route('stores.index') }}" style="margin:0; display:flex; align-items:center; gap:10px;">
                    <select name="responsible_employee_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">-- كل أصحاب العهد --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('responsible_employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <button class="btn-primary" onclick="openModal('createModal')">
                    <span>➕</span> إضافة مخزن
                </button>
                <button class="btn-secondary" onclick="window.print()">
                    <span>📄</span> طباعة
                </button>
            </div>
        </div>

        @if(session('success'))
            <div style="background:#d1fae5; color:#065f46; padding:12px; border-radius:8px; margin-bottom:20px; border:1px solid #a7f3d0;">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div style="background:#fee2e2; color:#b91c1c; padding:12px; border-radius:8px; margin-bottom:20px; border:1px solid #fecaca;">
                <ul style="margin:0; padding-right:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-card">
            <table id="storesTable">
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>اسم المخزن</th>
                        <th>اسم صاحب العهدة</th>
                        <th>التصنيف</th>
                        <th>نوع العهدة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stores as $store)
                    <tr>
                        <td style="font-family: monospace; font-size: 14px; color: #2563eb;">{{ $store->code ?? '-' }}</td>
                        <td style="font-weight:bold; color:#1f2937;">{{ $store->name }}</td>
                        <td>
                            @if($store->manager)
                                {{ $store->manager->name }}
                            @else
                                <span style="color:#ef4444; font-size:13px;">غير محدد</span>
                            @endif
                        </td>
                        <td><span class="badge badge-blue">{{ $store->classification }}</span></td>
                        <td>{{ $store->custody_type }}</td>
                        <td style="display:flex; gap:5px;">
                            <button class="btn-icon" title="تعديل" onclick='openEditModal(@json($store))'>✏️</button>
                            <form action="{{ route('stores.destroy', $store->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف {{ $store->name }}؟');" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon" title="حذف" style="color:#dc2626;">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 40px; color:#6b7280;">لا توجد بيانات مسجلة حالياً.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- CREATE MODAL -->
    <div id="createModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>إضافة مخزن جديد</h2>
                <span class="close-btn" onclick="closeModal('createModal')">&times;</span>
            </div>
            <form action="{{ route('stores.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>اسم المخزن <span style="color:red">*</span></label>
                    <input type="text" name="name" required placeholder="مثال: المخزن الرئيسي">
                </div>
                <div class="row">
                    <div class="col form-group">
                        <label>الكود</label>
                        <input type="text" name="code" placeholder="اختياري">
                    </div>
                    <div class="col form-group">
                        <label>أمين العهدة</label>
                        <select name="responsible_employee_id">
                            <option value="">-- اختر موظف --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>التصنيف <span style="color:red">*</span></label>
                    <input type="text" name="classification" list="classifications" required placeholder="مثال: المستديم">
                    <datalist id="classifications">
                        <option value="المستديم"><option value="المستهلك"><option value="المالية"><option value="المحاصيل الزراعية">
                    </datalist>
                </div>
                <div class="form-group">
                    <label>نوع العهدة <span style="color:red">*</span></label>
                    <select name="custody_type" required>
                        <option value="مخزن رئيسي">مخزن رئيسي</option>
                        <option value="عهدة فرعية">عهدة فرعية</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('createModal')">إلغاء</button>
                    <button type="submit" class="btn-primary">حفظ البيانات</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>تعديل بيانات المخزن</h2>
                <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>اسم المخزن <span style="color:red">*</span></label>
                    <input type="text" id="edit_name" name="name" required>
                </div>
                <div class="row">
                    <div class="col form-group">
                        <label>الكود</label>
                        <input type="text" id="edit_code" name="code">
                    </div>
                    <div class="col form-group">
                        <label>أمين العهدة</label>
                        <select id="edit_manager" name="responsible_employee_id">
                            <option value="">-- اختر موظف --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>التصنيف <span style="color:red">*</span></label>
                    <input type="text" id="edit_classification" name="classification" required>
                </div>
                <div class="form-group">
                    <label>نوع العهدة <span style="color:red">*</span></label>
                    <select id="edit_custody_type" name="custody_type" required>
                        <option value="مخزن رئيسي">مخزن رئيسي</option>
                        <option value="عهدة فرعية">عهدة فرعية</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('editModal')">إلغاء</button>
                    <button type="submit" class="btn-primary" style="background-color: #ca8a04;">تحديث</button>
                </div>
            </form>
        </div>
    </div>

    <script>
            function openModal(modalId) {
                var el = document.getElementById(modalId);
                if(el) el.style.display = "block";
            }

            function closeModal(modalId) {
                var el = document.getElementById(modalId);
                if(el) el.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target.classList.contains('modal')) {
                    event.target.style.display = "none";
                }
            }

            function openEditModal(store) {
                // DEBUG: Check if we actually received the store data
                console.log("Opening Edit Modal for Store:", store);

                if (!store || !store.id) {
                    alert("Error: Store ID is missing or invalid. Check Console for details.");
                    return;
                }

                // Populate fields
                document.getElementById('edit_name').value = store.name;
                document.getElementById('edit_code').value = store.code;
                document.getElementById('edit_manager').value = store.responsible_employee_id || "";
                document.getElementById('edit_classification').value = store.classification;
                document.getElementById('edit_custody_type').value = store.custody_type;

                // --- FIX & DEBUG FOR URL ---
                // Use a distinct placeholder to avoid accidental replacement
                var urlTemplate = "{{ route('stores.update', '___ID___') }}";
                var finalUrl = urlTemplate.replace('___ID___', store.id);

                console.log("Generated Update URL:", finalUrl);

                // Optional: Uncomment this alert to see the URL on screen before 404
                // alert("Debug: Submitting to " + finalUrl);

                document.getElementById('editForm').action = finalUrl;

                openModal('editModal');
            }
        </script>

</body>
</html>
