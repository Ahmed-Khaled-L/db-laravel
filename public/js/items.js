let items = [];
let editIndex = null;

function renderTable() {
  const table = document.getElementById("itemsTable");
  const search = document.getElementById("searchInput").value.toLowerCase();
  const unitFilter = document.getElementById("unitFilter").value;

  table.innerHTML = "";

  items
    .filter(item =>
      (
        item.item_name.toLowerCase().includes(search) ||
        item.barcode.toLowerCase().includes(search)
      ) &&
      (unitFilter === "" || item.unit === unitFilter)
    )
    .forEach((item, index) => {
      table.innerHTML += `
        <tr>
          <td>${index + 1}</td>
          <td>${item.item_name}</td>
          <td>${item.barcode}</td>
          <td>${item.unit}</td>
          <td>${item.description}</td>
          <td>
            <button onclick="editItem(${index})">✏️</button>
            <button onclick="deleteItem(${index})">🗑️</button>
          </td>
        </tr>
      `;
    });
}


function addItem() {
  const id = itemId.value.trim();
  const item_name = itemName.value.trim();
  const barcode = barcodeInput();
  const unit = unitInput();
  const description = descriptionInput();

  if (!id || !item_name) {
    alert("ID واسم الصنف مطلوبين");
    return;
  }

  if (editIndex === null) {
    const exists = items.some(i => i.id === id);
    if (exists) {
      alert("الصنف موجود بالفعل");
      return;
    }
    items.push({ id, item_name, barcode, unit, description });
  } else {
    items[editIndex] = { id, item_name, barcode, unit, description };
    editIndex = null;
    document.querySelector(".form-card button").textContent = "إضافة";
  }

  clearForm();
  renderTable();
}

function editItem(index) {
  const i = items[index];

  itemId.value = i.id;
  itemName.value = i.item_name;
  barcode.value = i.barcode;
  unit.value = i.unit;
  description.value = i.description;

  editIndex = index;
  document.querySelector(".form-card button").textContent = "حفظ التعديل";
}

function deleteItem(index) {
  if (confirm("هل أنت متأكد من الحذف؟")) {
    items.splice(index, 1);
    renderTable();
  }
}

function clearForm() {
  itemId.value = "";
  itemName.value = "";
  barcode.value = "";
  unit.value = "";
  description.value = "";
}

/* helpers */
function barcodeInput() {
  return document.getElementById("barcode").value.trim();
}
function unitInput() {
  return document.getElementById("unit").value.trim();
}
function descriptionInput() {
  return document.getElementById("description").value.trim();
}
