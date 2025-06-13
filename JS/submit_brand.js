import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
const submit_brand = document.getElementById("submit_brand");
if (submit_brand) {
  submit_brand.addEventListener("click", submitChannel);
  $("#modal_brand").on("shown.bs.modal", function () {
    $("#nama_brand").trigger("focus");
  });
}

async function submitChannel() {
  const name_brand = document.getElementById("nama_brand").value;

  if (!name_brand || name_brand.trim() === "") {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  if (
    helper.validateField(name_brand, /^[a-zA-Z\s]+$/, "Format nama tidak valid")
  ) {
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
      if (response.ok) {
        swal.fire("Berhasil", response.message, "success");
        document.getElementById("nama_brand").value = "";
        $("#modal_brand").modal("hide");
        window.brand_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("brand");
        }, 200);
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
