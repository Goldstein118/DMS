import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
let index = 0;
const submit_penjualan = document.getElementById("submit_retur_penjualan");
const submit_detail_penjualan = document.getElementById(
  "create_detail_penjualan_button"
);
const create_detail_penjualan_tbody = document.getElementById(
  "create_detail_penjualan_tbody"
);
function initPickadateOnce(selector) {
  const $el = $(selector);
  if (!$el.data("pickadate")) {
    $el.pickadate({
      format: "dd mmm yyyy",
      selectYears: 25,
      selectMonths: true,
    });
  }
}
if (submit_penjualan) {
  submit_penjualan.addEventListener("click", submitpenjualan);
  submit_detail_penjualan.addEventListener("click", () => {
    add_field("create", "produk_select", "satuan_select");
  });

  $(document).ready(function () {
    $("#modal_retur_penjualan").on("shown.bs.modal", function () {
      fetch_fk("gudang");
      fetch_fk("customer");
      fetch_fk("penjualan");

      $("#gudang_id").select2({
        placeholder: "Pilih Gudang",
        allowClear: true,
        dropdownParent: $("#modal_retur_penjualan"),
      });

      $("#customer_id").select2({
        placeholder: "Pilih Customer",
        allowClear: true,
        dropdownParent: $("#modal_retur_penjualan"),
      });

      $("#penjualan_id").select2({
        placeholder: "Pilih Penjualan",
        allowClear: true,
        dropdownParent: $("#modal_retur_penjualan"),
      });

      initPickadateOnce("#tanggal_penjualan");

      $("#penjualan_id").on("change", function () {
        create_detail_penjualan_tbody.innerHTML = "";
        const selectedValue = $(this).val();
        populate_penjualan(selectedValue);
      });
    });
    helper.format_nominal("nominal_pph");
    helper.format_nominal("diskon");

    add_field("create", "produk_select", "satuan_select");

    const jenis_input = document.getElementById("input");

    if (jenis_input.value === "otomatis") {
      document.getElementById("retur_penjualan_div").style.display = "none";
      document.getElementById("penjualan_id_div").style.display = "block";
    } else {
      document.getElementById("retur_penjualan_div").style.display = "block";
      document.getElementById("penjualan_id_div").style.display = "none";
      document.getElementById("penjualan_id").value = "";
    }

    jenis_input.addEventListener("change", () => {
      if (jenis_input.value === "otomatis") {
        document.getElementById("retur_penjualan_div").style.display = "none";
        document.getElementById("penjualan_id_div").style.display = "block";
      } else {
        document.getElementById("retur_penjualan_div").style.display = "block";
        document.getElementById("penjualan_id_div").style.display = "none";
        document.getElementById("penjualan_id").value = "";
      }
    });
  });
}

async function select_detail_penjualan(
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
      dropdownParent: $("#modal_penjualan"),
    });
    $(`#${satuan_element_id}${index}`).select2({
      placeholder: "Pilih satuan",
      allowClear: true,
      dropdownParent: $("#modal_penjualan"),
    });
  } else if (action == "update") {
    $(`#${produk_element_id}${index}`).select2({
      placeholder: "Pilih produk",
      allowClear: true,
      dropdownParent: $("#update_modal_penjualan"),
    });
    $(`#${satuan_element_id}${index}`).select2({
      placeholder: "Pilih satuan",
      allowClear: true,
      dropdownParent: $("#update_modal_penjualan"),
    });
  }

  delete_detail_penjualan(action);
  try {
    const response_produk = await apiRequest(
      `/PHP/API/produk_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_penjualan&context=create`
    );

    const response_satuan = await apiRequest(
      `/PHP/API/satuan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_penjualan&context=create`
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

function delete_detail_penjualan(action) {
  $(`#${action}_detail_penjualan_tbody`).on(
    "click",
    ".delete_detail_penjualan",
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
          Swal.fire("Berhasil", "penjualan dihapus.", "success");
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

function add_field(action, produk_element_id, satuan_element_id) {
  var myTable = document.getElementById(`${action}_detail_penjualan_tbody`);
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
  delete_button.className = "btn btn-danger btn-sm delete_detail_penjualan";
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
  select_detail_penjualan(
    currentIndex,
    action,
    produk_element_id,
    satuan_element_id
  );
}
async function fetch_fk(field) {
  try {
    const response = await apiRequest(
      `/PHP/API/${field}_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_retur_penjualan&context=create`,
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
          false
        );
        select.append(option);
      });
    } else if (field === "customer") {
      select.append(new Option("Pilih Customer", "", false, false));
      response.data.forEach((item) => {
        const option = new Option(
          `${item.customer_id} - ${item.nama} - ${item.channel_nama} `,
          item.customer_id,
          false,
          false
        );
        select.append(option);
      });
    } else if (field === "penjualan") {
      select.append(new Option("Pilih Penjualan", "", false, false));
      response.data.forEach((item) => {
        const option = new Option(
          `${item.penjualan_id} - ${item.customer_nama} - ${item.tanggal_penjualan} `,
          item.penjualan_id,
          false,
          false
        );
        select.append(option);
      });
    }

    select.trigger("change");
  } catch (error) {
    console.error("error:", error);
  }
}

async function submitpenjualan() {
  // Collect form data
  const picker_penjualan = $("#tanggal_penjualan").pickadate("picker");
  const tanggal_penjualan = picker_penjualan.get("select", "yyyy-mm-dd");
  const gudang_id = document.getElementById("gudang_id").value;
  const customer_id = document.getElementById("customer_id").value;
  const keterangan = document.getElementById("keterangan_penjualan").value;
  let diskon = document.getElementById("diskon").value;
  const ppn = document.getElementById("ppn").value;
  let nominal_pph = document.getElementById("nominal_pph").value;

  const status_penjualan = document.getElementById("status_penjualan").value;

  const details = [];
  const rows = document.querySelectorAll("#create_detail_penjualan_tbody tr");

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
      toastr.error("Semua field pada detail penjualan wajib diisi.");
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
    toastr.error("Minimal satu detail penjualan harus diisi.");
    return;
  }
  nominal_pph = helper.format_angka(nominal_pph);
  diskon = helper.format_angka(diskon);

  const data_penjualan = {
    create_penjualan: "create_penjualan",
    user_id: `${access.decryptItem("user_id")}`,
    created_by: `${access.decryptItem("nama")}`,
    tanggal_penjualan: tanggal_penjualan,
    gudang_id: gudang_id,
    customer_id: customer_id,
    keterangan: keterangan,
    ppn: ppn,
    diskon: diskon,
    nominal_pph: nominal_pph,
    status: status_penjualan,
    details: details,
  };
  console.log(data_penjualan);
  try {
    const response = await apiRequest(
      `/PHP/API/penjualan_API.php?action=create`,
      "POST",
      data_penjualan
    );
    if (response.ok) {
      swal.fire("Berhasil", response.message, "success");
      document.querySelector("#create_detail_penjualan_tbody").innerHTML = "";
      $("#modal_penjualan").modal("hide");
      window.penjualan_grid.forceRender();
      setTimeout(() => {
        helper.custom_grid_header("penjualan");
      }, 200);
    }
  } catch (error) {
    toastr.error(error.message);
  }
}

async function populate_penjualan(penjualan_id) {
  if (penjualan_id) {
    const penjualan_id = penjualan_id;

    let customer_id = "";
    let ppn = "";
    let nominal_pph = "";
    let diskon = "";
    let keterangan = "";
    let no_pengiriman = "";
    let status = "";
    let no_invoice = "";

    try {
      const response = await apiRequest(
        `/PHP/API/penjualan_API.php?action=select&user_id=${access.decryptItem(
          "user_id"
        )}&target=tb_retur_pembelian&context=create`,
        "POST",
        { penjualan_id: penjualan_id, table: "tb_penjualan" }
      );
      response.data.forEach((item) => {
        customer_id = item.customer_id;
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
    document.getElementById("penjualan_id").value = penjualan_id;
    document.getElementById("no_pengiriman").value = no_pengiriman;
    document.getElementById("no_invoice").value = no_invoice;
    document.getElementById("status_pembelian").value = status;

    document.getElementById("ppn").value = ppn;
    document.getElementById("nominal_pph").value =
      helper.unformat_angka(nominal_pph);
    document.getElementById("keterangan").value = keterangan;
    document.getElementById("diskon").value = helper.unformat_angka(diskon);

    try {
      const response = await apiRequest(
        `/PHP/API/supplier_API.php?action=select&user_id=${access.decryptItem(
          "user_id"
        )}&target=tb_retur_pembelian&context=create`
      );
      populate_supplier(response.data, supplier_id);
    } catch (error) {
      console.error("error:", error);
    }

    try {
      detail_pembelian_tbody.innerHTML = "";
      const renponse_detail_pembelian = await apiRequest(
        `/PHP/API/invoice_API.php?action=select&user_id=${access.decryptItem(
          "user_id"
        )}&target=tb_retur_pembelian&context=create`,
        "POST",
        { invoice_id: penjualan_id, table: "detail_invoice" }
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
  }
}
