import { apiRequest } from "./api.js";

const submit_karyawan = document.getElementById("submit_karyawan");
if (submit_karyawan) {
  submit_karyawan.addEventListener("click", submitKaryawan);
  $(document).ready(function () {
    $("#modal_karyawan").on("shown.bs.modal", function () {
      fetch_roles();
      $("#name_karyawan").trigger("focus");
      $("#role_select").select2({
        placeholder: "Pilih Role",
        allowClear: true,
        dropdownParent: $("#modal_karyawan"),
      });
    });
  });
}

async function fetch_roles() {
  try {
    const response = await apiRequest(
      `/PHP/API/role_API.php?action=select&user_id=${localStorage.getItem(
        "user_id"
      )}&target=tb_karyawan&context=create`
    );
    populateRoleDropdown(response.data);
  } catch (error) {
    toastr.error("Gagal mengambil data role: " + error.message);
  }
}

function populateRoleDropdown(data) {
  const select = $("#role_select");
  select.empty();
  select.append(new Option("Pilih Role", "", false, false));

  data.forEach((item) => {
    select.append(new Option(item.nama, item.role_id, false, false));
  });

  select.trigger("change");
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
function format_no_telp(str) {
  if (7 > str.length) {
    return "Invalid index";
  }

  let format = str.slice(0, 3) + "-" + str.slice(3, 7) + "-" + str.slice(7);
  let result = "+62 " + format;
  return result;
}

async function submitKaryawan() {
  // Collect form data
  const name_karyawan = document.getElementById("name_karyawan").value;
  const divisi_karyawan = document.getElementById("divisi_karyawan").value;
  let phone_karyawan = document.getElementById("phone_karyawan").value;
  const address_karyawan = document.getElementById("address_karyawan").value;
  const nik_karyawan = document.getElementById("nik_karyawan").value;
  const role_id = document.getElementById("role_select").value;
  const npwp_karyawan = document.getElementById("npwp_karyawan").value;
  const status_karyawan = document.getElementById("status_karyawan").value;

  // Validate form data
  if (
    !name_karyawan ||
    name_karyawan.trim() === "" ||
    !divisi_karyawan ||
    divisi_karyawan.trim() === "" ||
    !phone_karyawan ||
    phone_karyawan.trim() === "" ||
    !address_karyawan ||
    address_karyawan.trim() === "" ||
    !nik_karyawan ||
    nik_karyawan.trim() === "" ||
    !role_id ||
    role_id.trim() === ""
  ) {
    toastr.error("Harap isi semua kolom sebelum submit.");
    return;
  }
  const is_valid =
    validateField(name_karyawan, /^[a-zA-Z\s]+$/, "Format nama tidak valid") &&
    validateField(
      divisi_karyawan,
      /^[a-zA-Z0-9,. ]+$/,
      "Format divisi tidak valid"
    ) &&
    validateField(
      address_karyawan,
      /^[a-zA-Z0-9,. ]+$/,
      "Format alamat tidak valid"
    ) &&
    validateField(
      phone_karyawan,
      /^[0-9]{9,13}$/,
      "Format nomor telepon tidak valid"
    ) &&
    validateField(nik_karyawan, /^[0-9]+$/, "Format NIK tidak valid") &&
    validateField(npwp_karyawan, /^[0-9 .-]+$/, "Format NPWP tidak valid");

  if (is_valid) {
    const no_telp_karyawan = format_no_telp(phone_karyawan);

    const data_karyawan = {
      user_id: `${localStorage.getItem("user_id")}`,
      name_karyawan,
      divisi_karyawan,
      no_telp_karyawan,
      address_karyawan,
      nik_karyawan,
      role_id,
      npwp_karyawan,
      status_karyawan,
    };
    try {
      const response = await apiRequest(
        `/PHP/API/karyawan_API.php?action=create`,
        "POST",
        data_karyawan
      );
      swal.fire("Berhasil", response.message, "success");
      document.getElementById("name_karyawan").value = "";
      document.getElementById("divisi_karyawan").value = "";
      document.getElementById("phone_karyawan").value = "";
      document.getElementById("address_karyawan").value = "";
      document.getElementById("nik_karyawan").value = "";
      document.getElementById("npwp_karyawan").value = "";
      document.getElementById("status_karyawan").value = "";
      $("#role_select").val(null).trigger("change");
      $("#modal_karyawan").modal("hide");
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
