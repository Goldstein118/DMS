import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
const submit_brand = document.getElementById("submit_brand");
if (submit_brand) {
  submit_brand.addEventListener("click", submitChannel);
  $("#modal_brand").on("shown.bs.modal", function () {
    $("#nama_brand").trigger("focus");
  });
}
function validateField(field, pattern, errorMessage) {
  if (!pattern.test(field)) {
    toastr.error(errorMessage, {
      timeOut: 500,
      extendedTimeOut: 500,
    });
    return false;
  }
  return true;
}

async function submitChannel() {
  const name_brand = document.getElementById("nama_brand").value;

  if (!name_brand || name_brand.trim() === "") {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  if (validateField(name_brand, /^[a-zA-Z\s]+$/, "Format nama tidak valid")) {
    const data_brand = {
      user_id: `${access.decryptItem("user_id")}`,
      name_brand,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/brand_API.php?action=create`,
        "POST",
        data_brand
      );
      swal.fire("Berhasil", response.message, "success");
      document.getElementById("nama_brand").value = "";
      $("#modal_brand").modal("hide");
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
