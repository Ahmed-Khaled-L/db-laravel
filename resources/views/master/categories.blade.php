<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>بيان بالبنود المخزنية</title>
    <!-- Fonts & Style -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: "Cairo", "Segoe UI", Tahoma, sans-serif;
        }

        body {
            margin: 0;
            background: #f4f6f8;
        }

        .page {
            max-width: 1200px;
            margin: auto;
            padding: 40px 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 26px;
            color: #1f2937;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: "Cairo";
            background: white;
            min-width: 150px;
            cursor: pointer;
        }

        .btn-back {
            background: none;
            border: 1px solid #ddd;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            color: #555;
            transition: 0.2s;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-back:hover {
            background: #f3f4f6;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: 0.2s;
            font-size: 14px;
            text-decoration: none;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: white;
            border: 1px solid #ddd;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            color: #374151;
            transition: 0.2s;
            font-size: 14px;
        }

        .btn-secondary:hover {
            background: #f3f4f6;
        }

        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        thead {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        th,
        td {
            padding: 16px;
            text-align: right;
            border-bottom: 1px solid #f1f5f9;
            white-space: nowrap;
        }

        th {
            font-weight: bold;
            color: #475569;
            font-size: 15px;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        .btn-icon {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 6px;
            border-radius: 4px;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-icon:hover {
            background: #e2e8f0;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-gray {
            background: #f3f4f6;
            color: #374151;
        }
    </style>
</head>

<body>

    <div class="page">

        <div class="page-header">
            <a href="{{ url()->previous() }}" class="btn-back">
                <span>←</span> رجوع
            </a>

            <h1>بيان بالبنود المخزنية (التصنيفات)</h1>

            <div class="header-actions">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('categories.index') }}" style="margin:0;">
                    <select name="type" class="filter-select" onchange="this.form.submit()">
                        <option value="">كل الأنواع</option>
                        @foreach ($types as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Add Button -->
                <a href="{{ route('categories.create') }}" class="btn-primary">
                    <span>➕</span> إضافة
                </a>

                <button class="btn-secondary" onclick="window.print()">
                    <span>📄</span> طباعة
                </button>
            </div>
        </div>

        <!-- Feedback -->
        @if (session('success'))
            <div
                style="background:#d1fae5; color:#065f46; padding:12px; border-radius:8px; margin-bottom:20px; border:1px solid #a7f3d0;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <div class="table-card">
            <table id="categoriesTable">
                <thead>
                    <tr>
                        <th>رقم البند</th>
                        <th>اسم البند</th>
                        <th>الجهة</th>
                        <th>النوع</th>
                        <th>ملاحظات</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td style="font-weight:bold; font-family:monospace; color:#2563eb;">{{ $category->id }}</td>
                            <td style="font-weight:bold;">{{ $category->cat_name }}</td>
                            <td>{{ $category->organization ?? '-' }}</td>
                            <td>
                                <span class="badge badge-gray">
                                    {{ $category->type ?? '-' }}
                                </span>
                            </td>
                            <td style="color:#666; font-size:0.9em;">{{ $category->notes ?? '' }}</td>

                            <td style="display:flex; gap:5px;">
                                <!-- Edit: Passing Composite Key (ID + Type) -->
                                <a href="{{ route('categories.edit', ['id' => $category->id, 'type' => $category->type]) }}"
                                    class="btn-icon" title="تعديل">
                                    ✏️
                                </a>

                                <!-- Delete: Passing Composite Key (ID + Type) -->
                                <form
                                    action="{{ route('categories.destroy', ['id' => $category->id, 'type' => $category->type]) }}"
                                    method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا البند؟');"
                                    style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon" title="حذف"
                                        style="color:#dc2626;">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding: 40px; color:#888;">
                                لا توجد بيانات
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>
