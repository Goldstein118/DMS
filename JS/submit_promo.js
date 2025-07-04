import config from "./config.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const submit_promo = document.getElementById("submit_promo");
if (submit_promo) {
  submit_promo.addEventListener("click", submitPromo);
  document.getElementById("loading_spinner").style.visibility = "hidden";
  $("#loading_spinner").fadeOut();
  $(document).ready(function () {
    $("#jenis_brand").select2({
      dropdownParent: $("#modal_promo"),
    });
    $("#jenis_customer").select2({
      dropdownParent: $("#modal_promo"),
    });
    $("#jenis_produk").select2({
      dropdownParent: $("#modal_promo"),
    });
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
  });
}

async function fetch_fk(field) {
  try {
    const response = await apiRequest(
      `/PHP/API/${field}_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_promo&context=create`,
      "POST",
      { select: "select" }
    );
    populateDropdown(response.data, field);
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }
}

function populateDropdown(data, field) {
  const select = $(`#jenis_${field}`);
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
  }

  select.trigger("change");
}
let jenis_bonus = document.getElementById("jenis_bonus");

jenis_bonus.addEventListener("change", (event) => {
  let bonus = jenis_bonus.options[jenis_bonus.selectedIndex].text;
  if (bonus === "Barang") {
    document.getElementById("card_promo_3").style.display = "block";
  } else {
    document.getElementById("card_promo_3").style.display = "none";
  }
});

fetch_fk("brand");
fetch_fk("customer");
fetch_fk("produk");

async function submitPromo() {
  const nama = document.getElementById("nama_promo").value;
  const picker_tanggal_berlaku = $("#tanggal_berlaku").pickadate("picker");
  const tanggal_berlaku = picker_tanggal_berlaku.get("select", "yyyy-mm-dd");
  const picker_tanggal_selesai = $("#tanggal_selesai").pickadate("picker");
  const tanggal_selesai = picker_tanggal_selesai.get("select", "yyyy-mm-dd");
  const jenis_bonus = document.getElementById("jenis_bonus").value;
  const akumulasi = document.getElementById("akumulasi").value;
  const prioritas = document.getElementById("prioritas").value;
  const jenis_diskon = document.getElementById("jenis_diskon").value;
  const jumlah_diskon = document.getElementById("jumlah_diskon").value;

  var brand_val = [];
  var customer_val = [];
  var produk_val = [];

  $("#jenis_brand")
    .select2("data")
    .forEach(function (item) {
      brand_val.push(item.id);
    });

  $("#jenis_customer")
    .select2("data")
    .forEach(function (item) {
      customer_val.push(item.id);
    });

  $("#jenis_produk")
    .select2("data")
    .forEach(function (item) {
      produk_val.push(item.id);
    });

  const status_promo = document.getElementById("status_promo").value;
  const qty_akumulasi = document.getElementById("qty_akumulasi").value;
  const qty_min = document.getElementById("qty_min").value;
  const qty_max = document.getElementById("qty_max").value;
  const quota = document.getElementById("quota").value;

  const qty_bonus = document.getElementById("qty_bonus").value;
  const diskon_bonus_barang = document.getElementById(
    "diskon_bonus_barang"
  ).value;

  if (
    !nama ||
    nama.trim() === "" ||
    !tanggal_berlaku ||
    tanggal_berlaku.trim() === "" ||
    !tanggal_selesai ||
    tanggal_selesai.trim() === "" ||
    !jenis_bonus ||
    jenis_bonus.trim() === ""
  ) {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
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
  if (compare_tanggal) {
    const data_promo = {
      nama: nama,
      tanggal_berlaku: tanggal_berlaku,
      tanggal_selesai: tanggal_selesai,
      jenis_bonus: jenis_bonus,
      jenis_brand: brand_val,
      jenis_customer: customer_val,
      jenis_produk: produk_val,
      akumulasi: akumulasi,
      prioritas: prioritas,
      jenis_diskon: jenis_diskon,
      jumlah_diskon: jumlah_diskon,
      status_promo: status_promo,
      qty_akumulasi: qty_akumulasi,
      qty_min: qty_min,
      qty_max: qty_max,
      quota: quota,
      qty_bonus: qty_bonus,
      diskon_bonus_barang: diskon_bonus_barang,
    };
    console.log(data_promo);
    try {
      const response = await apiRequest(
        `/PHP/API/promo_API.php?action=create`,
        "POST",
        { data_promo }
      );
      if (response.ok) {
        swal.fire("Berhasil", response.message, "success");
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
