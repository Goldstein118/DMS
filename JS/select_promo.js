import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

const grid_container_promo = document.querySelector("#table_promo");
if (grid_container_promo) {
  window.promo_grid = new Grid({
    columns: [
      "Kode Promo",
      "Nama",
      "Tanggal Berlaku",
      "Tanggal Selesai",
      "Jenis Bonus",
      "Akumulasi",
      "Prioritas",
      "Dibuat Pada",
      "Jenis Diskon",
      "Jumlah Diskon",
      {
        name: "Aksi",
        formatter: () => {
          const edit = access.hasAccess("tb_promo", "edit");
          const can_delete = access.hasAccess("tb_promo", "delete");
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                class="btn btn-warning update_promo btn-sm"
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
                class="btn btn-danger delete_promo btn-sm"
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
      }/PHP/API/promo_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((item) => [
          item.promo_id,
          item.nama,
          item.tanggal_berlaku,
          item.tanggal_selesai,
          item.jenis_bonus,
          item.akumulasi,
          item.prioritas,
          item.created_on,
          item.jenis_diskon,
          item.jumlah_diskon,
          null,
        ]),
    },
  });

  window.promo_grid.render(document.getElementById("table_promo"));
  setTimeout(() => {
    helper.custom_grid_header("promo", handle_delete, handle_update);
  }, 200);
}

async function handle_delete(button) {
  const row = button.closest("tr");
  const promo_id = row.cells[0].textContent;
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
        `/PHP/API/promo_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { promo_id: promo_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire("Berhasil", response.message || "Promo dihapus.", "success");
      } else {
        Swal.fire("Gagal", response.error || "Gagal menghapus promo.", "error");
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

  const promo_id = row.cells[0].textContent;
  const current_nama = row.cells[1].textContent;

  document.getElementById("update_promo_id").value = promo_id;
  document.getElementById("update_nama_promo").value = current_nama;

  await new Promise((resolve) => setTimeout(resolve, 500));
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#modal_promo_update").modal("show");
}

const submit_promo_update = document.getElementById("submit_promo_update");
if (submit_promo_update) {
  submit_promo_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("no row selected for update.");
      return;
    }
    const row = window.currentRow;
    const promo_id = document.getElementById("update_promo_id").value;
    const nama_new = document.getElementById("update_nama_promo").value;
    const status_new = document.getElementById("update_promo_status").value;

    if (
      helper.validateField(nama_new, /^[a-zA-Z\s]+$/, "Format nama tidak valid")
    ) {
      try {
        const data_promo_update = {
          promo_id: promo_id,
          nama: nama_new,
          status: status_new,
        };

        const response = await apiRequest(
          `/PHP/API/promo_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_promo_update
        );
        if (response.ok) {
          row.cells[1].textContent = nama_new;

          $("#modal_promo_update").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.promo_grid.forceRender();
          setTimeout(() => {
            helper.custom_grid_header("promo", handle_delete, handle_update);
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
