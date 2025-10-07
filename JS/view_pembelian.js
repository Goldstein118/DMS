import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
async function populate_table_detail(pembelian_id) {
  const result = await apiRequest(
    `/PHP/API/pembelian_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}`,
    "POST",
    { pembelian_id: pembelian_id, table: "view_detail_pembelian" }
  );
  const tableBody = document.getElementById("view_detail_pembelian_tbody");

  tableBody.innerHTML = "";
  let nomor = 1;
  if (result.data.length != 0) {
    result.data.forEach((detail) => {
      const tr_detail = document.createElement("tr");

      const td_no = document.createElement("td");
      td_no.textContent = nomor;
      td_no.style.textAlign = "center";

      const td_kode_pembelian = document.getElementById("view_pembelian_id");
      td_kode_pembelian.textContent = detail.pembelian_id;

      const biaya_view_pembelian_id = document.getElementById(
        "biaya_view_pembelian_id"
      );
      biaya_view_pembelian_id.textContent = detail.pembelian_id;
      //   const td_status = document.getElementById("view_status");
      //   td_status.textContent = detail.status;

      //   const td_harga_default = document.getElementById("view_harga_default");
      //   td_harga_default.textContent = detail.harga_default;

      const tdProduk = document.createElement("td");
      tdProduk.textContent = detail.produk_nama;

      const tdQty = document.createElement("td");
      tdQty.textContent = detail.qty;

      const tdSatuan = document.createElement("td");
      tdSatuan.textContent = detail.satuan_nama;

      const tdHarga = document.createElement("td");
      tdHarga.setAttribute("id", "view_harga");
      tdHarga.textContent = helper.format_angka(detail.harga);
      tdHarga.style.textAlign = "right";

      const tdDiskon = document.createElement("td");
      tdDiskon.setAttribute("id", "view_diskon");
      tdDiskon.textContent = helper.format_angka(detail.diskon);
      tdDiskon.style.textAlign = "right";

      // Append all tds to tr
      tr_detail.appendChild(td_no);
      tr_detail.appendChild(tdProduk);
      tr_detail.appendChild(tdQty);
      tr_detail.appendChild(tdSatuan);
      tr_detail.appendChild(tdHarga);
      tr_detail.appendChild(tdDiskon);

      nomor += 1;
      // Append tr to tbody
      tableBody.appendChild(tr_detail);
    });
  } else {
    // Optional: show message if no data found
    const tr = document.createElement("tr");
    const td = document.createElement("td");
    td.colSpan = 4;
    td.className = "text-center text-muted";
    td.textContent = "No details found for this pembelian.";
    tr.appendChild(td);
    tableBody.appendChild(tr);
    document.getElementById("detail_pembelian").classList =
      "table table-hover table-bordered table-sm table-striped no_print";
  }
}
function getQueryParam(key) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(key);
}
const print = document.getElementById("print");
print.addEventListener("click", () => {
  document.getElementById("div_biaya_tambahan_header").style.display = "block";
  window.onafterprint = () => {
    document.getElementById("div_biaya_tambahan_header").style.display = "none";
  };

  window.print();
});

const tanggal_po = document.getElementById("view_tanggal_po");
const tanggal_terima = document.getElementById("view_tanggal_terima");
const tanggal_pengiriman = document.getElementById("view_tanggal_pengiriman");
const tanggal_invoice = document.getElementById("view_tanggal_invoice");

const biaya_tanggal_po = document.getElementById("biaya_tanggal_po");
const biaya_tanggal_terima = document.getElementById("biaya_tanggal_terima");
const biaya_tanggal_pengiriman = document.getElementById(
  "biaya_tanggal_pengiriman"
);
const biaya_tanggal_invoice = document.getElementById("biaya_tanggal_invoice");

async function populate_tanggal(pembelian_id) {
  const result = await apiRequest(
    `/PHP/API/pembelian_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}`,
    "POST",
    { pembelian_id: pembelian_id }
  );
  result.data.forEach((detail) => {
    detail.tanggal_po
      ? (tanggal_po.textContent =
          "Tanggal Po: " + helper.format_date(detail.tanggal_po))
      : (tanggal_po.textContent = "");

    detail.tanggal_pengiriman
      ? (tanggal_pengiriman.textContent =
          "Tanggal Pengiriman: " +
          helper.format_date(detail.tanggal_pengiriman))
      : (tanggal_pengiriman.textContent = "");

    detail.tanggal_terima
      ? (tanggal_terima.textContent =
          "Tanggal Terima: " + helper.format_date(detail.tanggal_terima))
      : (tanggal_terima.textContent = "");

    detail.tanggal_invoice
      ? (tanggal_invoice.textContent =
          "Tanggal Invoice: " + helper.format_date(detail.tanggal_invoice))
      : (tanggal_invoice.textContent = "");

    detail.tanggal_po
      ? (biaya_tanggal_po.textContent =
          "Tanggal Po: " + helper.format_date(detail.tanggal_po))
      : (biaya_tanggal_po.textContent = "");

    detail.tanggal_pengiriman
      ? (biaya_tanggal_pengiriman.textContent =
          "Tanggal Pengiriman: " +
          helper.format_date(detail.tanggal_pengiriman))
      : (biaya_tanggal_pengiriman.textContent = "");

    detail.tanggal_terima
      ? (biaya_tanggal_terima.textContent =
          "Tanggal Terima: " + helper.format_date(detail.tanggal_terima))
      : (biaya_tanggal_terima.textContent = "");

    detail.tanggal_invoice
      ? (biaya_tanggal_invoice.textContent =
          "Tanggal Invoice: " + helper.format_date(detail.tanggal_invoice))
      : (biaya_tanggal_invoice.textContent = "");
  });
}

async function populate_biaya_tambahan(pembelian_id) {
  const result = await apiRequest(
    `/PHP/API/pembelian_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}`,
    "POST",
    { pembelian_id: pembelian_id, table: "view_biaya_tambahan" }
  );

  const tableBody = document.getElementById("view_biaya_tambahan_tbody");

  tableBody.innerHTML = "";
  let nomor = 1;
  if (result.data.length != 0) {
    result.data.forEach((detail) => {
      const tr_detail = document.createElement("tr");

      const td_no = document.createElement("td");
      td_no.textContent = nomor;
      td_no.style.textAlign = "center";

      const tdBiaya = document.createElement("td");
      tdBiaya.textContent = detail.biaya_nama;

      const tdKeterangan = document.createElement("td");
      tdKeterangan.textContent = detail.keterangan;

      const tdJumlah = document.createElement("td");
      tdJumlah.setAttribute("id", "view_jumlah");
      tdJumlah.textContent = helper.format_angka(detail.jlh);
      tdJumlah.style.textAlign = "right";

      // Append all tds to tr
      tr_detail.appendChild(td_no);
      tr_detail.appendChild(tdBiaya);
      tr_detail.appendChild(tdKeterangan);
      tr_detail.appendChild(tdJumlah);
      nomor += 1;
      // Append tr to tbody
      tableBody.appendChild(tr_detail);
    });
  } else {
    const tr = document.createElement("tr");
    const td = document.createElement("td");
    td.colSpan = 4;
    td.className = "text-center text-muted";
    td.textContent = "No details found for this pembelian.";
    tr.appendChild(td);
    tableBody.appendChild(tr);
    document.getElementById("biaya_tambahan").classList =
      "table table-hover table-bordered table-sm table-striped no_print";
  }
}

async function populate_pembelian(pembelian_id) {
  const result = await apiRequest(
    `/PHP/API/pembelian_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}`,
    "POST",
    { pembelian_id: pembelian_id, table: "pembelian" }
  );

  result.data.forEach((detail) => {
    document.getElementById("nama_supplier").textContent =
      "Nama supplier : " + detail.supplier_nama;
    document.getElementById("status_pembelian").textContent =
      "Status : " + detail.status;

    document.getElementById("td_keterangan").textContent =
      "Keterangan : " + detail.keterangan;

    document.getElementById("td_sub_total").textContent = helper.format_angka(
      detail.sub_total
    );
    document.getElementById("td_diskon").textContent = helper.format_angka(
      detail.diskon
    );
    document.getElementById("td_ppn").textContent = helper.format_persen(
      detail.ppn
    );

    document.getElementById("td_pph").textContent = detail.nominal_pph
      ? helper.format_angka(detail.nominal_pph)
      : 0;
    document.getElementById("td_grand_total").textContent = helper.format_angka(
      detail.grand_total
    );

    document.getElementById("total_biaya_tambahan").textContent =
      helper.format_angka(detail.biaya_tambahan);

    document.getElementById("biaya_status_pembelian").textContent =
      "Status : " + detail.status;

    document.getElementById("biaya_nama_supplier").textContent =
      "Nama supplier : " + detail.supplier_nama;
  });
}
const pembelian_id = getQueryParam("pembelian_id");

if (pembelian_id) {
  console.log("Loaded pembelian_id:", pembelian_id);
  // Use pembelian_id to fetch and populate your table
}

populate_table_detail(pembelian_id);
populate_tanggal(pembelian_id);
populate_biaya_tambahan(pembelian_id);
populate_pembelian(pembelian_id);
