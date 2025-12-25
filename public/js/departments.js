let departments = [];
let editIndex = null;

function renderTable() {
  const table = document.getElementById("departmentsTable");
  const search = document.getElementById("searchInput").value.toLowerCase();

  table.innerHTML = "";

  departments
    .filter(d =>
      d.name.toLowerCase().includes(search) ||
      d.id.toString().includes(search)
    )
    .forEach((d, index) => {
      table.innerHTML += `
        <tr>
          <td>${index + 1}</td>
          <td>${d.id}</td>
          <td>${d.name}</td>
          <td>
            <button onclick="editDepartment(${index})">✏️</button>
            <button onclick="deleteDepartment(${index})">🗑️</button>
          </td>
        </tr>
      `;
    });
}

function addDepartment() {
  const id = deptId.value.trim();
  const name = deptName.value.trim();

  if (!id || !name) {
    alert("ID واسم القسم مطلوبين");
    return;
  }

  if (editIndex === null) {
    const exists = departments.some(d => d.id === id);
    if (exists) {
      alert("القسم موجود بالفعل");
      return;
    }
    departments.push({ id, name });
  } else {
    departments[editIndex] = { id, name };
    editIndex = null;
    document.querySelector(".form-card button").textContent = "إضافة قسم";
  }

  clearForm();
  renderTable();
}

function editDepartment(index) {
  const d = departments[index];
  deptId.value = d.id;
  deptName.value = d.name;
  editIndex = index;
  document.querySelector(".form-card button").textContent = "حفظ التعديل";
}

function deleteDepartment(index) {
  if (confirm("هل أنت متأكد من الحذف؟")) {
    departments.splice(index, 1);
    renderTable();
  }
}

function clearForm() {
  deptId.value = "";
  deptName.value = "";
}
