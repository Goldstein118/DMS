import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

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
          null,
        ]),
    },
  });

  window.promo_grid.render(document.getElementById("table_promo"));
  setTimeout(() => {
    helper.custom_grid_header("promo", handle_delete, handle_update);

    $(document).ready(function () {
      $("#update_jenis_brand").select2({
        dropdownParent: $("#modal_promo_update"),
      });
      $("#update_jenis_customer").select2({
        dropdownParent: $("#modal_promo_update"),
      });
      $("#update_jenis_produk").select2({
        dropdownParent: $("#modal_promo_update"),
      });
      $("#update_tanggal_berlaku").pickadate({
        format: "dd mmm yyyy",
        formatSubmit: "yyyy-mm-dd",
        selectYears: 25,
        selectMonths: true,
      });
      $("#update_tanggal_selesai").pickadate({
        format: "dd mmm yyyy",
        formatSubmit: "yyyy-mm-dd",
        selectYears: 25,
        selectMonths: true,
      });
    });
  }, 200);
}
function populateDropdown(data, field, selectedRaw) {
  const select = $(`#update_jenis_${field}`);
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

async function fetch_jenis(field, selectedRaw) {
  try {
    const response = await apiRequest(
      `/PHP/API/${field}_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_promo&context=edit`,
      "POST",
      { select: "select" }
    );

    const options = response.data;
    populateDropdown(options, field, selectedRaw);
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }
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
async function handle_update(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  const promo_id = row.cells[0].textContent;
  const current_nama = row.cells[1].textContent;
  const current_tanggal_berlaku = row.cells[2].textContent;
  const current_tanggal_selesai = row.cells[3].textContent;
  const jenis_bonus = row.cells[4].textContent;
  const akumulasi = row.cells[5].textContent;
  const prioritas = row.cells[6].textContent;
  const jenis_diskon = row.cells[8].textContent;
  const jumlah_diskon = row.cells[9].textContent;

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

  try {
    const jenis = await apiRequest(
      `/PHP/API/promo_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      "POST",
      { promo_id: promo_id, table: "tb_promo_kondisi" }
    );
    jenis.data.forEach(async (item) => {
      await fetch_jenis("brand", item.jenis_brand);
      await fetch_jenis("customer", item.jenis_customer);
      await fetch_jenis("produk", item.jenis_produk);
      document.getElementById("update_status_promo").value = item.status;
      document.getElementById("update_qty_akumulasi").value =
        item.qty_akumulasi;
      document.getElementById("update_qty_min").value = item.qty_min;
      document.getElementById("update_qty_max").value = item.qty_max;
      document.getElementById("update_quota").value = item.quota;
    });
    // Pass each selected array as third param!
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }
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
      document.getElementById("update_qty_bonus").value = item.qty_bonus
        ? item.qty_bonus
        : "";
      document.getElementById("update_diskon_bonus_barang").value =
        item.jlh_diskon ? item.jlh_diskon : "";
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
    let brand_val = [];
    let customer_val = [];
    let produk_val = [];
    $("#update_jenis_brand")
      .select2("data")
      .forEach(function (item) {
        brand_val.push(item.id);
      });

    $("#update_jenis_customer")
      .select2("data")
      .forEach(function (item) {
        customer_val.push(item.id);
      });

    $("#update_jenis_produk")
      .select2("data")
      .forEach(function (item) {
        produk_val.push(item.id);
      });

    const status = document.getElementById("update_status_promo").value;
    const qty_akumulasi = document.getElementById("update_qty_akumulasi").value;
    const qty_min = document.getElementById("update_qty_min").value;
    const qty_max = document.getElementById("update_qty_max").value;
    const quota = document.getElementById("update_quota").value;
    const qty_bonus = document.getElementById("update_qty_bonus").value;
    const jlh_diskon_bonus = document.getElementById(
      "update_diskon_bonus_barang"
    ).value;

    if (
      helper.validateField(
        nama_new,
        /^[a-zA-Z0-9\s]+$/,
        "Format nama tidak valid"
      )
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
          jenis_brand: brand_val,
          jenis_customer: customer_val,
          jenis_produk: produk_val,
          status: status,
          qty_akumulasi: qty_akumulasi,
          qty_min: qty_min,
          qty_max: qty_max,
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
