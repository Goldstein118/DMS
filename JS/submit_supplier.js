import config from "../JS/config.js";
const submit_supplier = document.getElementById("submit_supplier");
if (submit_supplier) {
  submit_supplier.addEventListener("click", submitSupplier);
  $("#modal_supplier").on("show.bs.modal", function () {
    $("#supplier_nama").trigger("focus");
  });
}

function submitSupplier() {
  const supplier_nama = document.getElementById("supplier_nama").value;
  const supplier_alamat = document.getElementById("supplier_alamat").value;
  const supplier_no_telp = document.getElementById("supplier_no_telp").value;
  const supplier_ktp = document.getElementById("supplier_ktp").value;
  const supplier_npwp = document.getElementById("supplier_npwp").value;
  const supplier_status = document.getElementById("supplier_status").value;

  if (
    !supplier_nama ||
    supplier_nama.trim() === "" ||
    !supplier_alamat ||
    supplier_alamat.trim() === "" ||
    !supplier_no_telp ||
    supplier_no_telp.trim() === "" ||
    !supplier_ktp ||
    supplier_ktp.trim() === "" ||
    !supplier_npwp ||
    supplier_npwp.trim() === "" ||
    !supplier_status ||
    supplier_status.trim() === ""
  ) {
    toastr.error("Harap isi semua kolom sebelum submit.");
    return;
  }

  const supplier_data = {
    action: "submit_supplier",
    supplier_nama,
    supplier_alamat,
    supplier_no_telp,
    supplier_ktp,
    supplier_npwp,
    supplier_status,
  };

  fetch(`${config.API_BASE_URL}/PHP/create.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(supplier_data),
  })
    .then((response) => {
      return response.json();
    })
    .then((jsonData) => {
      if (jsonData.success) {
        document.getElementById("supplier_nama").value = "";
        document.getElementById("supplier_alamat").value = "";
        document.getElementById("supplier_no_telp").value = "";
        document.getElementById("supplier_ktp").value = "";
        document.getElementById("supplier_npwp").value = "";
        document.getElementById("supplier_status").value = "";
        $("#modal_supplier").modal("hide");
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
      console.error("Error submitting supplier:", error);
      toastr.error(
        "An error occurred while submitting the form. Please try again."
      );
    });
}
