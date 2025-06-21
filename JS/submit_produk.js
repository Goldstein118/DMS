import * as access from "./cek_access.js";
import { apiRequest } from "./api.js";
import * as helper from "./helper.js";
const submit_produk = document.getElementById("submit_produk");
if (submit_produk) {
  submit_produk.addEventListener("click", submitProduk);
  $(document).ready(function () {
    $("#modal_produk").on("shown.bs.modal", function () {
      fetch_fk("kategori");
      fetch_fk("brand");
      pricelist();
      helper.format_nominal("harga_minimal");
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

async function pricelist() {
  const result = await apiRequest(
    `/PHP/API/pricelist_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}&target=tb_produk&context=create`
  );
  var table_detail_pricelist = document.getElementById(
    "create_detail_pricelist_produk_tbody"
  );
  table_detail_pricelist.innerHTML = "";
  result.data.forEach((item) => {
    const tr = document.createElement("tr");
    var currentIndex = table_detail_pricelist.rows.length;

    const td_detail_id = document.createElement("td");
    td_detail_id.setAttribute("id", "detail_pricelist_id");
    td_detail_id.textContent = item.detail_pricelist_id;

    const td_nama = document.createElement("td");
    td_nama.setAttribute("id", `pricelist_nama`);
    td_nama.textContent = item.nama;

    const td_harga = document.createElement("td");
    const input_harga = document.createElement("input");
    input_harga.setAttribute("id", "pricelist_harga" + currentIndex);
    input_harga.className = "form-control";
    input_harga.value = "0";
    input_harga.style.textAlign = "right";
    td_harga.appendChild(input_harga);

    const td_button = document.createElement("td");
    const button = document.createElement("button");
    button.type = "button";
    button.className = "btn btn-danger btn-sm delete_detail_pricelist";
    button.innerHTML = `<i class="bi bi-trash-fill"></i>`;
    td_button.appendChild(button);

    tr.appendChild(td_nama);
    tr.appendChild(td_harga);
    tr.appendChild(td_button);
    table_detail_pricelist.appendChild(tr);
    helper.format_nominal("pricelist_harga" + currentIndex);
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

async function submitProduk() {
  // Collect form data
  const name_produk = document.getElementById("name_produk").value;
  const kategori_id = document.getElementById("kategori").value;
  const brand_id = document.getElementById("brand").value;
  const no_sku = document.getElementById("no_sku").value;
  const status_produk = document.getElementById("status_produk").value;
  let harga_minimal = document.getElementById("harga_minimal").value;
  harga_minimal = helper.format_angka(harga_minimal);

  const details = [];
  const rows = document.querySelectorAll(
    "#create_detail_pricelist_produk_tbody tr"
  );

  for (const row of rows) {
    const input = row.querySelector("input");
    let harga = input?.value?.trim();
    harga = helper.format_angka(harga);
  }
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
    helper.validateField(
      name_produk,
      /^[a-zA-Z\s]+$/,
      "Format nama tidak valid"
    ) &&
    helper.validateField(
      no_sku,
      /^[a-zA-Z0-9,.\- ]+$/,
      "Format no sku tidak valid"
    ) &&
    helper.validateField(
      harga_minimal,
      /^[0-9., ]+$/,
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
      if (response.ok) {
        swal.fire("Berhasil", response.message, "success");
        document.getElementById("name_produk").value = "";
        document.getElementById("no_sku").value = "";
        document.getElementById("harga_minimal").value = "";
        $("#kategori").val(null).trigger("change");
        $("#brand").val(null).trigger("change");
        $("#modal_produk").modal("hide");
        window.produk_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("produk");
        }, 200);
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
