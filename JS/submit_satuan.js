import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const submit_satuan = document.getElementById("submit_satuan");
if (submit_satuan) {
  submit_satuan.addEventListener("click", submitSatuan);
  $("#modal_satuan").on("shown.bs.modal", function () {
    $("#nama_satuan").trigger("focus");

    $("#id_referensi").select2({
      placeholder: "Pilih Satuan Referensi",
      allowClear: true,
      dropdownParent: $("#modal_satuan"),
    });
    fetch_satuan();
  });
}

async function fetch_satuan() {
  try {
    const response = await apiRequest(
      `/PHP/API/satuan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`
    );
    populateSatuanDropdown(response.data);
  } catch (error) {
    toastr.error("Gagal mengambil data satuan: " + error.message);
  }
}

function populateSatuanDropdown(data) {
  const select = $("#id_referensi");
  select.empty();
  select.append(new Option("Pilih Satuan Referensi", "", false, false));

  data.forEach((item) => {
    select.append(
      new Option(
        `${item.satuan_id} - ${item.nama}`,
        item.satuan_id,
        false,
        false
      )
    );
  });

  select.trigger("change");
}

async function submitSatuan() {
  const name_satuan = document.getElementById("nama_satuan").value;

  const id_referensi = $("#id_referensi").val();
  const qty_satuan = document.getElementById("qty_satuan").value;

  if (!name_satuan || name_satuan.trim() === "") {
    toastr.error("Kolom * wajib diisi.");
    return;
  }
  if (
    helper.validateField(
      name_satuan,
      /^[a-zA-Z\s]+$/,
      "Format nama tidak valid"
    )
  ) {
    const data_satuan = {
      user_id: `${access.decryptItem("user_id")}`,
      nama: name_satuan,
      id_referensi: id_referensi,
      qty_satuan: qty_satuan,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/satuan_API.php?action=create`,
        "POST",
        data_satuan
      );
      if (response.ok) {
        swal.fire("Berhasil", response.message, "success");
        document.getElementById("nama_satuan").value = "";
        $("#modal_satuan").modal("hide");
        window.satuan_grid.forceRender();
        setTimeout(() => {
          helper.custom_grid_header("satuan");
        }, 200);
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
