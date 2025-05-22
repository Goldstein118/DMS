import config from "../JS/config.js";

const submit_role = document.getElementById("submit_role");
if (submit_role) {
  submit_role.addEventListener("click", submitRole);
  $("#modal_karyawan").on("shown.bs.modal", function () {
    $("#name_role").trigger("focus");
  });
}

function submitRole() {
  // Collect form data
  const name_role = document.getElementById("name_role").value;
  const akses_role = document.getElementById("akses_role").value;

  // Validate form data
  if (
    !name_role ||
    name_role.trim() === "" ||
    !akses_role ||
    akses_role.trim() === ""
  ) {
    toastr.error("Harap isi semua kolom sebelum submit.");
    return;
  }

  // Create a data object
  const data_role = { action: "submit_role", name_role, akses_role };

  fetch(`${config.API_BASE_URL}/PHP/create.php`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data_role),
  })
    .then((response) => response.json())
    .then((result) => {
      if (result.success) {
        // Reset the form
        document.getElementById("name_role").value = "";
        document.getElementById("akses_role").value = "";
        $("#modal_role").modal("hide");
        Swal.fire({
          title: "Berhasil",
          icon: "success",
        });
      } else {
        toastr.error(result.message, {
          timeOut: 500,
          extendedTimeOut: 500,
        });
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      toastr.error(
        "An error occurred while submitting the form. Please try again."
      );
    });
}
