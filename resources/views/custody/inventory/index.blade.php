<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>عهدة المخازن</title>
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

        /* Modal & General Styles */
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
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            border-radius: 12px;
            width: 90%;
            max-width: 650px;
            position: relative;
            padding-bottom: 20px;
        }

        .modal-header {
            background: var(--bg-light);
            padding: 15px 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 25px;
        }

        .details-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid var(--border-color);
            border-radius: 8px;
        }

        .details-table th,
        .details-table td {
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
        }

        /* Buttons */
        .btn-action {
            cursor: pointer;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .btn-primary-small {
            background: var(--primary-color);
            color: white;
        }

        .btn-danger-small {
            background: #fee2e2;
            color: var(--danger-color);
        }

        .btn-secondary-small {
            background: #e5e7eb;
            color: var(--text-color);
        }

        /* Form & Toast */
        .form-input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
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
            background-color: var(--success-color);
        }

        .toast.error {
            background-color: var(--danger-color);
        }

        /* Data Table Specifics */
        .table-card {
            overflow-x: auto;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background: white;
            padding-bottom: 10px;
        }

        .data-table {
            width: 100%;
            text-align: center;
            white-space: nowrap;
            font-size: 0.9rem;
        }

        .data-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            padding: 12px 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        .data-table td {
            padding: 10px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .data-table tr:hover {
            background-color: #f9fafb;
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
</head>

<body>
    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>إدارة العهد</h1>
                <p>سجل عهدة المخازن (Inventory Custody)</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('dashboard') }}'">الرئيسية</button>
        </div>
    </header>

    <main class="main">
        <div class="title">
            <h2>قائمة عهدة المخازن</h2>
        </div>
        <div id="toast" class="toast"></div>

        <div class="filter-bar">
            <input type="text" placeholder="بحث باسم المخزن أو الصنف..."
                style="padding: 10px; border-radius: 6px; border: 1px solid #d1d5db; width: 300px;">
            <a href="{{ route('custody.inventory.create') }}" class="btn-primary"
                style="text-decoration: none; padding: 10px 20px; background: #2563eb; color: white; border-radius: 6px;">+
                إضافة جديد</a>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>رقم الصنف</th>
                        <th>اسم الصنف</th>
                        <th>الوحدة</th>
                        <th>الموجود (واقع الجرد)</th>
                        <th>حالة الصنف (فعلي)</th>
                        <th>الرصيد الدفتري</th>
                        <th>حالة الصنف (دفتري)</th>
                        <th>الزيادة</th>
                        <th>العجز</th>
                        <th>سعر الوحدة</th>
                        <th>القيمة</th>
                        <th>اسم صاحب العهدة</th>
                        <th>رقم البند</th>
                        <th>اسم المخزن</th>
                        <th>نوع العهدة</th>
                        <th>رقم الصفحة</th>
                        <th>التاريخ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($audits as $audit)
                        @php
                            // Data Retrieval
                            $obsQty = $audit->inventoryDetail->observed_quantity ?? 0;
                            $bookedQty = $audit->inventoryDetail->booked_quantity ?? 0;
                            $unitPrice = $audit->unit_price ?? 0;

                            // Calculations
                            // Surplus (Ziada) = Observed > Booked
                            $surplus = max(0, $obsQty - $bookedQty);

                            // Deficit (Ajz) = Booked > Observed
                            $deficit = max(0, $bookedQty - $obsQty);

                            // Value = Price * Observed
                            $value = $unitPrice * $obsQty;

                            // Store & Custodian
                            $store = $audit->inventoryDetail->store;
                            $storeName = $store->name ?? 'غير محدد'; // Assuming 'name' in Store model
                            $custodyType = $store->custody_type ?? '-';
                            $custodianName = $store->manager->name ?? 'غير محدد';

                            // Category Retrieval (From Mappings)
                            // We look for the mapping for this specific item in this store
                            $categoryMapping = \App\Models\StoreItemMapping::where('store_id', $store->id)
                                ->where('item_id', $audit->item_id)
                                ->first();
                            $categoryId = $categoryMapping ? $categoryMapping->category_id : '-';

                            // Details
                            $hasDetails = $audit->itemDetails->count() > 0;
                            $detailsJson = json_encode($audit->itemDetails);
                        @endphp
                        <tr>
                            <td>{{ $audit->item_id }}</td>
                            <td><strong>{{ $audit->item->item_name ?? '-' }}</strong></td>
                            <td>{{ $audit->item->unit ?? '-' }}</td>

                            <td style="font-weight:bold; color:#2563eb;">{{ $obsQty }}</td>
                            <td>{{ $audit->inventoryDetail->observed_state ?? '-' }}</td>

                            <td>{{ $bookedQty }}</td>
                            <td>{{ $audit->inventoryDetail->booked_state ?? '-' }}</td>

                            <td style="color: {{ $surplus > 0 ? '#10b981' : '#9ca3af' }}; font-weight:bold;">
                                {{ $surplus }}
                            </td>
                            <td style="color: {{ $deficit > 0 ? '#dc2626' : '#9ca3af' }}; font-weight:bold;">
                                {{ $deficit }}
                            </td>

                            <td>{{ number_format($unitPrice, 2) }}</td>
                            <td style="color:#059669; font-weight:bold;">{{ number_format($value, 2) }}</td>

                            <td>{{ $custodianName }}</td>
                            <td>{{ $categoryId }}</td>
                            <td>{{ $storeName }}</td>
                            <td>{{ $custodyType }}</td>
                            <td>{{ $audit->page_no }}</td>
                            <td>{{ $audit->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div style="display:flex; gap:5px; justify-content:center;">
                                    <button class="btn-action btn-secondary-small"
                                        onclick='openDetailsModal({{ $detailsJson }}, {{ $audit->id }}, "{{ $audit->item->item_name ?? '' }}")'
                                        title="{{ $hasDetails ? 'عرض التفاصيل' : 'إضافة تفاصيل' }}">
                                        {{ $hasDetails ? '📋' : '➕' }}
                                    </button>
                                    <a href="{{ route('custody.inventory.edit', $audit->id) }}"
                                        class="btn-action btn-secondary-small" title="تعديل">✏️</a>
                                    <button class="btn-action btn-danger-small"
                                        onclick="openDeleteModal('{{ $audit->id }}')" title="حذف">🗑️</button>
                                </div>
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
                <div id="detailsTableContainer">
                    <table class="details-table">
                        <thead>
                            <tr>
                                <th>Serial No</th>
                                <th>تاريخ الانتهاء</th>
                                <th>تحكم</th>
                            </tr>
                        </thead>
                        <tbody id="detailsTableBody"></tbody>
                    </table>
                </div>
                <div id="noDetailsMsg" style="display:none; text-align:center; padding:20px; color:#6b7280;">
                    📂 لا توجد تفاصيل مسجلة
                </div>

                <div
                    style="margin-top:20px; background:#eff6ff; padding:15px; border-radius:8px; border:1px dashed #2563eb;">
                    <h4 style="margin-top:0; color:#2563eb;">✨ إضافة رقم تسلسلي جديد</h4>
                    <form id="addDetailForm" action="" method="POST"
                        style="display:flex; gap:10px; margin-top:10px;">
                        @csrf
                        <input type="text" name="serial_no" required placeholder="Serial No" class="form-input">
                        <input type="date" name="expiry_date" class="form-input">
                        <button type="submit" class="btn-action btn-primary-small">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteDetailForm" method="POST" action="{{ route('custody.details.destroySingle') }}"
        style="display:none;">
        @csrf @method('DELETE') <input type="hidden" name="serial_no" id="delete_serial_input">
    </form>
    <form id="updateDetailFormGeneric" method="POST" action="{{ route('custody.details.updateSingle') }}"
        style="display:none;">
        @csrf @method('PUT')
        <input type="hidden" name="original_serial_no" id="update_orig_serial">
        <input type="hidden" name="serial_no" id="update_new_serial">
        <input type="hidden" name="expiry_date" id="update_expiry">
    </form>

    <div id="deleteModal" class="modal">
        <div class="modal-content" style="max-width:400px; padding:20px; text-align:center;">
            <div style="font-size:3rem; margin-bottom:10px;">⚠️</div>
            <h3 style="color:#dc2626;">تأكيد الحذف</h3>
            <p style="color:#6b7280;">هل أنت متأكد من حذف هذا السجل؟</p>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div style="margin-top:20px; display:flex; gap:10px; justify-content:center;">
                    <button type="button" class="btn-action btn-secondary-small"
                        onclick="closeModal('deleteModal')">إلغاء</button>
                    <button type="submit" class="btn-action btn-danger-small">حذف</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Details Modal Logic
        function openDetailsModal(details, auditId, itemName) {
            document.getElementById('detailsModalTitle').innerText = 'تفاصيل: ' + itemName;
            let addUrl = "{{ route('custody.details.storeSingle', ':id') }}";
            document.getElementById('addDetailForm').action = addUrl.replace(':id', auditId);

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
                            <span class="view-mode" style="font-family:monospace; font-weight:bold;">${detail.serial_no}</span>
                            <input type="text" class="edit-mode form-input" value="${detail.serial_no}" style="display:none;">
                        </td>
                        <td>
                            <span class="view-mode">${expiry || '-'}</span>
                            <input type="date" class="edit-mode form-input" value="${expiry}" style="display:none;">
                        </td>
                        <td>
                            <div class="view-mode" style="display:flex; justify-content:center; gap:5px;">
                                <button class="btn-action btn-secondary-small" onclick="toggleEdit(this)">✏️</button>
                                <button class="btn-action btn-danger-small" onclick="deleteDetail('${detail.serial_no}')">🗑️</button>
                            </div>
                            <div class="edit-mode" style="display:none; justify-content:center; gap:5px;">
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
            tr.querySelectorAll('.view-mode, .edit-mode').forEach(el => {
                const isHidden = window.getComputedStyle(el).display === 'none';
                // Toggle between block (for inputs) and flex (for button containers)
                if (isHidden) {
                    el.style.display = el.tagName === 'DIV' ? 'flex' : 'block';
                } else {
                    el.style.display = 'none';
                }
            });
        }

        function saveEdit(btn, originalSerial) {
            const tr = btn.closest('tr');
            document.getElementById('update_orig_serial').value = originalSerial;
            document.getElementById('update_new_serial').value = tr.querySelector('input[type="text"]').value;
            document.getElementById('update_expiry').value = tr.querySelector('input[type="date"]').value;
            document.getElementById('updateDetailFormGeneric').submit();
        }

        function deleteDetail(serial) {
            if (!confirm('هل أنت متأكد من الحذف؟')) return;
            document.getElementById('delete_serial_input').value = serial;
            document.getElementById('deleteDetailForm').submit();
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        function openDeleteModal(id) {
            let url = "{{ route('custody.inventory.destroy', ':id') }}";
            document.getElementById('deleteForm').action = url.replace(':id', id);
            document.getElementById('deleteModal').style.display = 'block';
        }

        // Window click to close modal
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        @if (session('success'))
            showToast("{{ session('success') }}", "success");
        @endif
        @if (session('error'))
            showToast("{{ session('error') }}", "error");
        @endif

        function showToast(msg, type) {
            var x = document.getElementById("toast");
            x.textContent = msg;
            x.className = "toast show " + type;
            setTimeout(function() {
                x.className = x.className.replace("show", "");
            }, 3000);
        }
    </script>
</body>

</html>
