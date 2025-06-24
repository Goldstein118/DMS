import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const submit_pricelist = document.getElementById("submit_pricelist");
const submit_detail_pricelist = document.getElementById(
  "create_detail_pricelist"
);
if (submit_pricelist) {
  submit_pricelist.addEventListener("click", submitPricelist);
  submit_detail_pricelist.addEventListener("click", () => {
    helper.addField("create");
  });
  $(document).ready(function () {
    $("#modal_pricelist").on("shown.bs.modal", function () {
      $("#name_pricelist").trigger("focus");
      fetch_produk();
      helper.format_nominal("harga");
      $("#produk_select").select2({
        placeholder: "Pilih produk",
        allowClear: true,
        dropdownParent: $("#modal_pricelist"),
      });
    });
    $("#tanggal_berlaku").pickadate({
      format: "dd mmm yyyy", // user sees: 01 Jan 2025
      formatSubmit: "yyyy-mm-dd", // hidden value: 01/01/2025
      hiddenName: true, // use a single name="date" on hidden input
    });
  });
}

async function fetch_produk() {
  try {
    const response = await apiRequest(
      `/PHP/API/produk_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_pricelist&context=create`
    );
    const select = $("#produk_select");
    select.empty();
    select.append(new Option("Pilih Produk", "", false, false));
    response.data.forEach((produk) => {
      const option = new Option(
        `${produk.produk_id} - ${produk.nama}`,
        produk.produk_id,
        false,
        false
      );
      select.append(option);
    });
    select.trigger("change");
  } catch (error) {
    console.error("error:", error);
  }
}

async function submitPricelist() {
  // Collect form data
  const name_pricelist = document.getElementById("name_pricelist").value;
  const harga_default = document.getElementById("default_pricelist").value;
  let tanggal_berlaku = document.getElementById("tanggal_berlaku").value;
  const status_pricelist = document.getElementById("status_pricelist").value;

  // Validate form data
  if (
    !name_pricelist ||
    name_pricelist.trim() === "" ||
    !tanggal_berlaku ||
    tanggal_berlaku.trim() === ""
  ) {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  const is_valid = helper.validateField(
    name_pricelist,
    /^[a-zA-Z\s]+$/,
    "Format nama tidak valid"
  );
  const details = [];
  const rows = document.querySelectorAll("#create_detail_pricelist_tbody tr");

  for (const row of rows) {
    const select = row.querySelector("select");
    const input = row.querySelector("input");

    const produk_id = select?.value;
    let harga = input?.value?.trim();
    if (!produk_id || !harga) {
      toastr.error("Semua produk dan harga harus diisi.");
      return;
    }
    harga = helper.format_angka(harga);
    details.push({ produk_id, harga });
  }

  if (details.length === 0) {
    toastr.error("Minimal satu detail pricelist harus diisi.");
    return;
  }
  if (is_valid) {
    const data_pricelist = {
      user_id: `${access.decryptItem("user_id")}`,
      name_pricelist,
      harga_default,
      tanggal_berlaku,
      status_pricelist,
      details,
    };
    try {
      const response = await apiRequest(
        `/PHP/API/pricelist_API.php?action=create`,
        "POST",
        data_pricelist
      );
      if (response.ok) {
        swal.fire("Berhasil", response.message, "success");
        document.getElementById("name_pricelist").value = "";
        document.getElementById("tanggal_berlaku").value = "";
        document.querySelector("#create_detail_pricelist_tbody").innerHTML = "";
        $("#modal_pricelist").modal("hide");
        window.pricelist_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("pricelist");
        }, 200);
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
