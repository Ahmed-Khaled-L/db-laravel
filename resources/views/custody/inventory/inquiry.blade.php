<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>استعلام عهدة المخازن</title>
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

        /* Search Bar Styling */
        .search-container {
            margin-bottom: 20px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .search-input:focus {
            border-color: #2563eb;
            outline: none;
        }

        /* Sortable Headers */
        th {
            cursor: pointer;
            user-select: none;
            position: relative;
        }

        th:hover {
            background-color: #e5e7eb;
        }

        th::after {
            content: '↕';
            position: absolute;
            left: 10px;
            opacity: 0.3;
        }

        /* Ranking Column */
        .rank-col {
            font-weight: bold;
            color: #2563eb;
        }

        .empty-row {
            text-align: center;
            font-style: italic;
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
                <p>بحث في عهدة المخازن</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('dashboard') }}'">الرئيسية</button>
        </div>
    </header>

    <main class="main">
        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" onkeyup="filterTable()"
                placeholder="🔍 بحث شامل (اسم الصنف، المخزن، السعر، الكود...)">
        </div>

        <div class="table-card">
            <div style="overflow-x: auto;">
                <table class="data-table" id="inquiryTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">#</th>
                            <th onclick="sortTable(1)">رقم الصنف</th>
                            <th onclick="sortTable(2)">اسم الصنف</th>
                            <th onclick="sortTable(3)">المخزن</th>
                            <th onclick="sortTable(4)">الموجود</th>
                            <th onclick="sortTable(5)">الرصيد الدفتري</th>
                            <th onclick="sortTable(6)">الفائض/العجز</th>
                            <th onclick="sortTable(7)">سعر الوحدة</th>
                            <th onclick="sortTable(8)">القيمة الإجمالية</th>
                            <th onclick="sortTable(9)">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($audits as $index => $audit)
                            @php
                                $obsQty = $audit->inventoryDetail->observed_quantity ?? 0;
                                $bookedQty = $audit->inventoryDetail->booked_quantity ?? 0;
                                $diff = $obsQty - $bookedQty;
                                $totalValue = ($audit->unit_price ?? 0) * $obsQty;
                                $storeName = $audit->inventoryDetail->store->name ?? 'غير محدد';
                            @endphp
                            <tr>
                                <td class="rank-col">{{ $loop->iteration }}</td>
                                <td>{{ $audit->item_id }}</td>
                                <td style="font-weight:bold;">{{ $audit->item->item_name ?? '-' }}</td>
                                <td>{{ $storeName }}</td>
                                <td style="color:#2563eb; font-weight:bold;">{{ $obsQty }}</td>
                                <td>{{ $bookedQty }}</td>
                                <td>
                                    @if ($diff > 0)
                                        <span style="color:#10b981;">+{{ $diff }} (زيادة)</span>
                                    @elseif($diff < 0)
                                        <span style="color:#dc2626;">{{ $diff }} (عجز)</span>
                                    @else
                                        <span style="color:#9ca3af;">مطابق</span>
                                    @endif
                                </td>
                                <td>{{ number_format($audit->unit_price, 2) }}</td>
                                <td style="color:#059669; font-weight:bold;">{{ number_format($totalValue, 2) }}</td>
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
