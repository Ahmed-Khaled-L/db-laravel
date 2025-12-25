let employees = [];
let editIndex = null;

function renderTable() {
    const table = document.getElementById("employeesTable");
    const search = document.getElementById("searchInput").value.toLowerCase();
    const deptFilter = document.getElementById("deptFilter").value;

    table.innerHTML = "";

    employees
        .filter(e =>
            (
                e.name.toLowerCase().includes(search) ||
                e.ssn.includes(search)
            ) &&
            (deptFilter === "" || e.department === deptFilter)
        )
        .forEach((e, index) => {
            table.innerHTML += `
        <tr>
          <td>${index + 1}</td>
          <td>${e.name}</td>
          <td>${e.ssn}</td>
          <td>${e.job}</td>
          <td>${e.department}</td>
<td>
  ${e.mobiles.map((m, i) => `
    <span class="mobile-chip">
      ${m}
      <button onclick="removeMobile(${index}, ${i})">×</button>
    </span>
  `).join("")}
</td>


          <td>
            <button onclick="editEmployee(${index})">✏️</button>
            <button onclick="deleteEmployee(${index})">🗑️</button>
          </td>
        </tr>
      `;
        });
}

function addEmployee() {
const mobile = document.getElementById("mobile").value.trim();

const emp = {
  id: empId.value.trim(),
  name: empName.value.trim(),
  ssn: ssn.value.trim(),
  job: jobTitle.value.trim(),
  birth: birthDate.value,
  department: department.value.trim(),
  mobiles: mobile ? [mobile] : []   // 👈 رقم واحد افتراضي
};


    if (!emp.id || !emp.name) {
        alert("البيانات الأساسية مطلوبة");
        return;
    }

    if (editIndex === null) {
        employees.push(emp);
    } else {
        employees[editIndex] = emp;
        editIndex = null;
        document.querySelector(".form-card button").textContent = "إضافة موظف";
    }

    clearForm();
    renderTable();
}

function editEmployee(index) {
    const e = employees[index];

    empId.value = e.id;
    empName.value = e.name;
    ssn.value = e.ssn;
    jobTitle.value = e.job;
    birthDate.value = e.birth;
    department.value = e.department;

    editIndex = index;
    document.querySelector(".form-card button").textContent = "حفظ التعديل";
}

function deleteEmployee(index) {
    if (confirm("هل أنت متأكد؟")) {
        employees.splice(index, 1);
        renderTable();
    }
}

function addMobile(empIndex) {
  const input = document.getElementById(`mobileInput-${empIndex}`);
  const mobile = input.value.trim();

  if (!mobile) return;

  if (employees[empIndex].mobiles.length >= 1) {
    alert("الموظف له رقم موبايل واحد فقط");
    return;
  }

  employees[empIndex].mobiles.push(mobile);
  input.value = "";
  renderTable();
}



function clearForm() {
    empId.value = "";
    empName.value = "";
    ssn.value = "";
    jobTitle.value = "";
    birthDate.value = "";
    department.value = "";
}
