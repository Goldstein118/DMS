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

      const tdTotal = document.createElement("td");
      tdTotal.setAttribute("id", "view_total");
      tdTotal.textContent = detail.qty * (detail.harga - detail.diskon);
      tdTotal.style.textAlign = "right";
      // Append all tds to tr
      tr_detail.appendChild(td_no);
      tr_detail.appendChild(tdProduk);
      tr_detail.appendChild(tdQty);
      tr_detail.appendChild(tdSatuan);
      tr_detail.appendChild(tdHarga);
      tr_detail.appendChild(tdDiskon);
      tr_detail.appendChild(tdTotal);

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

async function populate_table_gudang_pengiriman(penjualan_id, tbody) {
  const result = await apiRequest(
    `/PHP/API/penjualan_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}`,
    "POST",
    { penjualan_id: penjualan_id, table: "view_detail_penjualan" }
  );
  const tableBody = document.getElementById(`${tbody}`);

  tableBody.innerHTML = "";
  let nomor = 1;
  let total_qty = 0;
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
      tdQty.style.textAlign = "right";

      total_qty += detail.qty;

      // Append all tds to tr
      tr_detail.appendChild(td_no);
      tr_detail.appendChild(tdProduk);
      tr_detail.appendChild(tdQty);

      nomor += 1;
      // Append tr to tbody
      tableBody.appendChild(tr_detail);
    });
    document.querySelectorAll(".total_qty").forEach((el) => {
      el.textContent = total_qty;
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
  // document.getElementById("div_biaya_tambahan_header").style.display = "block";
  // window.onafterprint = () => {
  //   document.getElementById("div_biaya_tambahan_header").style.display = "none";
  // };

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
    document.querySelectorAll(".view_penjualan_id").forEach((el) => {
      el.textContent = detail.penjualan_id;
    });

    document.querySelectorAll(".nama_customer").forEach((el) => {
      el.textContent = "Nama Customer : " + detail.nama_customer;
    });

    document.querySelectorAll(".view_tanggal_penjualan").forEach((el) => {
      el.textContent =
        "Tanggal Penjualan : " + helper.format_date(detail.tanggal_penjualan);
    });

    document.getElementById("td_keterangan").textContent =
      "Keterangan : " + detail.keterangan_penjualan;

    document.getElementById("td_sub_total").textContent = helper.format_angka(
      detail.sub_total
    );
    document.getElementById("td_diskon").textContent = helper.format_angka(
      detail.diskon
    );

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
populate_table_gudang_pengiriman(penjualan_id, "pengiriman_tbody");
populate_table_gudang_pengiriman(penjualan_id, "gudang_tbody");

const jenis_keterangan = document.getElementById("view_jenis_keterangan");
jenis_keterangan.addEventListener("change", () => {
  if (jenis_keterangan.value === "invoice") {
    document.getElementById("invoice").style.display = "block";
    document.getElementById("pengiriman").style.display = "none";
    document.getElementById("gudang").style.display = "none";

    document.getElementById("invoice_header").style.display = "block";
    document.getElementById("pengiriman_header").style.display = "none";
    document.getElementById("gudang_header").style.display = "none";
  } else if (jenis_keterangan.value === "pengiriman") {
    document.getElementById("invoice").style.display = "none";
    document.getElementById("pengiriman").style.display = "block";
    document.getElementById("gudang").style.display = "none";

    document.getElementById("invoice_header").style.display = "none";
    document.getElementById("pengiriman_header").style.display = "block";
    document.getElementById("gudang_header").style.display = "none";
  } else if (jenis_keterangan.value === "gudang") {
    document.getElementById("invoice").style.display = "none";
    document.getElementById("pengiriman").style.display = "none";
    document.getElementById("gudang").style.display = "block";

    document.getElementById("invoice_header").style.display = "none";
    document.getElementById("pengiriman_header").style.display = "none";
    document.getElementById("gudang_header").style.display = "block";
  }
});
if (jenis_keterangan.value === "invoice") {
  document.getElementById("invoice").style.display = "block";
  document.getElementById("pengiriman").style.display = "none";
  document.getElementById("gudang").style.display = "none";

  document.getElementById("invoice_header").style.display = "block";
  document.getElementById("pengiriman_header").style.display = "none";
  document.getElementById("gudang_header").style.display = "none";
} else if (jenis_keterangan.value === "pengiriman") {
  document.getElementById("invoice").style.display = "none";
  document.getElementById("pengiriman").style.display = "block";
  document.getElementById("gudang").style.display = "none";

  document.getElementById("invoice_header").style.display = "none";
  document.getElementById("pengiriman_header").style.display = "block";
  document.getElementById("gudang_header").style.display = "none";
} else if (jenis_keterangan.value === "gudang") {
  document.getElementById("invoice").style.display = "none";
  document.getElementById("pengiriman").style.display = "none";
  document.getElementById("gudang").style.display = "block";

  document.getElementById("invoice_header").style.display = "none";
  document.getElementById("pengiriman_header").style.display = "none";
  document.getElementById("gudang_header").style.display = "block";
}
