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
  $("#jenis_customer").select2({
    dropdownParent: $("#modal_promo"),
  });
  $("#jenis_produk").select2({
    dropdownParent: $("#modal_promo"),
  });
});

async function fetch_fk(field) {
  try {
    const response = await apiRequest(
      `/PHP/API/${field}_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_promo&context=create`,
      "POST",
      { select: "select" }
    );
    populateDropdown(response.data, field);
  } catch (error) {
    toastr.error("Gagal mengambil data : " + error.message);
  }
}

function populateDropdown(data, field) {
  const select = $(`#jenis_${field}`);
  select.empty();
  if (field === "brand") {
    data.forEach((item) => {
      select.append(
        new Option(
          `${item.brand_id} - ${item.nama}`,
          item.brand_id,
          false,
          false
        )
      );
    });
  } else if (field === "customer") {
    data.forEach((item) => {
      select.append(
        new Option(
          `${item.customer_id} - ${item.nama}`,
          item.customer_id,
          false,
          false
        )
      );
    });
  } else if (field === "produk") {
    data.forEach((item) => {
      select.append(
        new Option(
          `${item.produk_id} - ${item.nama}`,
          item.produk_id,
          false,
          false
        )
      );
    });
  }

  select.trigger("change");
}
let jenis_bonus = document.getElementById("jenis_bonus");

jenis_bonus.addEventListener("change", (event) => {
  let bonus = jenis_bonus.options[jenis_bonus.selectedIndex].text;
  if (bonus === "Barang") {
    document.getElementById("card_promo_3").style.display = "block";
  } else {
    document.getElementById("card_promo_3").style.display = "none";
  }
});

fetch_fk("brand");
fetch_fk("customer");
fetch_fk("produk");
