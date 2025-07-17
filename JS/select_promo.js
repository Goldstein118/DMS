import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const update_promo_kondisi_barang_button = document.getElementById(
  "update_promo_kondisi_barang_button"
);
const update_promo_bonus_barang_button = document.getElementById(
  "update_promo_bonus_barang_button"
);

update_promo_bonus_barang_button.addEventListener("click", add_field_barang);
update_promo_kondisi_barang_button.addEventListener("click", () => {
  add_field_kondisi(update_promo_kondisi_tbody);
});
const pickdate_tanggal_berlaku = $("#update_tanggal_berlaku")
  .pickadate({
    format: "dd mmm yyyy",
    formatSubmit: "yyyy-mm-dd",
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");
const pickdate_tanggal_selesai = $("#update_tanggal_selesai")
  .pickadate({
    format: "dd mmm yyyy",
    formatSubmit: "yyyy-mm-dd",
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");
const grid_container_promo = document.querySelector("#table_promo");
if (grid_container_promo) {
  window.promo_grid = new Grid({
    columns: [
      "Kode Promo",
      "Nama",
      "Tanggal Berlaku",
      "Tanggal Selesai",
      "Jenis Bonus",
      "Akumulasi",
      "Prioritas",
      "Dibuat Pada",
      "Jenis Diskon",
      "Jumlah Diskon",
      "Quota",
      "Status",
      {
        name: "Aksi",
        formatter: () => {
          const edit = access.hasAccess("tb_promo", "edit");
          const can_delete = access.hasAccess("tb_promo", "delete");
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                class="btn btn-warning update_promo btn-sm"
              >
                <span id="button_icon" class="button_icon">
                  <i class="bi bi-pencil-square"></i>
                </span>
                <span
                  id="spinner_update"
                  class="spinner-border spinner-border-sm spinner_update"
                  style="display: none;"
                  role="status"
                  aria-hidden="true"
                ></span>
              </button>`;
          }
          if (can_delete) {
            button += `<button
                type="button"
                class="btn btn-danger delete_promo btn-sm"
              >
                <i class="bi bi-trash-fill"></i>
              </button>`;
          }

          return html(button);
        },
      },
    ],
    search: {
      enabled: true,
      server: {
        url: (prev, keyword) => {
          if (keyword.length >= 3 && keyword !== "") {
            const separator = prev.includes("?") ? "&" : "?";
            return `${prev}${separator}search=${encodeURIComponent(keyword)}`;
          } else {
            return prev;
          }
        },
        method: "GET",
      },
    },
    sort: true,
    pagination: { limit: 15 },
    server: {
      url: `${
        config.API_BASE_URL
      }/PHP/API/promo_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((item) => [
          item.promo_id,
          item.nama,
          helper.format_date(item.tanggal_berlaku),
          helper.format_date(item.tanggal_selesai),
          item.jenis_bonus,
          item.akumulasi,
          item.prioritas,
          item.created_on,
          item.jenis_diskon,
          item.jumlah_diskon,
          item.quota,
          item.status,
          null,
        ]),
    },
  });

  window.promo_grid.render(document.getElementById("table_promo"));
  setTimeout(() => {
    helper.custom_grid_header("promo", handle_delete, handle_update);
  }, 200);
}

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
  update_promo_kondisi_tbody.appendChild(tr_detail);

  jenis_select.addEventListener("change", () => {
    if (jenis_select.value === "brand") {
      dynamic_select.className = "dynamic-select js-example-basic-multiple";
      dynamic_select.setAttribute("multiple", "multiple");
      dynamic_select.setAttribute("id", `jenis_brand${currentIndex}`);
      exclude_select.setAttribute("id", `exclude_include_brand${currentIndex}`);
      qty_min.removeAttribute("disabled");
      qty_max.removeAttribute("disabled");
      qty_akumulasi.removeAttribute("disabled");
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
      qty_akumulasi.setAttribute("disabled", "disabled");
      qty_max.setAttribute("disabled", "disabled");
      qty_min.setAttribute("disabled", "disabled");
      fetch_fk("produk", currentIndex, "jenis_produk");
    } else if (jenis_select.value === "channel") {
      dynamic_select.className = "dynamic-select js-example-basic-multiple";
      dynamic_select.setAttribute("multiple", "multiple");
      dynamic_select.setAttribute("id", `jenis_channel${currentIndex}`);
      exclude_select.setAttribute(
        "id",
        `exclude_include_channel${currentIndex}`
      );
      qty_min.removeAttribute("disabled");
      qty_max.removeAttribute("disabled");
      qty_akumulasi.removeAttribute("disabled");
      fetch_fk("channel", currentIndex, "jenis_channel");
    }
  });
  delete_promo_kondisi("update_jenis_promo_kondisi_tbody");
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
async function fetch_fk(field, index, element_id) {
  try {
    const response = await apiRequest(
      `/PHP/API/${field}_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_promo&context=create`,
      "POST",
      { select: "select" }
    );
    populateNewDropdown(response.data, field, index, element_id);
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }
}

function populateNewDropdown(data, field, index, element_id) {
  const select = $(`#${element_id}${index}`);
  console.log(`${element_id}${index}`);
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
  }
  select.select2({
    dropdownParent: $("#modal_promo_update"),
  });

  select.trigger("change");
}
async function handle_delete(button) {
  const row = button.closest("tr");
  const promo_id = row.cells[0].textContent;
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
      const response = await apiRequest(
        `/PHP/API/promo_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { promo_id: promo_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire("Berhasil", response.message || "Promo dihapus.", "success");
      } else {
        Swal.fire("Gagal", response.error || "Gagal menghapus promo.", "error");
      }
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: error.message,
      });
    }
  }
}

function populateDropdown(data, field, selectedRaw, element_id, index) {
  const select = $(`#${element_id}${index}`);
  select.empty();

  let selectedIds = [];
  try {
    if (selectedRaw) {
      selectedIds = Array.isArray(selectedRaw)
        ? selectedRaw
        : JSON.parse(selectedRaw);
    }
  } catch (e) {
    console.warn(`Failed to parse selectedRaw for ${field}:`, e);
  }

  data.forEach((item) => {
    const value = String(item[`${field}_id`]);
    const label = `${value} - ${item.nama}`;
    const isSelected = selectedIds.includes(value);
    select.append(new Option(label, value, isSelected, isSelected));
  });

  // ⚠️ Set values and trigger Select2 to render them
  select.val(selectedIds).trigger("change");
}

async function fetch_jenis(field, selectedRaw, element_id, index) {
  $(`#${element_id}${index}`).select2({
    dropdownParent: $("#modal_promo_update"),
  });
  delete_promo_kondisi("update_jenis_promo_kondisi_tbody");
  try {
    const response = await apiRequest(
      `/PHP/API/${field}_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_promo&context=edit`,
      "POST",
      { select: "select" }
    );

    const options = response.data;
    populateDropdown(options, field, selectedRaw, element_id, index);
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }
}
const update_table_bonus_barang_tbody = document.getElementById(
  "update_table_bonus_barang_tbody"
);
const update_promo_kondisi_tbody = document.getElementById(
  "update_jenis_promo_kondisi_tbody"
);
async function fetch_promo(promo_id) {
  try {
    const jenis = await apiRequest(
      `/PHP/API/promo_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      { promo_id: promo_id, table: "tb_promo_kondisi" }
    );
    jenis.data.forEach(async (item, currentIndex) => {
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
      jenis_select.value = item.jenis_kondisi;
      jenis_select.setAttribute("disabled", "disabled");
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
      exclude_select.value = item.exclude_include;

      td_exclude.appendChild(exclude_select);

      const qty_min_td = document.createElement("td");
      const qty_min = document.createElement("input");
      qty_min.className = "form-control";
      qty_min.setAttribute("id", `qty_min${currentIndex}`);
      qty_min.setAttribute("disabled", "disabled");
      qty_min.value = item.qty_min;
      qty_min_td.appendChild(qty_min);

      const qty_max_td = document.createElement("td");
      const qty_max = document.createElement("input");
      qty_max.className = "form-control";
      qty_max.setAttribute("id", `qty_max${currentIndex}`);
      qty_max.setAttribute("disabled", "disabled");
      qty_max.value = item.qty_max;
      qty_max_td.appendChild(qty_max);

      const qty_akumulasi_td = document.createElement("td");
      const qty_akumulasi = document.createElement("input");
      qty_akumulasi.className = "form-control";
      qty_akumulasi.setAttribute("id", `qty_akumulasi${currentIndex}`);
      qty_akumulasi.setAttribute("disabled", "disabled");
      qty_akumulasi.value = item.qty_akumulasi;
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
      update_promo_kondisi_tbody.appendChild(tr_detail);

      if (jenis_select.value === "brand") {
        dynamic_select.className = "dynamic-select js-example-basic-multiple";
        dynamic_select.setAttribute("multiple", "multiple");
        dynamic_select.setAttribute("id", `jenis_brand${currentIndex}`);
        exclude_select.setAttribute(
          "id",
          `exclude_include_brand${currentIndex}`
        );
        qty_min.removeAttribute("disabled");
        qty_max.removeAttribute("disabled");
        qty_akumulasi.removeAttribute("disabled");
        fetch_jenis("brand", item.kondisi, "jenis_brand", currentIndex); // ← Now that the element exists, fetch the data
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

        fetch_jenis("customer", item.kondisi, "jenis_customer", currentIndex);
      } else if (jenis_select.value === "produk") {
        dynamic_select.className = "dynamic-select js-example-basic-multiple";
        dynamic_select.setAttribute("multiple", "multiple");
        dynamic_select.setAttribute("id", `jenis_produk${currentIndex}`);
        exclude_select.setAttribute(
          "id",
          `exclude_include_produk${currentIndex}`
        );
        qty_akumulasi.setAttribute("disabled", "disabled");
        qty_max.setAttribute("disabled", "disabled");
        qty_min.setAttribute("disabled", "disabled");
        fetch_jenis("produk", item.kondisi, "jenis_produk", currentIndex);
      } else if (jenis_select.value === "channel") {
        dynamic_select.className = "dynamic-select js-example-basic-multiple";
        dynamic_select.setAttribute("multiple", "multiple");
        dynamic_select.setAttribute("id", `jenis_channel${currentIndex}`);
        exclude_select.setAttribute(
          "id",
          `exclude_include_channel${currentIndex}`
        );
        qty_min.removeAttribute("disabled");
        qty_max.removeAttribute("disabled");
        qty_akumulasi.removeAttribute("disabled");
        fetch_jenis("channel", item.kondisi, "jenis_channel", currentIndex);
      }
    });
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }
}
let index = 0;
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
  update_table_bonus_barang_tbody.appendChild(tr_bonus_barang);

  await fetch_fk("produk", currentIndex, "bonus_produk");

  delete_promo_kondisi("table_bonus_barang_tbody");
}
async function handle_update(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  const promo_id = row.cells[0].textContent;
  const current_nama = row.cells[1].textContent;
  let current_tanggal_berlaku = row.cells[2].textContent;
  let current_tanggal_selesai = row.cells[3].textContent;
  const jenis_bonus = row.cells[4].textContent;
  const akumulasi = row.cells[5].textContent;
  const prioritas = row.cells[6].textContent;
  const jenis_diskon = row.cells[8].textContent;
  const jumlah_diskon = row.cells[9].textContent;
  const quota = row.cells[10].textContent;
  const status = row.cells[11].textContent;

  current_tanggal_berlaku = helper.unformat_date(current_tanggal_berlaku);
  const parts_tanggal_berlaku = current_tanggal_berlaku.split("-"); // ["2025", "05", "02"]
  const tanggal_berlaku = new Date(
    parts_tanggal_berlaku[0],
    parts_tanggal_berlaku[1] - 1,
    parts_tanggal_berlaku[2]
  );
  pickdate_tanggal_berlaku.set("select", tanggal_berlaku);

  current_tanggal_selesai = helper.unformat_date(current_tanggal_selesai);
  const parts_tanggal_selesai = current_tanggal_selesai.split("-"); // ["2025", "05", "02"]
  const tanggal_selesai = new Date(
    parts_tanggal_selesai[0],
    parts_tanggal_selesai[1] - 1,
    parts_tanggal_selesai[2]
  );
  pickdate_tanggal_selesai.set("select", tanggal_selesai);

  document.getElementById("update_promo_id").value = promo_id;
  document.getElementById("update_nama_promo").value = current_nama;
  document.getElementById("update_tanggal_berlaku").value =
    current_tanggal_berlaku;

  document.getElementById("update_tanggal_selesai").value =
    current_tanggal_selesai;
  document.getElementById("update_jenis_bonus").value = jenis_bonus;
  document.getElementById("update_akumulasi").value = akumulasi;

  document.getElementById("update_prioritas").value = prioritas;
  document.getElementById("update_jenis_diskon").value = jenis_diskon;
  document.getElementById("update_jumlah_diskon").value = jumlah_diskon;
  document.getElementById("update_quota").value = quota;
  document.getElementById("update_status_promo").value = status;

  await fetch_promo(promo_id);

  let jenis_bonus_value = document.getElementById("update_jenis_bonus");

  jenis_bonus_value.addEventListener("change", (event) => {
    let bonus = jenis_bonus_value.options[jenis_bonus_value.selectedIndex].text;
    if (bonus === "Barang") {
      document.getElementById("update_card_promo_3").style.display = "block";
    } else {
      document.getElementById("update_card_promo_3").style.display = "none";
    }
  });

  try {
    const promo_barang = await apiRequest(
      `/PHP/API/promo_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      { promo_id: promo_id, table: "tb_promo_bonus_barang" }
    );
    promo_barang.data.forEach((item) => {
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
      jlh_qty.setAttribute("id", `jlh_qty${currentIndex}`);
      jlh_qty.value = item.qty_bonus;

      td_jlh_qty.appendChild(jlh_qty);

      const td_jenis_diskon = document.createElement("td");
      const jenis_diskon = document.createElement("select");
      jenis_diskon.innerHTML = `
    <option value="nominal">Nominal</option>
    <option value="persen">Persen</option>
  
  `;
      jenis_diskon.className = "form-select";
      jenis_diskon.setAttribute("id", `jenis_diskon${currentIndex}`);
      jenis_diskon.value = item.jenis_diskon;

      td_jenis_diskon.appendChild(jenis_diskon);

      const td_jumlah_diskon_nominal = document.createElement("td");
      const jumlah_diskon_nominal = document.createElement("input");
      jumlah_diskon_nominal.className = "form-control";
      jumlah_diskon_nominal.setAttribute(
        "id",
        `jumlah_diskon_nominal${currentIndex}`
      );
      jumlah_diskon_nominal.setAttribute("type", "number");
      jumlah_diskon_nominal.value = item.jlh_diskon;
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
      update_table_bonus_barang_tbody.appendChild(tr_bonus_barang);

      fetch_fk("produk", currentIndex, "bonus_produk");

      delete_promo_kondisi("update_table_bonus_barang_tbody");
    });
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }

  await new Promise((resolve) => setTimeout(resolve, 500));
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#modal_promo_update").modal("show");
}

const submit_promo_update = document.getElementById("submit_promo_update");
if (submit_promo_update) {
  submit_promo_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("no row selected for update.");
      return;
    }
    const row = window.currentRow;
    const promo_id = document.getElementById("update_promo_id").value;
    const nama_new = document.getElementById("update_nama_promo").value;
    const tanggal_berlaku = document.getElementById(
      "update_tanggal_berlaku"
    ).value;
    const tanggal_selesai = document.getElementById(
      "update_tanggal_selesai"
    ).value;
    const jenis_bonus = document.getElementById("update_jenis_bonus").value;
    const akumulasi = document.getElementById("update_akumulasi").value;
    const prioritas = document.getElementById("update_prioritas").value;
    const jenis_diskon = document.getElementById("update_jenis_diskon").value;
    const jumlah_diskon = document.getElementById("update_jumlah_diskon").value;

    const status = document.getElementById("update_status_promo").value;
    const quota = document.getElementById("update_quota").value;
    const qty_bonus = document.getElementById("update_qty_bonus").value;
    const jlh_diskon_bonus = document.getElementById(
      "update_diskon_bonus_barang"
    ).value;
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

    if (
      helper.validateField(
        nama_new,
        /^[a-zA-Z0-9\s]+$/,
        "Format nama tidak valid"
      ) &&
      compare_tanggal
    ) {
      try {
        const data_promo_update = {
          promo_id: promo_id,
          nama: nama_new,
          tanggal_berlaku: tanggal_berlaku,
          tanggal_selesai: tanggal_selesai,
          jenis_bonus: jenis_bonus,
          akumulasi: akumulasi,
          prioritas: prioritas,
          jenis_diskon: jenis_diskon,
          jumlah_diskon: jumlah_diskon,
          status: status,
          quota: quota,
          qty_bonus: qty_bonus,
          diskon_bonus_barang: jlh_diskon_bonus,
        };

        const response = await apiRequest(
          `/PHP/API/promo_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_promo_update
        );
        if (response.ok) {
          $("#modal_promo_update").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.promo_grid.forceRender();
          setTimeout(() => {
            helper.custom_grid_header("promo", handle_delete, handle_update);
          }, 200);
        }
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
