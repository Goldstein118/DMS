import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const submit_gudang = document.getElementById("submit_gudang");
if (submit_gudang) {
  submit_gudang.addEventListener("click", submitChannel);
  $("#modal_gudang").on("shown.bs.modal", function () {
    $("#nama_gudang").trigger("focus");
  });
}

async function submitChannel() {
  const name_gudang = document.getElementById("nama_gudang").value;
  const status = document.getElementById("gudang_status").value;

  if (!name_gudang || name_gudang.trim() === "") {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  if (
    helper.validateField(
      name_gudang,
      /^[a-zA-Z\s]+$/,
      "Format nama tidak valid"
    )
  ) {
    const data_gudang = {
      user_id: `${access.decryptItem("user_id")}`,
      name_gudang,
      status,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/gudang_API.php?action=create`,
        "POST",
        data_gudang
      );
      if (response.ok) {
        swal.fire("Berhasil", response.message, "success");
        document.getElementById("nama_gudang").value = "";
        $("#modal_gudang").modal("hide");
        window.gudang_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("gudang");
        }, 200);
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
