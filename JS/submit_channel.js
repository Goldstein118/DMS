import { apiRequest } from "./api.js";

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
    toastr.error("Harap isi semua kolom sebelum submit.");
    return;
  }
  if (validateField(name_channel, /^[a-zA-Z\s]+$/, "Format nama tidak valid")) {
    const data_channel = {
      user_id: `${localStorage.getItem("user_id")}`,
      name_channel,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/channel_API.php?action=create`,
        "POST",
        data_channel
      );
      swal.fire("Berhasil", response.message, "success");
      document.getElementById("nama_channel").value = "";
      $("#modal_channel").modal("hide");
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
