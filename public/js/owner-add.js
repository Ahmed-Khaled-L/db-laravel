let owners = JSON.parse(localStorage.getItem("owners")) || [];
const editIndex = localStorage.getItem("editIndex");

if (editIndex !== null) {
  const o = owners[editIndex];
  store.value = o.store;
  person.value = o.person;
  category.value = o.category;
  type.value = o.type;
}

function saveOwner(e) {
  e.preventDefault();

  const owner = {
    store: store.value,
    person: person.value,
    category: category.value,
    type: type.value
  };

  if (editIndex !== null) {
    owners[editIndex] = owner;
    localStorage.removeItem("editIndex");
  } else {
    owners.push(owner);
  }

  localStorage.setItem("owners", JSON.stringify(owners));
  location.href = "owners.html";
}
