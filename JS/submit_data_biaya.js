import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const submit_data_biaya = document.getElementById("submit_data_biaya");
if (submit_data_biaya) {
  submit_data_biaya.addEventListener("click", submitChannel);
  $("#modal_data_biaya").on("shown.bs.modal", function () {
    $("#nama_data_biaya").trigger("focus");
  });
}

async function submitChannel() {
  const name_data_biaya = document.getElementById("nama_data_biaya").value;

  if (!name_data_biaya || name_data_biaya.trim() === "") {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  if (
    helper.validateField(
      name_data_biaya,
      /^[a-zA-Z\s]+$/,
      "Format nama tidak valid"
    )
  ) {
    const data_data_biaya = {
      user_id: `${access.decryptItem("user_id")}`,
      name_data_biaya,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/data_biaya_API.php?action=create`,
        "POST",
        data_data_biaya
      );
      if (response.ok) {
        swal.fire("Berhasil", response.message, "success");
        document.getElementById("nama_data_biaya").value = "";
        $("#modal_data_biaya").modal("hide");
        window.data_biaya_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("data_biaya");
        }, 200);
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
