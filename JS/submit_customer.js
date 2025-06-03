import { apiRequest } from "./api.js";

const submit_customer = document.getElementById("submit_customer");
if (submit_customer) {
  submit_customer.addEventListener("click", submitCustomer);
  $(document).ready(function () {
    $("#modal_customer").on("shown.bs.modal", function () {
      $("#name_customer").trigger("focus");
    });
  });
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

async function submitCustomer() {
  // Collect form data
  const name_customer = document.getElementById("name_customer").value;
  let no_telp_customer = document.getElementById("no_telp_customer").value;
  const alamat_customer = document.getElementById("alamat_customer").value;
  const nik_customer = document.getElementById("nik_customer").value;
  const npwp_customer = document.getElementById("npwp_customer").value;
  const status_customer = document.getElementById("status_customer").value;
  const nitko = document.getElementById("nitko").value;
  const term_payment = document.getElementById("term_payment").value;
  const max_invoice = document.getElementById("max_invoice").value;
  const max_piutang = document.getElementById("max_piutang").value;

  // Validate form data
  if (
    !name_customer ||
    name_customer.trim() === "" ||
    !no_telp_customer ||
    no_telp_customer.trim() === "" ||
    !alamat_customer ||
    alamat_customer.trim() === "" ||
    !nik_customer ||
    nik_customer.trim() === "" ||
    !npwp_customer ||
    npwp_customer.trim() === "" ||
    !status_customer ||
    status_customer.trim() === "" ||
    !nitko ||
    nitko.trim() === "" ||
    !term_payment ||
    term_payment.trim() === "" ||
    !max_invoice ||
    max_invoice.trim() === "" ||
    !max_piutang ||
    max_piutang.trim() === ""
  ) {
    toastr.error("Harap isi semua kolom sebelum submit.");
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
      /^[a-zA-Z0-9 ]+$/,
      "Format term payment tidak valid"
    ) &&
    validateField(
      max_invoice,
      /^[a-zA-Z0-9 ]+$/,
      "Format max invoice tidak valid"
    ) &&
    validateField(
      max_piutang,
      /^[a-zA-Z0-9 ]+$/,
      "Format max piutang tidak valid"
    );

  if (is_valid) {
    no_telp_customer = format_no_telp(no_telp_customer);

    const data_customer = {
      user_id: `${localStorage.getItem("user_id")}`,
      name_customer,
      alamat_customer,
      no_telp_customer,
      nik_customer,
      npwp_customer,
      nitko,
      term_payment,
      max_invoice,
      max_piutang,
      status_customer,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/customer_API.php?action=create&user_id=user_id: ${localStorage.getItem(
          "user_id"
        )}`,
        "POST",
        data_customer
      );
      document.getElementById("name_customer").value = "";
      document.getElementById("no_telp_customer").value = "";
      document.getElementById("alamat_customer").value = "";
      document.getElementById("nik_customer").value = "";
      document.getElementById("npwp_customer").value = "";
      document.getElementById("status_customer").value = "";
      document.getElementById("nitko").value = "";
      document.getElementById("term_payment").value = "";
      document.getElementById("max_invoice").value = "";
      document.getElementById("max_piutang").value = "";

      $("#modal_customer").modal("hide");
      swal.fire("Berhasil", response.message, "success");
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
