import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";

async function populate_table(produk_id) {
  const result = await apiRequest(
    `/PHP/API/pricelist_API.php?action=select&user_id=${access.decryptItem(
      "user_id&target=tb_produk&context=edit"
    )}`,
    "POST",
    { produk_id }
  );
  const tableBody = document.getElementById("view_detail_produk_tbody");

  tableBody.innerHTML = "";

  if (result) {
    result.data.forEach((detail) => {
      const tr_detail = document.createElement("tr");

      const td_kode_produk = document.getElementById("view_produk_id");
      td_kode_produk.textContent = detail.produk_id;

      //   const td_status = document.getElementById("view_status");
      //   td_status.textContent = detail.status;

      const td_tanggal_berlaku = document.getElementById(
        "view_tanggal_berlaku"
      );
      td_tanggal_berlaku.textContent = detail.tanggal_berlaku;

      //   const td_harga_default = document.getElementById("view_harga_default");
      //   td_harga_default.textContent = detail.harga_default;

      const tdKode = document.createElement("td");
      tdKode.textContent = detail.detail_produk_id;

      const tdPriceNama = document.createElement("td");
      tdPriceNama.textContent = detail.price_nama;

      const tdProduk = document.createElement("td");
      tdProduk.textContent = detail.produk_nama;

      const tdHarga = document.createElement("td");
      tdHarga.setAttribute("id", "view_harga");
      tdHarga.textContent = detail.harga;

      // Append all tds to tr
      tr_detail.appendChild(tdKode);
      tr_detail.appendChild(tdPriceNama);
      tr_detail.appendChild(tdProduk);
      tr_detail.appendChild(tdHarga);

      // Append tr to tbody
      tableBody.appendChild(tr_detail);
    });
  } else {
    // Optional: show message if no data found
    const tr = document.createElement("tr");
    const td = document.createElement("td");
    td.colSpan = 4;
    td.className = "text-center text-muted";
    td.textContent = "No details found for this produk.";
    tr.appendChild(td);
    tableBody.appendChild(tr);
  }
}
function getQueryParam(key) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(key);
}

const produk_id = getQueryParam("produk_id");

if (produk_id) {
  console.log("Loaded produk_id:", produk_id);
  // Use produk_id to fetch and populate your table
}

populate_table(produk_id);
