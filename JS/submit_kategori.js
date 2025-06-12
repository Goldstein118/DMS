import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
const submit_kategori = document.getElementById("submit_kategori");
if (submit_kategori) {
  submit_kategori.addEventListener("click", submitChannel);
  $("#modal_kategori").on("shown.bs.modal", function () {
    $("#nama_kategori").trigger("focus");
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
  const name_kategori = document.getElementById("nama_kategori").value;

  if (!name_kategori || name_kategori.trim() === "") {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  if (
    validateField(name_kategori, /^[a-zA-Z\s]+$/, "Format nama tidak valid")
  ) {
    const data_kategori = {
      user_id: `${access.decryptItem("user_id")}`,
      name_kategori,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/kategori_API.php?action=create`,
        "POST",
        data_kategori
      );
      if (response.ok) {
        swal.fire("Berhasil", response.message, "success");
        document.getElementById("nama_kategori").value = "";
        $("#modal_kategori").modal("hide");
        window.kategori_grid.forceRender();
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
