import * as access from "./cek_access.js";
import { apiRequest } from "./api.js";
export function custom_grid_header(field, handle_delete, handle_update) {
  const grid_header = document.querySelector(`#table_${field} .gridjs-head`);
  if (!grid_header) return;

  const search_Box = grid_header.querySelector(".gridjs-search");
  if (!search_Box) return;

  const btn = document.createElement("button");
  btn.type = "button";
  btn.className = "btn btn-primary btn-sm";
  btn.setAttribute("data-bs-toggle", "modal");
  btn.setAttribute("data-bs-target", `#modal_${field}`);
  if (
    field === "karyawan" ||
    field === "user" ||
    field === "role" ||
    field === "supplier" ||
    field === "customer"
  ) {
    btn.innerHTML = `<i class="bi bi-person-plus-fill"></i> ${field}`;
  } else {
    btn.innerHTML = `<i class="bi bi-plus-circle"></i> ${field}`;
  }

  const wrapper = document.createElement("div");
  wrapper.className = "d-flex justify-content-between align-items-center mb-3";

  if (access.hasAccess(`tb_${field}`, "create")) {
    wrapper.appendChild(btn);
  }

  wrapper.appendChild(search_Box);

  grid_header.innerHTML = "";
  grid_header.appendChild(wrapper);

  const input = wrapper.querySelector(".gridjs-input");
  grid_header.style.display = "flex";
  grid_header.style.justifyContent = "flex-end";

  search_Box.style.display = "flex";
  search_Box.style.justifyContent = "flex-end";
  search_Box.style.marginLeft = "auto";
  if (input) input.placeholder = `Cari ${field}...`;
  document.getElementById("loading_spinner").style.visibility = "hidden";
  $("#loading_spinner").fadeOut();
  // Attach event listener after header is rebuilt
  document
    .getElementById(`table_${field}`)
    .addEventListener("click", function (event) {
      const delete_btn = event.target.closest(`.delete_${field}`);
      const update_btn = event.target.closest(`.update_${field}`);

      if (delete_btn && typeof handle_delete === "function") {
        handle_delete(delete_btn);
      } else if (update_btn && typeof handle_update === "function") {
        handle_update(update_btn);
      }
    });
}

export function format_no_telp(str) {
  if (str.trim() === "") {
    let result = str;
    return result;
  } else {
    if (7 > str.length) {
      return "Invalid index";
    }
    let format = str.slice(0, 3) + "-" + str.slice(3, 7) + "-" + str.slice(7);
    let result = "+62 " + format;
    return result;
  }
}
export function validateField(field, pattern, errorMessage) {
  if (!field || field.trim() === "") {
    return true;
  }
  if (!pattern.test(field)) {
    toastr.error(errorMessage, {
      timeOut: 500,
      extendedTimeOut: 500,
    });
    return false;
  }
  return true;
}

export function updateCheckbox(field) {
  const checkboxes = document.querySelectorAll(
    "#modal_role_update .perm-checkbox"
  );
  if (!field || field.length < checkboxes.length) {
    console.warn("Field length doesn't match checkbox count");
    return;
  }

  checkboxes.forEach((checkbox, index) => {
    checkbox.checked = field[index] === "1";
  });
}

export function proses_check_box(action) {
  if (action == "update") {
    const checkboxes = document.querySelectorAll(
      "#modal_role_update .perm-checkbox"
    );
    let results = [];

    checkboxes.forEach((checkbox) => {
      const value = checkbox.checked ? 1 : 0;
      results.push(value);
    });
    results = results.join("");
    console.log("Update");
    return results;
  } else {
    const checkboxes = document.querySelectorAll("#modal_role .perm-checkbox");
    let results = [];

    checkboxes.forEach((checkbox) => {
      const value = checkbox.checked ? 1 : 0;
      results.push(value);
    });
    results = results.join("");
    console.log("create");
    return results;
  }
}

export function event_check_box(field, aksi) {
  if (aksi == "update") {
    const selectAll = document.getElementById(`check_all_${field}_update`);
    if (!selectAll) return;
    const checked = selectAll.checked;
    ["view", "create", "edit", "delete"].forEach((action) => {
      const checkbox = document.getElementById(
        `check_${action}_${field}_update`
      );
      if (checkbox) {
        checkbox.checked = checked;
      }
    });
  } else {
    const selectAll = document.getElementById(`check_all_${field}`);
    if (!selectAll) return;
    const checked = selectAll.checked;
    ["view", "create", "edit", "delete"].forEach((action) => {
      const checkbox = document.getElementById(`check_${action}_${field}`);
      if (checkbox) {
        checkbox.checked = checked;
      }
    });
  }
}

export function view_checkbox(field, aksi) {
  if (aksi == "update") {
    ["create", "edit", "delete"].forEach((action) => {
      const checkbox = document.getElementById(
        `check_${action}_${field}_update`
      );
      checkbox.addEventListener("change", () => {
        const view = document.getElementById(`check_view_${field}_update`);
        if (checkbox.checked) {
          view.checked = true;
        }
      });
    });
  } else {
    ["create", "edit", "delete"].forEach((action) => {
      const checkbox = document.getElementById(`check_${action}_${field}`);
      checkbox.addEventListener("change", () => {
        const view = document.getElementById(`check_view_${field}`);
        if (checkbox.checked) {
          view.checked = true;
        }
      });
    });
  }
}
export function format_angka(str) {
  if (str === null || str === undefined || str === "") {
    return str;
  }

  const cleaned = str.toString().replace(/[.,\s]/g, "");

  if (!/^\d+$/.test(cleaned)) {
    return str;
  }
  const result = cleaned.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

  return result + ",00";
}

export function unformat_angka(formattedString) {
  if (
    formattedString === null ||
    formattedString === undefined ||
    formattedString === ""
  ) {
    return formattedString;
  }

  return formattedString.toString().replace(/,00$/, "");
}
export function format_nominal(element_id) {
  var nominal = document.getElementById(element_id);
  nominal.addEventListener(
    "keyup",
    function () {
      if (nominal.value === "") {
        return true;
      } else {
        var n = parseInt(this.value.replace(/\D/g, ""), 10);
        nominal.value = n.toLocaleString("id-ID");
      }
    },
    false
  );
}
export function load_file_link(inputId, displayId, originalLink) {
  const inputElement = document.getElementById(inputId);
  const displayElement = document.getElementById(displayId);

  // Clone to remove old event listeners if needed
  const newInput = inputElement.cloneNode(true);
  inputElement.replaceWith(newInput);

  newInput.addEventListener("change", function (e) {
    const file = e.target.files[0];

    if (file) {
      const blobUrl = URL.createObjectURL(file);
      displayElement.innerHTML = `<a href="${blobUrl}" target="_blank">Lihat</a>`;
    } else if (originalLink) {
      displayElement.innerHTML = `<a href="${originalLink}" target="_blank">Lihat</a>`;
    } else {
      displayElement.innerHTML = "Belum ada file";
    }
  });
}

export async function load_input_file_name(url, element_id, file_name) {
  if (!url) {
    const input = document.querySelector(element_id);
    input.value = "";
    return;
  }
  try {
    const response = await fetch(url);
    const imgBlob = await response.blob();

    const fileName = file_name;
    const file = new File([imgBlob], fileName, {
      type: "image/jpeg",
      lastModified: new Date().getTime(),
    });

    const container = new DataTransfer();
    container.items.add(file);
    document.querySelector(element_id).files = container.files;
  } catch (error) {
    console.error(`load_input_file_name error for ${element_id}:`, error);
  }
}
export function preview(element_id, img_id) {
  const file_input = document.getElementById(element_id);
  const frame = document.getElementById(img_id);

  file_input.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (file) {
      frame.src = URL.createObjectURL(file);
    } else {
      frame.src = "";
    }
  });
}
