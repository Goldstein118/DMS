import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
const submit_channel = document.getElementById("submit_channel");
if (submit_channel) {
  submit_channel.addEventListener("click", submitChannel);
  $("#modal_channel").on("shown.bs.modal", function () {
    $("#nama_channel").trigger("focus");
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
  const name_channel = document.getElementById("nama_channel").value;

  if (!name_channel || name_channel.trim() === "") {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  if (validateField(name_channel, /^[a-zA-Z\s]+$/, "Format nama tidak valid")) {
    const data_channel = {
      user_id: `${access.decryptItem("user_id")}`,
      name_channel,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/channel_API.php?action=create`,
        "POST",
        data_channel
      );
      if (response.ok) {
        swal.fire("Berhasil", response.message, "success");
        document.getElementById("nama_channel").value = "";
        $("#modal_channel").modal("hide");
        window.channel_grid.forceRender();
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
