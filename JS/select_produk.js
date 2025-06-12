import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";

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
          produk.status,
          produk.harga_minimal,
          produk.kategori_id,
          produk.brand_id,
          null,
        ]),
    },
  });
  window.produk_grid.render(document.getElementById("table_produk"));
  setTimeout(() => {
    const grid_header = document.querySelector("#table_produk .gridjs-head");
    const search_Box = grid_header.querySelector(".gridjs-search");

    // Create the button
    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = "btn btn-primary btn-sm";
    btn.setAttribute("data-bs-toggle", "modal");
    btn.setAttribute("data-bs-target", "#modal_produk");
    btn.innerHTML = '<i class="bi bi-person-plus-fill"></i> Produk';

    // Wrap both button and search bar in a flex container
    const wrapper = document.createElement("div");
    wrapper.className =
      "d-flex justify-content-between align-items-center mb-3";
    if (access.hasAccess("tb_produk", "create")) {
      wrapper.appendChild(btn);
    }

    wrapper.appendChild(search_Box);

    // Replace grid header content
    grid_header.innerHTML = "";
    grid_header.appendChild(wrapper);
    const input = document.querySelector("#table_produk .gridjs-input");
    grid_header.style.display = "flex";
    grid_header.style.justifyContent = "flex-end";

    search_Box.style.display = "flex";
    search_Box.style.justifyContent = "flex-end";
    search_Box.style.marginLeft = "auto";
    input.placeholder = "Cari Produk...";
    document.getElementById("loading_spinner").style.visibility = "hidden";
    $("#loading_spinner").fadeOut();
    attachEventListeners();
  }, 200);
}
function attachEventListeners() {
  document
    .getElementById("table_produk")
    .addEventListener("click", function (event) {
      const delete_btn = event.target.closest(".delete_produk");
      const update_btn = event.target.closest(".update_produk");

      if (delete_btn) {
        handleDeleteProduk(delete_btn);
      } else if (update_btn) {
        handleUpdateProduk(update_btn);
      }
    });
}
function format_angka(str) {
  if (str === null || str === undefined || str === "") {
    return str;
  }

  const cleaned = str.toString().replace(/[.,\s]/g, "");

  if (!/^\d+$/.test(cleaned)) {
    return str;
  }
  const result = cleaned.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

  return result + ",00";
}

function unformat_angka(formattedString) {
  if (
    formattedString === null ||
    formattedString === undefined ||
    formattedString === ""
  ) {
    return formattedString;
  }

  return formattedString
    .toString()
    .replace(/[.,\s]/g, "")
    .replace(/,00$/, "");
}
async function handleDeleteProduk(button) {
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

async function handleUpdateProduk(button) {
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
  const status = row.cells[5].textContent;
  let harga_minimal = row.cells[6].textContent;
  harga_minimal = unformat_angka(harga_minimal);

  // Populate the modal fields
  document.getElementById("update_produk_id").value = produk_id;
  document.getElementById("update_name_produk").value = nama;
  document.getElementById("update_no_sku").value = no_sku;
  document.getElementById("update_status_produk").value = status;
  document.getElementById("update_harga_minimal").value = harga_minimal;

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
    let harga_minimal_new = document.getElementById(
      "update_harga_minimal"
    ).value;
    harga_minimal_new = unformat_angka(harga_minimal_new);
    const kategori_id_new = $("#update_kategori").val();
    const brand_id_new = $("#update_brand").val();

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
      try {
        const data_produk_update = {
          produk_id: produk_id,
          nama: nama_new,
          no_sku: no_sku_new,
          status: status_new,
          harga_minimal: format_angka(harga_minimal_new),
          kategori_id: kategori_id_new,
          brand_id: brand_id_new,
        };
        const response = await apiRequest(
          `/PHP/API/produk_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_produk_update
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
          row.cells[6].textContent = format_angka(harga_minimal_new);

          $("#update_modal_produk").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.produk_grid.forceRender();
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
