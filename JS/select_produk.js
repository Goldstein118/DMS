import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const grid_container_produk = document.querySelector("#table_produk");
if (grid_container_produk) {
  $(document).ready(function () {
    $("#update_kategori").select2({
      allowClear: true,
      dropdownParent: $("#update_modal_produk"),
    });
    $("#update_brand").select2({
      allowClear: true,
      dropdownParent: $("#update_modal_produk"),
    });
  });
  window.produk_grid = new Grid({
    columns: [
      "Kode produk",
      "Nama",
      "Kategori",
      "Brand",
      "No Sku",
      "Status",
      "Harga Minimal",
      "kategori_id",
      "brand_id",
      "link_gambar",
      "Stock Awal",
      {
        name: "Aksi",
        formatter: () => {
          let edit;
          let can_delete;
          if (access.isOwner()) {
            edit = true;
          } else {
            edit = access.hasAccess("tb_produk", "edit");
          }
          if (access.isOwner()) {
            can_delete = true;
          } else {
            can_delete = access.hasAccess("tb_produk", "delete");
          }
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                id="update_produk_button"
                class="btn btn-warning update_produk btn-sm"
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
        <button type="button" class="btn btn-danger delete_produk btn-sm">
          <i class="bi bi-trash-fill"></i>
        </button>
        `;
          }
          button += `<button type="button" class="btn btn btn-info view_produk btn-sm" >
          <i class="bi bi-eye"></i>
        </button>`;
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
      }/PHP/API/produk_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((produk) => [
          produk.produk_id,
          produk.nama,
          produk.kategori_nama,
          produk.brand_nama,
          produk.no_sku,

          html(`
          ${
            produk.status === "aktif"
              ? `<span class="badge text-bg-success">Aktif</span>`
              : `<span class="badge text-bg-danger">Non Aktif</span>`
          }
          `),
          produk.harga_minimal,
          produk.kategori_id,
          produk.brand_id,
          html(`
             ${
               produk.produk_link
                 ? `<a class = "link-dark d-inline-flex text-decoration-none rounded" href="${produk.produk_link}" target="_blank" >
                    <i class="bi bi-person-vcard-fill"></i></a>`
                 : ``
             }`),
          produk.stock_awal,
          null,
        ]),
    },
  });
  window.produk_grid.render(document.getElementById("table_produk"));
  setTimeout(() => {
    helper.custom_grid_header(
      "produk",
      handle_delete,
      handle_update,
      handle_view
    );
  }, 200);
}
function handle_view(button) {
  const row = button.closest("tr");
  const produk_id = row.cells[0].textContent.trim();
  window.open(
    `../PHP/view_produk.php?produk_id=${encodeURIComponent(produk_id)}`,
    "_blank"
  );
}
async function handle_delete(button) {
  const row = button.closest("tr");
  const produk_id = row.cells[0].textContent;

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
        `/PHP/API/produk_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { produk_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire("Berhasil", response.message || "Produk dihapus.", "success");
      } else {
        Swal.fire(
          "Gagal",
          response.error || "Gagal menghapus produk.",
          "error"
        );
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

function populateDropdown(data, field_id, field) {
  const select = $(`#update_${field}`);
  select.empty();
  data.forEach((item) => {
    if (field == "kategori") {
      select.append(
        new Option(
          `${item.kategori_id} - ${item.nama}`,
          item.kategori_id,
          false,
          item.kategori_id == field_id
        )
      );
    } else if (field == "brand") {
      select.append(
        new Option(
          `${item.brand_id} - ${item.nama}`,
          item.brand_id,
          false,
          item.brand_id == field_id
        )
      );
    }
  });

  select.trigger("change");
}
async function update_pricelist(produk_id) {
  const result = await apiRequest(
    `/PHP/API/pricelist_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}&target=tb_produk&context=edit`,
    "POST",
    { produk_id }
  );

  const table_detail_pricelist = document.getElementById(
    "update_detail_pricelist_produk_tbody"
  );
  table_detail_pricelist.innerHTML = "";

  result.data.forEach((item, index) => {
    const tr = document.createElement("tr");

    const td_nama = document.createElement("td");
    td_nama.setAttribute("data-pricelist-id", item.pricelist_id);
    td_nama.textContent = item.nama;

    const td_harga = document.createElement("td");
    const input_harga = document.createElement("input");
    input_harga.setAttribute("id", `update_pricelist_harga${index}`);
    input_harga.className = "form-control";
    input_harga.value = helper.unformat_angka(item.harga);
    input_harga.style.textAlign = "right";
    td_harga.appendChild(input_harga);

    tr.appendChild(td_nama);
    tr.appendChild(td_harga);

    table_detail_pricelist.appendChild(tr);

    helper.format_nominal(`update_pricelist_harga${index}`);
  });
}

async function fetch_fk(field, field_id) {
  try {
    const response = await apiRequest(
      `/PHP/API/${field}_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_produk&context=edit`
    );
    populateDropdown(response.data, field_id, field);
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }
}

async function handle_update(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  const produk_id = row.cells[0].textContent;
  const nama = row.cells[1].textContent;
  const kategori_id = row.cells[7].textContent;
  const brand_id = row.cells[8].textContent;
  const no_sku = row.cells[4].textContent;
  const status = row.cells[5]
    .querySelector(".badge")
    ?.textContent.trim()
    .toLowerCase()
    .replace(/\s/g, " ");
  console.log(status);
  const stock_awal = row.cells[10].textContent;
  console.log(stock_awal);

  let harga_minimal = row.cells[6].textContent;
  harga_minimal = helper.unformat_angka(harga_minimal);
  let produk_gambar = row.cells[9];
  let produk_link = "";

  // Populate the modal fields
  document.getElementById("update_produk_id").value = produk_id;
  document.getElementById("update_name_produk").value = nama;
  document.getElementById("update_no_sku").value = no_sku;
  document.getElementById("update_status_produk").value = status;
  document.getElementById("update_harga_minimal").value = harga_minimal;
  document.getElementById("update_stock_awal").value = stock_awal;
  update_pricelist(produk_id);
  helper.format_nominal("update_harga_minimal");

  if (produk_gambar) {
    const div = document.createElement("div");
    div.innerHTML = produk_gambar.innerHTML;
    const a_tag = div.querySelector('a[href*="produk"]');
    produk_link = a_tag ? a_tag.getAttribute("href") : "";
  }
  const produk_filename = produk_link
    ? produk_link.split("/").pop()
    : "Belum ada file";

  helper.load_input_file_name(
    produk_link,
    "#update_produk_gambar",
    produk_filename
  );
  helper.load_file_link_group(
    "update_produk_gambar",
    "update_produk_input_group",
    produk_link
  );
  await new Promise((resolve) => setTimeout(resolve, 500));
  try {
    fetch_fk("kategori", kategori_id);
    fetch_fk("brand", brand_id);

    button_icon.style.display = "inline-block";
    spinner.style.display = "none";
    $("#update_modal_produk").modal("show");
  } catch (error) {
    toastr.error("Gagal mengambil data role: " + error.message);
    const button_icon = button.querySelector(".button_icon");
    const spinner = button.querySelector(".spinner_update");
    button_icon.style.display = "none";
    spinner.style.display = "inline-block";
  }
}
function validateField(field, pattern, errorMessage) {
  if (!field || field.trim() === "") {
    return true;
  }
  if (!pattern.test(field)) {
    toastr.error(errorMessage, {
      timeOut: 500,
      extendedTimeOut: 500,
    });
    return false;
  }
  return true;
}

const submit_produk_update = document.getElementById("update_submit_produk");
if (submit_produk_update) {
  submit_produk_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("No row selected for update.");
      return;
    }

    const row = window.currentRow;
    const produk_id = document.getElementById("update_produk_id").value;
    const nama_new = document.getElementById("update_name_produk").value;
    const no_sku_new = document.getElementById("update_no_sku").value;
    const status_new = document.getElementById("update_status_produk").value;
    const stock_awal_new = document.getElementById("update_stock_awal").value;
    console.log(stock_awal_new);
    let harga_minimal_new = document.getElementById(
      "update_harga_minimal"
    ).value;
    harga_minimal_new = helper.unformat_angka(harga_minimal_new);
    const kategori_id_new = $("#update_kategori").val();
    const brand_id_new = $("#update_brand").val();
    const details = [];
    const rows = document.querySelectorAll(
      "#update_detail_pricelist_produk_tbody tr"
    );

    for (const row of rows) {
      const td = row.querySelector("td");
      const input = row.querySelector("input");
      const pricelist_id = td?.getAttribute("data-pricelist-id");
      let harga = input?.value?.trim();
      if (harga == "0" || harga == 0) {
      } else {
        harga = helper.format_angka(harga);
        details.push({ pricelist_id, harga });
      }
    }

    if (!nama_new || nama_new.trim() === "") {
      toastr.error("Kolom * wajib diisi.");
      return;
    }

    const is_valid =
      validateField(nama_new, /^[a-zA-Z\s]+$/, "Format nama tidak valid") &&
      validateField(
        no_sku_new,
        /^[a-zA-Z0-9,.\- ]+$/,
        "Format no sku tidak valid"
      ) &&
      validateField(
        harga_minimal_new,
        /^[0-9., ]+$/,
        "Format harga minimal tidak valid"
      );
    if (is_valid) {
      const formData = new FormData();
      formData.append("produk_id", produk_id);
      formData.append("nama", nama_new);
      formData.append("no_sku", no_sku_new);
      formData.append("status", status_new);
      formData.append("harga_minimal", helper.format_angka(harga_minimal_new));
      formData.append("kategori_id", kategori_id_new);
      formData.append("brand_id", brand_id_new);
      formData.append("details", JSON.stringify(details));
      formData.append("stock_awal", stock_awal_new);
      const produk_file = document.getElementById("update_produk_gambar")
        .files[0];
      if (produk_file) {
        formData.append("produk_file", produk_file);
      } else {
        formData.append("remove_produk_file", "true");
      }
      try {
        const response = await apiRequest(
          `/PHP/API/produk_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          formData
        );
        if (response.ok) {
          row.cells[1].textContent = nama_new;

          const kategori = $("#update_kategori option:selected").text();
          const kategori_nama = kategori.split(" - ")[1];
          row.cells[2].textContent = kategori_nama;

          const brand = $("#update_brand option:selected").text();
          const brand_nama = brand.split(" - ")[1];
          row.cells[3].textContent = brand_nama;

          row.cells[4].textContent = no_sku_new;
          row.cells[5].textContent = status_new;
          row.cells[6].textContent = helper.format_angka(harga_minimal_new);

          $("#update_modal_produk").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.produk_grid.forceRender();
          setTimeout(() => {
            helper.custom_grid_header("produk", handle_delete, handle_update);
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
