<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>عهدة شخصية</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2563eb;
            --danger-color: #dc2626;
            --success-color: #10b981;
            --text-color: #374151;
            --bg-light: #f9fafb;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Tajawal', sans-serif;
        }

        /* Modal Backdrop */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(3px);
            transition: opacity 0.3s ease;
        }

        /* Modal Content Card */
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            border-radius: 12px;
            width: 90%;
            max-width: 650px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            /* Clips children to radius */
            animation: slideDown 0.3s ease-out;
            position: relative;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Modal Header */
        .modal-header {
            background: var(--bg-light);
            padding: 15px 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            color: var(--text-color);
            font-weight: 700;
        }

        .close-btn {
            cursor: pointer;
            font-size: 1.5rem;
            color: #9ca3af;
            transition: color 0.2s;
        }

        .close-btn:hover {
            color: var(--danger-color);
        }

        /* Modal Body */
        .modal-body {
            padding: 25px;
        }

        /* Enhanced Table */
        .details-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 10px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
        }

        .details-table th {
            background-color: var(--bg-light);
            color: var(--text-color);
            font-weight: 600;
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }

        .details-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            color: #4b5563;
        }

        .details-table tr:last-child td {
            border-bottom: none;
        }

        .details-table tr:hover {
            background-color: #f3f4f6;
        }

        /* Add New Section Card */
        .add-new-card {
            margin-top: 25px;
            background: #fff;
            border: 1px dashed var(--primary-color);
            background-color: #eff6ff;
            padding: 20px;
            border-radius: 8px;
        }

        .add-new-header {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Form Inputs */
        .form-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Buttons */
        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: opacity 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-action:hover {
            opacity: 0.9;
        }

        .btn-primary-small {
            background: var(--primary-color);
            color: white;
        }

        .btn-danger-small {
            background: #fee2e2;
            color: var(--danger-color);
        }

        .btn-danger-small:hover {
            background: #fecaca;
        }

        .btn-secondary-small {
            background: #e5e7eb;
            color: var(--text-color);
        }

        /* Layout Utils */
        .flex-row {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .flex-grow {
            flex: 1;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            text-align: right;
        }

        .input-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-color);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #9ca3af;
            font-style: italic;
            background: var(--bg-light);
            border-radius: 8px;
            border: 1px dashed var(--border-color);
        }

        /* Toast & General Overrides */
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
            background-color: var(--success-color);
        }

        .toast.error {
            background-color: var(--danger-color);
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

        .modal-footer {
            display: flex;
            justify-content: center;
            gap: 10px;
            padding-top: 20px;
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .data-table th,
        .data-table td {
            font-size: 0.9rem;
            padding: 10px;
            white-space: nowrap;
        }

        .table-card {
            overflow-x: auto;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>إدارة العهد</h1>
                <p>سجل العهد الشخصية (Personnel Custody)</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('dashboard') }}'">
                الرئيسية
            </button>
        </div>
    </header>

    <main class="main">
        <div class="title">
            <h2>قائمة العهد الشخصية</h2>
        </div>

        <div id="toast" class="toast"></div>

        <div class="filter-bar">
            <input type="text" placeholder="بحث باسم الموظف أو الصنف..."
                style="padding: 10px; border-radius: 6px; border: 1px solid #d1d5db; width: 300px;">
            <a href="{{ route('custody.personnel.create') }}" class="btn-primary"
                style="text-decoration: none; padding: 10px 20px; background: #2563eb; color: white; border-radius: 6px; display: inline-flex; align-items: center; gap: 5px;">
                <span>+</span> إضافة جديد
            </a>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>رقم (ID)</th>
                        <th>كود الصنف</th>
                        <th>اسم الصنف</th>
                        <th>العدد</th>
                        <th>سعر الوحدة</th>
                        <th>الاجمالي</th>
                        <th>رقم البند</th>
                        <th>اسم البند</th>
                        <th>اسم صاحب العهدة</th>
                        <th>القسم التابع له</th>
                        <th>ملاحظات</th>
                        <th>التاريخ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($audits as $audit)
                        @php
                            $quantity = $audit->personnelDetail->quantity ?? 0;
                            $price = $audit->unit_price ?? 0;
                            $total = $quantity * $price;
                            $catName = $audit->personnelDetail->category->cat_name ?? 'غير معروف';
                            $deptName = $audit->personnelDetail->employee->department->name ?? '-';

                            $hasDetails = $audit->itemDetails->count() > 0;
                            $detailsJson = json_encode($audit->itemDetails);
                        @endphp
                        <tr>
                            <td>{{ $audit->id }}</td>
                            <td>{{ $audit->item_id }}</td>
                            <td><strong>{{ $audit->item->item_name ?? '-' }}</strong></td>
                            <td><span
                                    style="background:#eff6ff; color:#2563eb; padding:2px 8px; border-radius:10px; font-size:0.85em;">{{ $quantity }}</span>
                            </td>
                            <td>{{ number_format($price, 2) }}</td>
                            <td style="font-weight:bold; color:#059669;">{{ number_format($total, 2) }}</td>
                            <td>{{ $audit->personnelDetail->category_id ?? '-' }}</td>
                            <td>{{ $catName }}</td>
                            <td>{{ $audit->personnelDetail->employee->name ?? 'غير محدد' }}</td>
                            <td>{{ $deptName }}</td>
                            <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis;">
                                {{ $audit->notes }}</td>
                            <td>{{ $audit->created_at->format('Y-m-d') }}</td>
                            <td style="display:flex; gap:8px; justify-content:center; align-items:center;">
                                <button class="btn-action btn-secondary-small"
                                    onclick='openDetailsModal({{ $detailsJson }}, {{ $audit->id }}, "{{ $audit->item->item_name ?? 'غير محدد' }}")'
                                    title="{{ $hasDetails ? 'عرض التفاصيل' : 'إضافة تفاصيل' }}">
                                    {{ $hasDetails ? '📋' : '➕' }}
                                </button>

                                <a href="{{ route('custody.personnel.edit', $audit->id) }}"
                                    class="btn-action btn-secondary-small" title="تعديل" style="text-decoration:none;">
                                    ✏️
                                </a>

                                <button class="btn-action btn-danger-small"
                                    onclick="openDeleteModal('{{ $audit->id }}')" title="حذف"
                                    style="background:none;">
                                    🗑️
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="detailsModalTitle">تفاصيل العهدة</h3>
                <span class="close-btn" onclick="closeModal('detailsModal')">&times;</span>
            </div>

            <div class="modal-body">
                <div style="text-align:right;">
                    <h4
                        style="font-size:0.95rem; color:#4b5563; margin-bottom:10px; border-right: 4px solid var(--primary-color); padding-right: 10px;">
                        الأرقام التسلسلية المسجلة</h4>

                    <div id="detailsTableContainer">
                        <table class="details-table">
                            <thead>
                                <tr>
                                    <th width="40%">Serial No</th>
                                    <th width="35%">تاريخ الانتهاء</th>
                                    <th width="25%">تحكم</th>
                                </tr>
                            </thead>
                            <tbody id="detailsTableBody">
                            </tbody>
                        </table>
                    </div>

                    <div id="noDetailsMsg" class="empty-state" style="display:none;">
                        <span style="font-size: 2rem;">📂</span>
                        <p>لا توجد أرقام تسلسلية مسجلة لهذا القيد حتى الآن.</p>
                    </div>
                </div>

                <div class="add-new-card">
                    <div class="add-new-header">
                        <span>✨</span> إضافة رقم تسلسلي جديد
                    </div>
                    <form id="addDetailForm" action="" method="POST" class="flex-row">
                        @csrf
                        <div class="input-group flex-grow" style="flex:2;">
                            <label class="input-label">Serial No <span style="color:red">*</span></label>
                            <input type="text" name="serial_no" required placeholder="مثال: SN-123456"
                                class="form-input">
                        </div>
                        <div class="input-group flex-grow" style="flex:1;">
                            <label class="input-label">Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-input">
                        </div>
                        <button type="submit" class="btn-action btn-primary-small"
                            style="height:38px; padding: 0 20px;">
                            حفظ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteDetailForm" method="POST" action="{{ route('custody.details.destroySingle') }}"
        style="display:none;">
        @csrf @method('DELETE')
        <input type="hidden" name="serial_no" id="delete_serial_input">
    </form>

    <form id="updateDetailFormGeneric" method="POST" action="{{ route('custody.details.updateSingle') }}"
        style="display:none;">
        @csrf @method('PUT')
        <input type="hidden" name="original_serial_no" id="update_orig_serial">
        <input type="hidden" name="serial_no" id="update_new_serial">
        <input type="hidden" name="expiry_date" id="update_expiry">
    </form>

    <div id="deleteModal" class="modal">
        <div class="modal-content" style="max-width:400px; text-align:center;">
            <div class="modal-body">
                <div style="font-size: 3rem; margin-bottom: 10px;">⚠️</div>
                <h3 style="color: #dc2626; margin-bottom: 10px;">تأكيد الحذف</h3>
                <p style="color: #6b7280; margin-bottom: 20px;">هل أنت متأكد من حذف هذا السجل الرئيسي؟<br>سيتم حذف جميع
                    التفاصيل المرتبطة به.</p>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary"
                            onclick="closeModal('deleteModal')">إلغاء</button>
                        <button type="submit" class="btn-danger">نعم، احذف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // --- DETAILS MODAL LOGIC ---
        function openDetailsModal(details, auditId, itemName) {
            document.getElementById('detailsModalTitle').innerText = 'تفاصيل: ' + itemName;

            // Set Add Form Action
            let addUrl = "{{ route('custody.details.storeSingle', ':id') }}";
            document.getElementById('addDetailForm').action = addUrl.replace(':id', auditId);

            // Populate Table
            const tbody = document.getElementById('detailsTableBody');
            tbody.innerHTML = '';

            if (details.length === 0) {
                document.getElementById('noDetailsMsg').style.display = 'block';
                document.getElementById('detailsTableContainer').style.display = 'none';
            } else {
                document.getElementById('noDetailsMsg').style.display = 'none';
                document.getElementById('detailsTableContainer').style.display = 'block';

                details.forEach(detail => {
                    const tr = document.createElement('tr');
                    const expiry = detail.expiry_date ? detail.expiry_date : '';

                    tr.innerHTML = `
                        <td>
                            <span class="view-mode" style="font-family:monospace; font-weight:bold; color:#4b5563;">${detail.serial_no}</span>
                            <input type="text" class="edit-mode form-input" value="${detail.serial_no}" style="display:none;">
                        </td>
                        <td>
                            <span class="view-mode">${expiry || '<span style="color:#d1d5db">-</span>'}</span>
                            <input type="date" class="edit-mode form-input" value="${expiry}" style="display:none;">
                        </td>
                        <td>
                            <div class="view-mode" style="display:flex; gap:5px; justify-content:center;">
                                <button class="btn-action btn-secondary-small" onclick="toggleEdit(this)" title="تعديل">✏️</button>
                                <button class="btn-action btn-danger-small" onclick="deleteDetail('${detail.serial_no}')" title="حذف">🗑️</button>
                            </div>
                            <div class="edit-mode" style="display:none; gap:5px; justify-content:center;">
                                <button class="btn-action btn-primary-small" onclick="saveEdit(this, '${detail.serial_no}')">حفظ</button>
                                <button class="btn-action btn-secondary-small" onclick="toggleEdit(this)">إلغاء</button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            document.getElementById('detailsModal').style.display = 'block';
        }

        function toggleEdit(btn) {
            const tr = btn.closest('tr');
            tr.querySelectorAll('.view-mode').forEach(el => el.style.display = el.style.display === 'none' ? 'block' :
                'none');
            tr.querySelectorAll('.edit-mode').forEach(el => el.style.display = el.style.display === 'none' ? 'block' :
                'none');

            // Fix flex display for buttons
            tr.querySelectorAll('div.edit-mode').forEach(el => {
                if (el.style.display !== 'none') el.style.display = 'flex';
            });
            tr.querySelectorAll('div.view-mode').forEach(el => {
                if (el.style.display !== 'none') el.style.display = 'flex';
            });
        }

        function saveEdit(btn, originalSerial) {
            const tr = btn.closest('tr');
            const newSerial = tr.querySelector('input[type="text"]').value;
            const newExpiry = tr.querySelector('input[type="date"]').value;

            document.getElementById('update_orig_serial').value = originalSerial;
            document.getElementById('update_new_serial').value = newSerial;
            document.getElementById('update_expiry').value = newExpiry;

            document.getElementById('updateDetailFormGeneric').submit();
        }

        function deleteDetail(serial) {
            if (!confirm('هل أنت متأكد من حذف هذا الرقم التسلسلي؟')) return;
            document.getElementById('delete_serial_input').value = serial;
            document.getElementById('deleteDetailForm').submit();
        }

        // --- GENERAL MODAL & TOAST ---
        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        function openDeleteModal(id) {
            let url = "{{ route('custody.personnel.destroy', ':id') }}";
            url = url.replace(':id', id);
            document.getElementById('deleteForm').action = url;
            document.getElementById('deleteModal').style.display = 'block';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

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
            showToast("يوجد خطأ في البيانات", "error");
        @endif
    </script>
</body>

</html>
