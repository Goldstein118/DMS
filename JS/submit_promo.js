import config from "./config.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

const submit_promo = document.getElementById("submit_promo");
const promo_kondisi_button = document.getElementById("promo_kondisi_button");
const promo_bonus_barang_button = document.getElementById(
  "promo_bonus_barang_button"
);
const toggle_jenis_bonus = document.getElementById("toggle_jenis_bonus");

if (submit_promo) {
  submit_promo.addEventListener("click", submitPromo);
  promo_kondisi_button.addEventListener("click", () => {
    add_field_kondisi(table_promo_kondisi);
  });
  promo_bonus_barang_button.addEventListener("click", () => {
    add_field_barang(table_promo_bonus_barang);
  });
  document.getElementById("loading_spinner").style.visibility = "hidden";
  $("#loading_spinner").fadeOut();
  $(document).ready(function () {
    $("#tanggal_berlaku").pickadate({
      format: "dd mmm yyyy",
      formatSubmit: "yyyy-mm-dd",
      selectYears: 25,
      selectMonths: true,
    });
    $("#tanggal_selesai").pickadate({
      format: "dd mmm yyyy",
      formatSubmit: "yyyy-mm-dd",
      selectYears: 25,
      selectMonths: true,
    });
    fetch_fk("satuan", "", "satuan_id");
  });
}

function delete_promo_kondisi(field) {
  $(`#${field}`).on("click", ".delete_promo_kondisi", async function () {
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
        Swal.fire("Berhasil", "Pricelist dihapus.", "success");
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

const table_promo_bonus_barang = document.getElementById(
  "table_bonus_barang_tbody"
);

const table_promo_kondisi = document.getElementById(
  "jenis_promo_kondisi_tbody"
);
let index = 0;
add_field_kondisi(table_promo_kondisi);
add_field_barang();
function add_field_kondisi(myTable) {
  var currentIndex = myTable.rows.length;
  const tr_detail = document.createElement("tr");

  // Jenis (td_select)
  const td_select = document.createElement("td");
  const jenis_select = document.createElement("select");
  jenis_select.className = "form-select jenis-select";
  jenis_select.innerHTML = `
    <option value="">-- Pilih --</option>
    <option value="brand">Brand</option>
    <option value="customer">Customer</option>
    <option value="produk">Produk</option>
    <option value="channel">Channel</option>
  `;
  td_select.appendChild(jenis_select);
  // Dynamic Select2 (td_dynamic)
  const td_dynamic = document.createElement("td");
  const dynamic_select = document.createElement("select");
  dynamic_select.className = "form-select";

  td_dynamic.appendChild(dynamic_select);

  const td_exclude = document.createElement("td");
  const exclude_select = document.createElement("select");
  exclude_select.className = "form-select";
  exclude_select.innerHTML = `
    <option value="include">Include</option>
    <option value="exclude">Exclude</option>
    `;

  td_exclude.appendChild(exclude_select);

  const qty_min_td = document.createElement("td");
  const qty_min = document.createElement("input");
  qty_min.className = "form-control";
  qty_min.setAttribute("id", `qty_min${currentIndex}`);
  qty_min.setAttribute("disabled", "disabled");
  qty_min_td.appendChild(qty_min);

  const qty_max_td = document.createElement("td");
  const qty_max = document.createElement("input");
  qty_max.className = "form-control";
  qty_max.setAttribute("id", `qty_max${currentIndex}`);
  qty_max.setAttribute("disabled", "disabled");
  qty_max_td.appendChild(qty_max);

  const qty_akumulasi_td = document.createElement("td");
  const qty_akumulasi = document.createElement("input");
  qty_akumulasi.className = "form-control";
  qty_akumulasi.setAttribute("id", `qty_akumulasi${currentIndex}`);
  qty_akumulasi.setAttribute("disabled", "disabled");
  qty_akumulasi_td.appendChild(qty_akumulasi);

  // Delete Button (td_aksi)
  const td_aksi = document.createElement("td");
  const delete_button = document.createElement("button");
  delete_button.type = "button";
  delete_button.className = "btn btn-danger btn-sm delete_promo_kondisi";
  delete_button.innerHTML = `<i class="bi bi-trash-fill"></i>`;
  td_aksi.style.textAlign = "center";
  td_aksi.appendChild(delete_button);

  tr_detail.appendChild(td_select);
  tr_detail.appendChild(td_dynamic);
  tr_detail.appendChild(td_exclude);
  tr_detail.appendChild(qty_max_td);
  tr_detail.appendChild(qty_min_td);
  tr_detail.appendChild(qty_akumulasi_td);
  tr_detail.appendChild(td_aksi);
  table_promo_kondisi.appendChild(tr_detail);

  jenis_select.addEventListener("change", () => {
    if (jenis_select.value === "brand") {
      dynamic_select.className = "dynamic-select js-example-basic-multiple";
      dynamic_select.setAttribute("multiple", "multiple");
      dynamic_select.setAttribute("id", `jenis_brand${currentIndex}`);
      exclude_select.setAttribute("id", `exclude_include_brand${currentIndex}`);
      qty_min.removeAttribute("disabled");
      qty_max.removeAttribute("disabled");

      if (document.getElementById("kelipatan").value === "ya") {
        qty_akumulasi.removeAttribute("disabled");
      }

      fetch_fk("brand", currentIndex, "jenis_brand"); // ← Now that the element exists, fetch the data
    } else if (jenis_select.value === "customer") {
      dynamic_select.className = "dynamic-select js-example-basic-multiple";
      dynamic_select.setAttribute("multiple", "multiple");
      dynamic_select.setAttribute("id", `jenis_customer${currentIndex}`);
      exclude_select.setAttribute(
        "id",
        `exclude_include_customer${currentIndex}`
      );
      qty_akumulasi.setAttribute("disabled", "disabled");
      qty_max.setAttribute("disabled", "disabled");
      qty_min.setAttribute("disabled", "disabled");

      fetch_fk("customer", currentIndex, "jenis_customer");
    } else if (jenis_select.value === "produk") {
      dynamic_select.className = "dynamic-select js-example-basic-multiple";
      dynamic_select.setAttribute("multiple", "multiple");
      dynamic_select.setAttribute("id", `jenis_produk${currentIndex}`);
      exclude_select.setAttribute(
        "id",
        `exclude_include_produk${currentIndex}`
      );
      qty_min.removeAttribute("disabled");
      qty_max.removeAttribute("disabled");
      if (document.getElementById("kelipatan").value === "ya") {
        qty_akumulasi.removeAttribute("disabled");
      }
      fetch_fk("produk", currentIndex, "jenis_produk");
    } else if (jenis_select.value === "channel") {
      dynamic_select.className = "dynamic-select js-example-basic-multiple";
      dynamic_select.setAttribute("multiple", "multiple");
      dynamic_select.setAttribute("id", `jenis_channel${currentIndex}`);
      exclude_select.setAttribute(
        "id",
        `exclude_include_channel${currentIndex}`
      );
      qty_akumulasi.setAttribute("disabled", "disabled");
      qty_max.setAttribute("disabled", "disabled");
      qty_min.setAttribute("disabled", "disabled");
      fetch_fk("channel", currentIndex, "jenis_channel");
    }
  });
  delete_promo_kondisi("jenis_promo_kondisi_tbody");
}
async function add_field_barang() {
  let currentIndex = index++;
  const tr_bonus_barang = document.createElement("tr");

  const td_select_produk = document.createElement("td");
  const select_produk = document.createElement("select");
  select_produk.className = "form-select";
  select_produk.setAttribute("id", `bonus_produk${currentIndex}`);
  td_select_produk.appendChild(select_produk);

  const td_jlh_qty = document.createElement("td");
  const jlh_qty = document.createElement("input");
  jlh_qty.className = "form-control";
  jlh_qty.setAttribute("type", "number");
  jlh_qty.setAttribute("min", "0");
  jlh_qty.setAttribute("id", `jlh_qty${currentIndex}`);
  td_jlh_qty.appendChild(jlh_qty);

  const td_jenis_diskon = document.createElement("td");
  const jenis_diskon = document.createElement("select");
  jenis_diskon.innerHTML = `
    <option value="nominal">Nominal</option>
    <option value="persen">Persen</option>
  
  `;
  jenis_diskon.className = "form-select";
  jenis_diskon.setAttribute("id", `jenis_diskon${currentIndex}`);
  td_jenis_diskon.appendChild(jenis_diskon);

  const td_jumlah_diskon_nominal = document.createElement("td");
  const jumlah_diskon_nominal = document.createElement("input");
  jumlah_diskon_nominal.className = "form-control";
  jumlah_diskon_nominal.setAttribute(
    "id",
    `jumlah_diskon_nominal${currentIndex}`
  );
  jumlah_diskon_nominal.setAttribute("type", "number");
  jumlah_diskon_nominal.setAttribute("min", "0");
  td_jumlah_diskon_nominal.appendChild(jumlah_diskon_nominal);

  const td_aksi = document.createElement("td");
  const delete_button = document.createElement("button");
  delete_button.type = "button";
  delete_button.className = "btn btn-danger btn-sm delete_promo_kondisi";
  delete_button.innerHTML = `<i class="bi bi-trash-fill"></i>`;
  td_aksi.style.textAlign = "center";
  td_aksi.appendChild(delete_button);

  tr_bonus_barang.appendChild(td_select_produk);
  tr_bonus_barang.appendChild(td_jlh_qty);
  tr_bonus_barang.appendChild(td_jenis_diskon);
  tr_bonus_barang.appendChild(td_jumlah_diskon_nominal);
  tr_bonus_barang.appendChild(td_aksi);
  table_promo_bonus_barang.appendChild(tr_bonus_barang);

  fetch_fk("produk", currentIndex, "bonus_produk", "bonus");

  delete_promo_kondisi("table_bonus_barang_tbody");
}
async function fetch_fk(field, index, element_id, tipe) {
  try {
    const response = await apiRequest(
      `/PHP/API/${field}_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_promo&context=create`,
      "POST",
      { select: "select" }
    );
    populateDropdown(response.data, field, index, element_id, tipe);
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }
}

function populateDropdown(data, field, index, element_id, tipe) {
  const select = $(`#${element_id}${index}`);
  select.empty();
  if (field === "brand") {
    data.forEach((item) => {
      select.append(
        new Option(
          `${item.brand_id} - ${item.nama}`,
          item.brand_id,
          false,
          false
        )
      );
    });
  } else if (field === "customer") {
    data.forEach((item) => {
      select.append(
        new Option(
          `${item.customer_id} - ${item.nama}`,
          item.customer_id,
          false,
          false
        )
      );
    });
  } else if (field === "produk") {
    if (tipe === "bonus") {
      select.append(new Option("Pilih Produk", "", false, false));
    }
    data.forEach((item) => {
      select.append(
        new Option(
          `${item.produk_id} - ${item.nama}`,
          item.produk_id,
          false,
          false
        )
      );
    });
  } else if (field === "channel") {
    data.forEach((item) => {
      select.append(
        new Option(
          `${item.channel_id} - ${item.nama}`,
          item.channel_id,
          false,
          false
        )
      );
    });
  } else if (field === "satuan") {
    select.append(new Option("Pilih Satuan", "", false, false));
    data.forEach((item) => {
      select.append(
        new Option(
          `${item.satuan_id} - ${item.nama}`,
          item.satuan_id,
          false,
          false
        )
      );
    });
  }
  select.select2({
    dropdownParent: $("#modal_promo"),
  });

  select.trigger("change");
}
let jenis_bonus = document.getElementById("jenis_bonus");

jenis_bonus.addEventListener("change", (event) => {
  let bonus = jenis_bonus.options[jenis_bonus.selectedIndex].text;
  if (bonus === "Barang") {
    document.getElementById("card_promo_3").style.display = "block";
    toggle_jenis_bonus.style.display = "none";
    document.getElementById("jumlah_diskon").value = "";
  } else {
    document.getElementById("card_promo_3").style.display = "none";
    document.getElementById("table_bonus_barang_tbody").innerHTML = "";
    toggle_jenis_bonus.style.display = "block";
  }
});

let akumulasi = document.getElementById("akumulasi");
let kelipatan = document.getElementById("kelipatan");
function handleToggle(source, target) {
  if (source.value === "ya") {
    target.value = "tidak";
    target.disabled = true;
  } else {
    target.disabled = false;
    target.value = "ya";
    source.disabled = true;
  }
}
handleToggle(akumulasi, kelipatan);
handleToggle(kelipatan, akumulasi);
akumulasi.addEventListener("change", () => {
  handleToggle(akumulasi, kelipatan);
});

kelipatan.addEventListener("change", () => {
  handleToggle(kelipatan, akumulasi);
});
async function submitPromo() {
  const nama = document.getElementById("nama_promo").value;
  const picker_tanggal_berlaku = $("#tanggal_berlaku").pickadate("picker");
  const tanggal_berlaku = picker_tanggal_berlaku.get("select", "yyyy-mm-dd");
  const picker_tanggal_selesai = $("#tanggal_selesai").pickadate("picker");
  const tanggal_selesai = picker_tanggal_selesai.get("select", "yyyy-mm-dd");
  const jenis_bonus = document.getElementById("jenis_bonus").value;
  const akumulasi = document.getElementById("akumulasi").value;
  const kelipatan = document.getElementById("kelipatan").value;
  const prioritas = document.getElementById("prioritas").value;
  const jenis_diskon = document.getElementById("jenis_diskon").value;
  const jumlah_diskon = document.getElementById("jumlah_diskon").value;
  const quota = document.getElementById("quota").value;
  const status_promo = document.getElementById("status_promo").value;
  const satuan_id = $("#satuan_id").val();
  const promo_kondisi = [];
  const row_kondisi = document.querySelectorAll(
    "#jenis_promo_kondisi_tbody tr"
  );

  for (const row of row_kondisi) {
    const jenis_select = row.querySelector("td:nth-child(1) select"); // Jenis
    const dynamic_select = row.querySelector("td:nth-child(2) select"); // Dynamic
    const exclude_select = row.querySelector("td:nth-child(3) select"); // Include/Exclude
    const qty_max = row.querySelector("td:nth-child(4) input"); // Qty Max
    const qty_min = row.querySelector("td:nth-child(5) input"); // Qty Min
    const qty_akumulasi = row.querySelector("td:nth-child(6) input"); // Qty Akumulasi

    const selected_jenis = jenis_select?.value?.trim();
    const selected_dynamic = Array.from(dynamic_select?.selectedOptions || [])
      .map((opt) => opt.value)
      .filter(Boolean);
    const exclude_option = exclude_select?.value?.trim();
    const min = qty_min?.value?.trim();
    const max = qty_max?.value?.trim();
    const akumulasi = qty_akumulasi?.value?.trim();

    if (
      !selected_jenis ||
      selected_jenis.trim() === "" ||
      selected_dynamic.length === 0 ||
      !exclude_option ||
      exclude_option.trim() === ""
    ) {
      toastr.error("Semua field pada promo kondisi wajib diisi.");
      return;
    }
    if (
      (selected_jenis === "brand" || selected_jenis === "channel") &&
      (!min || !max || !akumulasi)
    ) {
      toastr.error("Field qty pada brand/channel wajib diisi.");
      return;
    }
    promo_kondisi.push({
      jenis_kondisi: selected_jenis,
      kondisi: selected_dynamic,
      exclude_include: exclude_option,
      qty_min: min,
      qty_max: max,
      qty_akumulasi: akumulasi,
    });
  }

  // console.log(promo_kondisi);
  const promo_bonus_barang = [];
  const row_bonus_barang = document.querySelectorAll(
    "#table_bonus_barang_tbody tr"
  );
  if (jenis_bonus.value === "barang") {
    for (const row of row_bonus_barang) {
      const produk_select = row.querySelector("td:nth-child(1) select");
      const jumlah_qty = row.querySelector("td:nth-child(2) input");
      const jenis_diskon = row.querySelector("td:nth-child(3) select");
      const jumlah_diskon_nominal = row.querySelector("td:nth-child(4) input");

      const produk = produk_select?.value;
      const qty = jumlah_qty?.value?.trim();
      const diskon = jenis_diskon?.value;
      const jlh_diskon_nominal = jumlah_diskon_nominal?.value?.trim();

      if (
        !produk ||
        produk.trim() === "" ||
        !qty ||
        qty.trim() === "" ||
        !diskon ||
        diskon.trim() === "" ||
        !jlh_diskon_nominal ||
        jlh_diskon_nominal.trim() === ""
      ) {
        toastr.error("Semua field pada promo bonus barang wajib diisi.");
        return;
      }

      promo_bonus_barang.push({
        produk_id: produk,
        qty_bonus: qty,
        jenis_diskon: diskon,
        jlh_diskon: jlh_diskon_nominal,
      });
    }
  }

  // console.log(promo_bonus_barang);
  if (jenis_bonus.value === "barang") {
    if (
      !nama ||
      nama.trim() === "" ||
      !tanggal_berlaku ||
      tanggal_berlaku.trim() === "" ||
      !tanggal_selesai ||
      tanggal_selesai.trim() === "" ||
      !jenis_bonus ||
      jenis_bonus.trim() === "" ||
      !akumulasi ||
      akumulasi.trim() === "" ||
      !status_promo ||
      status_promo.trim() === "" ||
      !quota ||
      quota.trim() === ""
    ) {
      toastr.error("Kolom * wajib diisi.");
      return;
    }
  } else if (jenis_bonus.value === "nominal") {
    if (
      !nama ||
      nama.trim() === "" ||
      !tanggal_berlaku ||
      tanggal_berlaku.trim() === "" ||
      !tanggal_selesai ||
      tanggal_selesai.trim() === "" ||
      !jenis_bonus ||
      jenis_bonus.trim() === "" ||
      !akumulasi ||
      akumulasi.trim() === "" ||
      !jenis_diskon ||
      jenis_diskon.trim() === "" ||
      !jumlah_diskon ||
      jumlah_diskon.trim() === "" ||
      !status_promo ||
      status_promo.trim() === "" ||
      !quota ||
      quota.trim() === ""
    ) {
      toastr.error("Kolom * wajib diisi.");
      return;
    }
  }

  // if (promo_kondisi.length === 0 || promo_bonus_barang.length === 0) {
  //   return;
  // }
  let compare_tanggal = true;
  const startDate = new Date(tanggal_berlaku);
  const endDate = new Date(tanggal_selesai);
  if (startDate > endDate) {
    Swal.fire({
      title: "Gagal",
      text: "tanggal berlaku lebih besar dari tanggal selesai",
      icon: "error",
    });
    return;
  }
  const validate_input =
    helper.validateField(nama, /^[a-zA-Z0-9\s]+$/, "Format nama tidak valid") &&
    helper.validateField(prioritas, /^\d+$/, "Format prioritas tidak valid") &&
    helper.validateField(
      jumlah_diskon,
      /^\d+$/,
      "Format jumlah diskon tidak valid"
    ) &&
    helper.validateField(quota, /^\d+$/, "Format quota tidak valid");

  if (validate_input && compare_tanggal) {
    const data_promo = {
      user_id: `${access.decryptItem("user_id")}`,
      nama: nama,
      tanggal_berlaku: tanggal_berlaku,
      tanggal_selesai: tanggal_selesai,
      jenis_bonus: jenis_bonus,
      akumulasi: akumulasi,
      kelipatan: kelipatan,
      prioritas: prioritas,
      jenis_diskon: jenis_diskon,
      jumlah_diskon: jumlah_diskon,
      status: status_promo,
      quota: quota,
      satuan_id: satuan_id,
      promo_kondisi: promo_kondisi,
      promo_bonus_barang: promo_bonus_barang,
    };
    try {
      const response = await apiRequest(
        `/PHP/API/promo_API.php?action=create`,
        "POST",
        data_promo
      );
      if (response.ok) {
        $("#modal_promo").modal("hide");
        swal.fire("Berhasil", response.message, "success");
        window.promo_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("promo");
        }, 200);
        document.getElementById("nama_promo").value = "";
        document.getElementById("tanggal_berlaku").value = "";
        document.getElementById("tanggal_selesai").value = "";
        document.getElementById("prioritas").value = "";
        document.getElementById("jumlah_diskon").value = "";
        document.getElementById("quota").value = "";
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
