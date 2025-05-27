import config from "../JS/config.js";

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

function submitChannel() {
  const name_channel = document.getElementById("nama_channel").value;

  if (!name_channel || name_channel.trim() === "") {
    toastr.error("Harap isi semua kolom sebelum submit.");
    return;
  }
  if (validateField(name_channel, /^[a-zA-Z\s]+$/, "Format nama tidak valid")) {
    const data_channel = {
      action: "submit_channel",
      name_channel,
    };
    fetch(`${config.API_BASE_URL}/PHP/create.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data_channel),
    })
      .then((response) => {
        return response.json();
      })
      .then((jsonData) => {
        if (jsonData.success) {
          document.getElementById("nama_channel").value = "";
          $("#modal_channel").modal("hide");
          Swal.fire({
            title: "Berhasil",
            icon: "success",
          });
        } else {
          toastr.error(jsonData.message, {
            timeOut: 500,
            extendedTimeOut: 500,
          });
        }
      })
      .catch((error) => {
        console.error("Error submitting channel:", error);
        toastr.error(
          "An error occurred while submitting the form. Please try again."
        );
      });
  }
}
