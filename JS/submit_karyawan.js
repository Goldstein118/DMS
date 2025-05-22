import config from "./config.js";

const submit_karyawan = document.getElementById("submit_karyawan");
if (submit_karyawan) {
  submit_karyawan.addEventListener("click", function () {
    submitKaryawan();
    $(document).ready(function () {
      $("#modal_karyawan").on("shown.bs.modal", function () {
        fetch_roles();
        $("#name_karyawan").trigger("focus");
        $("#role_select").select2({
          placeholder: "Pilih Role",
          allowClear: true,
          dropdownParent: $("#modal_karyawan"),
          minimumResultforSearch: 5,
        });
      });
    });
  });
}

function fetch_roles() {
  fetch(`${config.API_BASE_URL}/PHP/API/role_API.php`)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error(
          `Failed to fetch roles. Status: ${response.status} ${response.statusText}`
        );
      }
    })
    .then((data) => {
      if (Array.isArray(data) && data.length > 0) {
        populateRoleDropdown(data);
      } else {
        console.warn("No roles found or invalid data format.");
      }
    })
    .catch((error) => {
      console.error("Error fetching roles:", error);
      toastr.error("Failed to load roles. Please refresh the page.");
    });
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

function submitKaryawan() {
  // Collect form data
  const name_karyawan = document.getElementById("name_karyawan").value;
  const divisi_karyawan = document.getElementById("divisi_karyawan").value;
  const phone_karyawan = document.getElementById("phone_karyawan").value;
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

  // Create a data object
  const data_karyawan = {
    action: "submit_karyawan",
    name_karyawan,
    divisi_karyawan,
    phone_karyawan,
    address_karyawan,
    nik_karyawan,
    role_id,
    npwp_karyawan,
    status_karyawan,
  };

  // Send the data to the PHP script
  fetch(`${config.API_BASE_URL}/PHP/create.php`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data_karyawan),
  })
    .then((response) => {
      return response.json();
    })
    .then((jsonData) => {
      if (jsonData.success) {
        // Reset the form
        document.getElementById("name_karyawan").value = "";
        document.getElementById("divisi_karyawan").value = "";
        document.getElementById("phone_karyawan").value = "";
        document.getElementById("address_karyawan").value = "";
        document.getElementById("nik_karyawan").value = "";
        document.getElementById("npwp_karyawan").value = "";
        document.getElementById("status_karyawan").value = "";
        $("#role_select").val(null).trigger("change");
        $("#modal_karyawan").modal("hide");
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
