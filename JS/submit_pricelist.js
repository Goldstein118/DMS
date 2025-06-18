import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const submit_pricelist = document.getElementById("submit_pricelist");
if (submit_pricelist) {
  submit_pricelist.addEventListener("click", submitPricelist);
  $(document).ready(function () {
    $("#modal_pricelist").on("shown.bs.modal", function () {
      $("#name_pricelist").trigger("focus");
      fetch_produk();
      $("#produk_select").select2({
        placeholder: "Pilih Select",
        allowClear: true,
        dropdownParent: $("#modal_pricelist"),
      });
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

  if (is_valid) {
    const data_pricelist = {
      user_id: `${access.decryptItem("user_id")}`,
      name_pricelist,
      harga_default,
      tanggal_berlaku,
      status_pricelist,
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
