import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

const update_detail_pembelian_tbody = document.getElementById(
  "update_detail_pembelian_tbody"
);
const update_biaya_tambahan_tbody = document.getElementById(
  "update_biaya_tambahan_tbody"
);
let index = 0;
const pickdatejs_po = $("#tanggal_po")
  .pickadate({
    format: "dd mmm yyyy", // user sees: 01 Jan 2025
    formatSubmit: "yyyy-mm-dd", // hidden value: 01/01/2025
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");

const pickdatejs_terima = $("#tanggal_terima")
  .pickadate({
    format: "dd mmm yyyy", // user sees: 01 Jan 2025
    formatSubmit: "yyyy-mm-dd", // hidden value: 01/01/2025
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");

const pickdatejs_pengiriman = $("#tanggal_pengiriman")
  .pickadate({
    format: "dd mmm yyyy", // user sees: 01 Jan 2025
    formatSubmit: "yyyy-mm-dd", // hidden value: 01/01/2025
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");

const pickdatejs_invoice = $("#update_tanggal_invoice")
  .pickadate({
    format: "dd mmm yyyy", // user sees: 01 Jan 2025
    formatSubmit: "yyyy-mm-dd", // hidden value: 01/01/2025
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");

const pickdatejs_expired = $("#update_tanggal_expired")
  .pickadate({
    format: "dd mmm yyyy", // user sees: 01 Jan 2025
    formatSubmit: "yyyy-mm-dd", // hidden value: 01/01/2025
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");

const detail_pembelian_button = document.getElementById(
  "detail_pembelian_button"
);
detail_pembelian_button.addEventListener("click", function () {
  add_field("update", "produk_id", "satuan_id");
});

const biaya_tambahan_button = document.getElementById("biaya_tambahan_button");
biaya_tambahan_button.addEventListener("click", function () {
  add_biaya("update", "data_biaya_id");
});
const grid_container_invoice = document.querySelector("#table_invoice");
if (grid_container_invoice) {
  function getStatusBadge(status) {
    switch (status) {
      case "proses":
        return `<span class="badge text-bg-secondary">proses</span>`;
      case "pengiriman":
        return `<span class="badge text-bg-warning">pengiriman</span>`;
      case "terima":
        return `<span class="badge text-bg-primary">terima</span>`;
      case "invoice":
        return `<span class="badge text-bg-success">invoice</span>`;
      case "cancel":
        return `<span class="badge text-bg-danger">cancel</span>`;
      default:
        return `<span class="badge text-bg-secondary">${status}</span>`;
    }
  }

  window.invoice_grid = new Grid({
    columns: [
      "No Pembelian",
      "Tanggal Invoice",
      "No Invoice Supplier",
      "Supplier",
      {
        name: "Status",
        formatter: (cell) => html(getStatusBadge(cell)),
      },
      "Sub Total",
      "Diskon",
      "PPN",
      "Nominal PPN",
      "Grand Total",
      {
        name: "Aksi",
        formatter: (cell, row) => {
          const status = row.cells[4].data;
          const edit = access.hasAccess("tb_invoice", "edit");
          const can_delete = access.hasAccess("tb_invoice", "delete");
          let button = "";

          if (edit && status != "cancel") {
            button += `<button
                type="button"
                class="btn btn-warning update_invoice btn-sm"
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
          if (can_delete && status != "cancel") {
            button += `<button
                type="button"
                class="btn btn-danger delete_invoice btn-sm"
              >
                <i class="bi bi-x-circle"></i>
              </button>`;
          }
          if (status != "cancel") {
            button += `
        <button type="button" class="btn btn btn-info view_invoice btn-sm" >
          <i class="bi bi-eye"></i>
        </button>
        `;
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
      }/PHP/API/invoice_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((invoice) => [
          invoice.invoice_id,
          helper.format_date(invoice.tanggal_invoice),
          invoice.no_invoice_supplier,
          invoice.supplier_nama,
          invoice.status,
          helper.format_angka(invoice.sub_total),
          helper.format_angka(invoice.diskon),
          helper.format_persen(invoice.ppn),
          helper.format_angka(invoice.nominal_ppn),
          helper.format_angka(invoice.grand_total),
          null,
        ]),
    },
  });

  window.invoice_grid.render(document.getElementById("table_invoice"));
  setTimeout(() => {
    helper.custom_grid_header(
      "invoice",
      handle_delete,
      handle_update,
      handle_view
    );
  }, 200);
}
function handle_view(button) {
  const row = button.closest("tr");
  const invoice_id = row.cells[0].textContent.trim();

  window.open(
    `../PHP/view_invoice.php?invoice_id=${encodeURIComponent(invoice_id)}`,
    "_blank",
    "toolbar=0,location=0,menubar=0"
  );
}
function add_biaya(action, data_biaya_element_id) {
  var myTable = document.getElementById(`${action}_biaya_tambahan_tbody`);
  var currentIndex = index++;
  const tr_detail = document.createElement("tr");

  const td_biaya = document.createElement("td");
  var biaya_select = document.createElement("select");
  biaya_select.setAttribute("id", data_biaya_element_id + currentIndex);
  biaya_select.classList.add("form-select");
  td_biaya.appendChild(biaya_select);

  const td_jumlah = document.createElement("td");
  var input_jumlah = document.createElement("input");
  input_jumlah.setAttribute("id", "jumlah" + currentIndex);
  input_jumlah.classList.add("form-control");
  input_jumlah.style.textAlign = "right";
  td_jumlah.appendChild(input_jumlah);

  const td_keterangan = document.createElement("td");
  var input_keterangan = document.createElement("input");
  input_keterangan.setAttribute("id", "keterangan" + currentIndex);
  input_keterangan.classList.add("form-control");
  input_keterangan.style.textAlign = "right";
  td_keterangan.appendChild(input_keterangan);

  const td_aksi = document.createElement("td");
  td_aksi.setAttribute("id", "aksi_tbody");
  var delete_button = document.createElement("button");
  delete_button.type = "button";
  delete_button.className = "btn btn-danger btn-sm delete_biaya_tambahan";
  delete_button.innerHTML = `<i class="bi bi-trash-fill"></i>`;
  td_aksi.appendChild(delete_button);
  td_aksi.style.textAlign = "center";

  tr_detail.appendChild(td_biaya);
  tr_detail.appendChild(td_jumlah);
  tr_detail.appendChild(td_keterangan);
  tr_detail.appendChild(td_aksi);

  myTable.appendChild(tr_detail);

  helper.format_nominal("jumlah" + currentIndex);
  select_data_biaya(currentIndex, action, data_biaya_element_id);
}
function add_field(action, produk_element_id, satuan_element_id) {
  var myTable = document.getElementById(`${action}_detail_pembelian_tbody`);
  var currentIndex = index++;
  const tr_detail = document.createElement("tr");

  const td_produk = document.createElement("td");
  var produk_select = document.createElement("select");
  produk_select.setAttribute("id", produk_element_id + currentIndex);
  produk_select.classList.add("form-select");
  td_produk.appendChild(produk_select);

  const td_qty = document.createElement("td");
  var input_qty = document.createElement("input");
  input_qty.setAttribute("id", "qty" + currentIndex);
  input_qty.classList.add("form-control");
  td_qty.appendChild(input_qty);

  const td_satuan = document.createElement("td");
  var satuan_select = document.createElement("select");
  satuan_select.setAttribute("id", satuan_element_id + currentIndex);
  satuan_select.classList.add("form-select");
  td_satuan.appendChild(satuan_select);

  const td_harga = document.createElement("td");
  var input_harga = document.createElement("input");
  input_harga.setAttribute("id", "harga" + currentIndex);
  input_harga.classList.add("form-control");
  input_harga.style.textAlign = "right";
  td_harga.appendChild(input_harga);

  const td_diskon = document.createElement("td");
  var input_diskon = document.createElement("input");
  input_diskon.setAttribute("id", "diskon" + currentIndex);
  input_diskon.classList.add("form-control");
  input_diskon.style.textAlign = "right";
  td_diskon.appendChild(input_diskon);

  const td_aksi = document.createElement("td");
  td_aksi.setAttribute("id", "aksi_tbody");
  var delete_button = document.createElement("button");
  delete_button.type = "button";
  delete_button.className = "btn btn-danger btn-sm delete_detail_pembelian";
  delete_button.innerHTML = `<i class="bi bi-trash-fill"></i>`;
  td_aksi.appendChild(delete_button);
  td_aksi.style.textAlign = "center";

  tr_detail.appendChild(td_produk);
  tr_detail.appendChild(td_qty);
  tr_detail.appendChild(td_satuan);
  tr_detail.appendChild(td_harga);
  tr_detail.appendChild(td_diskon);
  tr_detail.appendChild(td_aksi);

  myTable.appendChild(tr_detail);

  helper.format_nominal("harga" + currentIndex);
  helper.format_nominal("diskon" + currentIndex);
  select_detail_pembelian(
    currentIndex,
    action,
    produk_element_id,
    satuan_element_id
  );
}

function populate_select(data, current_id, field) {
  $(`#${field}_id`).select2({
    allowClear: true,
    dropdownParent: $("#update_modal_invoice"),
  });
  const select = $(`#${field}_id`);
  select.empty();
  if (field === "supplier") {
    data.forEach((item) => {
      const option = new Option(
        `${item.supplier_id} - ${item.nama}`,
        item.supplier_id,
        false,
        item.supplier_id == current_id
      );
      select.append(option);
    });
  } else if (field === "gudang") {
    data.forEach((item) => {
      const option = new Option(
        `${item.gudang_id} - ${item.nama}`,
        item.gudang_id,
        false,
        item.gudang_id == current_id
      );
      select.append(option);
    });
  }

  select.trigger("change");
}
async function handle_delete(button) {
  $("#modal_cancel").modal("show");
  const row = button.closest("tr");
  const invoice_id = row.cells[0].textContent;
  const submit_cancel = document.getElementById("submit_cancel_button");
  if (submit_cancel) {
    submit_cancel.addEventListener("click", async function () {
      const keterangan_cancel =
        document.getElementById("keterangan_cancel").value;
      if (!keterangan_cancel || keterangan_cancel.trim() === "") {
        toastr.error("Keterangan Cancel harus diisi.");
        return;
      }
      const result = await Swal.fire({
        title: "Cancel Pembelian?",
        text: "Setelah submit tidak bisa diganti lagi!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Iya, Cancel!",
        cancelButtonText: "Batalkan",
      });
      if (result.isConfirmed) {
        try {
          const response = await apiRequest(
            `/PHP/API/invoice_API.php?action=delete&user_id=${access.decryptItem(
              "user_id"
            )}`,
            "DELETE",
            {
              invoice_id: invoice_id,
              keterangan_cancel: keterangan_cancel,
              status: "cancel",
              cancel_by: `${access.decryptItem("nama")}`,
            }
          );
          if (response.ok) {
            Swal.fire(
              "Berhasil",
              response.message || "Pembelian dicancel.",
              "success"
            );

            $("#modal_cancel").modal("hide");

            window.invoice_grid.forceRender();
            setTimeout(() => {
              helper.custom_grid_header(
                "invoice",
                handle_delete,
                handle_update,
                handle_view
              );
            }, 200);
          } else {
            Swal.fire("Gagal", response.error || "Gagal.", "error");
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
}
function delete_detail_pembelian(action) {
  $(`#${action}_detail_pembelian_tbody`).on(
    "click",
    ".delete_detail_pembelian",
    async function () {
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
          $(this).closest("tr").remove();
          Swal.fire("Berhasil", "Pembelian dihapus.", "success");
        } catch (error) {
          Swal.fire({
            icon: "error",
            title: "Gagal",
            text: error.message,
          });
        }
      }
    }
  );
}
async function select_detail_pembelian(
  index,
  action,
  produk_element_id,
  satuan_element_id,

  current_produk_id,
  current_satuan_id
) {
  if (action == "create") {
    $(`#${produk_element_id}${index}`).select2({
      placeholder: "Pilih produk",
      allowClear: true,
      dropdownParent: $("#modal_invoice"),
    });
    $(`#${satuan_element_id}${index}`).select2({
      placeholder: "Pilih satuan",
      allowClear: true,
      dropdownParent: $("#modal_invoice"),
    });
  } else if (action == "update") {
    $(`#${produk_element_id}${index}`).select2({
      placeholder: "Pilih produk",
      allowClear: true,
      dropdownParent: $("#update_modal_invoice"),
    });
    $(`#${satuan_element_id}${index}`).select2({
      placeholder: "Pilih satuan",
      allowClear: true,
      dropdownParent: $("#update_modal_invoice"),
    });
  }

  delete_detail_pembelian(action);
  try {
    const response_produk = await apiRequest(
      `/PHP/API/produk_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_invoice&context=edit`
    );

    const response_satuan = await apiRequest(
      `/PHP/API/satuan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_invoice&context=edit`
    );

    const select_produk = $(`#${produk_element_id}${index}`);
    select_produk.empty();
    select_produk.append(new Option("Pilih Produk", "", false, false));

    const select_satuan = $(`#${satuan_element_id}${index}`);
    select_satuan.empty();
    select_satuan.append(new Option("Pilih Satuan", "", false, false));

    if (action == "create") {
      response_produk.data.forEach((produk) => {
        const option = new Option(
          `${produk.produk_id} - ${produk.nama}`,
          produk.produk_id,
          false,
          produk.produk_id === current_produk_id
        );
        select_produk.append(option);
      });
      select_produk.val(current_produk_id).trigger("change");

      response_satuan.data.forEach((satuan) => {
        const option = new Option(
          `${satuan.satuan_id} - ${satuan.nama}`,
          satuan.satuan_id,
          false,
          satuan.satuan_id === current_satuan_id
        );
        select_satuan.append(option);
      });
      select_satuan.val(current_satuan_id).trigger("change");
    } else if (action == "update") {
      response_produk.data.forEach((produk) => {
        const option = new Option(
          `${produk.produk_id} - ${produk.nama}`,
          produk.produk_id,
          false,
          produk.produk_id === current_produk_id
        );
        select_produk.append(option);
      });
      select_produk.val(current_produk_id).trigger("change");

      response_satuan.data.forEach((satuan) => {
        const option = new Option(
          `${satuan.satuan_id} - ${satuan.nama}`,
          satuan.satuan_id,
          false,
          satuan.satuan_id === current_satuan_id
        );
        select_satuan.append(option);
      });
      select_satuan.val(current_satuan_id).trigger("change");
    }
  } catch (error) {
    console.error("error:", error);
  }
}
function delete_biaya_tambahan(action) {
  $(`#${action}_biaya_tambahan_tbody`).on(
    "click",
    ".delete_biaya_tambahan",
    async function () {
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
          $(this).closest("tr").remove();
          Swal.fire("Berhasil", "Pembelian dihapus.", "success");
        } catch (error) {
          Swal.fire({
            icon: "error",
            title: "Gagal",
            text: error.message,
          });
        }
      }
    }
  );
}
async function select_data_biaya(
  index,
  action,
  data_biaya_id,
  current_data_biaya_id
) {
  if (action == "create") {
    $(`#${data_biaya_id}${index}`).select2({
      placeholder: "Pilih Biaya",
      allowClear: true,
      dropdownParent: $("#modal_invoice"),
    });
  } else if (action == "update") {
    $(`#${data_biaya_id}${index}`).select2({
      placeholder: "Pilih Biaya",
      allowClear: true,
      dropdownParent: $("#update_modal_invoice"),
    });
  }

  delete_biaya_tambahan(action);
  try {
    const response = await apiRequest(
      `/PHP/API/data_biaya_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_invoice&context=edit`
    );

    const select = $(`#${data_biaya_id}${index}`);
    select.empty();
    select.append(new Option("Pilih Biaya", "", false, false));

    if (action == "create") {
      response.data.forEach((item) => {
        const option = new Option(
          `${item.data_biaya_id} - ${item.nama}`,
          item.data_biaya_id,
          false,
          item.data_biaya_id === current_data_biaya_id
        );
        select.append(option);
      });
      select.val(current_data_biaya_id).trigger("change");
    } else if (action == "update") {
      response.data.forEach((item) => {
        const option = new Option(
          `${item.data_biaya_id} - ${item.nama}`,
          item.data_biaya_id,
          false,
          item.data_biaya_id === current_data_biaya_id
        );
        select.append(option);
      });
      select.val(current_data_biaya_id).trigger("change");
    }
  } catch (error) {
    console.error("error:", error);
  }
}

async function handle_update(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  let no_invoice_supplier = "";

  let supplier_id = "";
  let pembelian_id = "";
  let keterangan = "";
  let no_pengiriman = "";
  let ppn = "";
  let diskon = "";
  let nominal_pph = "";
  let status = "";
  let gudang_id = "";
  const invoice_id = row.cells[0].textContent;

  try {
    const response = await apiRequest(
      `/PHP/API/invoice_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      { invoice_id: invoice_id, table: "invoice" }
    );

    response.data.forEach((item) => {
      const part_invoice = item.tanggal_invoice
        ? item.tanggal_invoice.split("-")
        : "";
      if (part_invoice.length == 3) {
        const dateObj_invoice = new Date(
          part_invoice[0],
          part_invoice[1] - 1,
          part_invoice[2]
        );
        pickdatejs_invoice.set("select", dateObj_invoice);
      }

      const part_expired = item.tanggal_expired
        ? item.tanggal_expired.split("-")
        : "";
      if (part_expired.length == 3) {
        const dateObj_expired = new Date(
          part_expired[0],
          part_expired[1] - 1,
          part_expired[2]
        );
        pickdatejs_expired.set("select", dateObj_expired);
      }

      no_invoice_supplier = item.no_invoice_supplier;

      const part_po = item.tanggal_po ? item.tanggal_po.split("-") : "";
      if (part_po.length == 3) {
        const dateObj_po = new Date(part_po[0], part_po[1] - 1, part_po[2]);
        pickdatejs_po.set("select", dateObj_po);
      }

      const part_pengiriman = item.tanggal_pengiriman
        ? item.tanggal_pengiriman.split("-")
        : "";
      if (part_pengiriman.length == 3) {
        const dateObj_pengiriman = new Date(
          part_pengiriman[0],
          part_pengiriman[1] - 1,
          part_pengiriman[2]
        );
        pickdatejs_pengiriman.set("select", dateObj_pengiriman);
      }

      const part_terima = item.tanggal_terima
        ? item.tanggal_terima.split("-")
        : "";
      if (part_terima.length == 3) {
        const dateObj_terima = new Date(
          part_terima[0],
          part_terima[1] - 1,
          part_terima[2]
        );
        pickdatejs_terima.set("select", dateObj_terima);
      }
      supplier_id = item.supplier_id;
      gudang_id = item.gudang_id;
      pembelian_id = item.pembelian_id;
      keterangan = item.keterangan;
      no_pengiriman = item.no_pengiriman;
      ppn = item.ppn;
      diskon = item.diskon;
      nominal_pph = item.nominal_pph;
      status = item.status;
    });
  } catch (error) {
    console.error(error);
  }
  document.getElementById("update_invoice_id").value = invoice_id;
  document.getElementById("update_purchase_order").value = pembelian_id;
  document.getElementById("update_no_invoice").value = no_invoice_supplier;

  document.getElementById("no_pengiriman").value = no_pengiriman;
  document.getElementById("status_pembelian").value = status;

  document.getElementById("status_pembelian").value = status;

  document.getElementById("ppn").value = ppn;
  document.getElementById("nominal_pph").value = nominal_pph;

  document.getElementById("keterangan").value = keterangan;
  document.getElementById("diskon").value = diskon;

  try {
    const response = await apiRequest(
      `/PHP/API/supplier_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_invoice&context=edit`
    );
    populate_select(response.data, supplier_id, "supplier");
  } catch (error) {
    console.error("error:", error);
  }

  try {
    const response_gudang = await apiRequest(
      `/PHP/API/gudang_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_invoice&context=edit`
    );
    populate_select(response_gudang.data, gudang_id, "gudang");
  } catch (error) {
    console.error("error:", error);
  }

  try {
    update_detail_pembelian_tbody.innerHTML = "";
    const renponse_detail_pembelian = await apiRequest(
      `/PHP/API/invoice_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      { invoice_id: invoice_id, table: "detail_invoice" }
    );

    renponse_detail_pembelian.data.forEach((detail, index) => {
      var currentIndex = index++;
      const tr_detail = document.createElement("tr");
      const current_produk_id = detail.produk_id;
      const current_satuan_id = detail.satuan_id;

      const td_produk = document.createElement("td");
      var produk_select = document.createElement("select");
      produk_select.setAttribute(
        "id",
        "update_produk_element_id" + currentIndex
      );
      produk_select.classList.add("form-select");
      td_produk.appendChild(produk_select);

      const td_qty = document.createElement("td");
      var input_qty = document.createElement("input");
      input_qty.setAttribute("id", "qty" + currentIndex);
      input_qty.classList.add("form-control");
      input_qty.value = detail.qty;
      td_qty.appendChild(input_qty);

      const td_satuan = document.createElement("td");
      var satuan_select = document.createElement("select");
      satuan_select.setAttribute(
        "id",
        "update_satuan_element_id" + currentIndex
      );
      satuan_select.classList.add("form-select");
      td_satuan.appendChild(satuan_select);

      const td_harga = document.createElement("td");
      var input_harga = document.createElement("input");
      input_harga.setAttribute("id", "harga" + currentIndex);
      input_harga.classList.add("form-control");
      input_harga.style.textAlign = "right";
      input_harga.value = detail.harga;
      td_harga.appendChild(input_harga);

      const td_diskon = document.createElement("td");
      var input_diskon = document.createElement("input");
      input_diskon.setAttribute("id", "diskon" + currentIndex);
      input_diskon.classList.add("form-control");
      input_diskon.style.textAlign = "right";
      input_diskon.value = detail.diskon;
      td_diskon.appendChild(input_diskon);

      const td_aksi = document.createElement("td");
      td_aksi.setAttribute("id", "aksi_tbody");
      var delete_button = document.createElement("button");
      delete_button.type = "button";
      delete_button.className = "btn btn-danger btn-sm delete_detail_pembelian";
      delete_button.innerHTML = `<i class="bi bi-trash-fill"></i>`;
      td_aksi.appendChild(delete_button);
      td_aksi.style.textAlign = "center";

      tr_detail.appendChild(td_produk);
      tr_detail.appendChild(td_qty);
      tr_detail.appendChild(td_satuan);
      tr_detail.appendChild(td_harga);
      tr_detail.appendChild(td_diskon);
      tr_detail.appendChild(td_aksi);

      update_detail_pembelian_tbody.appendChild(tr_detail);

      helper.format_nominal("harga" + currentIndex);
      helper.format_nominal("diskon" + currentIndex);
      select_detail_pembelian(
        currentIndex,
        "update",
        "update_produk_element_id",
        "update_satuan_element_id",
        current_produk_id,
        current_satuan_id
      );
    });
  } catch (error) {
    console.error("error:", error);
  }

  try {
    update_biaya_tambahan_tbody.innerHTML = "";
    const response_biaya_tambahan = await apiRequest(
      `/PHP/API/invoice_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      {
        invoice_id: invoice_id,
        table: "biaya_tambahan_invoice",
      }
    );
    response_biaya_tambahan.data.forEach((detail, index) => {
      var currentIndex = index++;
      const tr_detail = document.createElement("tr");
      const current_data_biaya_id = detail.data_biaya_id;

      const td_biaya = document.createElement("td");
      var biaya_select = document.createElement("select");
      biaya_select.setAttribute(
        "id",
        "update_data_biaya_element_id" + currentIndex
      );
      biaya_select.classList.add("form-select");
      td_biaya.appendChild(biaya_select);

      const td_jumlah = document.createElement("td");
      var input_jumlah = document.createElement("input");
      input_jumlah.setAttribute("id", "jumlah" + currentIndex);
      input_jumlah.classList.add("form-control");
      input_jumlah.style.textAlign = "right";
      input_jumlah.value = helper.unformat_angka(detail.jlh);
      td_jumlah.appendChild(input_jumlah);

      const td_keterangan = document.createElement("td");
      var input_keterangan = document.createElement("input");
      input_keterangan.setAttribute("id", "keterangan" + currentIndex);
      input_keterangan.classList.add("form-control");
      input_keterangan.style.textAlign = "right";
      input_keterangan.value = detail.keterangan;
      td_keterangan.appendChild(input_keterangan);

      const td_aksi = document.createElement("td");
      td_aksi.setAttribute("id", "aksi_tbody");
      var delete_button = document.createElement("button");
      delete_button.type = "button";
      delete_button.className = "btn btn-danger btn-sm delete_biaya_tambahan";
      delete_button.innerHTML = `<i class="bi bi-trash-fill"></i>`;
      td_aksi.appendChild(delete_button);
      td_aksi.style.textAlign = "center";

      tr_detail.appendChild(td_biaya);
      tr_detail.appendChild(td_jumlah);
      tr_detail.appendChild(td_keterangan);
      tr_detail.appendChild(td_aksi);

      update_biaya_tambahan_tbody.appendChild(tr_detail);

      helper.format_nominal("jumlah" + currentIndex);
      select_data_biaya(
        currentIndex,
        "update",
        "update_data_biaya_element_id",
        current_data_biaya_id
      );
    });
  } catch (error) {
    console.error("error:", error);
  }

  await new Promise((resolve) => setTimeout(resolve, 500));
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#update_modal_invoice").modal("show");
}

const submit_invoice_update = document.getElementById("update_invoice_button");
if (submit_invoice_update) {
  submit_invoice_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("no row selected for update.");
      return;
    }
    const row = window.currentRow;
    const invoice_id = document.getElementById("update_invoice_id").value;
    const pembelian_id = document.getElementById("update_purchase_order").value;
    const picker_invoice = $("#update_tanggal_invoice").pickadate("picker");
    const tanggal_invoice = picker_invoice.get("select", "yyyy-mm-dd");
    const no_invoice = document.getElementById("update_no_invoice").value;

    const picker_po = $("#tanggal_po").pickadate("picker");
    const tanggal_po = picker_po.get("select", "yyyy-mm-dd");

    const picker_pengiriman = $("#tanggal_pengiriman").pickadate("picker");
    const tanggal_pengiriman = picker_pengiriman.get("select", "yyyy-mm-dd");

    const picker_expired = $("#tanggal_expired").pickadate("picker");
    const tanggal_expired = picker_expired.get("select", "yyyy-mm-dd");

    const no_pengiriman = document.getElementById("no_pengiriman").value;

    const picker_terima = $("#tanggal_terima").pickadate("picker");
    const tanggal_terima = picker_terima.get("select", "yyyy-mm-dd");

    const supplier_id = document.getElementById("supplier_id").value;
    const gudang_id = document.getElementById("gudang_id").value;
    const keterangan = document.getElementById("keterangan").value;
    let diskon = document.getElementById("diskon").value;
    const ppn = document.getElementById("ppn").value;
    let nominal_pph = document.getElementById("nominal_pph").value;

    const details = [];
    const rows = document.querySelectorAll("#update_detail_pembelian_tbody tr");

    for (const row of rows) {
      const produk_select = row.querySelector("td:nth-child(1) select");
      const qty = row.querySelector("td:nth-child(2) input");
      const satuan = row.querySelector("td:nth-child(3) select");
      const harga = row.querySelector("td:nth-child(4) input");
      const diskon = row.querySelector("td:nth-child(5) input");

      const produk_id = produk_select?.value?.trim();
      const kuantitas = qty?.value?.trim();
      let harga_ = harga?.value?.trim();
      const satuan_id = satuan?.value?.trim();
      let discount = diskon?.value?.trim();

      if (
        !produk_id ||
        produk_id.trim() === "" ||
        !kuantitas ||
        kuantitas.trim() === "" ||
        !harga_ ||
        harga_.trim() === "" ||
        !satuan_id ||
        satuan_id.trim() === "" ||
        !discount ||
        discount.trim() === ""
      ) {
        toastr.error("Semua field pada detail pembelian wajib diisi.");
        return;
      }
      harga_ = helper.format_angka(harga_);
      discount = helper.format_angka(discount);
      details.push({
        produk_id: produk_id,
        qty: kuantitas,
        harga: harga_,
        satuan_id: satuan_id,
        diskon: discount,
      });
    }

    const biaya_tambahan = [];
    const rows_biaya_tambahan = document.querySelectorAll(
      "#update_biaya_tambahan_tbody tr"
    );

    for (const row of rows_biaya_tambahan) {
      const biaya_select = row.querySelector("td:nth-child(1) select");
      const jumlah_biaya = row.querySelector("td:nth-child(2) input");
      const keterangan_biaya = row.querySelector("td:nth-child(3) input");

      const data_biaya_id = biaya_select?.value?.trim();
      let jumlah = jumlah_biaya?.value?.trim();
      const keterangan = keterangan_biaya?.value?.trim();

      if (
        !data_biaya_id ||
        data_biaya_id.trim() === "" ||
        !jumlah ||
        jumlah.trim() === "" ||
        !keterangan ||
        keterangan.trim() === ""
      ) {
        toastr.error("Semua field pada biaya tambahan wajib diisi.");
        return;
      }
      jumlah = helper.format_angka(jumlah);
      biaya_tambahan.push({
        data_biaya_id: data_biaya_id,
        jumlah: jumlah,
        keterangan: keterangan,
      });
    }

    const body = {
      invoice_id: invoice_id,
      user_id: `${access.decryptItem("user_id")}`,
      created_by: `${access.decryptItem("nama")}`,
      tanggal_invoice: tanggal_invoice,
      no_invoice: no_invoice,
      pembelian_id: pembelian_id,
      tanggal_po: tanggal_po,
      tanggal_pengiriman: tanggal_pengiriman,
      tanggal_expired: tanggal_expired,
      no_pengiriman: no_pengiriman,
      tanggal_terima: tanggal_terima,
      supplier_id: supplier_id,
      gudang_id: gudang_id,
      keterangan: keterangan,
      ppn: ppn,
      diskon: diskon,
      nominal_pph: nominal_pph,
      details: details,
      biaya_tambahan: biaya_tambahan,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/invoice_API.php?action=update&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "POST",
        body
      );
      if (response.ok) {
        $("#update_modal_invoice").modal("hide");
        Swal.fire("Berhasil", response.message, "success");

        window.invoice_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header(
            "invoice",
            handle_delete,
            handle_update,
            handle_view
          );
        }, 200);

        window.open(
          `../PHP/view_invoice.php?invoice_id=${encodeURIComponent(
            invoice_id
          )}`,
          "_blank",
          "toolbar=0,location=0,menubar=0"
        );
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
