import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

const grid_container_pricelist = document.querySelector("#table_pricelist");
const pickdatejs = $("#update_tanggal_berlaku")
  .pickadate({
    format: "dd mmm yyyy", // user sees: 01 Jan 2025
    formatSubmit: "yyyy-mm-dd", // hidden value: 01/01/2025
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");
if (grid_container_pricelist) {
  const create_detail_pricelist = document.getElementById(
    "update_detail_pricelist"
  );
  create_detail_pricelist.addEventListener("click", () => {
    helper.addField("update");
  });

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
        <button type="button" class="btn btn btn-info view_pricelist btn-sm" >
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
          helper.format_date(pricelist.tanggal_berlaku),
          pricelist.status,
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

function handle_view(button) {
  const row = button.closest("tr");
  const pricelist_id = row.cells[0].textContent.trim();

  window.open(
    `../PHP/view_pricelist.php?pricelist_id=${encodeURIComponent(
      pricelist_id
    )}`,
    "_blank"
  );
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
  let tanggal_berlaku = row.cells[3].textContent;
  const current_status = row.cells[4].textContent;

  // Populate the modal fields
  document.getElementById("update_pricelist_id").value = pricelist_id;
  document.getElementById("update_name_pricelist").value = current_nama;
  document.getElementById("update_default_pricelist").value = harga_default;
  document.getElementById("update_status_pricelist").value = current_status;
  tanggal_berlaku = helper.unformat_date(tanggal_berlaku);
  const parts = tanggal_berlaku.split("-"); // ["2025", "05", "02"]
  const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
  pickdatejs.set("select", dateObj);

  await new Promise((resolve) => setTimeout(resolve, 500));
  const result = await apiRequest(
    `/PHP/API/pricelist_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}`,
    "POST",
    { pricelist_id }
  );
  const tableBody = document.getElementById("update_detail_pricelist_tbody");
  tableBody.innerHTML = ""; // Clear previous rows

  if (result) {
    result.data.forEach((detail, index) => {
      const tr = document.createElement("tr");
      const current_produk_id = detail.produk_id;
      // Produk select2
      const tdProduk = document.createElement("td");
      const selectProduk = document.createElement("select");
      selectProduk.className = "form-select produk_select";
      selectProduk.setAttribute("id", `produk_select${index}`);
      tdProduk.appendChild(selectProduk);

      // Harga input
      const tdHarga = document.createElement("td");
      const inputHarga = document.createElement("input");
      inputHarga.setAttribute("id", `detail_harga${index}`);
      inputHarga.className = "form-control";
      inputHarga.style.textAlign = "right";

      let harga = helper.unformat_angka(detail.harga);
      inputHarga.value = harga;
      tdHarga.appendChild(inputHarga);

      // Delete button
      const tdDelete = document.createElement("td");
      const deleteBtn = document.createElement("button");
      deleteBtn.type = "button";
      deleteBtn.className = "btn btn-danger btn-sm delete_detail_pricelist";
      deleteBtn.innerHTML = `<i class="bi bi-trash-fill"></i>`;
      tdDelete.appendChild(deleteBtn);
      tdDelete.style.width = "50px";
      // Append all tds
      tr.appendChild(tdProduk);
      tr.appendChild(tdHarga);
      tr.appendChild(tdDelete);

      tableBody.appendChild(tr);
      helper.format_nominal(`detail_harga${index}`);

      helper.select_detail_pricelist(index, "update", current_produk_id);
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

  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#update_modal_pricelist").modal("show");
}

const submit_pricelist_update = document.getElementById(
  "update_submit_pricelist"
);

if (submit_pricelist_update) {
  submit_pricelist_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("No row selected for update.");
      return;
    }

    const row = window.currentRow;
    const update_pricelist_id = document.getElementById(
      "update_pricelist_id"
    ).value;
    const update_pricelist_nama = document.getElementById(
      "update_name_pricelist"
    ).value;
    const update_harga_default = document.getElementById(
      "update_default_pricelist"
    ).value;
    const update_status = document.getElementById(
      "update_status_pricelist"
    ).value;
    const picker = $("#update_tanggal_berlaku").pickadate("picker");
    const update_tanggal_berlaku = picker.get("select", "yyyy-mm-dd");
    const detail_rows = [];
    document
      .querySelectorAll("#update_detail_pricelist_tbody tr")
      .forEach((tr) => {
        const produk_id = $(tr.querySelector("select")).val();
        let harga = tr.querySelector("input").value;
        harga = helper.format_angka(harga);

        // Basic validation
        if (produk_id && harga) {
          detail_rows.push({ produk_id, harga });
        }
      });

    if (detail_rows.length === 0) {
      toastr.error("Minimal satu produk harus diisi.");
      return;
    }

    if (!update_pricelist_nama.trim() || !update_tanggal_berlaku.trim()) {
      toastr.error("Kolom * wajib diisi.");
      return;
    }

    const is_valid = helper.validateField(
      update_pricelist_nama,
      /^[a-zA-Z\s]+$/,
      "Format nama tidak valid"
    );
    if (!is_valid) return;

    try {
      const data_pricelist_update = {
        pricelist_id: update_pricelist_id,
        nama: update_pricelist_nama,
        harga_default: update_harga_default,
        status: update_status,
        tanggal_berlaku: update_tanggal_berlaku,
        detail: detail_rows,
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

        $("#update_modal_pricelist").modal("hide");
        Swal.fire("Berhasil", response.message, "success");

        if (window.pricelist_grid) window.pricelist_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("pricelist", handle_delete, handle_update);
        }, 200);
      } else {
        Swal.fire("Gagal", response.message || "Update gagal.", "error");
      }
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: error.message,
      });
    }
  });
}
