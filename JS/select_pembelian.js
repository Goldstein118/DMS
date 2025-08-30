import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

const submit_pengiriman_button = document.getElementById(
  "submit_pengiriman_button"
);
let index = 0;
const submit_terima_button = document.getElementById("submit_terima_button");

submit_pengiriman_button.addEventListener("click", submit_pengiriman);
submit_terima_button.addEventListener("click", submit_terima);

const update_detail_pembelian_tbody = document.getElementById(
  "update_detail_pembelian_tbody"
);
const update_biaya_tambahan_tbody = document.getElementById(
  "update_biaya_tambahan_tbody"
);

const update_detail_pembelian_button = document.getElementById(
  "update_detail_pembelian_button"
);
update_detail_pembelian_button.addEventListener("click", function () {
  add_field("update", "update_produk_id", "update_satuan_id");
});

const update_biaya_tambahan_button = document.getElementById(
  "update_biaya_tambahan_button"
);
update_biaya_tambahan_button.addEventListener("click", function () {
  add_biaya("update", "update_data_biaya_id");
});
const grid_container_pembelian = document.querySelector("#table_pembelian");
const pickdatejs_po = $("#update_tanggal_po")
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
      "Tanggal PO",
      "supplier_id",
      "Supplier",
      "Tanggal Tengiriman",
      "No Pengiriman",
      "Tanggal Terima",
      "Total Qty",
      "Ppn",
      "Nominal PPN",
      "Nominal PPH",
      "Diskon",
      "Keterangan",
      "Biaya Tambahan",
      "Grand Total",
      "Created On",
      "Created By",
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
              pembelian.tanggal_pengiriman && !pembelian.tanggal_terima
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

          pembelian.total_qty,
          helper.format_persen(pembelian.ppn),
          helper.format_angka(pembelian.nominal_ppn),
          helper.format_angka(pembelian.nominal_pph),
          helper.format_angka(pembelian.diskon),
          pembelian.keterangan,
          helper.format_angka(pembelian.biaya_tambahan),
          helper.format_angka(pembelian.grand_total),
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
      handle_terima
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
      const parts = item.tanggal_terima ? item.tanggal_terima.split("-") : "";
      if (parts.length === 3) {
        const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
        pickdatejs_terima.set("select", dateObj);
      }
    });
  });

  $("#modal_terima").modal("show");
}

async function submit_terima() {
  const pembelian_id = document.getElementById("terima_pembelian_id").value;

  const picker_terima = $("#tanggal_terima").pickadate("picker");
  const tanggal_terima = picker_terima.get("select", "yyyy-mm-dd");

  const body = {
    user_id: `${access.decryptItem("user_id")}`,
    pembelian_id: pembelian_id,
    tanggal_terima: tanggal_terima,
    status: "terima",
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
    status: "pengiriman",
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
      dropdownParent: $("#modal_pembelian"),
    });
  } else if (action == "update") {
    $(`#${data_biaya_id}${index}`).select2({
      placeholder: "Pilih Biaya",
      allowClear: true,
      dropdownParent: $("#update_modal_pembelian"),
    });
  }

  delete_biaya_tambahan(action);
  try {
    const response = await apiRequest(
      `/PHP/API/data_biaya_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`
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
          false
        );
        select.append(option);
      });
      select.trigger("change");
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
      dropdownParent: $("#modal_pembelian"),
    });
    $(`#${satuan_element_id}${index}`).select2({
      placeholder: "Pilih satuan",
      allowClear: true,
      dropdownParent: $("#modal_pembelian"),
    });
  } else if (action == "update") {
    $(`#${produk_element_id}${index}`).select2({
      placeholder: "Pilih produk",
      allowClear: true,
      dropdownParent: $("#update_modal_pembelian"),
    });
    $(`#${satuan_element_id}${index}`).select2({
      placeholder: "Pilih satuan",
      allowClear: true,
      dropdownParent: $("#update_modal_pembelian"),
    });
  }

  delete_detail_pembelian(action);
  try {
    const response_produk = await apiRequest(
      `/PHP/API/produk_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_pembelian&context=create`
    );

    const response_satuan = await apiRequest(
      `/PHP/API/satuan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_pembelian&context=create`
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
  index = 0;
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  const pembelian_id = row.cells[0].textContent;
  let tanggal_po = row.cells[1].textContent;
  const supplier_id = row.cells[2].textContent;
  let ppn = row.cells[8].textContent;
  ppn = helper.unformat_persen(ppn);

  const nominal_pph = row.cells[10].textContent;
  const diskon = row.cells[11].textContent;
  const keterangan = row.cells[12].textContent;
  const status = row.cells[17].textContent.trim();

  tanggal_po = helper.unformat_date(tanggal_po);
  const parts = tanggal_po.split("-"); // ["2025", "05", "02"]
  const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
  pickdatejs_po.set("select", dateObj);

  document.getElementById("update_pembelian_id").value = pembelian_id;
  document.getElementById("update_ppn").value = ppn;
  document.getElementById("update_nominal_pph").value =
    helper.unformat_angka(nominal_pph);
  document.getElementById("update_keterangan").value = keterangan;
  document.getElementById("update_diskon").value =
    helper.unformat_angka(diskon);
  document.getElementById("update_status_pembelian").value = status;

  try {
    const response = await apiRequest(
      `/PHP/API/supplier_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_pembelian&context=edit`
    );
    populate_supplier(response.data, supplier_id);
  } catch (error) {
    console.error("error:", error);
  }

  try {
    update_detail_pembelian_tbody.innerHTML = "";
    const renponse_detail_pembelian = await apiRequest(
      `/PHP/API/pembelian_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      { pembelian_id: pembelian_id, table: "detail_pembelian" }
    );

    renponse_detail_pembelian.data.forEach((detail, index) => {
      var currentIndex = index++;
      const tr_detail = document.createElement("tr");
      const current_produk_id = detail.produk_id;
      const current_satuan_id = detail.satuan_id;

      const td_produk = document.createElement("td");
      var produk_select = document.createElement("select");
      produk_select.setAttribute("id", "produk_element_id" + currentIndex);
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
      satuan_select.setAttribute("id", "satuan_element_id" + currentIndex);
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
        "produk_element_id",
        "satuan_element_id",
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
      `/PHP/API/pembelian_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      {
        pembelian_id: pembelian_id,
        table: "biaya_tambahan",
      }
    );
    response_biaya_tambahan.data.forEach((detail, index) => {
      var currentIndex = index++;
      const tr_detail = document.createElement("tr");
      const current_data_biaya_id = detail.data_biaya_id;

      const td_biaya = document.createElement("td");
      var biaya_select = document.createElement("select");
      biaya_select.setAttribute("id", "data_biaya_element_id" + currentIndex);
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
        "data_biaya_element_id",
        current_data_biaya_id
      );
    });
  } catch (error) {
    console.error("error:", error);
  }
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
    const picker_po = $("#update_tanggal_po").pickadate("picker");
    const tanggal_po = picker_po.get("select", "yyyy-mm-dd");
    const pembelian_id = document.getElementById("update_pembelian_id").value;
    const supplier_id = document.getElementById("update_supplier_id").value;
    const keterangan = document.getElementById("update_keterangan").value;
    let diskon = document.getElementById("update_diskon").value;
    const ppn = document.getElementById("update_ppn").value;
    let nominal_pph = document.getElementById("update_nominal_pph").value;

    const status_pembelian = document.getElementById(
      "update_status_pembelian"
    ).value;

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

    const data_pembelian = {
      user_id: `${access.decryptItem("user_id")}`,
      created_by: `${access.decryptItem("nama")}`,
      pembelian_id: pembelian_id,
      tanggal_po: tanggal_po,
      supplier_id: supplier_id,
      keterangan: keterangan,
      ppn: ppn,
      diskon: diskon,
      nominal_pph: nominal_pph,
      status: status_pembelian,
      details: details,
      biaya_tambahan: biaya_tambahan,
    };
    try {
      const response = await apiRequest(
        `/PHP/API/pembelian_API.php?action=update&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "POST",
        data_pembelian
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
