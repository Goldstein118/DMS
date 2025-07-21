import * as access from "./cek_access.js";
import { apiRequest } from "./api.js";
export function custom_grid_header(
  field,
  handle_delete,
  handle_update,
  handle_view
) {
  const grid_header = document.querySelector(`#table_${field} .gridjs-head`);
  if (!grid_header) return;

  const search_Box = grid_header.querySelector(".gridjs-search");
  if (!search_Box) return;
  let btn;
  if (field != "frezzer") {
    btn = document.createElement("button");
    btn.type = "button";
    btn.className = "btn btn-primary btn-sm";
    btn.setAttribute("data-bs-toggle", "modal");
    btn.setAttribute("data-bs-target", `#modal_${field}`);
  }

  if (
    field === "karyawan" ||
    field === "user" ||
    field === "role" ||
    field === "supplier" ||
    field === "customer"
  ) {
    btn.innerHTML = `<i class="bi bi-person-plus-fill"></i> ${field}`;
  } else {
    if (field != "frezzer") {
      btn.innerHTML = `<i class="bi bi-plus-circle"></i> ${field}`;
    }
  }

  const wrapper = document.createElement("div");
  wrapper.className = "d-flex justify-content-between align-items-center mb-3";

  if (access.hasAccess(`tb_${field}`, "create")) {
    if (field != "frezzer") {
      wrapper.appendChild(btn);
    }
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
  if (field == "pricelist" || field == "produk") {
    const tableElement = document.getElementById(`table_${field}`);
    const handler = function (event) {
      const delete_btn = event.target.closest(`.delete_${field}`);
      const update_btn = event.target.closest(`.update_${field}`);
      const view_btn = event.target.closest(`.view_${field}`);

      if (delete_btn && typeof handle_delete === "function") {
        handle_delete(delete_btn);
      } else if (update_btn && typeof handle_update === "function") {
        handle_update(update_btn);
      } else if (view_btn && typeof handle_view === "function") {
        handle_view(view_btn);
      }
    };

    // First remove any previously attached listeners by replacing the node
    const newTableElement = tableElement.cloneNode(true);
    tableElement.parentNode.replaceChild(newTableElement, tableElement);

    // Now attach the listener
    newTableElement.addEventListener("click", handler);
  } else {
    const tableElement = document.getElementById(`table_${field}`);
    const handler = function (event) {
      const delete_btn = event.target.closest(`.delete_${field}`);
      const update_btn = event.target.closest(`.update_${field}`);

      if (delete_btn && typeof handle_delete === "function") {
        handle_delete(delete_btn);
      } else if (update_btn && typeof handle_update === "function") {
        handle_update(update_btn);
      }
    };

    // First remove any previously attached listeners by replacing the node
    const newTableElement = tableElement.cloneNode(true);
    tableElement.parentNode.replaceChild(newTableElement, tableElement);

    // Now attach the listener
    newTableElement.addEventListener("click", handler);
  }
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
    return results;
  } else {
    const checkboxes = document.querySelectorAll("#modal_role .perm-checkbox");
    let results = [];

    checkboxes.forEach((checkbox) => {
      const value = checkbox.checked ? 1 : 0;
      results.push(value);
    });
    results = results.join("");
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
  // Remove existing commas for processing
  const raw = str.replace(/,/g, "");
  // If it's all digits (integer), format with commas and add .00
  if (/^\d+$/.test(raw)) {
    return Number(raw).toLocaleString("en-US") + ".00";
  }
  // If it's a number with decimals, format with commas
  if (/^\d+(\.\d+)?$/.test(raw)) {
    const parts = raw.split(".");
    let formatted = Number(parts[0]).toLocaleString("en-US");
    if (parts[1]) {
      formatted += "." + parts[1];
    }
    return formatted;
  }
  // Otherwise, return as is
  return str;
}
export function format_npwp(npwp) {
  if (npwp.length == 15) {
    let result = "0" + npwp;
    return result;
  } else {
    return npwp;
  }
}

export function unformat_angka(formattedString) {
  if (
    formattedString === null ||
    formattedString === undefined ||
    formattedString === ""
  ) {
    return formattedString;
  }

  return formattedString.toString().replace(/\.00$/, "");
}

export function format_nominal(element_id) {
  const nominal = document.getElementById(element_id);

  nominal.addEventListener("keyup", function () {
    // Remove all non-digit characters
    const cleaned = this.value.replace(/\D/g, "");

    // If empty, show empty string (don't default to 0)
    if (cleaned === "") {
      this.value = "";
      return;
    }

    // Convert to number and back to string with commas
    const formatted = Number(cleaned).toLocaleString("en-US");
    this.value = formatted;
  });
}

export function load_file_link_group(inputId, groupId, originalLink = null) {
  const inputElement = document.getElementById(inputId);
  const inputGroup = document.getElementById(groupId);

  // Replace input to clear previous event listeners
  const newInput = inputElement.cloneNode(true);
  inputElement.replaceWith(newInput);

  // Eye Button
  let previewBtn = inputGroup.querySelector(".preview-btn");
  if (!previewBtn) {
    previewBtn = document.createElement("button");
    previewBtn.type = "button";
    previewBtn.className = "btn btn-outline-primary preview-btn";
    previewBtn.innerHTML = '<i class="bi bi-eye-fill"></i>';
    previewBtn.style.display = "none";
    inputGroup.appendChild(previewBtn);
  }

  // Clear Button
  let clearBtn = inputGroup.querySelector(".clear-btn");
  if (!clearBtn) {
    clearBtn = document.createElement("button");
    clearBtn.type = "button";
    clearBtn.className = "btn btn-outline-danger clear-btn ";
    clearBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
    clearBtn.style.display = "none";
    inputGroup.appendChild(clearBtn);
  }

  const updateDisplay = (file) => {
    if (file) {
      const blobUrl = URL.createObjectURL(file);
      previewBtn.onclick = () => window.open(blobUrl, "_blank");
      previewBtn.style.display = "inline-block";
      clearBtn.style.display = "inline-block";
    } else if (originalLink) {
      previewBtn.onclick = () => window.open(originalLink, "_blank");
      previewBtn.style.display = "inline-block";
      clearBtn.style.display = "inline-block";
    } else {
      previewBtn.style.display = "none";
      clearBtn.style.display = "none";
    }
  };

  // On file change
  newInput.addEventListener("change", (event) => {
    const file = event.target.files[0];
    updateDisplay(file);
  });

  // Clear functionality
  clearBtn.addEventListener("click", () => {
    newInput.value = "";
    originalLink = null;
    updateDisplay(null);
  });

  // Initial state
  updateDisplay(null);
}

export function load_file_link(
  inputId,
  displayId,
  originalLink = null,
  clearBtnId = null
) {
  const inputElement = document.getElementById(inputId);
  const displayElement = document.getElementById(displayId);

  // Replace input element to clear previous event listeners
  const newInput = inputElement.cloneNode(true);
  inputElement.replaceWith(newInput);
  const clearBtn = clearBtnId ? document.getElementById(clearBtnId) : null;
  const updateDisplay = (file) => {
    if (file) {
      const blobUrl = URL.createObjectURL(file);
      displayElement.innerHTML = `<a href="${blobUrl}" target="_blank"><i class="bi bi-eye-fill"></i></a>`;
      if (clearBtn) clearBtn.style.display = "inline-block";
    } else if (originalLink) {
      displayElement.innerHTML = `<a href="${originalLink}" target="_blank"><i class="bi bi-eye-fill"></i></a>`;
      if (clearBtn) clearBtn.style.display = "inline-block";
    } else {
      displayElement.innerHTML = `<a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Belum ada file</a>`;
      if (clearBtn) clearBtn.style.display = "none";
    }
  };
  newInput.addEventListener("change", (event) => {
    const file = event.target.files[0];
    updateDisplay(file);
  });

  // Optional: Add "clear" button support
  if (clearBtnId) {
    const clearBtn = document.getElementById(clearBtnId);
    clearBtn?.addEventListener("click", () => {
      newInput.value = "";
      originalLink = null;
      updateDisplay(null);
    });
  }

  // Initial load
  updateDisplay(null);
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
  file_input.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (file) {
      let frame = document.getElementById(img_id);
      if (!frame) {
        frame = document.createElement("img");
        frame.id = img_id;
        frame.style.width = "100px";
        frame.style.height = "100px";
        frame.style.objectFit = "cover";
        file_input.parentElement.appendChild(frame);
      }
      frame.src = URL.createObjectURL(file);
      frame.style.display = "block";
    } else {
      let frame = document.getElementById(img_id);
      if (!frame) {
        return;
      } else {
        frame.style.display = "none";
        frame.src = "";
      }
    }
  });
}
let index = 0;
export function addField(action) {
  var myTable = document.getElementById(`${action}_detail_pricelist_tbody`);
  var currentIndex = index++;
  const tr_detail = document.createElement("tr");

  const td_harga = document.createElement("td");
  var input_box = document.createElement("input");
  input_box.setAttribute("id", "harga" + currentIndex);
  input_box.classList.add("form-control");
  input_box.style.textAlign = "right";
  td_harga.appendChild(input_box);

  const td_select = document.createElement("td");
  var select_box = document.createElement("select");
  select_box.setAttribute("id", "produk_select" + currentIndex);
  select_box.classList.add("form-select");
  td_select.appendChild(select_box);

  const td_aksi = document.createElement("td");
  td_aksi.setAttribute("id", "aksi_tbody");
  var delete_button = document.createElement("button");
  delete_button.type = "button";
  delete_button.className = "btn btn-danger btn-sm delete_detail_pricelist";
  delete_button.innerHTML = `<i class="bi bi-trash-fill"></i>`;
  td_aksi.appendChild(delete_button);
  td_aksi.style.textAlign = "center";

  tr_detail.appendChild(td_select);
  tr_detail.appendChild(td_harga);
  tr_detail.appendChild(td_aksi);

  myTable.appendChild(tr_detail);

  format_nominal("harga" + currentIndex);
  select_detail_pricelist(currentIndex, action);
}

export async function select_detail_pricelist(
  index,
  action,
  current_produk_id
) {
  if (action == "create") {
    $(`#produk_select${index}`).select2({
      placeholder: "Pilih produk",
      allowClear: true,
      dropdownParent: $("#modal_pricelist"),
    });
  } else if (action == "update") {
    $(`#produk_select${index}`).select2({
      placeholder: "Pilih produk",
      allowClear: true,
      dropdownParent: $("#update_modal_pricelist"),
    });
  }

  delete_detail_pricelist(action);
  try {
    const response = await apiRequest(
      `/PHP/API/produk_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_pricelist&context=create`
    );
    const select = $(`#produk_select${index}`);
    select.empty();
    select.append(new Option("Pilih Produk", "", false, false));
    if (action == "create") {
      response.data.forEach((produk) => {
        const option = new Option(
          `${produk.produk_id} - ${produk.nama}`,
          produk.produk_id,
          false,
          false
        );
        select.append(option);
      });
      select.trigger("change");
    } else if (action == "update") {
      response.data.forEach((produk) => {
        const option = new Option(
          `${produk.produk_id} - ${produk.nama}`,
          produk.produk_id,
          false,
          produk.produk_id === current_produk_id
        );
        select.append(option);
      });
      select.val(current_produk_id).trigger("change");
    }
  } catch (error) {
    console.error("error:", error);
  }
}

export function delete_detail_pricelist(action) {
  $(`#${action}_detail_pricelist_tbody`).on(
    "click",
    ".delete_detail_pricelist",
    async function () {
      const result = await Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "Anda tidak dapat mengembalikannya!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Iya, Hapus!",
        cancelButtonText: "Batalkan",
      });
      if (result.isConfirmed) {
        try {
          $(this).closest("tr").remove();
          Swal.fire("Berhasil", "Pricelist dihapus.", "success");
        } catch (error) {
          Swal.fire({
            icon: "error",
            title: "Gagal",
            text: error.message,
          });
        }
      }
    }
  );
}

export function format_date(str) {
  const date = new Date(str);

  const day = String(date.getDate()).padStart(2, "0");
  const monthNames = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
  ];
  const month = monthNames[date.getMonth()];
  const year = date.getFullYear();

  const formatted = `${day} ${month} ${year}`;
  return formatted;
}
export function unformat_date(str) {
  const [day, monthStr, year] = str.split(" ");

  const monthNames = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
  ];
  const month = String(monthNames.indexOf(monthStr) + 1).padStart(2, "0");

  return `${year}-${month}-${day}`;
}
