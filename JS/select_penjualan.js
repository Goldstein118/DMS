import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

let index = 0;

const update_detail_penjualan_tbody = document.getElementById(
  "update_detail_penjualan_tbody"
);

const update_detail_penjualan_button = document.getElementById(
  "update_detail_penjualan_button"
);
update_detail_penjualan_button.addEventListener("click", function () {
  add_field("update", "update_produk_id", "update_satuan_id");
});

const grid_container_penjualan = document.querySelector("#table_penjualan");
const pickdatejs_penjualan = $("#update_tanggal_penjualan")
  .pickadate({
    format: "dd mmm yyyy", // user sees: 01 Jan 2025
    formatSubmit: "yyyy-mm-dd", // hidden value: 01/01/2025
    selectYears: 25,
    selectMonths: true,
  })
  .pickadate("picker");

if (grid_container_penjualan) {
  function getStatusBadge(status) {
    switch (status) {
      case "lunas":
        return `<span class="badge text-bg-secondary">Lunas</span>`;
      case "belumlunas":
        return `<span class="badge text-bg-warning">Belum Lunas</span>`;
      default:
        return `<span class="badge text-bg-secondary">${status}</span>`;
    }
  }
  $(document).ready(function () {
    $("#update_customer_id").select2({
      allowClear: true,
      dropdownParent: $("#update_modal_penjualan"),
    });

    $("#update_gudang_id").select2({
      allowClear: true,
      dropdownParent: $("#update_modal_penjualan"),
    });
  });
  window.penjualan_grid = new Grid({
    columns: [
      "No Penjualan",
      "Tanggal penjualan",
      "Customer",
      "Promo",
      "Gudang",
      "Ket penjualan",
      "No kirim",
      "PPN",
      "Nominal PPN",
      "Nominal PPH",
      "Diskon",
      {
        name: "Status",
        formatter: (cell) => html(getStatusBadge(cell)),
      },
      {
        name: "Aksi",
        formatter: (cell, row) => {
          let edit;
          let can_delete;
          if (access.isOwner()) {
            edit = true;
          } else {
            edit = access.hasAccess("tb_penjualan", "edit");
          }
          if (access.isOwner()) {
            can_delete = true;
          } else {
            can_delete = access.hasAccess("tb_penjualan", "delete");
          }
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                id="update_penjualan_button"
                class="btn btn-warning update_penjualan btn-sm"
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
            button += `
        <button type="button" class="btn btn-danger delete_penjualan btn-sm">
         <i class="bi bi-x-circle"></i>
        </button>
        `;
          }

          button += `
        <button type="button" class="btn btn btn-info view_penjualan btn-sm" >
          <i class="bi bi-eye"></i>
        </button>
        `;

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
      }/PHP/API/penjualan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((penjualan) => [
          penjualan.penjualan_id,
          helper.format_date(penjualan.tanggal_penjualan),
          penjualan.customer_id,
          JSON.parse(penjualan.promo_id),
          penjualan.gudang_id,
          penjualan.keterangan_penjualan,
          penjualan.no_pengiriman,
          helper.format_persen(penjualan.ppn),
          helper.format_angka(penjualan.nominal_ppn),
          helper.format_angka(penjualan.nominal_pph),
          helper.format_angka(penjualan.diskon),
          penjualan.status,
          null,
        ]),
    },
  });
  window.penjualan_grid.render(document.getElementById("table_penjualan"));
  setTimeout(() => {
    helper.custom_grid_header(
      "penjualan",
      handle_delete,
      handle_update,
      handle_view
    );
  }, 200);
}

function handle_view(button) {
  const row = button.closest("tr");
  const penjualan_id = row.cells[0].textContent.trim();

  window.open(
    `../PHP/view_penjualan.php?penjualan_id=${encodeURIComponent(
      penjualan_id
    )}`,
    "_blank",
    "toolbar=0,location=0,menubar=0"
  );
}

// Attach delete listeners
async function handle_delete(button) {
  $("#modal_cancel").modal("show");
  const row = button.closest("tr");
  const penjualan_id = row.cells[0].textContent;

  const submit_cancel = document.getElementById("submit_cancel_button");
  if (submit_cancel) {
    submit_cancel.addEventListener("click", async function () {
      const keterangan_cancel =
        document.getElementById("keterangan_cancel").value;
      if (!keterangan_cancel || keterangan_cancel.trim() === "") {
        toastr.error("Keterangan Cancel harus diisi.");
        return;
      }
      const result = await Swal.fire({
        title: "Cancel Purchase Order?",
        text: "Setelah submit tidak bisa diganti lagi!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Iya, Cancel!",
        cancelButtonText: "Batalkan",
      });
      if (result.isConfirmed) {
        try {
          const response = await apiRequest(
            `/PHP/API/penjualan_API.php?action=delete&user_id=${access.decryptItem(
              "user_id"
            )}`,
            "DELETE",
            {
              penjualan_id: penjualan_id,
              keterangan_cancel: keterangan_cancel,
              status: "cancel",
              cancel_by: `${access.decryptItem("nama")}`,
            }
          );
          if (response.ok) {
            Swal.fire(
              "Berhasil",
              response.message || "Purchase Order dicancel.",
              "success"
            );

            $("#modal_cancel").modal("hide");

            window.penjualan_grid.forceRender();
            setTimeout(() => {
              helper.custom_grid_header(
                "penjualan",
                handle_delete,
                handle_update,
                handle_view
              );
            }, 200);
          } else {
            Swal.fire("Gagal", response.error || "Gagal.", "error");
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
}

function delete_detail_penjualan(action) {
  $(`#${action}_detail_penjualan_tbody`).on(
    "click",
    ".delete_detail_penjualan",
    async function () {
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
          Swal.fire("Berhasil", "penjualan dihapus.", "success");
        } catch (error) {
          Swal.fire({
            icon: "error",
            title: "Gagal",
            text: error.message,
          });
        }
      }
    }
  );
}
async function select_detail_penjualan(
  index,
  action,
  produk_element_id,
  satuan_element_id,

  current_produk_id,
  current_satuan_id
) {
  if (action == "create") {
    $(`#${produk_element_id}${index}`).select2({
      placeholder: "Pilih produk",
      allowClear: true,
      dropdownParent: $("#modal_penjualan"),
    });
    $(`#${satuan_element_id}${index}`).select2({
      placeholder: "Pilih satuan",
      allowClear: true,
      dropdownParent: $("#modal_penjualan"),
    });
  } else if (action == "update") {
    $(`#${produk_element_id}${index}`).select2({
      placeholder: "Pilih produk",
      allowClear: true,
      dropdownParent: $("#update_modal_penjualan"),
    });
    $(`#${satuan_element_id}${index}`).select2({
      placeholder: "Pilih satuan",
      allowClear: true,
      dropdownParent: $("#update_modal_penjualan"),
    });
  }

  delete_detail_penjualan(action);
  try {
    const response_produk = await apiRequest(
      `/PHP/API/produk_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_penjualan&context=create`
    );

    const response_satuan = await apiRequest(
      `/PHP/API/satuan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_penjualan&context=create`
    );

    const select_produk = $(`#${produk_element_id}${index}`);
    select_produk.empty();
    select_produk.append(new Option("Pilih Produk", "", false, false));

    const select_satuan = $(`#${satuan_element_id}${index}`);
    select_satuan.empty();
    select_satuan.append(new Option("Pilih Satuan", "", false, false));

    if (action == "create") {
      response_produk.data.forEach((produk) => {
        const option = new Option(
          `${produk.produk_id} - ${produk.nama}`,
          produk.produk_id,
          false,
          false
        );
        select_produk.append(option);
      });
      select_produk.trigger("change");

      response_satuan.data.forEach((satuan) => {
        const option = new Option(
          `${satuan.satuan_id} - ${satuan.nama}`,
          satuan.satuan_id,
          false,
          false
        );
        select_satuan.append(option);
      });
      select_satuan.trigger("change");
    } else if (action == "update") {
      response_produk.data.forEach((produk) => {
        const option = new Option(
          `${produk.produk_id} - ${produk.nama}`,
          produk.produk_id,
          false,
          produk.produk_id === current_produk_id
        );
        select_produk.append(option);
      });
      select_produk.val(current_produk_id).trigger("change");

      response_satuan.data.forEach((satuan) => {
        const option = new Option(
          `${satuan.satuan_id} - ${satuan.nama}`,
          satuan.satuan_id,
          false,
          satuan.satuan_id === current_satuan_id
        );
        select_satuan.append(option);
      });
      select_satuan.val(current_satuan_id).trigger("change");
    }
  } catch (error) {
    console.error("error:", error);
  }
}

function add_field(action, produk_element_id, satuan_element_id) {
  var myTable = document.getElementById(`${action}_detail_penjualan_tbody`);
  var currentIndex = index++;
  const tr_detail = document.createElement("tr");

  const td_produk = document.createElement("td");
  var produk_select = document.createElement("select");
  produk_select.setAttribute("id", produk_element_id + currentIndex);
  produk_select.classList.add("form-select");
  td_produk.appendChild(produk_select);

  const td_qty = document.createElement("td");
  var input_qty = document.createElement("input");
  input_qty.setAttribute("id", "qty" + currentIndex);
  input_qty.classList.add("form-control");
  td_qty.appendChild(input_qty);

  const td_satuan = document.createElement("td");
  var satuan_select = document.createElement("select");
  satuan_select.setAttribute("id", satuan_element_id + currentIndex);
  satuan_select.classList.add("form-select");
  td_satuan.appendChild(satuan_select);

  const td_harga = document.createElement("td");
  var input_harga = document.createElement("input");
  input_harga.setAttribute("id", "harga" + currentIndex);
  input_harga.classList.add("form-control");
  input_harga.style.textAlign = "right";
  td_harga.appendChild(input_harga);

  const td_diskon = document.createElement("td");
  var input_diskon = document.createElement("input");
  input_diskon.setAttribute("id", "diskon" + currentIndex);
  input_diskon.classList.add("form-control");
  input_diskon.style.textAlign = "right";
  td_diskon.appendChild(input_diskon);

  const td_aksi = document.createElement("td");
  td_aksi.setAttribute("id", "aksi_tbody");
  var delete_button = document.createElement("button");
  delete_button.type = "button";
  delete_button.className = "btn btn-danger btn-sm delete_detail_penjualan";
  delete_button.innerHTML = `<i class="bi bi-trash-fill"></i>`;
  td_aksi.appendChild(delete_button);
  td_aksi.style.textAlign = "center";

  tr_detail.appendChild(td_produk);
  tr_detail.appendChild(td_qty);
  tr_detail.appendChild(td_satuan);
  tr_detail.appendChild(td_harga);
  tr_detail.appendChild(td_diskon);
  tr_detail.appendChild(td_aksi);

  myTable.appendChild(tr_detail);

  helper.format_nominal("harga" + currentIndex);
  helper.format_nominal("diskon" + currentIndex);
  select_detail_penjualan(
    currentIndex,
    action,
    produk_element_id,
    satuan_element_id
  );
}

function populate_select(data, current_id, field) {
  const select = $(`#update_${field}_id`);
  select.empty();
  if (field === "customer") {
    select.append(new Option("Pilih Customer", "", false, false));
    data.forEach((item) => {
      const option = new Option(
        `${item.customer_id} - ${item.nama}`,
        item.customer_id,
        false,
        item.customer_id == current_id
      );
      select.append(option);
    });
  } else if (field === "gudang") {
    select.append(new Option("Pilih Gudang", "", false, false));
    data.forEach((item) => {
      const option = new Option(
        `${item.gudang_id} - ${item.nama}`,
        item.gudang_id,
        false,
        item.gudang_id == current_id
      );
      select.append(option);
    });
  }

  select.trigger("change");
}

async function handle_update(button) {
  index = 0;
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  const penjualan_id = row.cells[0].textContent;
  try {
    const response = await apiRequest(
      `/PHP/API/penjualan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      { penjualan_id: penjualan_id, table: "tb_penjualan" }
    );
    response.data.forEach((item) => {});
  } catch (error) {
    console.error("error:", error);
  }

  document.getElementById("update_penjualan_id").value = penjualan_id;
  document.getElementById("update_ppn").value = ppn;
  document.getElementById("update_nominal_pph").value =
    helper.unformat_angka(nominal_pph);
  document.getElementById("update_keterangan").value = keterangan;
  document.getElementById("update_diskon").value =
    helper.unformat_angka(diskon);
  document.getElementById("update_status_penjualan").value = status;

  try {
    const response = await apiRequest(
      `/PHP/API/supplier_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_penjualan&context=edit`
    );
    populate_select(response.data, supplier_id, "supplier");
  } catch (error) {
    console.error("error:", error);
  }
  try {
    const response = await apiRequest(
      `/PHP/API/gudang_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_penjualan&context=edit`
    );
    populate_select(response.data, gudang_id, "gudang");
  } catch (error) {
    console.error("error:", error);
  }
  try {
    update_detail_penjualan_tbody.innerHTML = "";
    const renponse_detail_penjualan = await apiRequest(
      `/PHP/API/penjualan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      { penjualan_id: penjualan_id, table: "detail_penjualan" }
    );

    renponse_detail_penjualan.data.forEach((detail, index) => {
      var currentIndex = index++;
      const tr_detail = document.createElement("tr");
      const current_produk_id = detail.produk_id;
      const current_satuan_id = detail.satuan_id;

      const td_produk = document.createElement("td");
      var produk_select = document.createElement("select");
      produk_select.setAttribute("id", "produk_element_id" + currentIndex);
      produk_select.classList.add("form-select");
      td_produk.appendChild(produk_select);

      const td_qty = document.createElement("td");
      var input_qty = document.createElement("input");
      input_qty.setAttribute("id", "qty" + currentIndex);
      input_qty.classList.add("form-control");
      input_qty.value = detail.qty;
      td_qty.appendChild(input_qty);

      const td_satuan = document.createElement("td");
      var satuan_select = document.createElement("select");
      satuan_select.setAttribute("id", "satuan_element_id" + currentIndex);
      satuan_select.classList.add("form-select");
      td_satuan.appendChild(satuan_select);

      const td_harga = document.createElement("td");
      var input_harga = document.createElement("input");
      input_harga.setAttribute("id", "harga" + currentIndex);
      input_harga.classList.add("form-control");
      input_harga.style.textAlign = "right";
      input_harga.value = detail.harga;
      td_harga.appendChild(input_harga);

      const td_diskon = document.createElement("td");
      var input_diskon = document.createElement("input");
      input_diskon.setAttribute("id", "diskon" + currentIndex);
      input_diskon.classList.add("form-control");
      input_diskon.style.textAlign = "right";
      input_diskon.value = detail.diskon;
      td_diskon.appendChild(input_diskon);

      const td_aksi = document.createElement("td");
      td_aksi.setAttribute("id", "aksi_tbody");
      var delete_button = document.createElement("button");
      delete_button.type = "button";
      delete_button.className = "btn btn-danger btn-sm delete_detail_penjualan";
      delete_button.innerHTML = `<i class="bi bi-trash-fill"></i>`;
      td_aksi.appendChild(delete_button);
      td_aksi.style.textAlign = "center";

      tr_detail.appendChild(td_produk);
      tr_detail.appendChild(td_qty);
      tr_detail.appendChild(td_satuan);
      tr_detail.appendChild(td_harga);
      tr_detail.appendChild(td_diskon);
      tr_detail.appendChild(td_aksi);

      update_detail_penjualan_tbody.appendChild(tr_detail);

      helper.format_nominal("harga" + currentIndex);
      helper.format_nominal("diskon" + currentIndex);
      select_detail_penjualan(
        currentIndex,
        "update",
        "produk_element_id",
        "satuan_element_id",
        current_produk_id,
        current_satuan_id
      );
    });
  } catch (error) {
    console.error("error:", error);
  }

  try {
    update_biaya_tambahan_tbody.innerHTML = "";
    const response_biaya_tambahan = await apiRequest(
      `/PHP/API/penjualan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      {
        penjualan_id: penjualan_id,
        table: "biaya_tambahan",
      }
    );
    response_biaya_tambahan.data.forEach((detail, index) => {
      var currentIndex = index++;
      const tr_detail = document.createElement("tr");
      const current_data_biaya_id = detail.data_biaya_id;

      const td_biaya = document.createElement("td");
      var biaya_select = document.createElement("select");
      biaya_select.setAttribute("id", "data_biaya_element_id" + currentIndex);
      biaya_select.classList.add("form-select");
      td_biaya.appendChild(biaya_select);

      const td_jumlah = document.createElement("td");
      var input_jumlah = document.createElement("input");
      input_jumlah.setAttribute("id", "jumlah" + currentIndex);
      input_jumlah.classList.add("form-control");
      input_jumlah.style.textAlign = "right";
      input_jumlah.value = helper.unformat_angka(detail.jlh);
      td_jumlah.appendChild(input_jumlah);

      const td_keterangan = document.createElement("td");
      var input_keterangan = document.createElement("input");
      input_keterangan.setAttribute("id", "keterangan" + currentIndex);
      input_keterangan.classList.add("form-control");
      input_keterangan.style.textAlign = "right";
      input_keterangan.value = detail.keterangan;
      td_keterangan.appendChild(input_keterangan);

      const td_aksi = document.createElement("td");
      td_aksi.setAttribute("id", "aksi_tbody");
      var delete_button = document.createElement("button");
      delete_button.type = "button";
      delete_button.className = "btn btn-danger btn-sm delete_biaya_tambahan";
      delete_button.innerHTML = `<i class="bi bi-trash-fill"></i>`;
      td_aksi.appendChild(delete_button);
      td_aksi.style.textAlign = "center";

      tr_detail.appendChild(td_biaya);
      tr_detail.appendChild(td_jumlah);
      tr_detail.appendChild(td_keterangan);
      tr_detail.appendChild(td_aksi);

      update_biaya_tambahan_tbody.appendChild(tr_detail);

      helper.format_nominal("jumlah" + currentIndex);
      select_data_biaya(
        currentIndex,
        "update",
        "data_biaya_element_id",
        current_data_biaya_id
      );
    });
  } catch (error) {
    console.error("error:", error);
  }
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#update_modal_penjualan").modal("show");
}

const submit_penjualan_update = document.getElementById(
  "update_submit_penjualan"
);

if (submit_penjualan_update) {
  submit_penjualan_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("No row selected for update.");
      return;
    }

    const row = window.currentRow;
    const picker_po = $("#update_tanggal_po").pickadate("picker");
    const tanggal_po = picker_po.get("select", "yyyy-mm-dd");
    const penjualan_id = document.getElementById("update_penjualan_id").value;
    const supplier_id = document.getElementById("update_supplier_id").value;
    const gudang_id = document.getElementById("update_gudang_id").value;
    const keterangan = document.getElementById("update_keterangan").value;
    let diskon = document.getElementById("update_diskon").value;
    const ppn = document.getElementById("update_ppn").value;
    let nominal_pph = document.getElementById("update_nominal_pph").value;

    const status_penjualan = document.getElementById(
      "update_status_penjualan"
    ).value;

    const details = [];
    const rows = document.querySelectorAll("#update_detail_penjualan_tbody tr");

    for (const row of rows) {
      const produk_select = row.querySelector("td:nth-child(1) select");
      const qty = row.querySelector("td:nth-child(2) input");
      const satuan = row.querySelector("td:nth-child(3) select");
      const harga = row.querySelector("td:nth-child(4) input");
      const diskon = row.querySelector("td:nth-child(5) input");

      const produk_id = produk_select?.value?.trim();
      const kuantitas = qty?.value?.trim();
      let harga_ = harga?.value?.trim();
      const satuan_id = satuan?.value?.trim();
      let discount = diskon?.value?.trim();

      if (
        !produk_id ||
        produk_id.trim() === "" ||
        !kuantitas ||
        kuantitas.trim() === "" ||
        !harga_ ||
        harga_.trim() === "" ||
        !satuan_id ||
        satuan_id.trim() === "" ||
        !discount ||
        discount.trim() === ""
      ) {
        toastr.error("Semua field pada detail penjualan wajib diisi.");
        return;
      }
      harga_ = helper.format_angka(harga_);
      discount = helper.format_angka(discount);
      details.push({
        produk_id: produk_id,
        qty: kuantitas,
        harga: harga_,
        satuan_id: satuan_id,
        diskon: discount,
      });
    }

    const biaya_tambahan = [];
    const rows_biaya_tambahan = document.querySelectorAll(
      "#update_biaya_tambahan_tbody tr"
    );

    for (const row of rows_biaya_tambahan) {
      const biaya_select = row.querySelector("td:nth-child(1) select");
      const jumlah_biaya = row.querySelector("td:nth-child(2) input");
      const keterangan_biaya = row.querySelector("td:nth-child(3) input");

      const data_biaya_id = biaya_select?.value?.trim();
      let jumlah = jumlah_biaya?.value?.trim();
      const keterangan = keterangan_biaya?.value?.trim();

      if (
        !data_biaya_id ||
        data_biaya_id.trim() === "" ||
        !jumlah ||
        jumlah.trim() === "" ||
        !keterangan ||
        keterangan.trim() === ""
      ) {
        toastr.error("Semua field pada biaya tambahan wajib diisi.");
        return;
      }
      jumlah = helper.format_angka(jumlah);
      biaya_tambahan.push({
        data_biaya_id: data_biaya_id,
        jumlah: jumlah,
        keterangan: keterangan,
      });
    }

    const data_penjualan = {
      user_id: `${access.decryptItem("user_id")}`,
      created_by: `${access.decryptItem("nama")}`,
      penjualan_id: penjualan_id,
      tanggal_po: tanggal_po,
      supplier_id: supplier_id,
      gudang_id: gudang_id,
      keterangan: keterangan,
      ppn: ppn,
      diskon: diskon,
      nominal_pph: nominal_pph,
      status: status_penjualan,
      details: details,
      biaya_tambahan: biaya_tambahan,
    };
    try {
      const response = await apiRequest(
        `/PHP/API/penjualan_API.php?action=update&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "POST",
        data_penjualan
      );

      if (response.ok) {
        $("#update_modal_penjualan").modal("hide");
        Swal.fire("Berhasil", response.message, "success");

        window.penjualan_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header(
            "penjualan",
            handle_delete,
            handle_update,
            handle_view
          );
        }, 200);
      } else {
        Swal.fire("Gagal", response.message || "Update gagal.", "error");
      }
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: error.message,
      });
    }
  });
}
