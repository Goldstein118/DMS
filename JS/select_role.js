import config from "../JS/config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
document.addEventListener("DOMContentLoaded", () => {
  const grid_container_role = document.querySelector("#table_role");
  if (grid_container_role) {
    new Grid({
      columns: [
        "Kode Role",
        "Nama",
        "Akses",
        {
          name: "Aksi",
          formatter: (_cells, row) => {
            const current_user_id = access.decryptItem("user_id");
            const row_user_id = row.cells[3].data;
            let edit;
            let can_delete;
            if (access.isOwner()) {
              edit = true;
            } else {
              edit =
                access.hasAccess("tb_role", "edit") &&
                row_user_id !== current_user_id;
            }
            if (access.isOwner) {
              can_delete = true;
            } else {
              can_delete =
                access.hasAccess("tb_role", "delete") &&
                row_user_id !== current_user_id;
            }

            let button = "";

            if (edit) {
              button += `<button type="button"   class="btn btn-warning update_role btn-sm">
          <span id ="button_icon" class="button_icon"><i class="bi bi-pencil-square"></i></span>
          <span id="spinner_update" class="spinner-border spinner-border-sm spinner_update" style="display: none;" role="status" aria-hidden="true"></span>
          </button>`;
            }
            if (can_delete) {
              button += `<button type="button" class="btn btn-danger delete_role btn-sm">
                <i class="bi bi-trash-fill"></i>
              </button>`;
            }
            return html(button);
          },
        },
      ],
      search: {
        enabled: true,
        server: {
          url: (prev, keyword) => {
            if (keyword.length >= 3 && keyword !== "") {
              const separator = prev.includes("?") ? "&" : "?";
              return `${prev}${separator}search=${encodeURIComponent(keyword)}`;
            } else {
              return prev;
            }
          },
          method: "GET",
        },
      },
      sort: true,
      pagination: { limit: 15 },
      server: {
        url: `${
          config.API_BASE_URL
        }/PHP/API/role_API.php?action=select&user_id=${access.decryptItem(
          "user_id"
        )}`,
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
        then: (data) =>
          data.map((role) => [
            role.role_id,
            role.nama,
            role.akses,
            role.user_id,
            null, // Placeholder for the action buttons column
          ]),
      },
    }).render(document.getElementById("table_role"));
    setTimeout(() => {
      const grid_header = document.querySelector("#table_role .gridjs-head");
      const search_Box = grid_header.querySelector(".gridjs-search");

      // Create the button
      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "btn btn-primary btn-sm";
      btn.setAttribute("data-bs-toggle", "modal");
      btn.setAttribute("data-bs-target", "#modal_role");
      btn.innerHTML = '<i class="bi bi-plus-square"></i> Role ';

      // Wrap both button and search bar in a flex container
      const wrapper = document.createElement("div");
      wrapper.className =
        "d-flex justify-content-between align-items-center mb-3";
      if (access.hasAccess("tb_role", "create")) {
        wrapper.appendChild(btn);
      }

      wrapper.appendChild(search_Box);

      // Replace grid header content
      grid_header.innerHTML = "";
      grid_header.appendChild(wrapper);
      const input = document.querySelector("#table_role .gridjs-input");
      grid_header.style.display = "flex";
      grid_header.style.justifyContent = "flex-end";

      search_Box.style.display = "flex";
      search_Box.style.justifyContent = "flex-end";
      search_Box.style.marginLeft = "auto";
      input.placeholder = "Cari Role...";
      document.getElementById("loading_spinner").style.visibility = "hidden";
      $("#loading_spinner").fadeOut();
      attachEventListeners();
    }, 200);
  }
});

function attachEventListeners() {
  document
    .getElementById("table_role")
    .addEventListener("click", function (event) {
      const delete_btn = event.target.closest(".delete_role");
      const update_btn = event.target.closest(".update_role");

      if (delete_btn) {
        handleDeleteRole(delete_btn);
      } else if (update_btn) {
        handleUpdateRole(update_btn);
      }
    });
}
function updateCheckbox(field) {
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

function proses_check_box() {
  const checkboxes = document.querySelectorAll(
    "#modal_role_update .perm-checkbox"
  );
  let results = [];

  checkboxes.forEach((checkbox) => {
    const value = checkbox.checked ? 1 : 0;
    results.push(value);
  });
  results = results.join("");
  console.log(results);
  return results;
}

function event_check_box(field) {
  const selectAll = document.getElementById(`check_all_${field}_update`);
  if (!selectAll) return;
  const checked = selectAll.checked;
  ["view", "create", "edit", "delete"].forEach((action) => {
    const checkbox = document.getElementById(`check_${action}_${field}_update`);
    if (checkbox) {
      checkbox.checked = checked;
    }
  });
}

function view_checkbox(field) {
  ["create", "edit", "delete"].forEach((action) => {
    const checkbox = document.getElementById(`check_${action}_${field}_update`);
    checkbox.addEventListener("change", () => {
      const view = document.getElementById(`check_view_${field}_update`);
      if (checkbox.checked) {
        view.checked = true;
      }
    });
  });
}

async function handleDeleteRole(button) {
  const row = button.closest("tr");
  const roleId = row.cells[0].textContent;
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
      const response = await apiRequest(
        `/PHP/API/role_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        {
          roleId,
        }
      );
      if (response.ok) {
        row.remove();
        Swal.fire("Berhasil", response.message || "Role dihapus.", "success");
      } else {
        Swal.fire(
          "Gagal",
          response.error || "Gagal meenghapus karyawan.",
          "error"
        );
      }
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: error.message,
      });
    }
  }
}

async function handleUpdateRole(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";
  const role_ID = row.cells[0].textContent;
  const currentNama = row.cells[1].textContent;
  const currentAkses = row.cells[2].textContent;
  console.log(currentAkses);

  document.getElementById("update_role_ID").value = role_ID;
  document.getElementById("update_role_name").value = currentNama;

  const checkboxFields = [
    "karyawan",
    "user",
    "role",
    "supplier",
    "customer",
    "channel",
  ];

  checkboxFields.forEach((field) => {
    const checkboxAll = document.getElementById(`check_all_${field}_update`);
    if (checkboxAll) {
      checkboxAll.addEventListener("change", () => event_check_box(field));
    }
  });

  checkboxFields.forEach((field) => {
    view_checkbox(field);
  });

  await new Promise((resolve) => setTimeout(resolve, 500));
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  updateCheckbox(currentAkses);
  $("#modal_role_update").modal("show");
}
const submit_role_update = document.getElementById("submit_role_update");
if (submit_role_update) {
  submit_role_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("No row selected for update.", {
        timeOut: 500,
        extendedTimeOut: 500,
      });
      return;
    }

    const row = window.currentRow;
    const role_ID = document.getElementById("update_role_ID").value;
    const newNama = document.getElementById("update_role_name").value;
    const newAkses = proses_check_box();
    if (
      !newNama ||
      newNama.trim() === "" ||
      !newAkses ||
      newAkses.trim() === ""
    ) {
      toastr.error("Harap isi semua kolom sebelum simpan.");
      return;
    }
    function validateField(field, pattern, errorMessage) {
      if (!pattern.test(field)) {
        toastr.error(errorMessage, {
          timeOut: 500,
          extendedTimeOut: 500,
        });
        return false;
      }
      return true;
    }
    const is_valid =
      validateField(newNama, /^[a-zA-Z\s]+$/, "Format nama tidak valid") &&
      validateField(newAkses, /^[0-9]+$/, "Format akses tidak valid");
    if (is_valid) {
      console.log(newAkses);
      const data_role_update = {
        role_id: role_ID,
        nama: newNama,
        akses: newAkses,
      };
      try {
        const response = await apiRequest(
          `/PHP/API/role_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_role_update
        );

        row.cells[1].textContent = newNama;
        row.cells[2].textContent = newAkses;

        $("#modal_role_update").modal("hide");
        Swal.fire("Berhasil", response.message, "success");
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Gagal",
          text: error.message,
        });
      }
    }
  });
}
