let categories = [];
let editIndex = null; // ✅ السطر الناقص

// Render
function renderTable() {
  const table = document.getElementById("categoryTable");
  table.innerHTML = "";

  categories.forEach((c, index) => {
    table.innerHTML += `
      <tr>
        <td>${c.id}</td>
        <td>${c.type}</td>
        <td>${c.name}</td>
        <td>${c.org}</td>
        <td>${c.notes}</td>
        <td>
          <button type="button" onclick="editCategory(${index})">✏️</button>
          <button type="button" onclick="deleteCategory(${index})">🗑️</button>
        </td>
      </tr>
    `;
  });
}


// Create
function addCategory() {

  const id = document.getElementById("catId").value;
  const type = document.getElementById("catType").value;
  const name = document.getElementById("catName").value;
  const org = document.getElementById("org").value;
  const notes = document.getElementById("notes").value;

  if (!id || !type) {
    alert("ID و Type مطلوبين");
    return;
  }

  // ===== Edit Mode =====
  if (editIndex !== null) {
    categories[editIndex] = { id, type, name, org, notes };
    editIndex = null;
    document.querySelector(".form-card button").textContent = "إضافة";
  }
  // ===== Add Mode =====
  else {
    const exists = categories.some(
      c => c.id == id && c.type === type
    );

    if (exists) {
      alert("التصنيف موجود بالفعل");
      return;
    }

    categories.push({ id, type, name, org, notes });
  }

  clearForm();
  renderTable();
}


// Update
function editCategory(index) {
  const c = categories[index];

  document.getElementById("catId").value = c.id;
  document.getElementById("catType").value = c.type;
  document.getElementById("catName").value = c.name;
  document.getElementById("org").value = c.org;
  document.getElementById("notes").value = c.notes;

  editIndex = index;

  document.querySelector(".form-card button").textContent = "حفظ التعديل";
}


// Delete
function deleteCategory(index) {
  if (confirm("هل أنت متأكد من الحذف؟")) {
    categories.splice(index, 1);
    renderTable();
  }
}

function clearForm() {
  document.querySelectorAll(".form-row input").forEach(i => i.value = "");
}
