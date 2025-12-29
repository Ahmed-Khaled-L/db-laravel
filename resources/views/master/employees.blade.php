<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>الموظفين</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div>
                <h1>قواعد البيانات</h1>
                <p>إدارة الموظفين</p>
            </div>
            <button class="back-btn" onclick="location.href='{{ route('dashboard') }}'">
                رجوع
            </button>
        </div>
    </header>

    <main class="main">

        <div class="title">
            <h2>الموظفين (Employees)</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0; padding-right:20px;">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <!-- ===== Search & Filter ===== -->
        <div class="filter-bar">
            <form method="GET" action="{{ route('employees.index') }}">
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="بحث بالاسم أو الرقم القومي">

                <select name="department_id" id="deptFilter" onchange="this.form.submit()">
                    <option value="">كل الأقسام</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" style="display:none;">بحث</button>
            </form>
        </div>

        <!-- ===== Employee Form (ADD) ===== -->
        <form action="{{ route('employees.store') }}" method="POST" class="form-card">
            @csrf
            <div class="form-row">
                <input disabled placeholder="ID (Auto)" style="background:#eee;">

                <input name="name" id="empName" required placeholder="اسم الموظف" value="{{ old('name') }}">
                <input name="ssn" id="ssn" required placeholder="الرقم القومي" value="{{ old('ssn') }}">
                <input name="mobile" id="mobile" placeholder="رقم الموبايل" value="{{ old('mobile') }}">
                <input name="job_title" id="jobTitle" required placeholder="الوظيفة" value="{{ old('job_title') }}">
                <input type="date" name="birth_date" id="birthDate" required value="{{ old('birth_date') }}">

                <!-- Changed from Input to Select for Data Integrity -->
                <select name="department_id" id="department" required>
                    <option value="">-- القسم --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit">إضافة موظف</button>
        </form>

        <!-- ===== Table ===== -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الرقم القومي</th>
                        <th>الوظيفة</th>
                        <th>القسم</th>
                        <th>موبايلات</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody id="employeesTable">
                    @forelse($employees as $employee)
                    <tr>
                        <td style="font-family: monospace; color: #2563eb;">{{ $employee->id }}</td>
                        <td style="font-weight: bold;">{{ $employee->name }}</td>
                        <td>{{ $employee->ssn }}</td>
                        <td>{{ $employee->job_title }}</td>
                        <td>
                            <span style="background:#f3f4f6; padding:2px 8px; border-radius:4px;">
                                {{ $employee->department->name ?? '-' }}
                            </span>
                        </td>
                        <td>{{ $employee->mobile ?? '-' }}</td>
                        <td style="display:flex; gap:5px;">
                            <button type="button" onclick='openEditModal(@json($employee))' style="border:none; background:none; cursor:pointer;">✏️</button>

                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit" style="border:none; background:none; cursor:pointer; color:red;">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:20px;">لا يوجد موظفين.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 20px; direction: ltr;">
            {{ $employees->withQueryString()->links() }}
        </div>

    </main>

    <!-- ===== Edit Modal ===== -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span>تعديل بيانات موظف</span>
                <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <input name="name" id="edit_name" required placeholder="اسم الموظف" style="padding:8px; border:1px solid #ddd; border-radius:4px;">
                    <input name="ssn" id="edit_ssn" required placeholder="الرقم القومي" style="padding:8px; border:1px solid #ddd; border-radius:4px;">
                    <input name="mobile" id="edit_mobile" placeholder="رقم الموبايل" style="padding:8px; border:1px solid #ddd; border-radius:4px;">
                    <input name="job_title" id="edit_job" required placeholder="الوظيفة" style="padding:8px; border:1px solid #ddd; border-radius:4px;">
                    <input type="date" name="birth_date" id="edit_birth" required style="padding:8px; border:1px solid #ddd; border-radius:4px;">
                    <select name="department_id" id="edit_dept" required style="padding:8px; border:1px solid #ddd; border-radius:4px;">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-top:20px; text-align:left;">
                    <button type="button" onclick="closeModal('editModal')" style="padding:8px 15px; border:1px solid #ddd; background:white; cursor:pointer;">إلغاء</button>
                    <button type="submit" style="padding:8px 15px; border:none; background:#2563eb; color:white; cursor:pointer;">تحديث</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function closeModal(id) { document.getElementById(id).style.display = "none"; }
        function openEditModal(emp) {
            document.getElementById('edit_name').value = emp.name;
            document.getElementById('edit_ssn').value = emp.ssn;
            document.getElementById('edit_mobile').value = emp.mobile || '';
            document.getElementById('edit_job').value = emp.job_title;
            document.getElementById('edit_birth').value = emp.birth_date;
            document.getElementById('edit_dept').value = emp.department_id;

            document.getElementById('editForm').action = "/employees/" + emp.id;
            document.getElementById('editModal').style.display = "block";
        }
    </script>

</body>

</html>
