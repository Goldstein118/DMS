import * as access from "./cek_access.js";
import { apiRequest } from "./api.js";

const submit_produk = document.getElementById("submit_produk");
if (submit_produk) {
  submit_produk.addEventListener("click", submitProduk);
  $(document).ready(function () {
    $("#modal_produk").on("shown.bs.modal", function () {
      fetch_fk("kategori");
      fetch_fk("brand");
      $("#name_produk").trigger("focus");
      $("#kategori").select2({
        placeholder: "Pilih Kategori",
        allowClear: true,
        dropdownParent: $("#modal_produk"),
      });
      $("#brand").select2({
        placeholder: "Pilih Brand",
        allowClear: true,
        dropdownParent: $("#modal_produk"),
      });
    });
  });
}

async function fetch_fk(field) {
  try {
    const response = await apiRequest(
      `/PHP/API/${field}_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_produk&context=create`
    );
    populateDropdown(response.data, field);
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }
}
function format_angka(str) {
  if (str === null || str === undefined || str === "") {
    return str;
  }

  const cleaned = str.toString().replace(/[.,\s]/g, "");

  if (!/^\d+$/.test(cleaned)) {
    return str;
  }

  return cleaned.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function populateDropdown(data, field) {
  const select = $(`#${field}`);
  select.empty();
  select.append(new Option(`Pilih ${field}`, "", false, false));

  data.forEach((item) => {
    if (field == "kategori") {
      select.append(
        new Option(
          `${item.kategori_id} - ${item.nama}`,
          item.kategori_id,
          false,
          false
        )
      );
    } else if (field == "brand") {
      select.append(
        new Option(
          `${item.brand_id} - ${item.nama}`,
          item.brand_id,
          false,
          false
        )
      );
    } else {
      toastr.error("field is empty or no matching field");
    }
  });

  select.trigger("change");
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

async function submitProduk() {
  // Collect form data
  const name_produk = document.getElementById("name_produk").value;
  const kategori_id = document.getElementById("kategori").value;
  const brand_id = document.getElementById("brand").value;
  const no_sku = document.getElementById("no_sku").value;
  const status_produk = document.getElementById("status_produk").value;
  let harga_minimal = document.getElementById("harga_minimal").value;
  harga_minimal = format_angka(harga_minimal);

  if (
    !name_produk ||
    name_produk.trim() === "" ||
    !kategori_id ||
    kategori_id.trim() === "" ||
    !brand_id ||
    brand_id.trim() === "" ||
    !status_produk ||
    status_produk.trim() === ""
  ) {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  const is_valid =
    validateField(name_produk, /^[a-zA-Z\s]+$/, "Format nama tidak valid") &&
    validateField(no_sku, /^[a-zA-Z0-9,.\- ]+$/, "Format no sku tidak valid") &&
    validateField(
      harga_minimal,
      /^[0-9. ]+$/,
      "Format harga minmal tidak valid"
    );

  if (is_valid) {
    const data_produk = {
      user_id: `${access.decryptItem("user_id")}`,
      name_produk,
      kategori_id,
      brand_id,
      no_sku,
      status_produk,
      harga_minimal,
    };
    try {
      const response = await apiRequest(
        `/PHP/API/produk_API.php?action=create`,
        "POST",
        data_produk
      );
      swal.fire("Berhasil", response.message, "success");
      document.getElementById("name_produk").value = "";
      document.getElementById("no_sku").value = "";
      document.getElementById("harga_minimal").value = "";
      $("#kategori").val(null).trigger("change");
      $("#brand").val(null).trigger("change");
      $("#modal_produk").modal("hide");
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
