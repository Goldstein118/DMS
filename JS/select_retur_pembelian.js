import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

const grid_container_retur_pembelian = document.querySelector(
  "#table_retur_pembelian"
);
const update_detail_retur_pembelian_tbody = document.getElementById(
  "update_detail_retur_pembelian_tbody"
);
const update_detail_retur_pembelian_button = document.getElementById(
  "update_detail_retur_pembelian_button"
);
let index = 0;
const pickdatejs_po = $("#update_tanggal_po")
  .pickadate({
    format: "dd mmm yyyy", // user sees: 01 Jan 2025
    formatSubmit: "yyyy-mm-dd", // hidden value: 01/01/2025
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");

const pickdatejs_terima = $("#update_tanggal_terima")
  .pickadate({
    format: "dd mmm yyyy", // user sees: 01 Jan 2025
    formatSubmit: "yyyy-mm-dd", // hidden value: 01/01/2025
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");

const pickdatejs_pengiriman = $("#update_tanggal_pengiriman")
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
if (grid_container_retur_pembelian) {
  update_detail_retur_pembelian_button.addEventListener("click", () => {
    add_field("update", "update_produk_id_new", "update_satuan_id_new");
  });

  window.retur_pembelian_grid = new Grid({
    columns: [
      "No Pembelian",
      "Tanggal Invoice",
      "No Invoice Supplier",
      "Supplier",
      "Status",
      "Sub Total",
      "Diskon",
      "PPN",
      "Nominal PPN",
      "Grand Total",
      {
        name: "Aksi",
        formatter: () => {
          const edit = access.hasAccess("tb_retur_pembelian", "edit");
          const can_delete = access.hasAccess("tb_retur_pembelian", "delete");
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                class="btn btn-warning update_retur_pembelian btn-sm"
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
                class="btn btn-danger delete_retur_pembelian btn-sm"
              >
                <i class="bi bi-trash-fill"></i>
              </button>`;
          }
          button += `
        <button type="button" class="btn btn btn-info view_retur_pembelian btn-sm" >
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
      }/PHP/API/retur_pembelian_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((invoice) => [
          invoice.retur_pembelian_id,
          invoice.tanggal_invoice,
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

  window.retur_pembelian_grid.render(
    document.getElementById("table_retur_pembelian")
  );
  setTimeout(() => {
    helper.custom_grid_header(
      "retur_pembelian",
      handle_delete,
      handle_update,
      handle_view
    );
  }, 200);
}

async function handle_delete(button) {
  const row = button.closest("tr");
  const retur_pembelian_id = row.cells[0].textContent;
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
        `/PHP/API/retur_pembelian_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { retur_pembelian_id: retur_pembelian_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire(
          "Berhasil",
          response.message || "Invoice dihapus.",
          "success"
        );
      } else {
        Swal.fire(
          "Gagal",
          response.error || "Gagal menghapus invoice.",
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
  $(`#update_supplier_id`).select2({
    allowClear: true,
    dropdownParent: $("#update_modal_retur_pembelian"),
  });

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

  let no_invoice_supplier = "";

  let supplier_id = "";
  let pembelian_id = "";
  let keterangan = "";
  let no_pengiriman = "";
  let ppn = "";
  let diskon = "";
  let nominal_pph = "";
  let status = "";
  let invoice_id = "";
  const retur_pembelian_id = row.cells[0].textContent;

  try {
    const response = await apiRequest(
      `/PHP/API/retur_pembelian_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      { retur_pembelian_id: retur_pembelian_id, table: "retur_pembelian" }
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
      invoice_id = item.invoice_id;
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
  document.getElementById("update_retur_pembelian_id").value =
    retur_pembelian_id;
  document.getElementById("update_invoice_id").value = invoice_id;
  document.getElementById("update_pembelian_id").value = pembelian_id;
  document.getElementById("update_no_invoice").value = no_invoice_supplier;

  document.getElementById("update_no_pengiriman").value = no_pengiriman;
  document.getElementById("update_status_pembelian").value = status;

  document.getElementById("update_ppn").value = ppn;
  document.getElementById("update_nominal_pph").value = nominal_pph;

  document.getElementById("update_keterangan").value = keterangan;
  document.getElementById("update_diskon").value = diskon;

  try {
    const response = await apiRequest(
      `/PHP/API/supplier_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_invoice&context=edit`
    );
    populate_supplier(response.data, supplier_id);
  } catch (error) {
    console.error("error:", error);
  }

  try {
    update_detail_retur_pembelian_tbody.innerHTML = "";
    const renponse_detail_pembelian = await apiRequest(
      `/PHP/API/retur_pembelian_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      {
        retur_pembelian_id: retur_pembelian_id,
        table: "detail_retur_pembelian",
      }
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

      update_detail_retur_pembelian_tbody.appendChild(tr_detail);

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

  await new Promise((resolve) => setTimeout(resolve, 500));
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#update_modal_retur_pembelian").modal("show");
}

function add_field(action, produk_element_id, satuan_element_id) {
  var myTable = document.getElementById(
    `${action}_detail_retur_pembelian_tbody`
  );
  var currentIndex = index++;
  const tr_detail = document.createElement("tr");

  const td_produk = document.createElement("td");
  var produk_select = document.createElement("select");
  produk_select.setAttribute("id", produk_element_id + currentIndex);
  produk_select.classList.add("form-select");
  td_produk.appendChild(produk_select);

  const td_qty = document.createElement("td");
  var input_qty = document.createElement("input");
  input_qty.setAttribute("id", "update_qty" + currentIndex);
  input_qty.classList.add("form-control");
  td_qty.appendChild(input_qty);

  const td_satuan = document.createElement("td");
  var satuan_select = document.createElement("select");
  satuan_select.setAttribute("id", satuan_element_id + currentIndex);
  satuan_select.classList.add("form-select");
  td_satuan.appendChild(satuan_select);

  const td_harga = document.createElement("td");
  var input_harga = document.createElement("input");
  input_harga.setAttribute("id", "update_harga" + currentIndex);
  input_harga.classList.add("form-control");
  input_harga.style.textAlign = "right";
  td_harga.appendChild(input_harga);

  const td_diskon = document.createElement("td");
  var input_diskon = document.createElement("input");
  input_diskon.setAttribute("id", "update_diskon" + currentIndex);
  input_diskon.classList.add("form-control");
  input_diskon.style.textAlign = "right";
  td_diskon.appendChild(input_diskon);

  const td_aksi = document.createElement("td");
  td_aksi.setAttribute("id", "update_aksi_tbody");
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

  helper.format_nominal("update_harga" + currentIndex);
  helper.format_nominal("update_diskon" + currentIndex);
  select_detail_retur_pembelian(
    currentIndex,
    action,
    produk_element_id,
    satuan_element_id
  );
}

async function select_detail_retur_pembelian(
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
      dropdownParent: $("#modal_retur_pembelian"),
    });
    $(`#${satuan_element_id}${index}`).select2({
      placeholder: "Pilih satuan",
      allowClear: true,
      dropdownParent: $("#modal_retur_pembelian"),
    });
  } else if (action == "update") {
    $(`#${produk_element_id}${index}`).select2({
      placeholder: "Pilih produk",
      allowClear: true,
      dropdownParent: $("#update_modal_retur_pembelian"),
    });
    $(`#${satuan_element_id}${index}`).select2({
      placeholder: "Pilih satuan",
      allowClear: true,
      dropdownParent: $("#update_modal_retur_pembelian"),
    });
  }

  delete_detail_retur_pembelian(action);
  try {
    const response_produk = await apiRequest(
      `/PHP/API/produk_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_retur_pembelian&context=edit`
    );

    const response_satuan = await apiRequest(
      `/PHP/API/satuan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_retur_pembelian&context=edit`
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
          false
        );
        select_produk.append(option);
      });
      select_produk.trigger("change");

      response_satuan.data.forEach((satuan) => {
        const option = new Option(
          `${satuan.satuan_id} - ${satuan.nama}`,
          satuan.satuan_id,
          false,
          false
        );
        select_satuan.append(option);
      });
      select_satuan.trigger("change");
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

function delete_detail_retur_pembelian(action) {
  $(`#${action}_detail_retur_pembelian_tbody`).on(
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
      dropdownParent: $("#modal_retur_pembelian"),
    });
    $(`#${satuan_element_id}${index}`).select2({
      placeholder: "Pilih satuan",
      allowClear: true,
      dropdownParent: $("#modal_retur_pembelian"),
    });
  } else if (action == "update") {
    $(`#${produk_element_id}${index}`).select2({
      placeholder: "Pilih produk",
      allowClear: true,
      dropdownParent: $("#update_modal_retur_pembelian"),
    });
    $(`#${satuan_element_id}${index}`).select2({
      placeholder: "Pilih satuan",
      allowClear: true,
      dropdownParent: $("#update_modal_retur_pembelian"),
    });
  }

  delete_detail_retur_pembelian(action);
  try {
    const response_produk = await apiRequest(
      `/PHP/API/produk_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_retur_pembelian&context=edit`
    );

    const response_satuan = await apiRequest(
      `/PHP/API/satuan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_retur_pembelian&context=edit`
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

function handle_view(button) {
  const row = button.closest("tr");
  const retur_pembelian_id = row.cells[0].textContent.trim();

  window.open(
    `../PHP/view_retur_pembelian.php?retur_pembelian_id=${encodeURIComponent(
      retur_pembelian_id
    )}`,
    "_blank",
    "toolbar=0,location=0,menubar=0"
  );
}

const submit_invoice_update = document.getElementById(
  "update_submit_retur_pembelian_button"
);
if (submit_invoice_update) {
  submit_invoice_update.addEventListener("click", async function () {
    const update_retur_pembelian_id = document.getElementById(
      "update_retur_pembelian_id"
    ).value;
    const pembelian_id = document.getElementById("update_pembelian_id").value;
    const picker_po = $("#update_tanggal_po").pickadate("picker");
    const tanggal_po = picker_po.get("select", "yyyy-mm-dd");

    const picker_pengiriman = $("#update_tanggal_pengiriman").pickadate(
      "picker"
    );
    const tanggal_pengiriman = picker_pengiriman.get("select", "yyyy-mm-dd");

    const picker_terima = $("#update_tanggal_terima").pickadate("picker");
    const tanggal_terima = picker_terima.get("select", "yyyy-mm-dd");

    const picker_invoice = $("#update_tanggal_invoice").pickadate("picker");
    const tanggal_invoice = picker_invoice.get("select", "yyyy-mm-dd");
    const no_pengiriman = document.getElementById("update_no_pengiriman").value;
    const no_invoice = document.getElementById("update_no_invoice").value;
    const invoice_id = document.getElementById("update_invoice_id").value;
    const supplier_id = document.getElementById("update_supplier_id").value;
    const keterangan = document.getElementById("update_keterangan").value;
    let diskon = document.getElementById("update_diskon").value;
    const ppn = document.getElementById("update_ppn").value;
    let nominal_pph = document.getElementById("update_nominal_pph").value;

    const status_pembelian = document.getElementById(
      "update_status_pembelian"
    ).value;

    const details = [];
    const rows = document.querySelectorAll(
      "#update_detail_retur_pembelian_tbody tr"
    );

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

    if (details.length === 0) {
      toastr.error("Minimal satu detail pembelian harus diisi.");
      return;
    }
    nominal_pph = helper.format_angka(nominal_pph);
    diskon = helper.format_angka(diskon);

    const data_pembelian = {
      retur_pembelian_id: update_retur_pembelian_id,
      user_id: `${access.decryptItem("user_id")}`,
      created_by: `${access.decryptItem("nama")}`,
      invoice_id: invoice_id,
      pembelian_id: pembelian_id,
      tanggal_po: tanggal_po,
      tanggal_pengiriman: tanggal_pengiriman,
      tanggal_terima: tanggal_terima,
      tanggal_invoice: tanggal_invoice,
      no_pengiriman: no_pengiriman,
      no_invoice: no_invoice,
      supplier_id: supplier_id,
      keterangan: keterangan,
      ppn: ppn,
      diskon: diskon,
      nominal_pph: nominal_pph,
      status: status_pembelian,
      details: details,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/retur_pembelian_API.php?action=update`,
        "POST",
        data_pembelian
      );
      if (response.ok) {
        swal.fire("Berhasil", response.message, "success");
        document.querySelector(
          "#update_detail_retur_pembelian_tbody"
        ).innerHTML = "";
        $("#update_modal_retur_pembelian").modal("hide");
        window.retur_pembelian_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header(
            "retur_pembelian",
            handle_delete,
            handle_update,
            handle_view
          );
        }, 200);
      }
    } catch (error) {
      toastr.error(error.message);
    }
  });
}
