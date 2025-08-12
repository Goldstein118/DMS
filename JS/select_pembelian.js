import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

const submit_pengiriman_button = document.getElementById(
  "submit_pengiriman_button"
);
const submit_terima_button = document.getElementById("submit_terima_button");
const submit_invoice_button = document.getElementById("submit_invoice_button");
submit_pengiriman_button.addEventListener("click", submit_pengiriman);
submit_terima_button.addEventListener("click", submit_terima);
submit_invoice_button.addEventListener("click", submit_invoice);

const grid_container_pembelian = document.querySelector("#table_pembelian");
const pickdatejs_po = $("#update_tanggal_po")
  .pickadate({
    format: "dd mmm yyyy", // user sees: 01 Jan 2025
    formatSubmit: "yyyy-mm-dd", // hidden value: 01/01/2025
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");

if (grid_container_pembelian) {
  $(document).ready(function () {
    $("#update_supplier_id").select2({
      allowClear: true,
      dropdownParent: $("#update_modal_pembelian"),
    });
  });
  window.pembelian_grid = new Grid({
    columns: [
      "Kode Pembelian",
      "tanggal_po",
      "supplier_id",
      "supplier",
      "tanggal_pengiriman",
      "no_pengiriman",
      "tanggal_terima",
      "tanggal_invoice",
      "no_invoice_supplier",
      "total_qty",
      "ppn",
      "nominal_ppn",
      "nominal_pph",
      "diskon",
      "keterangan",
      "biaya_tambahan",
      "grand_total",
      "created_on",
      "created_by",
      "Status",
      {
        name: "Aksi",
        formatter: () => {
          let edit;
          let can_delete;
          if (access.isOwner()) {
            edit = true;
          } else {
            edit = access.hasAccess("tb_pembelian", "edit");
          }
          if (access.isOwner()) {
            can_delete = true;
          } else {
            can_delete = access.hasAccess("tb_pembelian", "delete");
          }
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                id="update_pembelian_button"
                class="btn btn-warning update_pembelian btn-sm"
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
        <button type="button" class="btn btn-danger delete_pembelian btn-sm">
          <i class="bi bi-trash-fill"></i>
        </button>
        `;
          }
          button += `
        <button type="button" class="btn btn btn-info view_pembelian btn-sm" >
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
      }/PHP/API/pembelian_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((pembelian) => [
          pembelian.pembelian_id,
          helper.format_date(pembelian.tanggal_po),
          pembelian.supplier_id,
          pembelian.supplier_nama,
          html(`
            
            ${
              pembelian.tanggal_po && !pembelian.tanggal_terima
                ? `${helper.format_date(pembelian.tanggal_pengiriman)} 
                <button
                type="button"
                id="tanggal_pengiriman"
                class="btn btn-warning tanggal_pengiriman btn-sm" data-bs-toggle="modal" data-bs-target="#modal_pengiriman"
              >
                <i class="bi bi-pencil-fill"></i>
              </button>`
                : `${helper.format_date(pembelian.tanggal_pengiriman)}`
            }
            `),
          pembelian.no_pengiriman,
          html(
            `${
              pembelian.tanggal_pengiriman && !pembelian.tanggal_invoice
                ? `${helper.format_date(pembelian.tanggal_terima)}
                <button
                type="button"
                id="tanggal_pengiriman"
                class="btn btn-warning tanggal_terima btn-sm" data-bs-toggle="modal" data-bs-target="#modal_terima"
              >
                <i class="bi bi-pencil-fill"></i>
              </button>`
                : `${helper.format_date(pembelian.tanggal_terima)}`
            }`
          ),

          html(
            `${
              !helper.isTwoWeeksLater(pembelian.tanggal_input_invoice) &&
              pembelian.tanggal_terima
                ? `${helper.format_date(pembelian.tanggal_invoice)} 
                <button type="button"  class="btn btn-warning tanggal_invoice btn-sm" data-bs-toggle="modal" data-bs-target="#modal_invoice"
                    >
              <i class="bi bi-pencil-fill"></i> 
            </button>`
                : `${helper.format_date(pembelian.tanggal_invoice)}`
            }`
          ),
          pembelian.no_invoice_supplier,
          pembelian.total_qty,
          pembelian.ppn,
          pembelian.nominal_ppn,
          pembelian.nominal_pph,
          pembelian.diskon,
          pembelian.keterangan,
          pembelian.biaya_tambahan,
          pembelian.grand_total,
          helper.format_date_time(pembelian.created_on),
          pembelian.created_by,
          html(`
          ${pembelian.status}
          `),
          null,
        ]),
    },
  });
  window.pembelian_grid.render(document.getElementById("table_pembelian"));
  setTimeout(() => {
    helper.custom_grid_header(
      "pembelian",
      handle_delete,
      handle_update,
      handle_view,
      handle_pengiriman,
      handle_terima,
      handle_invoice
    );
  }, 200);
}

function handle_pengiriman(button) {
  $("#modal_pengiriman").on("shown.bs.modal", async function () {
    // Prevent multiple bindings
    $(this).off("shown.bs.modal");

    const row = button.closest("tr");
    window.currentRow = row;
    const pembelian_id = row.cells[0].textContent;
    document.getElementById("pengiriman_pembelian_id").value = pembelian_id;
    const result = await apiRequest(
      `/PHP/API/pembelian_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      { pembelian_id: pembelian_id }
    );

    result.data.forEach((item) => {
      const pickdatejs_pengiriman = $("#tanggal_pengiriman").pickadate(
        "picker"
      );
      const parts = item.tanggal_pengiriman
        ? item.tanggal_pengiriman.split("-")
        : "";
      if (parts.length === 3) {
        const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
        pickdatejs_pengiriman.set("select", dateObj);
      }
      document.getElementById("no_pengiriman").value = item.no_pengiriman;
    });
  });

  $("#modal_pengiriman").modal("show");
}

function handle_terima(button) {
  $("#modal_terima").on("shown.bs.modal", async function () {
    // Prevent multiple bindings
    $(this).off("shown.bs.modal");

    const row = button.closest("tr");
    window.currentRow = row;
    const pembelian_id = row.cells[0].textContent;
    document.getElementById("terima_pembelian_id").value = pembelian_id;
    const result = await apiRequest(
      `/PHP/API/pembelian_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      { pembelian_id: pembelian_id }
    );

    result.data.forEach((item) => {
      const pickdatejs_terima = $("#tanggal_terima").pickadate("picker");
      const parts = item.tanggal_terima ? item.tanggal_terima.split("-") : "";
      if (parts.length === 3) {
        const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
        pickdatejs_terima.set("select", dateObj);
      }
    });
  });

  $("#modal_terima").modal("show");
}

function handle_invoice(button) {
  $("#modal_invoice").on("shown.bs.modal", async function () {
    // Prevent multiple bindings
    $(this).off("shown.bs.modal");

    const row = button.closest("tr");
    window.currentRow = row;
    const pembelian_id = row.cells[0].textContent;
    document.getElementById("invoice_pembelian_id").value = pembelian_id;
    const result = await apiRequest(
      `/PHP/API/pembelian_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      { pembelian_id: pembelian_id }
    );

    result.data.forEach((item) => {
      const pickdatejs_invoice = $("#tanggal_invoice").pickadate("picker");
      const parts = item.tanggal_invoice ? item.tanggal_invoice.split("-") : "";
      if (parts.length === 3) {
        const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
        pickdatejs_invoice.set("select", dateObj);
      }
      document.getElementById("no_invoice").value = item.no_invoice_supplier;
    });
  });

  $("#modal_invoice").modal("show");
}

async function submit_terima() {
  const pembelian_id = document.getElementById("terima_pembelian_id").value;

  const picker_terima = $("#tanggal_terima").pickadate("picker");
  const tanggal_terima = picker_terima.get("select", "yyyy-mm-dd");

  const body = {
    user_id: `${access.decryptItem("user_id")}`,
    pembelian_id: pembelian_id,
    tanggal_terima: tanggal_terima,
  };
  try {
    const response = await apiRequest(
      `/PHP/API/pembelian_API.php?action=update`,
      "POST",
      body
    );
    if (response.ok) {
      swal.fire("Berhasil", response.message, "success");
      $("#modal_terima").modal("hide");
      window.pembelian_grid.forceRender();
      setTimeout(() => {
        helper.custom_grid_header("pembelian");
      }, 200);
    }
  } catch (error) {
    toastr.error(error.message);
  }
}

async function submit_pengiriman() {
  const pembelian_id = document.getElementById("pengiriman_pembelian_id").value;
  const picker_pengiriman = $("#tanggal_pengiriman").pickadate("picker");
  const tanggal_pengiriman = picker_pengiriman.get("select", "yyyy-mm-dd");
  const no_pengiriman = document.getElementById("no_pengiriman").value;
  const body = {
    user_id: `${access.decryptItem("user_id")}`,
    pembelian_id: pembelian_id,
    tanggal_pengiriman: tanggal_pengiriman,
    no_pengiriman: no_pengiriman,
  };
  try {
    const response = await apiRequest(
      `/PHP/API/pembelian_API.php?action=update`,
      "POST",
      body
    );
    if (response.ok) {
      swal.fire("Berhasil", response.message, "success");
      $("#modal_pengiriman").modal("hide");
      window.pembelian_grid.forceRender();
      setTimeout(() => {
        helper.custom_grid_header("pembelian");
      }, 200);
    }
  } catch (error) {
    toastr.error(error.message);
  }
}

async function submit_invoice() {
  const pembelian_id = document.getElementById("invoice_pembelian_id").value;
  const picker_invoice = $("#tanggal_invoice").pickadate("picker");
  const tanggal_invoice = picker_invoice.get("select", "yyyy-mm-dd");
  const no_invoice = document.getElementById("no_invoice").value;
  const body = {
    user_id: `${access.decryptItem("user_id")}`,
    pembelian_id: pembelian_id,
    tanggal_invoice: tanggal_invoice,
    no_invoice: no_invoice,
  };
  try {
    const response = await apiRequest(
      `/PHP/API/pembelian_API.php?action=update`,
      "POST",
      body
    );
    if (response.ok) {
      swal.fire("Berhasil", response.message, "success");
      $("#modal_invoice").modal("hide");
      window.pembelian_grid.forceRender();
      setTimeout(() => {
        helper.custom_grid_header("pembelian");
      }, 200);
    }
  } catch (error) {
    toastr.error(error.message);
  }
}

function handle_view(button) {
  const row = button.closest("tr");
  const pembelian_id = row.cells[0].textContent.trim();

  window.open(
    `../PHP/view_pembelian.php?pembelian_id=${encodeURIComponent(
      pembelian_id
    )}`,
    "_blank"
  );
}

// Attach delete listeners
async function handle_delete(button) {
  const row = button.closest("tr");
  const pembelian_id = row.cells[0].textContent;

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
        `/PHP/API/pembelian_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { pembelian_id }
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
          response.error || "Gagal menghapus pembelian.",
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
function populate_supplier(data, current_supplier_id) {
  const supplier_id_Field = $("#update_supplier_id");
  supplier_id_Field.empty();
  data.forEach((item) => {
    const option = new Option(
      `${item.supplier_id} - ${item.nama}`,
      item.supplier_id,
      false,
      item.supplier_id == current_supplier_id
    );
    supplier_id_Field.append(option);
  });

  supplier_id_Field.trigger("change");
}

async function handle_update(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  const pembelian_id = row.cells[0].textContent;
  let tanggal_po = row.cells[1].textContent;
  const supplier_id = row.cells[2].textContent;
  const ppn = row.cells[10].textContent;
  const nominal_pph = row.cells[12].textContent;
  const diskon = row.cells[13].textContent;
  const keterangan = row.cells[14].textContent;

  tanggal_po = helper.unformat_date(tanggal_po);
  const parts = tanggal_po.split("-"); // ["2025", "05", "02"]
  const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
  pickdatejs_po.set("select", dateObj);

  document.getElementById("update_pembelian_id").value = pembelian_id;
  document.getElementById("update_ppn").value = ppn;
  document.getElementById("update_nominal_pph").value = nominal_pph;
  document.getElementById("update_keterangan").value = keterangan;
  document.getElementById("update_diskon").value = diskon;
  try {
    const response = await apiRequest(
      `/PHP/API/supplier_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_pembelian&context=edit`
    );
    populate_supplier(response.data, supplier_id);
  } catch (error) {}
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#update_modal_pembelian").modal("show");
}

const submit_pembelian_update = document.getElementById(
  "update_submit_pembelian"
);

if (submit_pembelian_update) {
  submit_pembelian_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("No row selected for update.");
      return;
    }

    const row = window.currentRow;
    const update_pembelian_id = document.getElementById(
      "update_pembelian_id"
    ).value;
    const update_pembelian_nama = document.getElementById(
      "update_name_pembelian"
    ).value;
    const update_harga_default = document.getElementById(
      "update_default_pembelian"
    ).value;
    const update_status = document.getElementById(
      "update_status_pembelian"
    ).value;
    const picker = $("#update_tanggal_berlaku").pickadate("picker");
    const update_tanggal_berlaku = picker.get("select", "yyyy-mm-dd");

    const detail_rows = [];

    document
      .querySelectorAll("#update_detail_pembelian_tbody tr")
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

    if (!update_pembelian_nama.trim() || !update_tanggal_berlaku.trim()) {
      toastr.error("Kolom * wajib diisi.");
      return;
    }

    const is_valid = helper.validateField(
      update_pembelian_nama,
      /^[a-zA-Z\s]+$/,
      "Format nama tidak valid"
    );
    if (!is_valid) return;

    try {
      const data_pembelian_update = {
        pembelian_id: update_pembelian_id,
        nama: update_pembelian_nama,
        harga_default: update_harga_default,
        status: update_status,
        tanggal_berlaku: update_tanggal_berlaku,
        detail: detail_rows,
      };

      const response = await apiRequest(
        `/PHP/API/pembelian_API.php?action=update&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "POST",
        data_pembelian_update
      );

      if (response.ok) {
        $("#update_modal_pembelian").modal("hide");
        Swal.fire("Berhasil", response.message, "success");

        window.pembelian_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("pembelian", handle_delete, handle_update);
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
