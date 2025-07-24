import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";

import * as helper from "./helper.js";
const grid_container_satuan = document.querySelector("#table_satuan");
if (grid_container_satuan) {
  $(document).ready(function () {
    $("#update_id_referensi").select2({
      placeholder: "Pilih Satuan Referensi",
      allowClear: true,
      dropdownParent: $("#modal_satuan_update"),
    });
  });
  window.satuan_grid = new Grid({
    columns: [
      "Kode Satuan",
      "Nama",
      "Id Referensi",
      "Kuantitas",
      {
        name: "Aksi",
        formatter: () => {
          const edit = access.hasAccess("tb_satuan", "edit");
          const can_delete = access.hasAccess("tb_satuan", "delete");
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                class="btn btn-warning update_satuan btn-sm"
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
                class="btn btn-danger delete_satuan btn-sm"
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
      }/PHP/API/satuan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((satuan) => [
          satuan.satuan_id,
          satuan.nama,
          satuan.id_referensi,
          satuan.qty,
          null,
        ]),
    },
  });
  window.satuan_grid.render(document.getElementById("table_satuan"));
  setTimeout(() => {
    helper.custom_grid_header("satuan", handle_delete, handle_update);
  }, 200);
}
function populateSatuanDropdown(data, current_satuan_id) {
  const select = $("#update_id_referensi");
  select.empty();
  select.append(new Option("Pilih Satuan Referensi", "", false, false));
  data.forEach((item) => {
    const option = new Option(
      `${item.satuan_id} - ${item.nama}`,
      item.satuan_id,
      false,
      item.satuan_id === current_satuan_id
    );
    select.append(option);
  });

  select.trigger("change");
}
async function handle_delete(button) {
  const row = button.closest("tr");
  const satuan_id = row.cells[0].textContent;
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
        `/PHP/API/satuan_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { satuan_id: satuan_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire("Berhasil", response.message || "Satuan dihapus.", "success");
      } else {
        Swal.fire(
          "Gagal",
          response.error || "Gagal menghapus satuan.",
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

  const satuan_id = row.cells[0].textContent;
  const current_nama = row.cells[1].textContent;
  const current_id_ref = row.cells[2].textContent;
  const current_qty = row.cells[3].textContent;
  document.getElementById("update_satuan_id").value = satuan_id;
  document.getElementById("update_nama_satuan").value = current_nama;
  document.getElementById("update_qty_satuan").value = current_qty;

  await new Promise((resolve) => setTimeout(resolve, 500));

  try {
    const response = await apiRequest(
      `/PHP/API/satuan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`
    );
    populateSatuanDropdown(response.data, current_id_ref);
    button_icon.style.display = "inline-block";
    spinner.style.display = "none";
    $("#modal_satuan_update").modal("show");
    $(`#update_id_referensi option[value="${satuan_id}"]`).detach();
  } catch (error) {
    toastr.error("Gagal mengambil data satuan: " + error.message);
    const button_icon = button.querySelector(".button_icon");
    const spinner = button.querySelector(".spinner_update");
    button_icon.style.display = "none";
    spinner.style.display = "inline-block";
  }
}

const submit_satuan_update = document.getElementById("submit_satuan_update");
if (submit_satuan_update) {
  submit_satuan_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("no row selected for update.");
      return;
    }
    const row = window.currentRow;
    const satuan_id = document.getElementById("update_satuan_id").value;
    const nama_new = document.getElementById("update_nama_satuan").value;
    const id_ref = $("#update_id_referensi").val();
    const qty = document.getElementById("update_qty_satuan").value;

    if (
      helper.validateField(nama_new, /^[a-zA-Z\s]+$/, "Format nama tidak valid")
    ) {
      try {
        const data_satuan_update = {
          satuan_id: satuan_id,
          nama: nama_new,
          id_referensi: id_ref,
          qty: qty,
        };

        const response = await apiRequest(
          `/PHP/API/satuan_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_satuan_update
        );
        if (response.ok) {
          $("#modal_satuan_update").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.satuan_grid.forceRender();
          setTimeout(() => {
            helper.custom_grid_header("satuan", handle_delete, handle_update);
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
