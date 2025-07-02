import config from "./config.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
document.getElementById("loading_spinner").style.visibility = "hidden";
$("#loading_spinner").fadeOut();
$(document).ready(function () {
  $("#jenis_brand").select2({
    dropdownParent: $("#modal_promo"),
  });
});

async function fetch_fk(field) {
  try {
    const response = await apiRequest(
      `/PHP/API/${field}_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_produk&context=create`
    );
    populateDropdown(response.data, field);
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }
}

function populateDropdown(data, field) {
  const select = $(`#jenis_${field}`);
  select.empty();

  data.forEach((item) => {
    select.append(
      new Option(`${item.brand_id} - ${item.nama}`, item.brand_id, false, false)
    );
  });

  select.trigger("change");
}
fetch_fk("brand");
