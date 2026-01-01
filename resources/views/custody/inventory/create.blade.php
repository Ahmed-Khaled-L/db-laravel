<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إضافة عهدة مخزن</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <style>
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
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        .full-width {
            grid-column: span 2;
        }

        .section-title {
            grid-column: span 2;
            color: #2563eb;
            border-bottom: 2px solid #e5e7eb;
            margin: 10px 0;
            padding-bottom: 5px;
            font-weight: bold;
        }

        .save-btn {
            background-color: #10b981;
            color: white;
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header class="navbar">
        <div class="nav-container">
            <h1>إضافة عهدة مخزن</h1>
        </div>
    </header>

    <main class="main">
        @if ($errors->any())
            <div
                style="background:#fee2e2; color:#b91c1c; padding:15px; margin:20px auto; max-width:1000px; border-radius:8px;">
                <ul style="margin-right:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('custody.inventory.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="section-title">بيانات الدفتر</div>
                    <div class="form-group">
                        <label>اسم الدفتر</label>
                        <select name="register_id" required>
                            <option value="">-- اختر الدفتر --</option>
                            @foreach ($registers as $reg)
                                <option value="{{ $reg->id }}">{{ $reg->register_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>رقم الصفحة</label>
                        <input type="number" name="page_no" required placeholder="مثال: 10">
                    </div>

                    <div class="section-title">بيانات المخزن والصنف</div>
                    <div class="form-group">
                        <label>المخزن</label>
                        <select name="store_id" required>
                            <option value="">-- اختر المخزن --</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->code }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>الصنف</label>
                        <select name="item_id" required>
                            <option value="">-- اختر الصنف --</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- NEW: Category Fields --}}
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

                    <div class="section-title">حالة الجرد</div>
                    <div class="form-group">
                        <label>العدد الفعلي (Observed)</label>
                        <input type="number" name="observed_quantity" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>الحالة الفعلية</label>
                        <input type="text" name="observed_state" placeholder="مثال: جيد، تالف..." required>
                    </div>
                    <div class="form-group">
                        <label>العدد الدفتري (Booked)</label>
                        <input type="number" name="booked_quantity" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>الحالة الدفترية</label>
                        <input type="text" name="booked_state" placeholder="مثال: جيد..." required>
                    </div>

                    <div class="section-title">بيانات مالية</div>
                    <div class="form-group">
                        <label>سعر الوحدة</label>
                        <input type="number" step="0.01" name="unit_price" required>
                    </div>

                    <div class="form-group full-width">
                        <label>ملاحظات</label>
                        <textarea name="notes" rows="3"></textarea>
                    </div>

                    <div class="form-group full-width" style="background: #f3f4f6; padding: 15px; border-radius: 6px;">
                        <label style="display:flex; align-items:center; gap:10px;">
                            <input type="checkbox" name="add_details" value="1" style="width:20px;"> إضافة الأرقام
                            التسلسلية الآن
                        </label>
                    </div>
                </div>

                <div style="margin-top:30px; display:flex; justify-content:flex-end; gap:10px;">
                    <a href="{{ route('custody.inventory.index') }}"
                        style="padding:10px 20px; background:#6b7280; color:white; text-decoration:none; border-radius:6px;">إلغاء</a>
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
