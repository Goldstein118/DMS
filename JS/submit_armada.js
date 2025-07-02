import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const submit_armada = document.getElementById("submit_armada");
if (submit_armada) {
  submit_armada.addEventListener("click", submitChannel);
  $(document).ready(function () {
    $("#modal_armada").on("shown.bs.modal", function () {
      $("#nama_armada").trigger("focus");
      fetch_karyawan();
    });
    $("#karyawan_select").select2({
      placeholder: "Pilih Karyawan",
      allowClear: true,
      dropdownParent: $("#modal_armada"),
    });
  });
}
async function fetch_karyawan() {
  try {
    const response = await apiRequest(
      `/PHP/API/karyawan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_armada&context=create`
    );
    populate_karyawan(response.data);
  } catch (error) {
    toastr.error("Gagal mengambil data karyawan: " + error.message);
  }
}

function populate_karyawan(data) {
  const select = $("#karyawan_select");
  select.empty();
  select.append(new Option("Pilih Karyawan", "", false, false));

  data.forEach((item) => {
    select.append(
      new Option(
        `${item.karyawan_id} - ${item.nama}`,
        item.karyawan_id,
        false,
        false
      )
    );
  });

  select.trigger("change");
}
async function submitChannel() {
  const nama = document.getElementById("nama_armada").value;
  const karyawan_id = document.getElementById("karyawan_select").value;

  if (!nama || nama.trim() === "") {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  if (helper.validateField(nama, /^[a-zA-Z\s]+$/, "Format nama tidak valid")) {
    const data_armada = {
      user_id: `${access.decryptItem("user_id")}`,
      nama,
      karyawan_id,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/armada_API.php?action=create`,
        "POST",
        data_armada
      );
      if (response.ok) {
        swal.fire("Berhasil", response.message, "success");
        document.getElementById("nama_armada").value = "";
        $("#modal_armada").modal("hide");
        window.armada_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("armada");
        }, 200);
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
