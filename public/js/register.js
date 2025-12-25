let registers = [];
let editIndex = null;

function renderTable() {
  const table = document.getElementById("registerTable");
  const search = document.getElementById("searchInput").value.toLowerCase();

  table.innerHTML = "";

  registers
    .filter(r =>
      r.register_name.toLowerCase().includes(search) ||
      r.id.toString().includes(search)
    )
    .forEach((r, index) => {
      table.innerHTML += `
        <tr>
          <td>${index + 1}</td>
          <td>${r.id}</td>
          <td>${r.register_name}</td>
          <td>
            <button onclick="editRegister(${index})">✏️</button>
            <button onclick="deleteRegister(${index})">🗑️</button>
          </td>
        </tr>
      `;
    });
}

function addRegister() {
  const id = regId.value.trim();
  const register_name = regName.value.trim();

  if (!id || !register_name) {
    alert("البيانات مطلوبة");
    return;
  }

  if (editIndex === null) {
    const exists = registers.some(r => r.id === id);
    if (exists) {
      alert("السجل موجود بالفعل");
      return;
    }
    registers.push({ id, register_name });
  } else {
    registers[editIndex] = { id, register_name };
    editIndex = null;
    document.querySelector(".form-card button").textContent = "إضافة سجل";
  }

  clearForm();
  renderTable();
}

function editRegister(index) {
  const r = registers[index];
  regId.value = r.id;
  regName.value = r.register_name;
  editIndex = index;
  document.querySelector(".form-card button").textContent = "حفظ التعديل";
}

function deleteRegister(index) {
  if (confirm("هل أنت متأكد من الحذف؟")) {
    registers.splice(index, 1);
    renderTable();
  }
}

function clearForm() {
  regId.value = "";
  regName.value = "";
}
