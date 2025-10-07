import config from "../JS/config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
document.addEventListener("DOMContentLoaded", () => {
  const grid_container_role = document.querySelector("#table_role");
  if (grid_container_role) {
    window.role_grid = new Grid({
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
            if (access.isOwner()) {
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
          "X-Requested-With": "XMLHttpRequest",
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
    });
    window.role_grid.render(document.getElementById("table_role"));
    setTimeout(() => {
      helper.custom_grid_header("role", handle_delete, handle_update);
    }, 200);
  }
});

async function handle_delete(button) {
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

async function handle_update(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";
  const role_ID = row.cells[0].textContent;
  const currentNama = row.cells[1].textContent;
  const currentAkses = row.cells[2].textContent;

  const checkboxFields = [
    "karyawan",
    "user",
    "role",
    "supplier",
    "customer",
    "channel",
    "kategori",
    "brand",
    "produk",
    "divisi",
    "gudang",
    "pricelist",
    "armada",
    "frezzer",
    "promo",
    "satuan",
    "pembelian",
    "data_biaya",
    "invoice",
  ];

  document.getElementById("update_role_ID").value = role_ID;
  document.getElementById("update_role_name").value = currentNama;

  checkboxFields.forEach((field) => {
    const checkboxAll = document.getElementById(`check_all_${field}_update`);
    if (checkboxAll) {
      checkboxAll.addEventListener("change", () =>
        helper.event_check_box(field, "update")
      );
    }
  });

  checkboxFields.forEach((field) => {
    helper.view_checkbox(field);
  });

  await new Promise((resolve) => setTimeout(resolve, 500));
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  helper.updateCheckbox(currentAkses);
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
    const newAkses = helper.proses_check_box("update");
    console.log(newAkses);
    if (
      !newNama ||
      newNama.trim() === "" ||
      !newAkses ||
      newAkses.trim() === ""
    ) {
      toastr.error("Kolom * wajib diisi.");
      return;
    }

    const is_valid =
      helper.validateField(
        newNama,
        /^[a-zA-Z\s]+$/,
        "Format nama tidak valid"
      ) &&
      helper.validateField(newAkses, /^[0-9]+$/, "Format akses tidak valid");
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
        if (response.ok) {
          row.cells[1].textContent = newNama;
          row.cells[2].textContent = newAkses;

          $("#modal_role_update").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.role_grid.forceRender();
          setTimeout(() => {
            helper.custom_grid_header("role", handle_delete, handle_update);
          }, 200);
        }
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
