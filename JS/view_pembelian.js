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
  if (result) {
    result.data.forEach((detail) => {
      const tr_detail = document.createElement("tr");

      const td_no = document.createElement("td");
      td_no.textContent = nomor;
      td_no.style.textAlign = "center";

      const td_kode_pembelian = document.getElementById("view_pembelian_id");
      td_kode_pembelian.textContent = detail.pembelian_id;

      //   const td_status = document.getElementById("view_status");
      //   td_status.textContent = detail.status;

      //   const td_harga_default = document.getElementById("view_harga_default");
      //   td_harga_default.textContent = detail.harga_default;

      const tdProduk = document.createElement("td");
      tdProduk.textContent = detail.produk_nama;

      const tdQty = document.createElement("td");
      tdQty.textContent = detail.qty;

      const tdSatuan = document.getElementById("nama_pembelian");
      tdSatuan.textContent = detail.satuan_nama;

      const tdHarga = document.createElement("td");
      tdHarga.setAttribute("id", "view_harga");
      tdHarga.textContent = detail.harga;
      tdHarga.style.textAlign = "right";

      const tdDiskon = document.createElement("td");
      tdDiskon.setAttribute("id", "view_diskon");
      tdDiskon.textContent = detail.diskon;
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
  }
}
function getQueryParam(key) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(key);
}

async function populate_tanggal(pembelian_id) {
  const result = await apiRequest(
    `/PHP/API/pembelian_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}`,
    "POST",
    { pembelian_id: pembelian_id }
  );
  result.data.forEach((detail) => {
    const td_tanggal_berlaku = document.getElementById("view_tanggal_po");
    td_tanggal_berlaku.textContent =
      "Tanggal Po: " + helper.format_date(detail.tanggal_po);
  });
}

const pembelian_id = getQueryParam("pembelian_id");

if (pembelian_id) {
  console.log("Loaded pembelian_id:", pembelian_id);
  // Use pembelian_id to fetch and populate your table
}

populate_table_detail(pembelian_id);
populate_tanggal(pembelian_id);
