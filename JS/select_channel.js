import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";

import * as helper from "./helper.js";
const grid_container_channel = document.querySelector("#table_channel");
if (grid_container_channel) {
  window.channel_grid = new Grid({
    columns: [
      "Kode Channel",
      "Nama",
      {
        name: "Aksi",
        formatter: () => {
          const edit = access.hasAccess("tb_channel", "edit");
          const can_delete = access.hasAccess("tb_channel", "delete");
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                class="btn btn-warning update_channel btn-sm"
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
                class="btn btn-danger delete_channel btn-sm"
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
      }/PHP/API/channel_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((channel) => [channel.channel_id, channel.nama, null]),
    },
  });
  window.channel_grid.render(document.getElementById("table_channel"));
  setTimeout(() => {
    helper.custom_grid_header("channel", handle_delete, handle_update);
  }, 200);
}
async function handle_delete(button) {
  const row = button.closest("tr");
  const channel_id = row.cells[0].textContent;
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
        `/PHP/API/channel_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { channel_id: channel_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire(
          "Berhasil",
          response.message || "Channel dihapus.",
          "success"
        );
      } else {
        Swal.fire(
          "Gagal",
          response.error || "Gagal menghapus channel.",
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

  const channel_id = row.cells[0].textContent;
  const current_nama = row.cells[1].textContent;
  document.getElementById("update_channel_id").value = channel_id;
  document.getElementById("update_nama_channel").value = current_nama;

  await new Promise((resolve) => setTimeout(resolve, 500));
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#modal_channel_update").modal("show");
}

const submit_channel_update = document.getElementById("submit_channel_update");
if (submit_channel_update) {
  submit_channel_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("no row selected for update.");
      return;
    }
    const row = window.currentRow;
    const channel_id = document.getElementById("update_channel_id").value;
    const nama_new = document.getElementById("update_nama_channel").value;
    if (
      helper.validateField(nama_new, /^[a-zA-Z\s]+$/, "Format nama tidak valid")
    ) {
      try {
        const data_channel_update = {
          channel_id: channel_id,
          nama: nama_new,
        };

        const response = await apiRequest(
          `/PHP/API/channel_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_channel_update
        );
        if (response.ok) {
          row.cells[1].textContent = nama_new;

          $("#modal_channel_update").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.channel_grid.forceRender();
          setTimeout(() => {
            helper.custom_grid_header("channel", handle_delete, handle_update);
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
