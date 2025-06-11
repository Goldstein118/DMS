import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
const submit_supplier = document.getElementById("submit_supplier");
if (submit_supplier) {
  submit_supplier.addEventListener("click", submitSupplier);
  $("#modal_supplier").on("shown.bs.modal", function () {
    $("#supplier_nama").trigger("focus");
  });
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
function format_no_telp(str) {
  if (!str || str.trim() === "") {
    let result = str;
    return result;
  } else {
    if (7 > str.length) {
      return "Invalid index";
    }
    let format = str.slice(0, 3) + "-" + str.slice(3, 7) + "-" + str.slice(7);
    let result = "+62 " + format;
    return result;
  }
}
async function submitSupplier() {
  const supplier_nama = document.getElementById("supplier_nama").value;
  const supplier_alamat = document.getElementById("supplier_alamat").value;
  let supplier_phone = document.getElementById("supplier_no_telp").value;
  const supplier_ktp = document.getElementById("supplier_ktp").value;
  const supplier_npwp = document.getElementById("supplier_npwp").value;
  const supplier_status = document.getElementById("supplier_status").value;

  if (
    !supplier_nama ||
    supplier_nama.trim() === "" ||
    !supplier_status ||
    supplier_status.trim() === ""
  ) {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  const is_valid =
    validateField(supplier_nama, /^[a-zA-Z\s]+$/, "Format nama tidak valid") &&
    validateField(
      supplier_alamat,
      /^[a-zA-Z0-9,. ]+$/,
      "Format alamat tidak valid"
    ) &&
    validateField(
      supplier_phone,
      /^[0-9]{9,13}$/,
      "Format nomor telepon tidak valid"
    ) &&
    validateField(supplier_ktp, /^[0-9]+$/, "Format NIK tidak valid") &&
    validateField(supplier_npwp, /^[0-9 .-]+$/, "Format NPWP tidak valid");

  if (is_valid) {
    const supplier_no_telp = format_no_telp(supplier_phone);
    const supplier_data = {
      user_id: `${access.decryptItem("user_id")}`,
      supplier_nama,
      supplier_alamat,
      supplier_no_telp,
      supplier_ktp,
      supplier_npwp,
      supplier_status,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/supplier_API.php?action=create`,
        "POST",
        supplier_data
      );

      document.getElementById("supplier_nama").value = "";
      document.getElementById("supplier_alamat").value = "";
      document.getElementById("supplier_no_telp").value = "";
      document.getElementById("supplier_ktp").value = "";
      document.getElementById("supplier_npwp").value = "";
      document.getElementById("supplier_status").value = "";
      $("#modal_supplier").modal("hide");
      swal.fire("Berhasil", response.message, "success");
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
