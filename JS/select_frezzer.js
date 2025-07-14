import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

const grid_container_frezzer = document.querySelector("#table_frezzer");
if (grid_container_frezzer) {
  // Helper function to get badge HTML based on status
  function getStatusBadge(status) {
    switch (status) {
      case "ready":
        return `<span class="badge text-bg-success">Ready</span>`;
      case "dipakai":
        return `<span class="badge text-bg-primary">Dipakai</span>`;
      case "prosesclaim":
        return `<span class="badge text-bg-warning">Proses Claim</span>`;
      case "rusak":
        return `<span class="badge text-bg-danger">Rusak</span>`;
      default:
        return `<span class="badge text-bg-secondary">${status}</span>`;
    }
  }

  window.frezzer_grid = new Grid({
    columns: [
      "Kode Frezzer",
      "Kode Barcode",
      "Tipe",
      {
        name: "Status",
        formatter: (cell) => html(getStatusBadge(cell)),
      },
      "Merek",
      "Size",
      {
        name: "Aksi",
        formatter: () => {
          const edit = access.hasAccess("tb_frezzer", "edit");
          const can_delete = access.hasAccess("tb_frezzer", "delete");
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                class="btn btn-warning update_frezzer btn-sm"
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
                class="btn btn-danger delete_frezzer btn-sm"
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
      }/PHP/API/frezzer_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((frezzer) => [
          frezzer.frezzer_id,
          frezzer.kode_barcode,
          frezzer.tipe,
          frezzer.status,
          frezzer.merek,
          frezzer.size,
          null,
        ]),
    },
  });

  window.frezzer_grid.render(document.getElementById("table_frezzer"));
  setTimeout(() => {
    helper.custom_grid_header("frezzer", handle_delete, handle_update);
  }, 200);
}

async function handle_delete(button) {
  const row = button.closest("tr");
  const frezzer_id = row.cells[0].textContent;
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
        `/PHP/API/frezzer_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { frezzer_id: frezzer_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire("Berhasil", response.message || "Gudang dihapus.", "success");
      } else {
        Swal.fire(
          "Gagal",
          response.error || "Gagal menghapus frezzer.",
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

  const frezzer_id = row.cells[0].textContent;
  const kode_barcode = row.cells[1].textContent;
  const tipe = row.cells[2].textContent;
  const status = row.cells[3].textContent;
  const merek = row.cells[4].textContent;
  const size = row.cells[5].textContent;
  document.getElementById("update_frezzer_id").value = frezzer_id;
  document.getElementById("update_kode_barcode").value = kode_barcode;
  document.getElementById("update_tipe").value = tipe;
  document.getElementById("update_merek").value = merek;
  document.getElementById("update_size").value = size;
  document.getElementById("update_frezzer_status").value = status;

  await new Promise((resolve) => setTimeout(resolve, 500));
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#modal_frezzer_update").modal("show");
}

const submit_frezzer_update = document.getElementById("submit_frezzer_update");
if (submit_frezzer_update) {
  submit_frezzer_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("no row selected for update.");
      return;
    }
    const row = window.currentRow;
    const frezzer_id = document.getElementById("update_frezzer_id").value;
    const kode_barcode = document.getElementById("update_kode_barcode").value;
    const tipe = document.getElementById("update_tipe").value;
    const merek = document.getElementById("update_merek").value;
    const size = document.getElementById("update_size").value;
    const status = document.getElementById("update_frezzer_status").value;
    if (
      helper.validateField(
        kode_barcode,
        /^[a-zA-Z0-9\s]+$/,
        "Format nama tidak valid"
      )
    ) {
      try {
        const data_frezzer_update = {
          frezzer_id: frezzer_id,
          kode_barcode: kode_barcode,
          tipe: tipe,
          merek: merek,
          size: size,
          status: status,
        };

        const response = await apiRequest(
          `/PHP/API/frezzer_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_frezzer_update
        );
        if (response.ok) {
          $("#modal_frezzer_update").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.frezzer_grid.forceRender();
          setTimeout(() => {
            helper.custom_grid_header("frezzer", handle_delete, handle_update);
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
