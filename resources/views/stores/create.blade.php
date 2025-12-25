<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة صاحب عهد</title>
  <link rel="stylesheet" href="styles/owner-add.css">
</head>
<body>

<div class="form-page">

  <form class="card" onsubmit="saveOwner(event)">
    <h2>إضافة صاحب عهد</h2>

    <div class="field">
      <label>اسم المخزن</label>
      <input required id="store">
    </div>

    <div class="field">
      <label>اسم صاحب العهدة</label>
      <input required id="person">
    </div>

    <div class="field">
      <label>تصنيف العهدة</label>
      <input required id="category">
    </div>

    <div class="field">
      <label>نوع العهدة</label>
      <select id="type">
        <option>مخزن رئيسي</option>
        <option>عهدة فرعية</option>
      </select>
    </div>

    <div class="actions">
      <button type="submit" class="btn-primary">حفظ</button>
      <button type="button" class="btn-secondary" onclick="history.back()">إلغاء</button>
    </div>
  </form>

</div>

<script src="js/owner-add.js"></script>
</body>
</html>
