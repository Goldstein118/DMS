import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

const grid_container_kategori = document.querySelector("#table_kategori");
if (grid_container_kategori) {
  window.kategori_grid = new Grid({
    columns: [
      "Kode Kategori",
      "Nama",
      {
        name: "Aksi",
        formatter: () => {
          const edit = access.hasAccess("tb_kategori", "edit");
          const can_delete = access.hasAccess("tb_kategori", "delete");
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                class="btn btn-warning update_kategori btn-sm"
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
                class="btn btn-danger delete_kategori btn-sm"
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
      }/PHP/API/kategori_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((kategori) => [kategori.kategori_id, kategori.nama, null]),
    },
  });

  window.kategori_grid.render(document.getElementById("table_kategori"));
  setTimeout(() => {
    helper.custom_grid_header("kategori", handle_delete, handle_update);
  }, 200);
}

async function handle_delete(button) {
  const row = button.closest("tr");
  const kategori_id = row.cells[0].textContent;
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
        `/PHP/API/kategori_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { kategori_id: kategori_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire(
          "Berhasil",
          response.message || "Kategori dihapus.",
          "success"
        );
      } else {
        Swal.fire(
          "Gagal",
          response.error || "Gagal menghapus kategori.",
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

  const kategori_id = row.cells[0].textContent;
  const current_nama = row.cells[1].textContent;
  document.getElementById("update_kategori_id").value = kategori_id;
  document.getElementById("update_nama_kategori").value = current_nama;

  await new Promise((resolve) => setTimeout(resolve, 500));
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#modal_kategori_update").modal("show");
}

const submit_kategori_update = document.getElementById(
  "submit_kategori_update"
);
if (submit_kategori_update) {
  submit_kategori_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("no row selected for update.");
      return;
    }
    const row = window.currentRow;
    const kategori_id = document.getElementById("update_kategori_id").value;
    const nama_new = document.getElementById("update_nama_kategori").value;
    if (
      helper.validateField(nama_new, /^[a-zA-Z\s]+$/, "Format nama tidak valid")
    ) {
      try {
        const data_kategori_update = {
          kategori_id: kategori_id,
          nama: nama_new,
        };

        const response = await apiRequest(
          `/PHP/API/kategori_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_kategori_update
        );
        if (response.ok) {
          row.cells[1].textContent = nama_new;

          $("#modal_kategori_update").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.kategori_grid.forceRender();
          setTimeout(() => {
            helper.custom_grid_header("kategori", handle_delete, handle_update);
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
