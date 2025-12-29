<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة بند مخزني جديد</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { margin: 0; background: #f4f6f8; font-family: "Cairo", sans-serif; }
    .form-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
    .card { background: white; padding: 32px; width: 450px; max-width: 100%; border-radius: 14px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12); }
    .card h2 { margin-top: 0; margin-bottom: 24px; text-align: center; color: #1f2937; }
    .field { margin-bottom: 16px; }
    .field label { display: block; font-size: 15px; margin-bottom: 6px; font-weight: bold; color: #374151; }
    .field input, .field select, .field textarea { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; font-family: inherit; font-size: 14px; transition: 0.2s; }
    .field input:focus, .field select:focus, .field textarea:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
    .actions { display: flex; gap: 10px; margin-top: 24px; }
    .btn-primary { background: #2563eb; color: white; border: none; padding: 12px; border-radius: 8px; flex: 1; cursor: pointer; font-weight: bold; font-size: 15px; transition: background 0.2s; }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-secondary { background: #f3f4f6; color: #374151; border: none; padding: 12px; border-radius: 8px; flex: 1; cursor: pointer; font-weight: bold; font-size: 15px; transition: background 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
    .btn-secondary:hover { background: #e5e7eb; }
    .alert-error { background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 8px; margin-bottom: 15px; }
  </style>
</head>
<body>

<div class="form-page">
  <form class="card" method="POST" action="{{ route('categories.store') }}">
    @csrf

    <h2>إضافة بند مخزني جديد</h2>

    @if($errors->any())
      <div class="alert-error">
        <ul style="margin:0; padding-right:20px;">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="field">
      <label>رقم البند (ID)</label>
      <input type="number" name="id" required placeholder="مثال: 101" value="{{ old('id') }}">
    </div>

    <div class="field">
      <label>اسم البند</label>
      <input type="text" name="cat_name" required placeholder="مثال: أجهزة كمبيوتر" value="{{ old('cat_name') }}">
    </div>

    <div class="field">
      <label>النوع</label>
      <input type="text" name="type" required placeholder="مثال: المستديم" value="{{ old('type') }}" list="typeOptions">
      <datalist id="typeOptions">
          <option value="المستديم">
          <option value="المستهلك">
          <option value="المالية">
      </datalist>
    </div>

    <div class="field">
      <label>الجهة / المنشأ (اختياري)</label>
      <input type="text" name="organization" placeholder="مثال: Dell" value="{{ old('organization') }}">
    </div>

    <div class="field">
      <label>ملاحظات (اختياري)</label>
      <textarea name="notes" rows="3">{{ old('notes') }}</textarea>
    </div>

    <div class="actions">
      <button type="submit" class="btn-primary">حفظ البيانات</button>
      <a href="{{ route('categories.index') }}" class="btn-secondary">إلغاء</a>
    </div>
  </form>
</div>

</body>
</html>
