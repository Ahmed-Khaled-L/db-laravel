<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>الأصناف</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>قواعد البيانات</h1>
                <p>إدارة الأصناف</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('dashboard') }}'">
                رجوع
            </button>
        </div>
    </header>

    <main class="main">

        <div class="title">
            <h2>الأصناف (Items)</h2>
        </div>

        <!-- ===== Feedback Messages ===== -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0; padding-right:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ===== Search & Filter (GET Request) ===== -->
        <form method="GET" action="{{ route('items.index') }}" class="filter-bar">
            <!-- Filter by Unit -->
            <select name="unit" onchange="this.form.submit()">
                <option value="">كل الوحدات</option>
                @foreach($units as $u)
                    <option value="{{ $u }}" {{ request('unit') == $u ? 'selected' : '' }}>
                        {{ $u }}
                    </option>
                @endforeach
            </select>

            <!-- Search Input -->
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الباركود">

            <button type="submit">بحث</button>

            @if(request('search') || request('unit'))
                <button type="button" onclick="location.href='{{ route('items.index') }}'" style="background:#6b7280;">مسح</button>
            @endif
        </form>

        <!-- ===== Add Form (POST Request) ===== -->
        <form action="{{ route('items.store') }}" method="POST" class="form-card">
            @csrf
            <div class="form-row">
                <!-- ID is Auto-increment, hidden or readonly usually, but keeping layout -->
                <input type="text" disabled placeholder="ID (Auto)" style="background:#f3f4f6;">

                <input name="item_name" required placeholder="اسم الصنف *" value="{{ old('item_name') }}">
                <input name="barcode" placeholder="Barcode (اختياري)" value="{{ old('barcode') }}">

                <!-- Datalist for Units to allow new ones or pick existing -->
                <input name="unit" required placeholder="الوحدة *" list="unitOptions" value="{{ old('unit') }}">
                <datalist id="unitOptions">
                    @foreach($units as $u)
                        <option value="{{ $u }}">
                    @endforeach
                </datalist>

                <input name="description" placeholder="الوصف (اختياري)" value="{{ old('description') }}">
            </div>

            <button type="submit" class="btn-add">إضافة صنف جديد</button>
        </form>

        <!-- ===== Table ===== -->
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الصنف</th>
                        <th>Barcode</th>
                        <th>الوحدة</th>
                        <th>الوصف</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td style="font-family:monospace; color:#2563eb;">{{ $item->id }}</td>
                            <td style="font-weight:bold;">{{ $item->item_name }}</td>
                            <td>{{ $item->barcode ?? '-' }}</td>
                            <td><span style="background:#f3f4f6; padding:2px 8px; border-radius:4px;">{{ $item->unit }}</span></td>
                            <td>{{ $item->description ?? '-' }}</td>
                            <td>
                                <!-- Edit (Could be modal or inline, keeping it simple for now) -->
                                <button class="action-btn btn-edit" title="تعديل" onclick="alert('للتعديل يرجى استخدام صفحة التعديل المخصصة')">✏️</button>

                                <!-- Delete -->
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من حذف {{ $item->item_name }}؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" title="حذف">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:30px; color:#888;">
                                لا توجد أصناف مطابقة للبحث.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div style="padding:20px; direction:ltr;">
            {{ $items->withQueryString()->links() }}
        </div>

    </main>

</body>

</html>
