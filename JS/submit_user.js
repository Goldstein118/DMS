import config from "../JS/config.js";

const submit_user = document.getElementById("submit_user");
if (submit_user) {
  submit_user.addEventListener("click", submitUser);
  $("#karyawan_ID").select2({
    placeholder: "Pilih Karyawan",
    allowClear: true,
    dropdownParent: $("#modal_user"),
  });
  $("#modal_user").on("shown.bs.modal", () => {
    fetch_karyawan();
  });
}
async function fetch_karyawan() {
  const response = await fetch(`${config.API_BASE_URL}/PHP/API/user_API.php`);
  const karyawans = await response.json();
  const karyawan_id = $("#karyawan_ID");
  karyawan_id.empty();

  karyawans.forEach((karyawan) => {
    const option = new Option(
      `${karyawan.karyawan_id} - ${karyawan.karyawan_nama}`,
      karyawan.karyawan_id,
      false,
      false
    );
    karyawan_id.append(option);
  });

  karyawan_id.trigger("change");
}

function submitUser() {
  const level = document.getElementById("level").value;
  const karyawan_id = document.getElementById("karyawan_ID").value;

  const data_user = {
    action: "submit_user",
    karyawan_id,
    level,
  };

  fetch(`${config.API_BASE_URL}/PHP/create.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data_user),
  })
    .then((response) => {
      return response.json();
    })
    .then((jsonData) => {
      if (jsonData.success) {
        document.getElementById("level").value = "user";
        $("#karyawan_ID").val(null).trigger("change");
        $("#modal_user").modal("hide");
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
      console.error("Error submitting karyawan:", error);
      toastr.error(
        "An error occurred while submitting the form. Please try again."
      );
    });
}
