import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const grid_container_brand = document.querySelector("#table_brand");
if (grid_container_brand) {
  window.brand_grid = new Grid({
    columns: [
      "Kode Brand",
      "Nama",
      {
        name: "Aksi",
        formatter: () => {
          const edit = access.hasAccess("tb_brand", "edit");
          const can_delete = access.hasAccess("tb_brand", "delete");
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                class="btn btn-warning update_brand btn-sm"
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
                class="btn btn-danger delete_brand btn-sm"
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
      }/PHP/API/brand_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) => data.map((brand) => [brand.brand_id, brand.nama, null]),
    },
  });
  window.brand_grid.render(document.getElementById("table_brand"));
  setTimeout(() => {
    helper.custom_grid_header("brand", handle_delete, handle_update);
  }, 200);
}

async function handle_delete(button) {
  const row = button.closest("tr");
  const brand_id = row.cells[0].textContent;
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
        `/PHP/API/brand_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { brand_id: brand_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire("Berhasil", response.message || "Brand dihapus.", "success");
      } else {
        Swal.fire("Gagal", response.error || "Gagal menghapus brand.", "error");
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

  const brand_id = row.cells[0].textContent;
  const current_nama = row.cells[1].textContent;
  document.getElementById("update_brand_id").value = brand_id;
  document.getElementById("update_nama_brand").value = current_nama;

  await new Promise((resolve) => setTimeout(resolve, 500));
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#modal_brand_update").modal("show");
}

const submit_brand_update = document.getElementById("submit_brand_update");
if (submit_brand_update) {
  submit_brand_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("no row selected for update.");
      return;
    }
    const row = window.currentRow;
    const brand_id = document.getElementById("update_brand_id").value;
    const nama_new = document.getElementById("update_nama_brand").value;
    if (
      helper.validateField(nama_new, /^[a-zA-Z\s]+$/, "Format nama tidak valid")
    ) {
      try {
        const data_brand_update = {
          brand_id: brand_id,
          nama: nama_new,
        };

        const response = await apiRequest(
          `/PHP/API/brand_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_brand_update
        );

        if (response.ok) {
          row.cells[1].textContent = nama_new;

          $("#modal_brand_update").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.brand_grid.forceRender();
          setTimeout(() => {
            helper.custom_grid_header("brand", handle_delete, handle_update);
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
