import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const submit_channel = document.getElementById("submit_channel");
if (submit_channel) {
  submit_channel.addEventListener("click", submitChannel);
  $("#modal_channel").on("shown.bs.modal", function () {
    $("#nama_channel").trigger("focus");
  });
}

async function submitChannel() {
  const name_channel = document.getElementById("nama_channel").value;

  if (!name_channel || name_channel.trim() === "") {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  if (
    helper.validateField(
      name_channel,
      /^[a-zA-Z\s]+$/,
      "Format nama tidak valid"
    )
  ) {
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
        setTimeout(() => {
          helper.custom_grid_header("channel");
        }, 200);
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
