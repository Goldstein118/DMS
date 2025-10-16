import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
async function populate_table_detail(penjualan_id) {
  const result = await apiRequest(
    `/PHP/API/penjualan_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}`,
    "POST",
    { penjualan_id: penjualan_id, table: "view_detail_penjualan" }
  );
  const tableBody = document.getElementById("view_detail_penjualan_tbody");

  tableBody.innerHTML = "";
  let nomor = 1;
  if (result.data.length != 0) {
    result.data.forEach((detail) => {
      const tr_detail = document.createElement("tr");

      const td_no = document.createElement("td");
      td_no.textContent = nomor;
      td_no.style.textAlign = "center";

      const tdProduk = document.createElement("td");
      tdProduk.textContent = detail.nama_produk;

      const tdQty = document.createElement("td");
      tdQty.textContent = detail.qty;

      const tdSatuan = document.createElement("td");
      tdSatuan.textContent = detail.nama_satuan;

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
    td.textContent = "No details found for this penjualan.";
    tr.appendChild(td);
    tableBody.appendChild(tr);
    document.getElementById("detail_penjualan").classList =
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

async function populate_penjualan(penjualan_id) {
  const result = await apiRequest(
    `/PHP/API/penjualan_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}`,
    "POST",
    { penjualan_id: penjualan_id, table: "view_penjualan" }
  );

  result.data.forEach((detail) => {
    document.getElementById("nama_customer").textContent =
      "Nama Customer : " + detail.nama_customer;
    document.getElementById("view_tanggal_penjualan").textContent =
      "Tanggal Penjualan : " + detail.tanggal_penjualan;

    document.getElementById("td_keterangan").textContent =
      "Keterangan : " + detail.keterangan_penjualan;

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
  });
}
const penjualan_id = getQueryParam("penjualan_id");

if (penjualan_id) {
  console.log("Loaded penjualan_id:", penjualan_id);
  // Use penjualan_id to fetch and populate your table
}

populate_table_detail(penjualan_id);
populate_penjualan(penjualan_id);
