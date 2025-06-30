import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
async function populate_table(pricelist_id) {
  const result = await apiRequest(
    `/PHP/API/pricelist_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}`,
    "POST",
    { pricelist_id }
  );
  const tableBody = document.getElementById("view_detail_pricelist_tbody");

  tableBody.innerHTML = "";
  let nomor = 1;
  if (result) {
    result.data.forEach((detail) => {
      const tr_detail = document.createElement("tr");

      const td_no = document.createElement("td");
      td_no.textContent = nomor;
      td_no.style.textAlign = "center";

      const td_kode_pricelist = document.getElementById("view_pricelist_id");
      td_kode_pricelist.textContent = detail.pricelist_id;

      //   const td_status = document.getElementById("view_status");
      //   td_status.textContent = detail.status;

      const td_tanggal_berlaku = document.getElementById(
        "view_tanggal_berlaku"
      );
      td_tanggal_berlaku.textContent = helper.format_date(
        detail.tanggal_berlaku
      );

      //   const td_harga_default = document.getElementById("view_harga_default");
      //   td_harga_default.textContent = detail.harga_default;

      const tdKode = document.createElement("td");
      tdKode.textContent = detail.produk_id;

      const tdPriceNama = document.getElementById("nama_pricelist");
      tdPriceNama.textContent = detail.price_nama;

      const tdProduk = document.createElement("td");
      tdProduk.textContent = detail.produk_nama;

      const tdHarga = document.createElement("td");
      tdHarga.setAttribute("id", "view_harga");
      tdHarga.textContent = detail.harga;
      tdHarga.style.textAlign = "right";

      // Append all tds to tr
      tr_detail.appendChild(td_no);
      tr_detail.appendChild(tdKode);
      tr_detail.appendChild(tdProduk);
      tr_detail.appendChild(tdHarga);
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
    td.textContent = "No details found for this pricelist.";
    tr.appendChild(td);
    tableBody.appendChild(tr);
  }
}
function getQueryParam(key) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(key);
}

const pricelist_id = getQueryParam("pricelist_id");

if (pricelist_id) {
  console.log("Loaded pricelist_id:", pricelist_id);
  // Use pricelist_id to fetch and populate your table
}

populate_table(pricelist_id);
