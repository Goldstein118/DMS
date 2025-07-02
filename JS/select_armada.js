import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
$("#modal_karyawan_update").select2({
  allowClear: true,
  dropdownParent: $("#modal_armada_update"),
});
const grid_container_armada = document.querySelector("#table_armada");
if (grid_container_armada) {
  window.armada_grid = new Grid({
    columns: [
      "Kode Armada",
      "Nama",
      "Karyawan",
      "karyawan_id",
      {
        name: "Aksi",
        formatter: () => {
          const edit = access.hasAccess("tb_armada", "edit");
          const can_delete = access.hasAccess("tb_armada", "delete");
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                class="btn btn-warning update_armada btn-sm"
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
                class="btn btn-danger delete_armada btn-sm"
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
      }/PHP/API/armada_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((armada) => [
          armada.armada_id,
          armada.nama,
          armada.nama_karyawan,
          armada.karyawan_id,
          null,
        ]),
    },
  });
  window.armada_grid.render(document.getElementById("table_armada"));
  setTimeout(() => {
    helper.custom_grid_header("armada", handle_delete, handle_update);
  }, 200);
}

async function handle_delete(button) {
  const row = button.closest("tr");
  const armada_id = row.cells[0].textContent;
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
        `/PHP/API/armada_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { armada_id: armada_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire("Berhasil", response.message || "Armada dihapus.", "success");
      } else {
        Swal.fire(
          "Gagal",
          response.error || "Gagal menghapus armada.",
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
function populate_karyawan(data, current_karyawan_id) {
  const select = $("#update_karyawan_select");
  select.empty();

  data.forEach((item) => {
    select.append(
      new Option(
        `${item.karyawan_id} - ${item.nama}`,
        item.karyawan_id,
        false,
        item.karyawan_id == current_karyawan_id
      )
    );
  });

  select.trigger("change");
}

async function handle_update(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  const armada_id = row.cells[0].textContent;
  const current_nama = row.cells[1].textContent;
  const current_karyawan_id = row.cells[3].textContent;
  document.getElementById("update_armada_id").value = armada_id;
  document.getElementById("update_nama_armada").value = current_nama;

  await new Promise((resolve) => setTimeout(resolve, 500));
  try {
    const response = await apiRequest(
      `/PHP/API/karyawan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_armada&context=edit`
    );
    populate_karyawan(response.data, current_karyawan_id);
  } catch (error) {
    toastr.error("Gagal mengambik data karyawan: " + error.message);
    const button_icon = button.querySelector(".button_icon");
    const spinner = button.querySelector(".spinner_update");
    button_icon.style.display = "none";
    spinner.style.display = "inline-block";
  }
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#modal_armada_update").modal("show");
}

const submit_armada_update = document.getElementById("submit_armada_update");
if (submit_armada_update) {
  submit_armada_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("no row selected for update.");
      return;
    }
    const row = window.currentRow;
    const armada_id = document.getElementById("update_armada_id").value;
    const nama_new = document.getElementById("update_nama_armada").value;
    const karyawan_id_new = $("#update_karyawan_select").val();
    console.log(karyawan_id_new);
    if (
      helper.validateField(nama_new, /^[a-zA-Z\s]+$/, "Format nama tidak valid")
    ) {
      try {
        const data_armada_update = {
          armada_id: armada_id,
          nama: nama_new,
          karyawan_id: karyawan_id_new,
        };

        const response = await apiRequest(
          `/PHP/API/armada_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_armada_update
        );

        if (response.ok) {
          row.cells[1].textContent = nama_new;

          $("#modal_armada_update").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.armada_grid.forceRender();
          setTimeout(() => {
            helper.custom_grid_header("armada", handle_delete, handle_update);
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
