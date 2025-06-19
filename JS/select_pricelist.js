import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

document.getElementById("loading_spinner").style.visibility = "hidden";
$("#loading_spinner").fadeOut();

const grid_container_pricelist = document.querySelector("#table_pricelist");
if (grid_container_pricelist) {
  window.pricelist_grid = new Grid({
    columns: [
      "Kode Pricelist",
      "Nama",
      "Harga Default",
      "Tanggal Berlaku",
      "Status",
      {
        name: "Aksi",
        formatter: () => {
          let edit;
          let can_delete;
          if (access.isOwner()) {
            edit = true;
          } else {
            edit = access.hasAccess("tb_pricelist", "edit");
          }
          if (access.isOwner()) {
            can_delete = true;
          } else {
            can_delete = access.hasAccess("tb_pricelist", "delete");
          }
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                id="update_pricelist_button"
                class="btn btn-warning update_pricelist btn-sm"
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
            button += `
        <button type="button" class="btn btn-danger delete_pricelist btn-sm">
          <i class="bi bi-trash-fill"></i>
        </button>
        `;
          }
          button += `
        <button type="button" class="btn btn btn-info view_pricelist btn-sm" data-bs-toggle= "modal" data-bs-target ="#view_modal_pricelist">
          <i class="bi bi-eye"></i>
        </button>
        `;
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
      }/PHP/API/pricelist_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((pricelist) => [
          pricelist.pricelist_id,
          pricelist.nama,
          pricelist.harga_default,
          pricelist.status,
          pricelist.tanggal_berlaku,
          null,
        ]),
    },
  });
  window.pricelist_grid.render(document.getElementById("table_pricelist"));
  setTimeout(() => {
    helper.custom_grid_header(
      "pricelist",
      handle_delete,
      handle_update,
      handle_view
    );
  }, 200);
}
async function handle_view(button) {
  const row = button.closest("tr");
  const pricelist_id = row.cells[0].textContent;
  const result = await apiRequest(
    `/PHP/API/pricelist_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}`,
    "POST",
    { pricelist_id }
  );
  const tableBody = document.getElementById("view_detail_pricelist_tbody");
  tableBody.innerHTML = ""; // Clear previous rows

  if (result) {
    result.data.forEach((detail) => {
      const tr = document.createElement("tr");

      // Create columns
      const tdProduk = document.createElement("td");
      tdProduk.textContent = detail.produk_nama;

      const tdHarga = document.createElement("td");
      tdHarga.textContent = detail.harga;

      const tdPriceNama = document.createElement("td");
      tdPriceNama.textContent = detail.price_nama;

      const tdActions = document.createElement("td");
      const deleteButton = document.createElement("button");
      deleteButton.type = "button";
      deleteButton.className = "btn btn-danger btn-sm delete_detail_pricelist";
      deleteButton.innerHTML = `<i class="bi bi-trash-fill"></i>`;
      tdActions.appendChild(deleteButton);

      // Append all tds to tr
      tr.appendChild(tdProduk);
      tr.appendChild(tdHarga);
      tr.appendChild(tdPriceNama);
      tr.appendChild(tdActions);

      // Append tr to tbody
      tableBody.appendChild(tr);
    });
  } else {
    // Optional: show message if no data found
    const tr = document.createElement("tr");
    const td = document.createElement("td");
    td.colSpan = 4;
    td.className = "text-center text-muted";
    td.textContent = "No details found for this pricelist.";
    tr.appendChild(td);
    tableBody.appendChild(tr);
  }
}

// Attach delete listeners
async function handle_delete(button) {
  const row = button.closest("tr");
  const pricelist_id = row.cells[0].textContent;

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
        `/PHP/API/pricelist_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { pricelist_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire(
          "Berhasil",
          response.message || "Pricelist dihapus.",
          "success"
        );
      } else {
        Swal.fire(
          "Gagal",
          response.error || "Gagal menghapus pricelist.",
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

  const pricelist_id = row.cells[0].textContent;
  const current_nama = row.cells[1].textContent;
  const harga_default = row.cells[2].textContent;
  const current_status = row.cells[3].textContent;
  const tanggal_berlaku = row.cells[4].textContent;

  // Populate the modal fields
  document.getElementById("update_pricelist_id").value = pricelist_id;
  document.getElementById("update_name_pricelist").value = current_nama;
  document.getElementById("update_default_pricelist").value = harga_default;
  document.getElementById("update_status_pricelist").value = current_status;
  document.getElementById("update_tanggal_berlaku").value = tanggal_berlaku;

  await new Promise((resolve) => setTimeout(resolve, 500));
}

const submit_pricelist_update = document.getElementById(
  "submit_pricelist_update"
);

if (submit_pricelist_update) {
  submit_pricelist_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("No row selected for update.");
      return;
    }

    const row = window.currentRow;
    const update_pricelist_id = document.getElementById(
      "update_pricelist_ID"
    ).value;
    const update_pricelist_nama = document.getElementById(
      "update_name_pricelist"
    ).value;
    const update_harga_default = document.getElementById(
      "update_harga_default"
    );
    const update_status = document.getElementById(
      "update_status_pricelist"
    ).value;
    const update_tanggal_berlaku = document.getElementById(
      "update_tanggal_berlaku"
    );
    if (
      !update_pricelist_nama ||
      update_pricelist_nama.trim() === "" ||
      !update_tanggal_berlaku ||
      update_tanggal_berlaku.trim() === ""
    ) {
      toastr.error("Kolom * wajib diisi.");
      return;
    }

    const is_valid = helper.validateField(
      pricelist_nama_new,
      /^[a-zA-Z\s]+$/,
      "Format nama tidak valid"
    );
    if (is_valid) {
      try {
        const data_pricelist_update = {
          pricelist_id: update_pricelist_id,
          nama: update_pricelist_nama,
          harga_default: update_harga_default,
          status: update_status,
          tanggal_berlaku: update_tanggal_berlaku,
        };
        const response = await apiRequest(
          `/PHP/API/pricelist_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_pricelist_update
        );
        if (response.ok) {
          row.cells[1].textContent = update_pricelist_nama;
          row.cells[2].textContent = update_harga_default;
          row.cells[3].textContent = update_status;
          row.cells[4].textContent = update_tanggal_berlaku;

          $("#modal_pricelist_update").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.pricelist_grid.forceRender();
          setTimeout(() => {
            helper.custom_grid_header(
              "pricelist",
              handle_delete,
              handle_update
            );
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
