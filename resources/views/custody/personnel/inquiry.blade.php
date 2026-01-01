<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>استعلام العهد الشخصية</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f3f4f6;
        }

        .table-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .search-container {
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
        }

        .search-input:focus {
            border-color: #7c3aed;
            outline: none;
        }

        th {
            cursor: pointer;
            user-select: none;
        }

        th:hover {
            background-color: #e5e7eb;
        }

        .rank-col {
            font-weight: bold;
            color: #7c3aed;
        }

        .empty-row {
            text-align: center;
            color: #9ca3af;
            display: none;
        }
    </style>
</head>

<body>
    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>قسم الاستعلامات</h1>
                <p>بحث في العهد الشخصية</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('dashboard') }}'">الرئيسية</button>
        </div>
    </header>

    <main class="main">
        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" onkeyup="filterTable()"
                placeholder="🔍 بحث شامل (الموظف، القسم، الصنف، الملاحظات...)">
        </div>

        <div class="table-card">
            <div style="overflow-x: auto;">
                <table class="data-table" id="inquiryTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">#</th>
                            <th onclick="sortTable(1)">الموظف</th>
                            <th onclick="sortTable(2)">القسم</th>
                            <th onclick="sortTable(3)">رقم الصنف</th>
                            <th onclick="sortTable(4)">اسم الصنف</th>
                            <th onclick="sortTable(5)">العدد</th>
                            <th onclick="sortTable(6)">سعر الوحدة</th>
                            <th onclick="sortTable(7)">الإجمالي</th>
                            <th onclick="sortTable(8)">ملاحظات</th>
                            <th onclick="sortTable(9)">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($audits as $audit)
                            @php
                                $qty = $audit->personnelDetail->quantity ?? 0;
                                $price = $audit->unit_price ?? 0;
                                $total = $qty * $price;
                                $empName = $audit->personnelDetail->employee->name ?? 'غير محدد';
                                $deptName = $audit->personnelDetail->employee->department->name ?? '-';
                            @endphp
                            <tr>
                                <td class="rank-col">{{ $loop->iteration }}</td>
                                <td style="font-weight:bold;">{{ $empName }}</td>
                                <td>{{ $deptName }}</td>
                                <td>{{ $audit->item_id }}</td>
                                <td>{{ $audit->item->item_name ?? '-' }}</td>
                                <td style="color:#7c3aed; font-weight:bold;">{{ $qty }}</td>
                                <td>{{ number_format($price, 2) }}</td>
                                <td style="color:#059669; font-weight:bold;">{{ number_format($total, 2) }}</td>
                                <td style="max-width:200px; font-size:0.85rem;">{{ $audit->notes }}</td>
                                <td>{{ $audit->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                        <tr id="noResultsRow" class="empty-row">
                            <td colspan="10">لا توجد نتائج مطابقة للبحث</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // --- 1. General Search Function ---
        function filterTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toUpperCase();
            const table = document.getElementById("inquiryTable");
            const rows = table.getElementsByTagName("tr");
            let hasResults = false;
            let visibleCount = 1;

            for (let i = 1; i < rows.length; i++) {
                if (rows[i].id === 'noResultsRow') continue;

                let rowVisible = false;
                const cells = rows[i].getElementsByTagName("td");

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        const txtValue = cells[j].textContent || cells[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            rowVisible = true;
                            break;
                        }
                    }
                }

                if (rowVisible) {
                    rows[i].style.display = "";
                    // Update Rank (#) dynamically for visible rows
                    rows[i].getElementsByTagName("td")[0].innerText = visibleCount++;
                    hasResults = true;
                } else {
                    rows[i].style.display = "none";
                }
            }

            document.getElementById('noResultsRow').style.display = hasResults ? "none" : "table-row";
        }

        // --- 2. High-Performance Sorting Function ---
        function sortTable(n) {
            const table = document.getElementById("inquiryTable");
            const tbody = table.tBodies[0];
            // Convert NodeList to Array for fast sorting
            const rows = Array.from(tbody.querySelectorAll('tr:not(#noResultsRow)'));

            // Determine direction
            const isAsc = table.getAttribute('data-sort-col') == n && table.getAttribute('data-sort-dir') === 'asc' ?
                false : true;
            const dir = isAsc ? 'asc' : 'desc';

            // Update Attributes for next sort
            table.setAttribute('data-sort-col', n);
            table.setAttribute('data-sort-dir', dir);

            // Update Headers (Add Arrows)
            table.querySelectorAll('th').forEach(th => {
                th.innerHTML = th.innerText.replace(/[⬆⬇]/g, '').trim(); // Remove old icons
            });
            const targetTH = table.querySelectorAll('th')[n];
            targetTH.innerHTML += isAsc ? ' ⬆' : ' ⬇';

            // Perform Sort
            rows.sort((rowA, rowB) => {
                const cellA = rowA.cells[n].innerText.trim();
                const cellB = rowB.cells[n].innerText.trim();

                // Remove commas for currency (e.g. "1,200.50" -> "1200.50")
                const cleanA = cellA.replace(/,/g, '');
                const cleanB = cellB.replace(/,/g, '');

                const numA = parseFloat(cleanA);
                const numB = parseFloat(cleanB);

                // Check if valid numbers
                const isNumA = !isNaN(numA) && isFinite(numA);
                const isNumB = !isNaN(numB) && isFinite(numB);

                if (isNumA && isNumB) {
                    return isAsc ? numA - numB : numB - numA;
                } else {
                    return isAsc ? cellA.localeCompare(cellB, 'ar') : cellB.localeCompare(cellA, 'ar');
                }
            });

            // Re-append rows in new order (DOM Move)
            const fragment = document.createDocumentFragment();
            rows.forEach(row => fragment.appendChild(row));
            tbody.appendChild(fragment);

            // Re-apply filter and ranking to ensure UI is consistent
            filterTable();
        }
    </script>
</body>

</html>
