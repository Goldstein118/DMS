import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";

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
      "kategori",
      "brand",
      "produk",
      "divisi",
      "gudang",
      "pricelist",
      "armada",
      "frezzer",
      "promo",
      "satuan",
      "pembelian",
      "data_biaya",
      "invoice",
    ];

    checkboxFields.forEach((field) => {
      const checkboxAll = document.getElementById(`check_all_${field}`);
      if (checkboxAll) {
        checkboxAll.addEventListener("click", () =>
          helper.event_check_box(field, "create")
        );
      }
    });

    checkboxFields.forEach((field) => {
      helper.view_checkbox(field, "create");
    });
  });
}

async function submitRole() {
  const akses_role = helper.proses_check_box();
  // Collect form data
  const name_role = document.getElementById("name_role").value;

  if (
    !name_role ||
    name_role.trim() === "" ||
    !akses_role ||
    akses_role.trim() === ""
  ) {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  const is_valid =
    helper.validateField(
      name_role,
      /^[a-zA-Z\s]+$/,
      "Format nama tidak valid"
    ) &&
    helper.validateField(akses_role, /^[0-9]+$/, "Format akses tidak valid");

  const data_role = {
    user_id: `${access.decryptItem("user_id")}`,
    name_role,
    akses_role,
  };

  if (is_valid) {
    try {
      const response = await apiRequest(
        "/PHP/API/role_API.php?action=create",
        "POST",
        data_role
      );
      if (response.ok) {
        document.getElementById("name_role").value = "";
        Swal.fire("Berhasil", response.message, "success");
        $("#modal_role").modal("hide");
        window.role_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("role");
        }, 200);
      }
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: error.message,
      });
    }
  }
}
