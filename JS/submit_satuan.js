import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const submit_satuan = document.getElementById("submit_satuan");
if (submit_satuan) {
  submit_satuan.addEventListener("click", submitSatuan);
  $("#modal_satuan").on("shown.bs.modal", function () {
    $("#nama_satuan").trigger("focus");
  });
}

async function submitSatuan() {
  const name_satuan = document.getElementById("nama_satuan").value;
  const id_referensi = document.getElementById("id_referensi").value;
  const qty_satuan = document.getElementById("qty_satuan").value;

  if (!name_satuan || name_satuan.trim() === "") {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  if (
    helper.validateField(
      name_satuan,
      /^[a-zA-Z\s]+$/,
      "Format nama tidak valid"
    )
  ) {
    const data_satuan = {
      user_id: `${access.decryptItem("user_id")}`,
      nama: name_satuan,
      id_referensi: id_referensi,
      qty_satuan: qty_satuan,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/satuan_API.php?action=create`,
        "POST",
        data_satuan
      );
      if (response.ok) {
        swal.fire("Berhasil", response.message, "success");
        document.getElementById("nama_satuan").value = "";
        $("#modal_satuan").modal("hide");
        window.satuan_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("satuan");
        }, 200);
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
