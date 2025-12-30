<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إدارة الموظفين</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>قواعد البيانات</h1>
                <p>إدارة الموظفين</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('dashboard') }}'">رجوع</button>
        </div>
    </header>

    <main class="main">
        <div id="toast" class="toast"></div>

        <div class="title">
            <h2>الموظفين (Employees)</h2>
        </div>

        <div class="filter-bar">
            <form method="GET" action="{{ route('employees.index') }}">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="بحث بالاسم أو الرقم القومي...">
                <select name="department_id" onchange="this.form.submit()">
                    <option value="">كل الأقسام</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}"
                            {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </form>
            <button class="btn-primary" onclick="openModal('addModal')">➕ إضافة موظف</button>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الرقم القومي</th>
                        <th>الوظيفة</th>
                        <th>القسم</th>
                        <th>موبايل</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->ssn }}</td>
                            <td>{{ $employee->job_title }}</td>
                            <td><span class="badge">{{ $employee->department->name ?? '-' }}</span></td>
                            <td>{{ $employee->mobile ?? '-' }}</td>
                            <td>
                                <button class="btn-icon edit"
                                    onclick='openEditModal(@json($employee))'>✏️</button>
                                <button class="btn-icon delete"
                                    onclick='openDeleteModal({{ $employee->id }})'>🗑️</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center">لا يوجد موظفين.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding:10px">{{ $employees->withQueryString()->links() }}</div>
        </div>
    </main>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('addModal')">&times;</span>
            <h3>إضافة موظف جديد</h3>
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>اسم الموظف</label>
                    <input name="name" required>
                </div>
                <div class="form-group">
                    <label>الرقم القومي</label>
                    <input name="ssn" required>
                </div>
                <div class="form-group">
                    <label>الموبايل</label>
                    <input name="mobile">
                </div>
                <div class="form-group">
                    <label>الوظيفة</label>
                    <input name="job_title" required>
                </div>
                <div class="form-group">
                    <label>تاريخ الميلاد</label>
                    <input type="date" name="birth_date" required>
                </div>
                <div class="form-group">
                    <label>القسم</label>
                    <div style="display:flex; gap:5px;">
                        <select name="department_id" required style="flex:1">
                            <option value="">-- اختر --</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('departments.index') }}" class="btn-secondary" title="إدارة الأقسام">+</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
            <h3>تعديل بيانات موظف</h3>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>اسم الموظف</label>
                    <input name="name" id="edit_name" required>
                </div>
                <div class="form-group">
                    <label>الرقم القومي</label>
                    <input name="ssn" id="edit_ssn" required>
                </div>
                <div class="form-group">
                    <label>الموبايل</label>
                    <input name="mobile" id="edit_mobile">
                </div>
                <div class="form-group">
                    <label>الوظيفة</label>
                    <input name="job_title" id="edit_job" required>
                </div>
                <div class="form-group">
                    <label>تاريخ الميلاد</label>
                    <input type="date" name="birth_date" id="edit_birth" required>
                </div>
                <div class="form-group">
                    <label>القسم</label>
                    <select name="department_id" id="edit_dept" required>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-primary">تحديث</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content" style="max-width:400px; text-align:center;">
            <h3 style="color:red">تأكيد الحذف</h3>
            <p>هل أنت متأكد من حذف هذا الموظف؟</p>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div class="modal-footer" style="justify-content:center">
                    <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">إلغاء</button>
                    <button type="submit" class="btn-danger">حذف</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .close-btn {
            float: left;
            font-size: 24px;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-secondary {
            background: #eee;
            border: 1px solid #ccc;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            color: black;
        }

        .toast {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 16px;
            position: fixed;
            z-index: 1000;
            left: 50%;
            bottom: 30px;
            transform: translateX(-50%);
        }

        .toast.show {
            visibility: visible;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        .toast.success {
            background-color: #10b981;
        }

        .toast.error {
            background-color: #ef4444;
        }

        @keyframes fadein {
            from {
                bottom: 0;
                opacity: 0;
            }

            to {
                bottom: 30px;
                opacity: 1;
            }
        }

        @keyframes fadeout {
            from {
                bottom: 30px;
                opacity: 1;
            }

            to {
                bottom: 0;
                opacity: 0;
            }
        }
    </style>

    <script>
        function openModal(id) {
            document.getElementById(id).style.display = 'block';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }
        window.onclick = function(e) {
            if (e.target.classList.contains('modal')) e.target.style.display = 'none';
        }

        function openEditModal(emp) {
            document.getElementById('edit_name').value = emp.name;
            document.getElementById('edit_ssn').value = emp.ssn;
            document.getElementById('edit_mobile').value = emp.mobile || '';
            document.getElementById('edit_job').value = emp.job_title;
            document.getElementById('edit_birth').value = emp.birth_date;
            document.getElementById('edit_dept').value = emp.department_id;
            document.getElementById('editForm').action = "/employees/" + emp.id;
            openModal('editModal');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = "/employees/" + id;
            openModal('deleteModal');
        }

        function showToast(msg, type) {
            var x = document.getElementById("toast");
            x.textContent = msg;
            x.className = "toast show " + type;
            setTimeout(function() {
                x.className = x.className.replace("show", "");
            }, 3000);
        }
        @if (session('success'))
            showToast("{{ session('success') }}", "success");
        @endif
        @if (session('error'))
            showToast("{{ session('error') }}", "error");
        @endif
        @if ($errors->any())
            showToast("خطأ في البيانات", "error");
        @endif
    </script>
</body>

</html>
