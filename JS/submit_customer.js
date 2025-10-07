import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const submit_customer = document.getElementById("submit_customer");
const jenis_customer = document.getElementById("jenis_customer");
if (submit_customer) {
  submit_customer.addEventListener("click", submitCustomer);
  $(document).ready(function () {
    $("#modal_customer").on("shown.bs.modal", function () {
      $("#name_customer").trigger("focus");
    });
    fetch_FK("channel");
    fetch_FK("pricelist");
    helper.format_nominal("max_piutang");
    document
      .getElementById("ktp_image")
      .addEventListener("change", function () {
        access.validateImageFile(this);
      });

    jenis_customer.addEventListener("change", () => {
      if (jenis_customer.value === "pribadi") {
        document.getElementById("div_npwp_customer").style.display = "none";
        document.getElementById("div_nik_customer").style.display = "block";
        document.getElementById("alamat_customer_pribadi").style.display =
          "block";
        document.getElementById("alamat_customer_perusahaan").style.display =
          "none";
      } else if (jenis_customer.value === "perusahaan") {
        document.getElementById("div_npwp_customer").style.display = "block";
        document.getElementById("div_nik_customer").style.display = "none";
        document.getElementById("alamat_customer_pribadi").style.display =
          "none";
        document.getElementById("alamat_customer_perusahaan").style.display =
          "block";
      }
    });
    if (jenis_customer.value === "pribadi") {
      document.getElementById("div_npwp_customer").style.display = "none";
      document.getElementById("div_nik_customer").style.display = "block";
      document.getElementById("alamat_customer_pribadi").style.display =
        "block";
      document.getElementById("alamat_customer_perusahaan").style.display =
        "none";
    } else if (jenis_customer.value === "perusahaan") {
      document.getElementById("div_npwp_customer").style.display = "block";
      document.getElementById("div_nik_customer").style.display = "none";
      document.getElementById("alamat_customer_pribadi").style.display = "none";
      document.getElementById("alamat_customer_perusahaan").style.display =
        "block";
    }

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
    $("#pricelist_id").select2({
      placeholder: "Pilih pricelist",
      allowClear: true,
      dropdownParent: $("#modal_customer"),
    });
  });
}

async function fetch_FK(element) {
  try {
    const response = await apiRequest(
      `/PHP/API/${element}_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_customer&context=create`
    );
    const select = $(`#${element}_id`);
    select.empty();
    select.append(new Option(`"Pilih ${element}`, "", false, false));
    response.data.forEach((item) => {
      if (element == "channel") {
        const option = new Option(
          `${item.channel_id} - ${item.nama}`,
          item.channel_id,
          false,
          false
        );
        select.append(option);
      } else if (element == "pricelist") {
        if (item.status !== "aktif") return;

        const option = new Option(
          `${item.pricelist_id} - ${item.nama}`,
          item.pricelist_id,
          false,
          false
        );
        select.append(option);
      }
    });
    select.trigger("change");
  } catch (error) {
    console.error("error:", error);
  }
}

helper.load_file_link_group("npwp_image", "npwp_input_group");
helper.load_file_link_group("ktp_image", "ktp_input_group");

async function submitCustomer() {
  const form = document.getElementById("form_customer");
  const formData = new FormData(form);

  const name_customer = document.getElementById("name_customer").value;
  let no_telp_customer = document.getElementById("no_telp_customer").value;
  const alamat_customer = document.getElementById("alamat_customer").value;
  let nik_customer = document.getElementById("nik_customer").value;
  let npwp_customer = document.getElementById("npwp_customer").value;
  const status_customer = document.getElementById("status_customer").value;
  const nitko = document.getElementById("nitko").value;
  const term_payment = document.getElementById("term_payment").value;
  const max_invoice = document.getElementById("max_invoice").value;
  let max_piutang = document.getElementById("max_piutang").value;
  const longitude = document.getElementById("longitude").value;
  const latitude = document.getElementById("latitude").value;
  const channel_id = document.getElementById("channel_id").value;
  const pricelist_id = document.getElementById("pricelist_id").value;

  const nama_jalan = document.getElementById("nama_jalan").value;
  const rt = document.getElementById("rt").value;
  const kelurahan = document.getElementById("kelurahan").value;
  const kecamatan = document.getElementById("kecamatan").value;

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

  let nik_npwp_valid = true;

  if (jenis_customer.value == "pribadi") {
    nik_npwp_valid = nik_customer && nik_customer.trim() !== "";
    console.log("pribadi");
  } else if (jenis_customer.value == "perusahaan") {
    nik_npwp_valid = npwp_customer && npwp_customer.trim() !== "";
    console.log("usaha");
  }

  const is_valid =
    helper.validateField(
      name_customer,
      /^[a-zA-Z\s]+$/,
      "Format nama tidak valid"
    ) &&
    helper.validateField(
      alamat_customer,
      /^[a-zA-Z0-9, .-]+$/,
      "Format alamat tidak valid"
    ) &&
    helper.validateField(
      no_telp_customer,
      /^[0-9]{9,13}$/,
      "Nomor Telepon harus terdiri dari 10-12 digit angka"
    ) &&
    helper.validateField(
      nik_customer,
      /^[0-9]{16}$/,
      "NIK harus terdiri dari 16 digit angka"
    ) &&
    helper.validateField(
      npwp_customer,
      /^[0-9]{15,16}$/,
      "NPWP harus terdiri dari 15-16 digit angka"
    ) &&
    helper.validateField(
      nitko,
      /^[0-9]{22}$/,
      "NITKO harus terdiri dari 22 digit angka"
    ) &&
    helper.validateField(
      term_payment,
      /^[0-9]+$/,
      "Format term payment tidak valid"
    ) &&
    helper.validateField(
      max_invoice,
      /^[0-9]+$/,
      "Format max invoice tidak valid"
    ) &&
    helper.validateField(
      max_piutang,
      /^[0-9., ]+$/,
      "Format max piutang tidak valid"
    ) &&
    helper.validateField(
      longitude,
      /^[-+]?((1[0-7]\d|\d{1,2})(\.\d{1,6})?|180(\.0{1,6})?)$/,
      "Format longitude tidak valid"
    ) &&
    helper.validateField(
      latitude,
      /^[-+]?([1-8]?\d(\.\d{1,6})?|90(\.0{1,6})?)$/,
      "Format latitude tidak valid"
    );

  if (!is_valid || !nik_npwp_valid) {
    toastr.error("Kolom NIK/NPWP harus diisi.");
    return;
  }
  npwp_customer = helper.format_npwp(npwp_customer);
  no_telp_customer = helper.format_no_telp(no_telp_customer);

  formData.set("name_customer", name_customer);
  formData.set("alamat_customer", alamat_customer);
  formData.set("no_telp_customer", no_telp_customer);
  formData.set("nik_customer", nik_customer);
  formData.set("npwp_customer", npwp_customer);
  formData.set("status_customer", status_customer);
  formData.set("nitko", nitko);
  formData.set("term_payment", term_payment);
  formData.set("max_invoice", max_invoice);
  formData.set("max_piutang", max_piutang);
  formData.set("longitude", longitude);
  formData.set("latitude", latitude);
  formData.set("channel_id", channel_id);
  formData.set("kecamatan", kecamatan);
  formData.set("nama_jalan", nama_jalan);
  formData.set("rt", rt);
  formData.set("kelurahan", kelurahan);
  formData.set("pricelist_id", pricelist_id);
  formData.set("jenis_customer", jenis_customer.value);
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
      setTimeout(() => {
        helper.custom_grid_header("customer");
        const tooltipTriggerList = document.querySelectorAll(
          '[data-bs-toggle="tooltip"]'
        );
        const tooltipList = [...tooltipTriggerList].map(
          (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
        );
      }, 200);
    }
  } catch (error) {
    console.error("Submit error:", error);
    toastr.error(error.message || "Submit gagal");
  }
}
