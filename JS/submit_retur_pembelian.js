import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

let index = 0;

const detail_pembelian_tbody = document.getElementById(
  "create_detail_retur_pembelian_tbody"
);
const input = document.getElementById("input");
const detail_retur_pembelian_button = document.getElementById(
  "detail_retur_pembelian_button"
);
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

const pickadatejs_invoice = $("#tanggal_invoice")
  .pickadate({
    format: "dd mmm yyyy", // user sees: 01 Jan 2025
    formatSubmit: "yyyy-mm-dd", // hidden value: 01/01/2025
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");
const submit_retur_pembelian_button = document.getElementById(
  "submit_retur_pembelian_button"
);
if (submit_retur_pembelian_button) {
  detail_retur_pembelian_button.addEventListener("click", () => {
    add_field("create", "produk_select", "satuan_select");
  });

  $(document).ready(function () {
    fetch_pembelian();
    fetch_fk("supplier");
    fetch_fk("gudang");

    $("#modal_retur_pembelian").on("shown.bs.modal", function () {
      $("#invoice_id").select2({
        placeholder: "Pilih Pembelian",
        allowClear: true,
        dropdownParent: $("#modal_retur_pembelian"),
      });

      $("#supplier_id").select2({
        placeholder: "Pilih Supplier",
        allowClear: true,
        dropdownParent: $("#modal_retur_pembelian"),
      });

      $("#gudang_id").select2({
        placeholder: "Pilih Gudang",
        allowClear: true,
        dropdownParent: $("#modal_retur_pembelian"),
      });
    });
    $("#invoice_id").on("change", function () {
      detail_pembelian_tbody.innerHTML = "";
      const selectedValue = $(this).val();
      populate_pembelian(selectedValue);
    });
    helper.format_nominal("nominal_pph");
    helper.format_nominal("diskon");

    if (input.value === "otomatis") {
      document.getElementById("invoice_id_div").style.display = "block";
      document.getElementById("retur_pembelian_div").style.display = "none";
      detail_retur_pembelian_button.style.display = "none";
    } else {
      document.getElementById("invoice_id_div").style.display = "none";
      document.getElementById("retur_pembelian_div").style.display = "block";
      detail_retur_pembelian_button.style.display = "block";
    }

    input.addEventListener("click", () => {
      if (input.value === "otomatis") {
        document.getElementById("invoice_id_div").style.display = "block";
        document.getElementById("retur_pembelian_div").style.display = "none";
        detail_retur_pembelian_button.style.display = "none";
      } else {
        document.getElementById("invoice_id_div").style.display = "none";
        document.getElementById("retur_pembelian_div").style.display = "block";
        document.getElementById("invoice_id").value = "";
        detail_retur_pembelian_button.style.display = "block";
      }
    });
  });

  submit_retur_pembelian_button.addEventListener(
    "click",
    submitRetur_pembelian
  );
}

async function fetch_pembelian() {
  try {
    const response = await apiRequest(
      `/PHP/API/invoice_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_retur_pembelian&context=create`,
      "POST",
      { select: "select_retur_pembelian" }
    );
    populate_pembelian_select(response.data);
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }
}

function populate_pembelian_select(data) {
  const select = $("#invoice_id");
  select.empty();
  select.append(new Option("Pilih Pembelian", "", false, false));

  data.forEach((item) => {
    select.append(
      new Option(
        `${item.invoice_id} - ${item.supplier_nama} - ${item.tanggal_invoice}`,
        item.invoice_id,
        false,
        false
      )
    );
  });

  select.trigger("change");
}

async function fetch_fk(field, current_field_id) {
  try {
    const response = await apiRequest(
      `/PHP/API/${field}_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_retur_pembelian&context=create`,
      "POST",
      { select: "select" }
    );
    const select = $(`#${field}_id`);
    select.empty();
    if (field === "gudang") {
      select.append(new Option("Pilih Gudang", "", false, false));
      response.data.forEach((item) => {
        const option = new Option(
          `${item.gudang_id} - ${item.nama}`,
          item.gudang_id,
          false,
          item.gudang_id == current_field_id
        );
        select.append(option);
      });
    } else if (field === "supplier") {
      select.append(new Option("Pilih Supplier", "", false, false));
      response.data.forEach((item) => {
        const option = new Option(
          `${item.supplier_id} - ${item.nama}`,
          item.supplier_id,
          false,
          item.supplier_id == current_field_id
        );
        select.append(option);
      });
    }

    select.trigger("change");
  } catch (error) {
    console.error("error:", error);
  }
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
      )}&target=tb_retur_pembelian&context=create`
    );

    const response_satuan = await apiRequest(
      `/PHP/API/satuan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_retur_pembelian&context=create`
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
    ".delete_detail_retur_pembelian",
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
          Swal.fire("Berhasil", "Retur Pembelian dihapus.", "success");
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

async function populate_pembelian(id_pembelian) {
  if (id_pembelian) {
    const pembelian_id = id_pembelian;

    let supplier_id = "";
    let ppn = "";
    let nominal_pph = "";
    let diskon = "";
    let keterangan = "";
    let no_pengiriman = "";
    let status = "";
    let no_invoice = "";
    let pembelian_ID = "";
    let gudang_id = "";
    try {
      const response = await apiRequest(
        `/PHP/API/invoice_API.php?action=select&user_id=${access.decryptItem(
          "user_id"
        )}&target=tb_retur_pembelian&context=create`,
        "POST",
        { invoice_id: pembelian_id, select: "select" }
      );
      response.data.forEach((item) => {
        supplier_id = item.supplier_id;
        ppn = item.ppn;
        nominal_pph = item.nominal_pph;
        diskon = item.diskon;
        keterangan = item.keterangan;
        status = item.status;
        pembelian_ID = item.pembelian_id;
        gudang_id = item.gudang_id;

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
        no_invoice = item.no_invoice_supplier;

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

        const parts_invoice = item.tanggal_invoice
          ? item.tanggal_invoice.split("-")
          : "";
        if (parts_invoice.length === 3) {
          const dateObj_invoice = new Date(
            parts_invoice[0],
            parts_invoice[1] - 1,
            parts_invoice[2]
          );
          pickadatejs_invoice.set("select", dateObj_invoice);
        }
      });
    } catch (error) {
      console.error("error:", error);
    }
    document.getElementById("pembelian_id").value = pembelian_ID;
    document.getElementById("no_pengiriman").value = no_pengiriman;
    document.getElementById("no_invoice").value = no_invoice;
    document.getElementById("status_pembelian").value = status;

    document.getElementById("ppn").value = ppn;
    document.getElementById("nominal_pph").value =
      helper.unformat_angka(nominal_pph);
    document.getElementById("keterangan").value = keterangan;
    document.getElementById("diskon").value = helper.unformat_angka(diskon);

    fetch_fk("supplier", supplier_id);
    fetch_fk("gudang", gudang_id);

    try {
      detail_pembelian_tbody.innerHTML = "";
      const renponse_detail_pembelian = await apiRequest(
        `/PHP/API/invoice_API.php?action=select&user_id=${access.decryptItem(
          "user_id"
        )}&target=tb_retur_pembelian&context=create`,
        "POST",
        { invoice_id: pembelian_id, table: "detail_invoice" }
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

        detail_pembelian_tbody.appendChild(tr_detail);

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

    document.getElementById("retur_pembelian_div").style.display = "block";
  } else {
    document.getElementById("retur_pembelian_div").style.display = "none";
  }
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

  delete_detail_pembelian(action);
  try {
    const response_produk = await apiRequest(
      `/PHP/API/produk_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_retur_pembelian&context=create`
    );

    const response_satuan = await apiRequest(
      `/PHP/API/satuan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_retur_pembelian&context=create`
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

function delete_detail_pembelian(action) {
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
async function submitRetur_pembelian() {
  const pembelian_id = document.getElementById("pembelian_id").value;
  const picker_po = $("#tanggal_po").pickadate("picker");
  const tanggal_po = picker_po.get("select", "yyyy-mm-dd");
  const picker_pengiriman = $("#tanggal_pengiriman").pickadate("picker");
  const tanggal_pengiriman = picker_pengiriman.get("select", "yyyy-mm-dd");
  const picker_terima = $("#tanggal_terima").pickadate("picker");
  const tanggal_terima = picker_terima.get("select", "yyyy-mm-dd");
  const picker_invoice = $("#tanggal_invoice").pickadate("picker");
  const tanggal_invoice = picker_invoice.get("select", "yyyy-mm-dd");
  const no_pengiriman = document.getElementById("no_pengiriman").value;
  const no_invoice = document.getElementById("no_invoice").value;
  const invoice_id = document.getElementById("invoice_id").value
    ? document.getElementById("invoice_id").value
    : "";
  const input = document.getElementById("input").value;
  const supplier_id = document.getElementById("supplier_id").value;
  const gudang_id = document.getElementById("gudang_id").value;
  const keterangan = document.getElementById("keterangan").value;
  let diskon = document.getElementById("diskon").value;
  const ppn = document.getElementById("ppn").value;
  let nominal_pph = document.getElementById("nominal_pph").value;
  const status_pembelian = document.getElementById("status_pembelian").value;
  const details = [];
  const rows = document.querySelectorAll(
    "#create_detail_retur_pembelian_tbody tr"
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
    user_id: `${access.decryptItem("user_id")}`,
    created_by: `${access.decryptItem("nama")}`,
    input: input,
    invoice_id: invoice_id,
    gudang_id: gudang_id,
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
      `/PHP/API/retur_pembelian_API.php?action=create`,
      "POST",
      data_pembelian
    );
    if (response.ok) {
      swal.fire("Berhasil", response.message, "success");
      document.querySelector("#create_detail_retur_pembelian_tbody").innerHTML =
        "";
      $("#modal_retur_pembelian").modal("hide");
      window.retur_pembelian_grid.forceRender();
      setTimeout(() => {
        helper.custom_grid_header("retur_pembelian");
      }, 200);
    }
  } catch (error) {
    toastr.error(error.message);
  }
}
