import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const submit_frezzer = document.getElementById("submit_frezzer");
if (submit_frezzer) {
  submit_frezzer.addEventListener("click", submitChannel);
  $("#modal_frezzer").on("shown.bs.modal", function () {
    $("#kode_barcode").trigger("focus");
  });
}

async function submitChannel() {
  const kode_barcode = document.getElementById("kode_barcode").value;
  const tipe = document.getElementById("tipe").value;
  const status = document.getElementById("frezzer_status").value;
  const merek = document.getElementById("merek").value;
  const size = document.getElementById("size").value;

  if (!kode_barcode || kode_barcode.trim() === "") {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  if (
    helper.validateField(
      kode_barcode,
      /^[a-zA-Z0-9\s]+$/,
      "Format nama tidak valid"
    )
  ) {
    const data_frezzer = {
      user_id: `${access.decryptItem("user_id")}`,
      kode_barcode,
      tipe,
      status,
      merek,
      size,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/frezzer_API.php?action=create`,
        "POST",
        data_frezzer
      );
      if (response.ok) {
        swal.fire("Berhasil", response.message, "success");
        document.getElementById("kode_barcode").value = "";
        $("#modal_frezzer").modal("hide");
        window.frezzer_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("frezzer");
        }, 200);
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
