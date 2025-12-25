let owners = JSON.parse(localStorage.getItem("owners")) || [];

const table = document.getElementById("ownersTable");
const filterName = document.getElementById("filterName");
const filterStore = document.getElementById("filterStore");

/* ===== ملء الدروب داون ===== */
function populateFilters() {
  const names = [...new Set(owners.map(o => o.person))];
  const stores = [...new Set(owners.map(o => o.store))];

  names.forEach(n => {
    const opt = document.createElement("option");
    opt.value = n;
    opt.textContent = n;
    filterName.appendChild(opt);
  });

  stores.forEach(s => {
    const opt = document.createElement("option");
    opt.value = s;
    opt.textContent = s;
    filterStore.appendChild(opt);
  });
}

/* ===== رسم الجدول ===== */
function render(data) {
  table.innerHTML = "";

  if (data.length === 0) {
    table.innerHTML = `
      <tr>
        <td colspan="6" style="text-align:center; color:#888">
          لا توجد بيانات
        </td>
      </tr>
    `;
    return;
  }

  data.forEach((o, i) => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${i + 1}</td>
      <td>${o.store}</td>
      <td>${o.person}</td>
      <td>${o.category}</td>
      <td>${o.type}</td>
      <td class="actions">
        <button onclick="editOwner(${i})">✏️</button>
        <button onclick="deleteOwner(${i})">🗑️</button>
      </td>
    `;
    table.appendChild(tr);
  });
}

/* ===== الفلترة ===== */
function applyFilters() {
  const nameValue = filterName.value;
  const storeValue = filterStore.value;

  let filtered = owners;

  if (nameValue) {
    filtered = filtered.filter(o => o.person === nameValue);
  }

  if (storeValue) {
    filtered = filtered.filter(o => o.store === storeValue);
  }

  render(filtered);
}

/* ===== Events ===== */
filterName.addEventListener("change", applyFilters);
filterStore.addEventListener("change", applyFilters);

/* ===== حذف ===== */
function deleteOwner(index) {
  if (confirm("هل تريد حذف هذا السجل؟")) {
    owners.splice(index, 1);
    localStorage.setItem("owners", JSON.stringify(owners));
    location.reload();
  }
}

/* ===== تعديل ===== */
function editOwner(index) {
  localStorage.setItem("editIndex", index);
  location.href = "owner-add.html";
}

/* ===== init ===== */
populateFilters();
render(owners);
