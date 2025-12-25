<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>بيان بأسماء أصحاب العهد</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/owners.css') }}">

    <!-- PDF Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
</head>

<body>

    <div class="page">

        <!-- ===== Header ===== -->
        <div class="page-header">

            <!-- زر الرجوع -->
            <button class="btn-back" onclick="location.href='#'">
                ← رجوع
            </button>

            <h1>بيان بأسماء أصحاب العهد</h1>

            <div class="header-actions">

                <!-- Filters -->
                <select id="filterName">
                    <option value="">كل أصحاب العهد</option>
                    <!-- يتم ملؤها من owners.js -->
                </select>

                <select id="filterStore">
                    <option value="">كل المخازن</option>
                    <!-- يتم ملؤها من owners.js -->
                </select>

                <!-- Actions -->
                <button class="btn-primary" onclick="location.href='#'">
                    ➕ إضافة
                </button>

                <button class="btn-secondary" onclick="exportPDF()">
                    📄 PDF
                </button>

            </div>
        </div>

        <!-- ===== Table ===== -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المخزن</th>
                        <th>اسم صاحب العهدة</th>
                        <th>أرقام الموبايل</th>
                        <th>تصنيف العهدة</th>
                        <th>نوع العهدة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody id="ownersTable">
                    <!-- Rows rendered by owners.js -->
                </tbody>
            </table>
        </div>

    </div>

    <!-- Script -->
    <script src="{{ asset('js/owners.js') }}"></script>
</body>

</html>
