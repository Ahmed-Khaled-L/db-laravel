<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تعديل عهدة شخصية</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <style>
        /* Reusing styles from create.blade.php */
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
            background-color: #f59e0b;
            /* Amber for update */
            color: white;
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.2s;
        }

        .save-btn:hover {
            background-color: #d97706;
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
                <h1>تعديل عهدة شخصية</h1>
                <p>تعديل بيانات القيد رقم: {{ $audit->id }}</p>
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
            <form action="{{ route('custody.personnel.update', $audit->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">

                    <div class="section-title">بيانات الدفتر والسجل</div>

                    <div class="form-group">
                        <label>اسم الدفتر</label>
                        <select name="register_id" required>
                            <option value="">-- اختر الدفتر --</option>
                            @foreach ($registers as $reg)
                                <option value="{{ $reg->id }}"
                                    {{ $audit->register_id == $reg->id ? 'selected' : '' }}>
                                    {{ $reg->register_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>رقم الصفحة</label>
                        <input type="number" name="page_no" value="{{ $audit->page_no }}" required>
                    </div>

                    <div class="section-title">بيانات الصنف والمستلم</div>

                    <div class="form-group">
                        <label>الموظف المستلم</label>
                        <select name="employee_id" required>
                            <option value="">-- اختر الموظف --</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    {{ $audit->personnelDetail->employee_id == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>الصنف</label>
                        <select name="item_id" required>
                            <option value="">-- اختر الصنف --</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}"
                                    {{ $audit->item_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->item_name }} ({{ $item->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>نوع البند</label>
                        <select id="catTypeSelect" name="category_type" required onchange="filterCategories()">
                            <option value="">-- اختر النوع --</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}"
                                    {{ $audit->personnelDetail->category_type == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>اسم البند</label>
                        <select id="catNameSelect" name="category_id" required>
                            <option value="">-- اختر البند --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>العدد (الكمية)</label>
                        <input type="number" name="quantity" value="{{ $audit->personnelDetail->quantity }}"
                            min="1" required>
                    </div>

                    <div class="form-group">
                        <label>سعر الوحدة</label>
                        <input type="number" step="0.01" name="unit_price" value="{{ $audit->unit_price }}"
                            required>
                    </div>

                    <div class="form-group full-width">
                        <label>ملاحظات إضافية</label>
                        <textarea name="notes" rows="3">{{ $audit->notes }}</textarea>
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('custody.personnel.index') }}" class="back-btn-secondary">إلغاء</a>
                    <button type="submit" class="save-btn">تحديث البيانات</button>
                </div>

            </form>
        </div>
    </main>

    <script>
        const allCategories = @json($categories);
        const savedCategoryId = "{{ $audit->personnelDetail->category_id }}";

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
                if (cat.id == savedCategoryId) {
                    option.selected = true;
                }
                nameSelect.appendChild(option);
            });
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            filterCategories();
        });
    </script>
</body>

</html>
