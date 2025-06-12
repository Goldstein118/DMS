import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
const submit_customer = document.getElementById("submit_customer");
if (submit_customer) {
  submit_customer.addEventListener("click", submitCustomer);
  $(document).ready(function () {
    $("#modal_customer").on("shown.bs.modal", function () {
      $("#name_customer").trigger("focus");
    });
    fetch_channel();
    document
      .getElementById("ktp_image")
      .addEventListener("change", function () {
        access.validateImageFile(this);
      });

    document
      .getElementById("npwp_image")
      .addEventListener("change", function () {
        access.validateImageFile(this);
      });
    $("#channel_id").select2({
      placeholder: "Pilih channel",
      allowClear: true,
      dropdownParent: $("#modal_customer"),
    });
  });
}
function format_no_telp(str) {
  if (!str || str.trim() === "") {
    let result = str;
    return result;
  } else {
    if (7 > str.length) {
      return "Invalid index";
    }
    let format = str.slice(0, 3) + "-" + str.slice(3, 7) + "-" + str.slice(7);
    let result = "+62 " + format;
    return result;
  }
}
function validateField(field, pattern, errorMessage) {
  if (!field || field.trim() === "") {
    return true;
  }
  if (!pattern.test(field)) {
    toastr.error(errorMessage, {
      timeOut: 500,
      extendedTimeOut: 500,
    });
    return false;
  }
  return true;
}
function format_angka(str) {
  if (str === null || str === undefined || str === "") {
    return str;
  }

  const cleaned = str.toString().replace(/[.,\s]/g, "");

  if (!/^\d+$/.test(cleaned)) {
    return str;
  }
  const result = cleaned.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

  return result + ",00";
}

async function fetch_channel() {
  try {
    const response = await apiRequest(
      `/PHP/API/channel_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_customer&context=create`
    );
    const select = $("#channel_id");
    select.empty();
    select.append(new Option("Pilih Channel", "", false, false));
    response.data.forEach((channel) => {
      const option = new Option(
        `${channel.channel_id} - ${channel.nama}`,
        channel.channel_id,
        false,
        false
      );
      select.append(option);
    });
    select.trigger("change");
  } catch (error) {
    console.error("error:", error);
  }
}

async function submitCustomer() {
  const form = document.getElementById("form_customer");
  const formData = new FormData(form);

  const name_customer = document.getElementById("name_customer").value;
  let no_telp_customer = document.getElementById("no_telp_customer").value;
  const alamat_customer = document.getElementById("alamat_customer").value;
  const nik_customer = document.getElementById("nik_customer").value;
  const npwp_customer = document.getElementById("npwp_customer").value;
  const status_customer = document.getElementById("status_customer").value;
  const nitko = document.getElementById("nitko").value;
  const term_payment = document.getElementById("term_payment").value;
  const max_invoice = document.getElementById("max_invoice").value;
  let max_piutang = document.getElementById("max_piutang").value;
  const longitude = document.getElementById("longitude").value;
  const latidude = document.getElementById("latidude").value;
  const channel_id = document.getElementById("channel_id").value;

  if (
    !name_customer ||
    name_customer.trim() === "" ||
    !status_customer ||
    status_customer.trim() === "" ||
    !channel_id ||
    channel_id.trim() === ""
  ) {
    toastr.error("Kolom * wajib diisi.");
    return;
  }

  const is_valid =
    validateField(name_customer, /^[a-zA-Z\s]+$/, "Format nama tidak valid") &&
    validateField(
      alamat_customer,
      /^[a-zA-Z0-9, .-]+$/,
      "Format alamat tidak valid"
    ) &&
    validateField(
      no_telp_customer,
      /^[0-9]{9,13}$/,
      "Format nomor telepon tidak valid"
    ) &&
    validateField(nik_customer, /^[0-9]+$/, "Format NIK tidak valid") &&
    validateField(npwp_customer, /^[0-9 .-]+$/, "Format NPWP tidak valid") &&
    validateField(nitko, /^[a-zA-Z0-9, .-]+$/, "Format nitko tidak valid") &&
    validateField(
      term_payment,
      /^[0-9]+$/,
      "Format term payment tidak valid"
    ) &&
    validateField(max_invoice, /^[0-9]+$/, "Format max invoice tidak valid") &&
    validateField(
      max_piutang,
      /^[0-9., ]+$/,
      "Format max piutang tidak valid"
    ) &&
    validateField(
      longitude,
      /^[-+]?((1[0-7]\d|\d{1,2})(\.\d{1,6})?|180(\.0{1,6})?)$/,
      "Format longitude tidak valid"
    ) &&
    validateField(
      latidude,
      /^[-+]?([1-8]?\d(\.\d{1,6})?|90(\.0{1,6})?)$/,
      "Format latidude tidak valid"
    );

  if (!is_valid) return;

  no_telp_customer = format_no_telp(no_telp_customer);

  formData.set("name_customer", name_customer);
  formData.set("alamat_customer", alamat_customer);
  formData.set("no_telp_customer", no_telp_customer);
  formData.set("nik_customer", nik_customer);
  formData.set("npwp_customer", npwp_customer);
  formData.set("status_customer", status_customer);
  formData.set("nitko", nitko);
  formData.set("term_payment", term_payment);
  formData.set("max_invoice", max_invoice);
  formData.set("max_piutang", format_angka(max_piutang));
  formData.set("longitude", longitude);
  formData.set("latidude", latidude);
  formData.set("channel_id", channel_id);
  formData.set("action", "create");
  formData.set("user_id", access.decryptItem("user_id"));

  try {
    const response = await apiRequest(
      "/PHP/API/customer_API.php",
      "POST",
      formData
    );

    if (response.ok) {
      form.reset();
      $("#modal_customer").modal("hide");
      Swal.fire("Berhasil", response.message, "success");
      window.customer_grid.forceRender();
    }
  } catch (error) {
    console.error("Submit error:", error);
    toastr.error(error.message || "Submit gagal");
  }
}
