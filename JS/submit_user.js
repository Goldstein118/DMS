import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";

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
  if (access.isOwner) {
    const select = document.getElementById("level");
    const options = document.createElement("option");
    options.value = "owner";
    options.textContent = "Owner";
    select.appendChild(options);
  }
}

async function fetch_karyawan() {
  const response = await apiRequest(
    ` /PHP/API/karyawan_API.php?action=select&user_id=${access.decryptItem(
      "user_id"
    )}&target=tb_user&context=create`
  );
  const karyawan_id = $("#karyawan_ID");
  karyawan_id.empty();
  karyawan_id.append(new Option("Pilih Karyawan", "", false, false));

  response.data.forEach((karyawan) => {
    const option = new Option(
      `${karyawan.karyawan_id} - ${karyawan.nama}`,
      karyawan.karyawan_id,
      false,
      false
    );
    karyawan_id.append(option);
  });

  karyawan_id.trigger("change");
}

async function submitUser() {
  const level = document.getElementById("level").value;
  const karyawan_id = document.getElementById("karyawan_ID").value;

  const data_user = {
    user_id: `${access.decryptItem("user_id")}`,
    karyawan_id,
    level,
  };

  try {
    const response = await apiRequest(
      "/PHP/API/user_API.php?action=create",
      "POST",
      data_user
    );
    document.getElementById("level").value = "user";
    $("#karyawan_ID").val(null).trigger("change");
    $("#modal_user").modal("hide");
    Swal.fire("Berhasil", response.message, "success");
  } catch (error) {
    toastr.error(error.message);
  }
}
