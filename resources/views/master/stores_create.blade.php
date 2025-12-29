<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة عهدة جديدة</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
        margin: 0;
        background: #f4f6f8;
        font-family: "Cairo", "Segoe UI", Tahoma, sans-serif;
    }

    .form-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .card {
        background: white;
        padding: 32px;
        width: 450px;
        max-width: 100%;
        border-radius: 14px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    .card h2 {
        margin-top: 0;
        margin-bottom: 24px;
        text-align: center;
        color: #1f2937;
    }

    .field {
        margin-bottom: 16px;
    }

    .field label {
        display: block;
        font-size: 15px;
        margin-bottom: 6px;
        font-weight: bold;
        color: #374151;
    }

    .field input,
    .field select {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-family: inherit;
        font-size: 14px;
        transition: 0.2s;
    }

    .field input:focus,
    .field select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .helper-link {
        font-size: 12px;
        color: #2563eb;
        margin-top: 4px;
        display: inline-block;
        text-decoration: none;
    }
    .helper-link:hover {
        text-decoration: underline;
    }

    .actions {
        display: flex;
        gap: 10px;
        margin-top: 24px;
    }

    .btn-primary {
        background: #2563eb;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        flex: 1;
        cursor: pointer;
        font-weight: bold;
        font-size: 15px;
        transition: background 0.2s;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border: none;
        padding: 12px;
        border-radius: 8px;
        flex: 1;
        cursor: pointer;
        font-weight: bold;
        font-size: 15px;
        transition: background 0.2s;
        text-decoration: none; /* Ensure link looks like button */
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    /* Alert Styles */
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 15px;
        text-align: center;
    }

    .alert-error {
        background: #fee2e2;
        color: #b91c1c;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 15px;
    }
  </style>
</head>
<body>

<div class="form-page">

  <!-- Pointing to stores.store -->
  <form class="card" method="POST" action="{{ route('stores.store') }}">
    @csrf

    <h2>إضافة عهدة / مخزن جديد</h2>

    <!-- Feedback Messages -->
    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="alert-error">
        <ul style="margin:0; padding-right:20px;">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- 1. Store Name -->
    <div class="field">
      <label>اسم المخزن (العهدة)</label>
      <input type="text" name="name" required placeholder="مثال: معمل الحاسب الآلي" value="{{ old('name') }}">
    </div>

    <!-- 2. Code -->
    <div class="field">
      <label>الكود (اختياري)</label>
      <input type="text" name="code" placeholder="مثال: 55" value="{{ old('code') }}">
    </div>

    <!-- 3. Employee Selection -->
    <div class="field">
      <label>اسم صاحب العهدة</label>
      <!-- Changed from manager_id to responsible_employee_id -->
      <select name="responsible_employee_id">
          <option value="">-- اختر الموظف --</option>
          @foreach($employees as $emp)
            <option value="{{ $emp->id }}" {{ old('responsible_employee_id') == $emp->id ? 'selected' : '' }}>
                {{ $emp->name }}
            </option>
          @endforeach
      </select>
      <a href="#" class="helper-link" onclick="alert('سيتم إضافة هذه الميزة قريباً')">+ إضافة موظف جديد</a>
    </div>

    <!-- 4. Classification -->
    <div class="field">
      <label>تصنيف العهدة</label>
      <input type="text" name="classification" required placeholder="مثال: المستديم" value="{{ old('classification') }}" list="classOptions">
      <datalist id="classOptions">
          <option value="المستديم">
          <option value="المستهلك">
          <option value="المالية">
      </datalist>
    </div>

    <!-- 5. Custody Type -->
    <div class="field">
      <label>نوع العهدة</label>
      <select name="custody_type">
        <option value="مخزن رئيسي" {{ old('custody_type') == 'مخزن رئيسي' ? 'selected' : '' }}>مخزن رئيسي</option>
        <option value="عهدة فرعية" {{ old('custody_type') == 'عهدة فرعية' ? 'selected' : '' }}>عهدة فرعية</option>
        <option value="عهدة شخصية" {{ old('custody_type') == 'عهدة شخصية' ? 'selected' : '' }}>عهدة شخصية</option>
      </select>
    </div>

    <!-- Actions -->
    <div class="actions">
      <button type="submit" class="btn-primary">حفظ البيانات</button>
      <!-- Pointing to stores.index -->
      <a href="{{ route('stores.index') }}" class="btn-secondary">إلغاء</a>
    </div>
  </form>

</div>

</body>
</html>
