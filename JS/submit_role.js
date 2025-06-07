import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
function proses_check_box() {
  const checkboxes = document.querySelectorAll("#modal_role .perm-checkbox");
  let results = [];

  checkboxes.forEach((checkbox) => {
    const value = checkbox.checked ? 1 : 0;
    results.push(value);
  });
  results = results.join("");
  console.log(results);
  return results;
}
function event_check_box(field) {
  ["view", "create", "edit", "delete"].forEach((action) => {
    const checkbox = document.getElementById(`check_${action}_${field}`);
    if (checkbox) {
      checkbox.checked = !checkbox.checked;
    }
  });
}
function view_checkbox(field) {
  ["create", "edit", "delete"].forEach((action) => {
    const checkbox = document.getElementById(`check_${action}_${field}`);
    checkbox.addEventListener("change", () => {
      const view = document.getElementById(`check_view_${field}`);
      if (checkbox.checked) {
        view.checked = true;
      }
    });
  });
}
const submit_role = document.getElementById("submit_role");
if (submit_role) {
  submit_role.addEventListener("click", submitRole);
  $("#modal_role").on("shown.bs.modal", function () {
    $("#name_role").trigger("focus");
  });

  document.addEventListener("DOMContentLoaded", () => {
    const checkboxFields = [
      "karyawan",
      "user",
      "role",
      "supplier",
      "customer",
      "channel",
    ];

    checkboxFields.forEach((field) => {
      const checkboxAll = document.getElementById(`check_all_${field}`);
      if (checkboxAll) {
        checkboxAll.addEventListener("click", () => event_check_box(field));
      }
    });

    checkboxFields.forEach((field) => {
      view_checkbox(field);
    });
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
function submitRole() {
  const akses_role = proses_check_box();
  // Collect form data
  const name_role = document.getElementById("name_role").value;

  if (
    !name_role ||
    name_role.trim() === "" ||
    !akses_role ||
    akses_role.trim() === ""
  ) {
    toastr.error("Harap isi semua kolom sebelum submit.");
    return;
  }
  const is_valid =
    validateField(name_role, /^[a-zA-Z\s]+$/, "Format nama tidak valid") &&
    validateField(akses_role, /^[0-9]+$/, "Format akses tidak valid");

  const data_role = {
    user_id: `${access.decryptItem("user_id")}`,
    name_role,
    akses_role,
  };

  if (is_valid) {
    try {
      const response = apiRequest(
        "/PHP/API/role_API.php?action=create",
        "POST",
        data_role
      );
      Swal.fire("Berhasil", response.message, "success");
      document.getElementById("name_role").value = "";
      $("#modal_role").modal("hide");
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
