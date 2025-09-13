import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
let index = 0;
const submit_pembelian = document.getElementById("submit_pembelian");
const submit_detail_pembelian = document.getElementById(
  "create_detail_pembelian_button"
);
const create_biaya_tambahan = document.getElementById(
  "create_biaya_tambahan_button"
);
create_biaya_tambahan.addEventListener("click", function () {
  add_biaya("create", "data_biaya_id");
});
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
if (submit_pembelian) {
  submit_pembelian.addEventListener("click", submitPembelian);
  submit_detail_pembelian.addEventListener("click", () => {
    add_field("create", "produk_select", "satuan_select");
  });

  $(document).ready(function () {
    $("#modal_pembelian").on("shown.bs.modal", function () {
      $("#name_pembelian").trigger("focus");
      fetch_supplier();

      $("#supplier_id").select2({
        placeholder: "Pilih Supplier",
        allowClear: true,
        dropdownParent: $("#modal_pembelian"),
      });

      initPickadateOnce("#tanggal_po");
    });
    helper.format_nominal("nominal_pph");
    helper.format_nominal("diskon");

    add_field("create", "produk_select", "satuan_select");
    add_biaya("create", "data_biaya_id");
  });
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
async function fetch_supplier() {
  try {
    const response = await apiRequest(
      `/PHP/API/supplier_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_pembelian&context=create`
    );
    const select = $("#supplier_id");
    select.empty();
    select.append(new Option("Pilih Supplier", "", false, false));
    response.data.forEach((item) => {
      const option = new Option(
        `${item.supplier_id} - ${item.nama}`,
        item.supplier_id,
        false,
        false
      );
      select.append(option);
    });
    select.trigger("change");
  } catch (error) {
    console.error("error:", error);
  }
}

async function submitPembelian() {
  // Collect form data
  const picker_po = $("#tanggal_po").pickadate("picker");
  const tanggal_po = picker_po.get("select", "yyyy-mm-dd");

  const supplier_id = document.getElementById("supplier_id").value;
  const keterangan = document.getElementById("keterangan").value;
  let diskon = document.getElementById("diskon").value;
  const ppn = document.getElementById("ppn").value;
  let nominal_pph = document.getElementById("nominal_pph").value;

  const status_pembelian = document.getElementById("status_pembelian").value;

  //   const is_valid = helper.validateField(
  //     name_pembelian,
  //     /^[a-zA-Z\s]+$/,
  //     "Format nama tidak valid"
  //   );
  const details = [];
  const rows = document.querySelectorAll("#create_detail_pembelian_tbody tr");

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
    "#create_biaya_tambahan_tbody tr"
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

  if (details.length === 0) {
    toastr.error("Minimal satu detail pembelian harus diisi.");
    return;
  }
  nominal_pph = helper.format_angka(nominal_pph);
  diskon = helper.format_angka(diskon);

  const data_pembelian = {
    user_id: `${access.decryptItem("user_id")}`,
    created_by: `${access.decryptItem("nama")}`,
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
      `/PHP/API/pembelian_API.php?action=create`,
      "POST",
      data_pembelian
    );
    if (response.ok) {
      swal.fire("Berhasil", response.message, "success");
      document.querySelector("#create_detail_pembelian_tbody").innerHTML = "";
      $("#modal_pembelian").modal("hide");
      window.pembelian_grid.forceRender();
      setTimeout(() => {
        helper.custom_grid_header("pembelian");
      }, 200);
    }
  } catch (error) {
    toastr.error(error.message);
  }
}
