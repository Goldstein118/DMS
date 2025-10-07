import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
async function populate_table(produk_id) {
  const result = await apiRequest(
    `/PHP/API/pricelist_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}&target=tb_produk&context=edit`,
    "POST",
    { produk_id }
  );
  const tableBody = document.getElementById("view_detail_produk_tbody");

  tableBody.innerHTML = "";
  let nomor = 1;
  if (result) {
    result.data.forEach((detail) => {
      const tr_detail = document.createElement("tr");

      const td_no = document.createElement("td");
      td_no.textContent = nomor;
      td_no.style.textAlign = "center";

      const tdKode = document.createElement("td");
      tdKode.textContent = detail.pricelist_id;

      const tdPriceNama = document.createElement("td");
      tdPriceNama.textContent = detail.nama;

      const tdHarga = document.createElement("td");
      tdHarga.setAttribute("id", "view_harga");
      tdHarga.textContent = helper.format_angka(detail.harga);
      tdHarga.style.textAlign = "right";

      // Append all tds to tr
      tr_detail.appendChild(td_no);
      tr_detail.appendChild(tdKode);
      tr_detail.appendChild(tdPriceNama);
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
async function data_produk(produk_id) {
  const result = await apiRequest(
    `/PHP/API/produk_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}`,
    "POST",
    { produk_id }
  );

  const div_gambar = document.getElementById("gambar_produk");
  div_gambar.innerHTML = "";

  if (result) {
    result.data.forEach((item) => {
      const produk_id = document.getElementById("view_produk_id");
      produk_id.textContent = item.produk_id;

      const img_produk = document.createElement("img");
      img_produk.setAttribute("id", "img_produk");
      item.produk_link
        ? (img_produk.src = item.produk_link)
        : img_produk.setAttribute("alt", " tidak ada file");
      img_produk.style.maxWidth = "200px";
      img_produk.style.maxHeight = "250px";
      img_produk.className = "img-fluid  mx-auto";
      div_gambar.appendChild(img_produk);

      const nama = document.getElementById("nama_produk");
      item.nama
        ? (nama.textContent = item.nama)
        : (document.getElementById("tr_produk").style.display = "none");

      const brand_nama = document.getElementById("nama_brand");
      item.brand_nama
        ? (brand_nama.textContent = item.brand_nama)
        : (document.getElementById("tr_brand").style.display = "none");

      const kategori_nama = document.getElementById("nama_kategori");
      item.kategori_nama
        ? (kategori_nama.textContent = item.kategori_nama)
        : (document.getElementById("tr_kategori").style.display = "none");

      const no_sku = document.getElementById("no_sku");
      item.no_sku
        ? (no_sku.textContent = item.no_sku)
        : (document.getElementById("tr_no_sku").style.display = "none");

      const harga_minimal = document.getElementById("harga_minimal");
      // harga_minimal.style.textAlign = "right";
      item.harga_minimal
        ? (harga_minimal.textContent = helper.format_angka(item.harga_minimal))
        : (document.getElementById("tr_harga_minimal").style.display = "none");

      const status = document.getElementById("status");
      item.status
        ? (status.textContent = item.status)
        : (document.getElementById("tr_status").style.display = "none");
    });
  }
}
populate_table(produk_id);
data_produk(produk_id);
