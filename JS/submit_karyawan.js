import * as access from "./cek_access.js";
import { apiRequest } from "./api.js";
import * as helper from "./helper.js";
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
      `/PHP/API/role_API.php?action=select&user_id=${access.decryptItem(
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
    select.append(
      new Option(`${item.role_id} - ${item.nama}`, item.role_id, false, false)
    );
  });

  select.trigger("change");
}

async function submitKaryawan() {
  // Collect form data
  const name_karyawan = document.getElementById("name_karyawan").value;
  const departement_karyawan = document.getElementById("divisi_karyawan").value;
  let phone_karyawan = document.getElementById("phone_karyawan").value;
  const address_karyawan = document.getElementById("address_karyawan").value;
  const nik_karyawan = document.getElementById("nik_karyawan").value;
  const role_id = document.getElementById("role_select").value;
  let npwp_karyawan = document.getElementById("npwp_karyawan").value;
  const status_karyawan = document.getElementById("status_karyawan").value;

  // Validate form data
  if (
    !name_karyawan ||
    name_karyawan.trim() === "" ||
    !departement_karyawan ||
    departement_karyawan.trim() === "" ||
    !role_id ||
    role_id.trim() === "" ||
    !status_karyawan ||
    status_karyawan.trim() === ""
  ) {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  const is_valid =
    // helper.validateField(
    //   name_karyawan,
    //   /^[a-zA-Z\s]+$/,
    //   "Format nama tidak valid"
    // ) &&
    helper.validateField(
      address_karyawan,
      /^[a-zA-Z0-9,. ]+$/,
      "Format alamat tidak valid"
    ) &&
    helper.validateField(
      phone_karyawan,
      /^[0-9]{9,13}$/,
      "Format nomor telepon tidak valid"
    ) &&
    helper.validateField(
      nik_karyawan,
      /^[0-9]{16}$/,
      "NIK harus terdiri dari 16 digit angka"
    ) &&
    helper.validateField(
      npwp_karyawan,
      /^[0-9]{15,16}$/,
      "NPWP harus terdiri dari 15-16 digit angka"
    );

  if (is_valid) {
    const no_telp_karyawan = helper.format_no_telp(phone_karyawan);
    npwp_karyawan = helper.format_npwp(npwp_karyawan);
    const data_karyawan = {
      user_id: `${access.decryptItem("user_id")}`,
      name_karyawan,
      departement_karyawan,
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
      if (response.ok) {
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
        window.karyawan_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("karyawan");
        }, 200);
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
