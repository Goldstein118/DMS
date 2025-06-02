import { apiRequest } from "./api.js";
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
  let view = document.getElementById("check_view_" + field);
  view.checked = !view.checked;

  let create = document.getElementById("check_create_" + field);
  create.checked = !create.checked;

  let edit = document.getElementById("check_edit_" + field);
  edit.checked = !edit.checked;

  let delete_check_box = document.getElementById("check_delete_" + field);
  delete_check_box.checked = !delete_check_box.checked;
}
const submit_role = document.getElementById("submit_role");
if (submit_role) {
  submit_role.addEventListener("click", submitRole);
  $("#modal_role").on("shown.bs.modal", function () {
    $("#name_role").trigger("focus");
  });
  document.addEventListener("DOMContentLoaded", () => {
    let checkbox_karyawan = document.getElementById("check_all_karyawan");
    checkbox_karyawan.addEventListener("click", () => {
      event_check_box("karyawan");
    });

    let checkbox_user = document.getElementById("check_all_user");
    checkbox_user.addEventListener("click", () => event_check_box("user"));

    let checkbox_role = document.getElementById("check_all_role");
    checkbox_role.addEventListener("click", () => event_check_box("role"));

    let checkbox_supplier = document.getElementById("check_all_supplier");
    checkbox_supplier.addEventListener("click", () =>
      event_check_box("supplier")
    );

    let checkbox_customer = document.getElementById("check_all_customer");
    checkbox_customer.addEventListener("click", () =>
      event_check_box("customer")
    );

    let checkbox_channel = document.getElementById("check_all_channel");
    checkbox_channel.addEventListener("click", () =>
      event_check_box("channel")
    );
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
    user_id: `${localStorage.getItem("user_id")}`,
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
