import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

let index = 0;

const update_detail_pembelian_tbody = document.getElementById(
  "create_detail_pembelian_tbody"
);
const update_biaya_tambahan_tbody = document.getElementById(
  "create_biaya_tambahan_tbody"
);

const update_detail_pembelian_button = document.getElementById(
  "update_detail_pembelian_button"
);
update_detail_pembelian_button.addEventListener("click", function () {
  add_field("create", "update_produk_id", "update_satuan_id");
});

const update_biaya_tambahan_button = document.getElementById(
  "update_biaya_tambahan_button"
);
update_biaya_tambahan_button.addEventListener("click", function () {
  add_biaya("create", "update_data_biaya_id");
});

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

$("#tanggal_invoice")
  .pickadate({
    format: "dd mmm yyyy", // user sees: 01 Jan 2025
    formatSubmit: "yyyy-mm-dd", // hidden value: 01/01/2025
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");
const submit_invoice_button = document.getElementById("submit_invoice_button");
if (submit_invoice_button) {
  submit_invoice_button.addEventListener("click", submitInvoice);
  fetch_purchase_order();
  $(document).ready(function () {
    $("#modal_invoice").on("shown.bs.modal", function () {
      $("#purchase_order").select2({
        placeholder: "Pilih Purchase Order",
        allowClear: true,
        dropdownParent: $("#modal_invoice"),
      });
      $("#purchase_order").on("change", function () {
        update_detail_pembelian_tbody.innerHTML = "";
        update_biaya_tambahan_tbody.innerHTML = "";
        const selectedValue = $(this).val();
        populate_po(selectedValue);
      });
    });
  });
}

async function populate_po(purchase_order_id) {
  if (purchase_order_id) {
    const pembelian_id = purchase_order_id;

    let supplier_id = "";
    let ppn = "";
    let nominal_pph = "";
    let diskon = "";
    let keterangan = "";
    let no_pengiriman = "";
    let status = "";
    try {
      const response = await apiRequest(
        `/PHP/API/pembelian_API.php?action=select&user_id=${access.decryptItem(
          "user_id"
        )}&target=tb_invoice&context=create`,
        "POST",
        { pembelian_id: pembelian_id, select: "select" }
      );
      response.data.forEach((item) => {
        supplier_id = item.supplier_id;
        ppn = item.ppn;
        nominal_pph = item.nominal_pph;
        diskon = item.diskon;
        keterangan = item.keterangan;
        status = item.status;

        const parts_po = item.tanggal_po ? item.tanggal_po.split("-") : "";
        if (parts_po.length === 3) {
          let dateObj_po = new Date(parts_po[0], parts_po[1] - 1, parts_po[2]);
          pickdatejs_po.set("select", dateObj_po);
        }

        const parts_pengiriman = item.tanggal_pengiriman
          ? item.tanggal_pengiriman.split("-")
          : "";
        if (parts_pengiriman.length === 3) {
          const dateObj_pengiriman = new Date(
            parts_pengiriman[0],
            parts_pengiriman[1] - 1,
            parts_pengiriman[2]
          );
          pickdatejs_pengiriman.set("select", dateObj_pengiriman);
        }
        no_pengiriman = item.no_pengiriman;

        const parts_terima = item.tanggal_terima
          ? item.tanggal_terima.split("-")
          : "";
        if (parts_terima.length === 3) {
          const dateObj_terima = new Date(
            parts_terima[0],
            parts_terima[1] - 1,
            parts_terima[2]
          );
          pickdatejs_terima.set("select", dateObj_terima);
        }
      });
    } catch (error) {
      console.error("error:", error);
    }

    document.getElementById("update_pembelian_id").value = pembelian_id;
    document.getElementById("update_ppn").value = ppn;
    document.getElementById("update_nominal_pph").value =
      helper.unformat_angka(nominal_pph);
    document.getElementById("update_keterangan").value = keterangan;
    document.getElementById("update_diskon").value =
      helper.unformat_angka(diskon);
    document.getElementById("no_pengiriman").value = no_pengiriman;
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
        delete_button.className =
          "btn btn-danger btn-sm delete_detail_pembelian";
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
          "create",
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
          "create",
          "data_biaya_element_id",
          current_data_biaya_id
        );
      });
    } catch (error) {
      console.error("error:", error);
    }
    document.getElementById("purchase_order_card").style.display = "block";
  } else {
    document.getElementById("purchase_order_card").style.display = "none";
  }
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
  $(`#update_supplier_id`).select2({
    allowClear: true,
    dropdownParent: $("#modal_invoice"),
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

async function fetch_purchase_order() {
  try {
    const response = await apiRequest(
      `/PHP/API/pembelian_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_invoice&context=create`
    );
    populateRoleDropdown(response.data);
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }
}

function populateRoleDropdown(data) {
  const select = $("#purchase_order");
  select.empty();
  select.append(new Option("Pilih Purchase Order", "", false, false));

  data.forEach((item) => {
    if (item.tanggal_terima && item.status === "terima") {
      select.append(
        new Option(
          `${item.pembelian_id} - ${item.supplier_nama}`,
          item.pembelian_id,
          false,
          false
        )
      );
    }
  });

  select.trigger("change");
}

async function submitInvoice() {
  const pembelian_id = document.getElementById("purchase_order").value;
  const picker_invoice = $("#tanggal_invoice").pickadate("picker");
  const tanggal_invoice = picker_invoice.get("select", "yyyy-mm-dd");
  const no_invoice = document.getElementById("no_invoice").value;

  const picker_po = $("#update_tanggal_po").pickadate("picker");
  const tanggal_po = picker_po.get("select", "yyyy-mm-dd");

  const picker_pengiriman = $("#update_tanggal_pengiriman").pickadate("picker");
  const tanggal_pengiriman = picker_pengiriman.get("select", "yyyy-mm-dd");
  const no_pengiriman = document.getElementById("update_no_pengiriman").value;

  const picker_terima = $("#update_tanggal_terima").pickadate("picker");
  const tanggal_terima = picker_terima.get("select", "yyyy-mm-dd");

  console.log(tanggal_po);
  console.log(tanggal_pengiriman);
  console.log(tanggal_terima);

  const supplier_id = document.getElementById("update_supplier_id").value;
  const keterangan = document.getElementById("update_keterangan").value;
  let diskon = document.getElementById("update_diskon").value;
  const ppn = document.getElementById("update_ppn").value;
  let nominal_pph = document.getElementById("update_nominal_pph").value;

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
    user_id: `${access.decryptItem("user_id")}`,
    pembelian_id: pembelian_id,
    tanggal_invoice: tanggal_invoice,
    no_invoice: no_invoice,
    status: "invoice",
    user_id: `${access.decryptItem("user_id")}`,
    created_by: `${access.decryptItem("nama")}`,
    pembelian_id: pembelian_id,
    tanggal_po: tanggal_po,
    tanggal_pengiriman: tanggal_pengiriman,
    no_pengiriman: no_pengiriman,
    tanggal_terima: tanggal_terima,

    supplier_id: supplier_id,
    keterangan: keterangan,
    ppn: ppn,
    diskon: diskon,
    nominal_pph: nominal_pph,
    status: "invoice",
    details: details,
    biaya_tambahan: biaya_tambahan,
  };
  try {
    const response = await apiRequest(
      `/PHP/API/invoice_API.php?action=create`,
      "POST",
      body
    );
    if (response.ok) {
      swal.fire("Berhasil", "success", "success");
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
