import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

const grid_container_gudang = document.querySelector("#table_gudang");
if (grid_container_gudang) {
  window.gudang_grid = new Grid({
    columns: [
      "Kode Gudang",
      "Nama",
      "Status",
      {
        name: "Aksi",
        formatter: () => {
          const edit = access.hasAccess("tb_gudang", "edit");
          const can_delete = access.hasAccess("tb_gudang", "delete");
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                class="btn btn-warning update_gudang btn-sm"
              >
                <span id="button_icon" class="button_icon">
                  <i class="bi bi-pencil-square"></i>
                </span>
                <span
                  id="spinner_update"
                  class="spinner-border spinner-border-sm spinner_update"
                  style="display: none;"
                  role="status"
                  aria-hidden="true"
                ></span>
              </button>`;
          }
          if (can_delete) {
            button += `<button
                type="button"
                class="btn btn-danger delete_gudang btn-sm"
              >
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
      }/PHP/API/gudang_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((gudang) => [
          gudang.gudang_id,
          gudang.nama,
          gudang.status,
          null,
        ]),
    },
  });

  window.gudang_grid.render(document.getElementById("table_gudang"));
  setTimeout(() => {
    helper.custom_grid_header("gudang", handle_delete, handle_update);
  }, 200);
}

async function handle_delete(button) {
  const row = button.closest("tr");
  const gudang_id = row.cells[0].textContent;
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
        `/PHP/API/gudang_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { gudang_id: gudang_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire("Berhasil", response.message || "Gudang dihapus.", "success");
      } else {
        Swal.fire(
          "Gagal",
          response.error || "Gagal menghapus gudang.",
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

  const gudang_id = row.cells[0].textContent;
  const current_nama = row.cells[1].textContent;
  const status = row.cells[2].textContent;
  document.getElementById("update_gudang_id").value = gudang_id;
  document.getElementById("update_nama_gudang").value = current_nama;
  document.getElementById("update_gudang_status").value = status;

  await new Promise((resolve) => setTimeout(resolve, 500));
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#modal_gudang_update").modal("show");
}

const submit_gudang_update = document.getElementById("submit_gudang_update");
if (submit_gudang_update) {
  submit_gudang_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("no row selected for update.");
      return;
    }
    const row = window.currentRow;
    const gudang_id = document.getElementById("update_gudang_id").value;
    const nama_new = document.getElementById("update_nama_gudang").value;
    const status_new = document.getElementById("update_gudang_status").value;

    if (
      helper.validateField(nama_new, /^[a-zA-Z\s]+$/, "Format nama tidak valid")
    ) {
      try {
        const data_gudang_update = {
          gudang_id: gudang_id,
          nama: nama_new,
          status: status_new,
        };

        const response = await apiRequest(
          `/PHP/API/gudang_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_gudang_update
        );
        if (response.ok) {
          row.cells[1].textContent = nama_new;

          $("#modal_gudang_update").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.gudang_grid.forceRender();
          setTimeout(() => {
            helper.custom_grid_header("gudang", handle_delete, handle_update);
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
