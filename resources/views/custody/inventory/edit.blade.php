<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تعديل عهدة مخزن</title>
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
            background-color: #f59e0b;
            color: white;
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .save-btn:hover {
            background-color: #d97706;
        }

        .back-btn-secondary {
            background-color: #6b7280;
            color: white;
            padding: 10px 24px;
            border-radius: 6px;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>تعديل عهدة مخزن</h1>
                <p>تعديل بيانات القيد رقم: {{ $audit->id }}</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('custody.inventory.index') }}'">رجوع
                للقائمة</button>
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
            <form action="{{ route('custody.inventory.update', $audit->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="section-title">بيانات الدفتر</div>
                    <div class="form-group">
                        <label>اسم الدفتر</label>
                        <select name="register_id" required>
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

                    <div class="section-title">بيانات المخزن والصنف</div>
                    <div class="form-group">
                        <label>المخزن</label>
                        <select name="store_id" required>
                            @foreach ($stores as $store)
                                <option value="{{ $store->code }}"
                                    {{ $audit->inventoryDetail->store_id == $store->code ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>الصنف</label>
                        <select name="item_id" required>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}"
                                    {{ $audit->item_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->item_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="section-title">حالة الجرد (الكميات)</div>
                    <div class="form-group">
                        <label>الموجود من واقع الجرد</label>
                        <input type="number" name="observed_quantity"
                            value="{{ $audit->inventoryDetail->observed_quantity }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>الحالة الفعلية</label>
                        <input type="text" name="observed_state"
                            value="{{ $audit->inventoryDetail->observed_state }}" required>
                    </div>

                    <div class="form-group">
                        <label>العدد الدفتري</label>
                        <input type="number" name="booked_quantity"
                            value="{{ $audit->inventoryDetail->booked_quantity }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>الحالة الدفترية</label>
                        <input type="text" name="booked_state" value="{{ $audit->inventoryDetail->booked_state }}"
                            required>
                    </div>

                    <div class="section-title">بيانات مالية</div>
                    <div class="form-group">
                        <label>سعر الوحدة</label>
                        <input type="number" step="0.01" name="unit_price" value="{{ $audit->unit_price }}"
                            required>
                    </div>

                    <div class="form-group full-width">
                        <label>ملاحظات</label>
                        <textarea name="notes" rows="3">{{ $audit->notes }}</textarea>
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('custody.inventory.index') }}" class="back-btn-secondary">إلغاء</a>
                    <button type="submit" class="save-btn">تحديث البيانات</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
