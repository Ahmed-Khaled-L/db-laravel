<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إضافة عهدة شخصية</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <style>
        /* Local overrides to ensure the form looks good as a full page */
        .form-card {
            max-width: 1000px;
            margin: 20px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: bold;
            color: #374151;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .section-title {
            grid-column: span 2;
            font-size: 1.1rem;
            color: #2563eb;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .actions {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .save-btn {
            background-color: #10b981;
            /* Emerald green */
            color: white;
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.2s;
        }

        .save-btn:hover {
            background-color: #059669;
        }

        .back-btn-secondary {
            background-color: #6b7280;
            color: white;
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>إضافة عهدة شخصية</h1>
                <p>تسجيل قيد جديد في عهدة الموظفين</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('custody.personnel.index') }}'">
                رجوع للقائمة
            </button>
        </div>
    </header>

    <main class="main">

        @if ($errors->any())
            <div
                style="max-width:1000px; margin: 0 auto 20px; background: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px;">
                <ul style="margin-right: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('custody.personnel.store') }}" method="POST">
                @csrf

                <div class="form-grid">

                    <div class="section-title">بيانات الدفتر والسجل</div>

                    <div class="form-group">
                        <label>اسم الدفتر <a href="{{ route('registers.index') }}" target="_blank"
                                style="font-size:0.8em; color:#2563eb;">(إضافة جديد؟)</a></label>
                        <select name="register_id" required>
                            <option value="">-- اختر الدفتر --</option>
                            @foreach ($registers as $reg)
                                <option value="{{ $reg->id }}">{{ $reg->register_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>رقم الصفحة</label>
                        <input type="number" name="page_no" placeholder="مثال: 15" required>
                    </div>

                    <div class="section-title">بيانات الصنف والمستلم</div>

                    <div class="form-group">
                        <label>الموظف المستلم</label>
                        <select name="employee_id" required>
                            <option value="">-- اختر الموظف --</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>الصنف <a href="{{ route('items.index') }}" target="_blank"
                                style="font-size:0.8em; color:#2563eb;">(إضافة جديد؟)</a></label>
                        <select name="item_id" required>
                            <option value="">-- اختر الصنف --</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->item_name }} ({{ $item->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>نوع البند (Category Type)</label>
                        <select id="catTypeSelect" name="category_type" required onchange="filterCategories()">
                            <option value="">-- اختر النوع --</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>اسم البند (Category Name)</label>
                        <select id="catNameSelect" name="category_id" required>
                            <option value="">-- اختر البند --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>العدد (الكمية)</label>
                        <input type="number" name="quantity" value="1" min="1" required>
                    </div>

                    <div class="form-group">
                        <label>سعر الوحدة</label>
                        <input type="number" step="0.01" name="unit_price" placeholder="0.00" required>
                    </div>

                    <div class="form-group full-width">
                        <label>ملاحظات إضافية</label>
                        <textarea name="notes" rows="3" placeholder="اكتب أي ملاحظات هنا..."></textarea>
                    </div>

                    <div class="form-group full-width"
                        style="margin-top: 10px; background: #f3f4f6; padding: 15px; border-radius: 6px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" name="add_details" value="1" id="detailsCheck"
                                style="width: 20px; height: 20px;">
                            <label for="detailsCheck" style="margin:0; cursor: pointer;">إضافة الأرقام التسلسلية (Serial
                                Numbers) لهذا القيد الآن</label>
                        </div>
                    </div>

                </div>

                <div class="actions">
                    <a href="{{ route('custody.personnel.index') }}" class="back-btn-secondary">إلغاء</a>
                    <button type="submit" class="save-btn">حفظ البيانات</button>
                </div>

            </form>
        </div>
    </main>

    <script>
        const allCategories = @json($categories);

        function filterCategories() {
            const typeSelect = document.getElementById('catTypeSelect');
            const nameSelect = document.getElementById('catNameSelect');
            const selectedType = typeSelect.value;

            nameSelect.innerHTML = '<option value="">-- اختر البند --</option>';

            if (!selectedType) return;

            const filtered = allCategories.filter(cat => cat.type === selectedType);

            filtered.forEach(cat => {
                const option = document.createElement('option');
                option.value = cat.id;
                option.textContent = cat.cat_name || cat.Cat_Name;
                nameSelect.appendChild(option);
            });
        }
    </script>
</body>

</html>
